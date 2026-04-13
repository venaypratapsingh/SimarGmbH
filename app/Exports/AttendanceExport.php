<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances = null)
    {
        $this->attendances = $attendances ?: Attendance::with('employee')->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->attendances;
    }

    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'Date',
            'Employee ID',
            'Employee Name',
            'Attendance Time',
            'Status',
            'Absence Reason',
            'Time In',
            'Break Start',
            'Break End',
            'Break Duration',
            'Time Out',
            'Total Working Time'
        ];
    }

    /**
     * Map the attendance data for export
     */
    public function map($attendance): array
    {
        $isAbsent = $attendance->status == 0;
        
        // Extract only valid codes from absence_reason (handles legacy multi-code/multi-line data)
        $reasonText = $attendance->absence_reason;
        if ($reasonText) {
            $reasonText = trim($reasonText);
            
            // Split by newline, comma, or whitespace
            $codes = preg_split('/[\n,\s]+/', $reasonText, -1, PREG_SPLIT_NO_EMPTY);
            
            // Filter out 'Not specified' and get only valid codes
            $validCodes = array_filter($codes, function($code) {
                return $code !== 'Not specified' && strlen($code) > 0;
            });
            
            // Take the first valid code
            $reasonText = reset($validCodes) ?: ($reasonText === 'Not specified' ? 'Not specified' : '');
        }
        
        return [
            $attendance->attendance_date,
            $attendance->emp_id,
            $attendance->employee->name ?? 'Unknown',
            $attendance->attendance_time,
            $attendance->status == 1 ? 'Present' : 'Absent',
            $reasonText ?? ($isAbsent ? 'N/A' : ''),
            $isAbsent ? 'N/A' : ($attendance->time_in ?? ($attendance->employee->schedules->first()->time_in ?? 'N/A')),
            $isAbsent ? 'N/A' : ($attendance->break_start ?? 'N/A'),
            $isAbsent ? 'N/A' : ($attendance->break_end ?? 'N/A'),
            $isAbsent ? 'N/A' : ($attendance->break_duration_formatted ?? 'N/A'),
            $isAbsent ? 'N/A' : ($attendance->time_out ?? ($attendance->employee->schedules->first()->time_out ?? 'N/A')),
            $attendance->total_working_time_formatted ?? 'N/A'
        ];
    }
}
