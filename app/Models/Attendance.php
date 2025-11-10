<?php

namespace App\Models;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';
    
    protected $fillable = [
        'emp_id',
        'attendance_date',
        'attendance_time',
        'break_start',
        'break_end',
        'break_duration',
        'time_in',
        'time_out',
        'total_working_time',
        'status',
        'note',
        'absence_reason'
    ];
    
    protected $appends = ['break_duration_formatted', 'total_working_time_formatted'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
    
    /**
     * Calculate break duration in minutes
     */
    public function getBreakDurationAttribute()
    {
        $breakStart = $this->break_start;
        $breakEnd = $this->break_end;
        if (!$breakStart || in_array($breakStart, ['00:00:00', '00:00'])) {
            $breakStart = $this->employee?->schedules?->first()?->break_start;
        }
        if (!$breakEnd || in_array($breakEnd, ['00:00:00', '00:00'])) {
            $breakEnd = $this->employee?->schedules?->first()?->break_end;
        }
        if ($breakStart && $breakEnd) {
            $start = Carbon::parse($breakStart);
            $end = Carbon::parse($breakEnd);
            return $end->diffInMinutes($start);
        }
        return 0;
    }
    
    /**
     * Get time_in attribute, return null if absent
     */
    public function getTimeInAttribute($value)
    {
        if ($this->status == 0) {
            return null;
        }
        return $value;
    }

    /**
     * Get time_out attribute, return null if absent
     */
    public function getTimeOutAttribute($value)
    {
        if ($this->status == 0) {
            return null;
        }
        return $value;
    }

    /**
     * Calculate total working time in minutes (time_out - time_in - break_duration)
     */
    public function getTotalWorkingTimeAttribute()
    {
        if ($this->status == 0) {
            return 0;
        }
        $timeIn = $this->time_in;
        $timeOut = $this->time_out;
        if (!$timeIn || in_array($timeIn, ['00:00:00', '00:00'])) {
            $timeIn = $this->employee?->schedules?->first()?->time_in;
        }
        if (!$timeOut || in_array($timeOut, ['00:00:00', '00:00'])) {
            $timeOut = $this->employee?->schedules?->first()?->time_out;
        }
        if ($timeIn && $timeOut) {
            $start = Carbon::parse($timeIn);
            $end = Carbon::parse($timeOut);
            $totalMinutes = $end->diffInMinutes($start);

            // Subtract break duration
            return $totalMinutes - $this->break_duration;
        }
        return 0;
    }
    
    /**
     * Format total working time as HH:MM
     */
    public function getTotalWorkingTimeFormattedAttribute()
    {
        if ($this->status == 0) {
            return '00:00';
        }
        $minutes = $this->total_working_time;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
    
    /**
     * Format break duration as HH:MM
     */
    public function getBreakDurationFormattedAttribute()
    {
        $minutes = $this->break_duration;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
}
