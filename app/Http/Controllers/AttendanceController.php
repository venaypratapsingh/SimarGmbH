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
        $attendance->absence_reason = request()->input('absence_reason');
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
