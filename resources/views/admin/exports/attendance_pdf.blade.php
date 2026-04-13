<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
        }
        .status-on-time {
            color: green;
            font-weight: bold;
        }
        .status-late {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Emp ID</th>
                <th>Name</th>
                <th>Time</th>
                <th>Status</th>
                <th>Absence Reason</th>
                <th>Time In</th>
                <th>Break Start</th>
                <th>Break End</th>
                <th>Break Duration</th>
                <th>Time Out</th>
                <th>Total Working Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->attendance_date }}</td>
                <td>{{ $attendance->emp_id }}</td>
                <td>{{ $attendance->employee->name ?? 'Unknown' }}</td>
                <td>{{ $attendance->attendance_time }}</td>
                <td class="{{ $attendance->status == 1 ? 'status-on-time' : 'status-late' }}">
                    {{ $attendance->status == 1 ? 'Present' : 'Absent' }}
                </td>
                <td>
                    @php
                        $reasonText = $attendance->absence_reason;
                        // Clean up the absence reason - extract valid codes only
                        if ($reasonText) {
                            $reasonText = trim($reasonText);
                            
                            // Split by newline, comma, or whitespace
                            $codes = preg_split('/[\n,\s]+/', $reasonText, -1, PREG_SPLIT_NO_EMPTY);
                            
                            // Filter out 'Not specified' and get only valid codes
                            $validCodes = array_filter($codes, function($code) {
                                return $code !== 'Not specified' && strlen($code) > 0;
                            });
                            
                            // Take the first valid code, or 'Not specified' if none found
                            $reasonText = reset($validCodes) ?: ($reasonText === 'Not specified' ? 'Not specified' : '');
                        }
                    @endphp
                    {{ $reasonText ?? ($attendance->status == 0 ? 'N/A' : '') }}
                </td>
                <td>
                    @if ($attendance->status == 0)
                        N/A
                    @else
                        {{ $attendance->time_in ?? ($attendance->employee->schedules->first()->time_in ?? 'N/A') }}
                    @endif
                </td>
                <td>
                    @if ($attendance->status == 0)
                        N/A
                    @else
                        {{ $attendance->break_start ?? 'N/A' }}
                    @endif
                </td>
                <td>
                    @if ($attendance->status == 0)
                        N/A
                    @else
                        {{ $attendance->break_end ?? 'N/A' }}
                    @endif
                </td>
                <td>
                    @if ($attendance->status == 0)
                        00:00
                    @else
                        {{ $attendance->break_duration_formatted ?? 'N/A' }}
                    @endif
                </td>
                <td>
                    @if ($attendance->status == 0)
                        N/A
                    @else
                        {{ $attendance->time_out ?? ($attendance->employee->schedules->first()->time_out ?? 'N/A') }}
                    @endif
                </td>
                <td>{{ $attendance->total_working_time_formatted ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $attendances->count() }}</p>
        <p>On Time: {{ $attendances->where('status', 1)->count() }}</p>
        <p>Late: {{ $attendances->where('status', 0)->count() }}</p>
        @php
            // Calculate total working hours
            $totalMinutes = 0;
            foreach($attendances as $a) {
                if ($a->total_working_time) {
                    $parts = explode(':', $a->total_working_time);
                    $hours = intval($parts[0] ?? 0);
                    $minutes = intval($parts[1] ?? 0);
                    $totalMinutes += ($hours * 60) + $minutes;
                }
            }
            $totalHours = floor($totalMinutes / 60);
            $remainingMins = $totalMinutes % 60;
            $totalTime = sprintf('%02d:%02d', $totalHours, $remainingMins);
        @endphp
        <p><strong>Total Working Hours: {{ $totalTime }}</strong></p>
    </div>

    <div style="margin-top: 40px;">
        <p style="font-size: 11px; font-weight: bold; margin-bottom: 6px;">Remarks:</p>
        <div style="border: 1px solid #999; border-radius: 4px; min-height: 80px; padding: 8px; font-size: 10px; color: #555;">
            &nbsp;
        </div>
        <div style="margin-top: 30px; display: flex; justify-content: space-between;">
            <div style="text-align: center; width: 40%;">
                <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px;">Prepared by</div>
            </div>
            <div style="text-align: center; width: 40%;">
                <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px;">Approved by</div>
            </div>
        </div>
    </div>
</body>
</html>
