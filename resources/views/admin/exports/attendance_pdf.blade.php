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
                <td>{{ $attendance->absence_reason ?? ($attendance->status == 0 ? 'N/A' : '') }}</td>
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
    </div>
</body>
</html>
