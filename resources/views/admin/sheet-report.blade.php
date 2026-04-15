@extends('layouts.master')
@section('button')
@endsection

@section('content')

    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>{{ __('global.timetable') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="printTable">
                    <thead>
                        <tr >

                            <th>{{ __('global.employee_name') }}</th>
                            <th>{{ __('global.employee_position') }}</th>
                            <th>{{ __('global.employee_id') }}</th>
                            @php
                                $today = today();
                                $dates = [];
                                
                                for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                }
                                
                            @endphp
                            @foreach ($dates as $date)
                            <th style="">
                                {{ $date }}
                            </th>
                            @endforeach

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($employees as $employee)

                            <input type="hidden" name="emp_id" value="{{ $employee->id }}">

                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->id }}</td>

                                @for ($i = 1; $i < $today->daysInMonth + 1; ++$i)

                                    @php
                                        $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                        
                                        $check_attd = \App\Models\Attendance::query()
                                            ->where('emp_id', $employee->id)
                                            ->where('attendance_date', $date_picker)
                                            ->first();
                                        
                                        $check_leave = \App\Models\Leave::query()
                                            ->where('emp_id', $employee->id)
                                            ->where('leave_date', $date_picker)
                                            ->first();
                                    @endphp
                                    <td>
                                        <div class="form-check form-check-inline">
                                            @if (isset($check_attd))
                                                @if ($check_attd->status == 1)
                                                    <i class="fa fa-check text-success attendance-check cursor-pointer" 
                                                       data-toggle="modal" 
                                                       data-target="#editTimeModal"
                                                       data-attendance-id="{{ $check_attd->id }}"
                                                       data-employee-id="{{ $employee->id }}"
                                                       data-employee-name="{{ $employee->name }}"
                                                       data-date="{{ $date_picker }}"
                                                       data-time-in="{{ $check_attd->time_in }}"
                                                       data-time-out="{{ $check_attd->time_out }}"
                                                       data-schedule="{{ $employee->schedules->first()->id ?? '' }}"
                                                       title="Click to edit time and schedule">
                                                    </i>
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif
                                            @elseif (isset($check_leave))
                                                @if ($check_leave->status == 1)
                                                    <i class="fa fa-check text-success"></i>
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif
                                            @elseif ($date_picker <= today()->format('Y-m-d'))
                                                <i class="fa fa-times text-danger"></i>
                                            @endif
                                        </div>
                                    </td>

                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Time and Schedule Modal -->
    <div class="modal fade" id="editTimeModal" tabindex="-1" role="dialog" aria-labelledby="editTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTimeModalLabel">{{ __('global.edit_time_schedule') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editTimeForm" method="POST" action="{{ route('attendance.update') }}">
                    @csrf
                    {{ method_field('PUT') }}
                    <input type="hidden" id="attendanceId" name="attendance_id">
                    <input type="hidden" id="employeeId" name="employee_id">
                    
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label><strong>Employee:</strong> <span id="employeeName"></span></label>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Date:</strong> <span id="attendanceDate"></span></label>
                        </div>
                        
                        <div class="form-group">
                            <label for="timeIn">{{ __('global.time_in') }}</label>
                            <input type="time" class="form-control" id="timeIn" name="time_in" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="timeOut">{{ __('global.time_out') }}</label>
                            <input type="time" class="form-control" id="timeOut" name="time_out" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="scheduleSelect">{{ __('global.schedule') }}</label>
                            <select class="form-control" id="scheduleSelect" name="schedule_id">
                                <option value="">-- {{ __('global.select_schedule') }} --</option>
                                @foreach(\App\Models\Schedule::all() as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->name ?? $schedule->slug }} ({{ $schedule->time_in }} - {{ $schedule->time_out }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).on('show.bs.modal', '#editTimeModal', function(e) {
        const check = $(e.relatedTarget);
        const attendanceId = check.data('attendance-id');
        const employeeId = check.data('employee-id');
        const employeeName = check.data('employee-name');
        const date = check.data('date');
        const timeIn = check.data('time-in');
        const timeOut = check.data('time-out');
        
        $('#attendanceId').val(attendanceId);
        $('#employeeId').val(employeeId);
        $('#employeeName').text(employeeName);
        $('#attendanceDate').text(date);
        $('#timeIn').val(timeIn || '');
        $('#timeOut').val(timeOut || '');
    });
</script>
<style>
    .attendance-check {
        cursor: pointer;
        font-size: 18px;
        transition: transform 0.2s;
    }
    .attendance-check:hover {
        transform: scale(1.3);
    }
</style>
@endsection
