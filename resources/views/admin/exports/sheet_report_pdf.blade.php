<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sheet Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 14px;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 3px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .employee-row {
            background-color: #f9f9f9;
        }
        .date-header {
            background-color: #e8f4f8;
            font-weight: bold;
        }
        .present {
            background-color: #d4edda;
            color: #155724;
        }
        .absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        .leave {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Attendance Sheet Report</h1>
        <p>Month: {{ $today->format('F Y') }}</p>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Employee ID</th>
                <th rowspan="2">Name</th>
                <th colspan="{{ count($dates) }}" class="date-header">Dates</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                <th>{{ \Carbon\Carbon::parse($date)->format('d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr class="employee-row">
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->name }}</td>
                @foreach($dates as $date)
                @php
                    $attendance = $employee->attendances->where('attendance_date', $date)->first();
                    $leave = $employee->leaves->where('leave_date', $date)->first();
                @endphp
                <td class="
                    @if($attendance)
                        @if($attendance->status == 1)
                            present
                        @else
                            absent
                        @endif
                    @elseif($leave)
                        leave
                    @else
                        absent
                    @endif
                ">
                    @if($attendance)
                        @if($attendance->status == 1)
                            P
                        @else
                            {{ $attendance->absence_reason ?? 'A' }}
                        @endif
                    @elseif($leave)
                        L
                    @else
                        A
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Legend: P = Present, A = Absent, L = Leave</p>
        <p>Total Employees: {{ $employees->count() }}</p>
        <p>Total Days: {{ count($dates) }}</p>
    </div>
</body>
</html>
