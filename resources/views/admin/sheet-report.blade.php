@extends('layouts.master')
@section('button')
@endsection

@section('content')
    @php
        $allSchedules = \App\Models\Schedule::all();
    @endphp

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
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
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
                                                       data-break-start="{{ $check_attd->break_start }}"
                                                       data-break-end="{{ $check_attd->break_end }}"
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
                        
                        <hr>
                        <p class="text-muted"><strong>{{ __('global.option_1') }}:</strong> {{ __('global.select_schedule') }}</p>
                        <div class="form-group">
                            <label for="scheduleSelect">{{ __('global.schedule') }}</label>
                            <select class="form-control" id="scheduleSelect" name="schedule_id">
                                <option value="">-- {{ __('global.select_schedule') }} --</option>
                                @foreach($allSchedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->name ?? $schedule->slug }} ({{ $schedule->time_in }} - {{ $schedule->time_out }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <hr>
                        <p class="text-muted"><strong>{{ __('global.option_2') }}:</strong> {{ __('global.manually_edit_times') }}</p>
                        
                        <div class="form-group">
                            <label for="timeIn">{{ __('global.time_in') }}</label>
                            <input type="time" class="form-control" id="timeIn" name="time_in">
                        </div>
                        
                        <div class="form-group">
                            <label for="timeOut">{{ __('global.time_out') }}</label>
                            <input type="time" class="form-control" id="timeOut" name="time_out">
                        </div>
                        
                        <div class="form-group">
                            <label for="breakStart">{{ __('global.break_start') }}</label>
                            <input type="time" class="form-control" id="breakStart" name="break_start">
                        </div>
                        
                        <div class="form-group">
                            <label for="breakEnd">{{ __('global.break_end') }}</label>
                            <input type="time" class="form-control" id="breakEnd" name="break_end">
                        </div>
                        
                        <div id="validationError" class="alert alert-danger d-none mt-3" role="alert">
                            Please either select a schedule OR fill in Time In and Time Out
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('global.save') }}</button>
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
        const breakStart = check.data('break-start');
        const breakEnd = check.data('break-end');
        
        $('#attendanceId').val(attendanceId);
        $('#employeeId').val(employeeId);
        $('#employeeName').text(employeeName);
        $('#attendanceDate').text(date);
        $('#timeIn').val(timeIn || '');
        $('#timeOut').val(timeOut || '');
        $('#breakStart').val(breakStart || '');
        $('#breakEnd').val(breakEnd || '');
        $('#scheduleSelect').val('');
        $('#validationError').addClass('d-none');
    });

    // Form submission validation
    $('#editTimeForm').on('submit', function(e) {
        e.preventDefault();
        
        const scheduleId = $('#scheduleSelect').val();
        const timeIn = $('#timeIn').val();
        const timeOut = $('#timeOut').val();
        
        // Validation: Either schedule is selected OR both time_in and time_out are filled
        const hasSchedule = scheduleId && scheduleId.trim() !== '';
        const hasTimesFilledCompletely = timeIn && timeIn.trim() !== '' && timeOut && timeOut.trim() !== '';
        
        if (!hasSchedule && !hasTimesFilledCompletely) {
            $('#validationError').removeClass('d-none');
            return false;
        }
        
        // If validation passes, submit the form
        this.submit();
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
