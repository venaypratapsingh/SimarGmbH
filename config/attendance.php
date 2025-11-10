<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the attendance system.
    |
    */

    'clearance_time' => env('ATTENDANCE_CLEARANCE_TIME', '23:50'),
    
    'late_threshold' => env('ATTENDANCE_LATE_THRESHOLD', 15), // minutes
    
    'overtime_threshold' => env('ATTENDANCE_OVERTIME_THRESHOLD', 30), // minutes
    
    'working_hours' => [
        'start' => '09:00',
        'end' => '17:00',
    ],
    
    'break_time' => [
        'start' => '12:00',
        'end' => '13:00',
    ],
    
    'timezone' => env('APP_TIMEZONE', 'UTC'),
]; 