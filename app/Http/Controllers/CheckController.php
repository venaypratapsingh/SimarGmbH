<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckController extends Controller
{
    public function index()
    {
        return view('admin.check')->with(['employees' => Employee::all()]);
    }

    public function CheckStore(Request $request)
    {
        $employees = Employee::all();
        $today = now()->startOfMonth();
        $daysInMonth = $today->daysInMonth;

        // Loop through each employee and each day of the month
        foreach ($employees as $employee) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $today->copy()->addDays($day - 1)->format('Y-m-d');

                $attdValue = $request->attd[$date][$employee->id] ?? null;

                $attendance = Attendance::where('emp_id', $employee->id)
                    ->where('attendance_date', $date)
                    ->where('type', 0)
                    ->first();

                if ($attdValue == 'present') {
                    // If present selected, create or update attendance record as present
                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->emp_id = $employee->id;
                        $attendance->attendance_date = $date;
                    }
                    $schedule = $employee->schedules->first();
                    if ($schedule) {
                        $attendance->attendance_time = date('H:i:s', strtotime($schedule->time_in));
                        $attendance->break_start = $request->break_start[$date][$employee->id] ?? $schedule->break_start;
                        $attendance->break_end = $request->break_end[$date][$employee->id] ?? $schedule->break_end;

                        try {
                            if ($attendance->break_start && $attendance->break_end) {
                                $breakStart = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->break_start);
                                $breakEnd = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->break_end);
                                $breakDuration = $breakEnd->diffInMinutes($breakStart);
                                $attendance->break_duration = $breakDuration;

                                $timeIn = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->time_in);
                                $timeOut = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->time_out);
                                $totalMinutes = $timeOut->diffInMinutes($timeIn) - $breakDuration;
                                $attendance->total_working_time = $totalMinutes;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error calculating break duration or total working time: ' . $e->getMessage());
                            $attendance->break_duration = null;
                            $attendance->total_working_time = null;
                        }
                    }
                    $attendance->status = 1; // present
                    $attendance->absence_reason = null;
                    $attendance->type = 0;
                    $attendance->save();
                } elseif ($attdValue == 'absent') {
                    // If absent selected, create or update attendance record as absent
                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->emp_id = $employee->id;
                        $attendance->attendance_date = $date;
                    }
                    $attendance->status = 0; // absent
                    $attendance->absence_reason = 'Not specified'; // default reason
                    $attendance->type = 0;
                    $attendance->attendance_time = '00:00:00';
                    $attendance->break_start = null;
                    $attendance->break_end = null;
                    $attendance->break_duration = null;
                    $attendance->total_working_time = null;
                    $attendance->save();
                } else {
                    // If neither present nor absent selected, do nothing (no record)
                    // If there is an existing record, we can leave it or delete it, but since user said "should not show on attendance log until tick or cross is applied", perhaps delete if exists
                    if ($attendance) {
                        $attendance->delete();
                    }
                }
            }
        }

        flash()->success('Success', 'You have successfully submitted the attendance!');
        return back();
    }
    public function sheetReport()
    {
        return view('admin.sheet-report')->with(['employees' => Employee::all()]);
    }

    /**
     * Export sheet report to PDF
     */
    public function exportPDF()
    {
        $employees = Employee::all();
        $today = today();
        $dates = [];
        
        for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
            $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
        }
        
        $pdf = PDF::loadView('admin.exports.sheet_report_pdf', compact('employees', 'dates', 'today'));
        return $pdf->download('sheet_report_' . date('Y-m-d') . '.pdf');
    }
}
