<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AttendanceEmp;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{   
    //show attendance 
    public function index()
    {  
        $query = Attendance::with('employee');

        if (request()->has('start_date') && request()->start_date != '') {
            $query->where('attendance_date', '>=', request()->start_date);
        }

        if (request()->has('end_date') && request()->end_date != '') {
            $query->where('attendance_date', '<=', request()->end_date);
        }

        $attendances = $query->get();

        return view('admin.attendance')->with(['attendances' => $attendances]);
    }

    // Update absence reason
    public function updateReason($id)
    {
        $attendance = Attendance::findOrFail($id);
        $reasonInput = request()->input('absence_reason');
        
        // Take only the first code if multiple are passed (handle legacy data)
        // Split by newline, comma, or space and take first non-empty value
        if ($reasonInput) {
            $codes = preg_split('/[\n,\s]+/', trim($reasonInput));
            $reasonInput = reset($codes); // Get first element
        }
        
        $attendance->absence_reason = $reasonInput;
        $attendance->save();

        flash()->success('Success', 'Absence reason updated successfully!');
        return back();
    }

    //show late times
    public function indexLatetime()
    {
        return view('admin.latetime')->with(['latetimes' => Latetime::all()]);
    }

    

    // public static function lateTime(Employee $employee)
    // {
    //     $current_t = new DateTime(date('H:i:s'));
    //     $start_t = new DateTime($employee->schedules->first()->time_in);
    //     $difference = $start_t->diff($current_t)->format('%H:%I:%S');

    //     $latetime = new Latetime();
    //     $latetime->emp_id = $employee->id;
    //     $latetime->duration = $difference;
    //     $latetime->latetime_date = date('Y-m-d');
    //     $latetime->save();
    // }

    public static function lateTimeDevice($att_dateTime, Employee $employee)
    {
        $attendance_time = new DateTime($att_dateTime);
        $checkin = new DateTime($employee->schedules->first()->time_in);
        $difference = $checkin->diff($attendance_time)->format('%H:%I:%S');

        $latetime = new Latetime();
        $latetime->emp_id = $employee->id;
        $latetime->duration = $difference;
        $latetime->latetime_date = date('Y-m-d', strtotime($att_dateTime));
        $latetime->save();
    }

    /**
     * Update attendance time and schedule from sheet report
     */
    public function updateAttendance()
    {
        try {
            $attendance = Attendance::findOrFail(request()->input('attendance_id'));
            
            // Update time in and time out if provided
            if (request()->filled('time_in')) {
                $attendance->time_in = request()->input('time_in');
            }
            if (request()->filled('time_out')) {
                $attendance->time_out = request()->input('time_out');
            }
            
            // Update break times if provided
            if (request()->filled('break_start')) {
                $attendance->break_start = request()->input('break_start');
            }
            if (request()->filled('break_end')) {
                $attendance->break_end = request()->input('break_end');
            }
            
            // Recalculate total working time if both time_in and time_out are present
            if ($attendance->time_in && $attendance->time_out) {
                $start = \Carbon\Carbon::parse($attendance->time_in);
                $end = \Carbon\Carbon::parse($attendance->time_out);
                $totalMinutes = $end->diffInMinutes($start);
                
                // Recalculate break duration if both break times are present
                if ($attendance->break_start && $attendance->break_end) {
                    $breakStart = \Carbon\Carbon::parse($attendance->break_start);
                    $breakEnd = \Carbon\Carbon::parse($attendance->break_end);
                    $breakDuration = $breakEnd->diffInMinutes($breakStart);
                    $attendance->break_duration = $breakDuration;
                    $totalMinutes -= $breakDuration;
                } elseif ($attendance->break_duration) {
                    // Use existing break duration if no new break times provided
                    $totalMinutes -= $attendance->break_duration;
                }
                
                // Ensure total working time doesn't go negative
                $attendance->total_working_time = max(0, $totalMinutes);
            }
            
            $attendance->save();

            return redirect()->back()->with('success', 'Attendance updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Export attendance to CSV
     */
    public function exportCSV()
    {
        $query = Attendance::with('employee');

        if (request()->has('start_date') && request()->start_date != '') {
            $query->where('attendance_date', '>=', request()->start_date);
        }

        if (request()->has('end_date') && request()->end_date != '') {
            $query->where('attendance_date', '<=', request()->end_date);
        }

        $attendances = $query->get();

        return Excel::download(new AttendanceExport($attendances), 'attendance_' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Export attendance to Excel
     */
    public function exportExcel()
    {
        $query = Attendance::with('employee');

        if (request()->has('start_date') && request()->start_date != '') {
            $query->where('attendance_date', '>=', request()->start_date);
        }

        if (request()->has('end_date') && request()->end_date != '') {
            $query->where('attendance_date', '<=', request()->end_date);
        }

        $attendances = $query->get();

        return Excel::download(new AttendanceExport($attendances), 'attendance_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export attendance to PDF
     */
    public function exportPDF()
    {
        $query = Attendance::with('employee');

        if (request()->has('start_date') && request()->start_date != '') {
            $query->where('attendance_date', '>=', request()->start_date);
        }

        if (request()->has('end_date') && request()->end_date != '') {
            $query->where('attendance_date', '<=', request()->end_date);
        }

        $attendances = $query->get();

        $pdf = PDF::loadView('admin.exports.attendance_pdf', compact('attendances'));
        return $pdf->download('attendance_' . date('Y-m-d') . '.pdf');
    }
}
