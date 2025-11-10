@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
        </ol>
    </div>
@endsection
@section('button')
    <form method="GET" action="{{ route('attendance') }}" class="form-inline mb-2">
        <div class="form-group mr-2">
            <label for="start_date" class="mr-2">From:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="form-group mr-2">
            <label for="end_date" class="mr-2">To:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <button type="submit" class="btn btn-primary btn-sm btn-flat mr-2">Filter</button>
    </form>

    <a href="attendance/assign" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add New</a>
    
    <div class="btn-group ml-2">
        <button type="button" class="btn btn-success btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-download mr-2"></i>Export
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('attendance.export.csv', request()->query()) }}"><i class="mdi mdi-file-delimited-outline mr-2"></i>CSV</a>
            <a class="dropdown-item" href="{{ route('attendance.export.excel', request()->query()) }}"><i class="mdi mdi-file-excel-outline mr-2"></i>Excel</a>
            <a class="dropdown-item" href="{{ route('attendance.export.pdf', request()->query()) }}"><i class="mdi mdi-file-pdf-outline mr-2"></i>PDF</a>
        </div>
    </div>
@endsection

@section('content')
@include('includes.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                <thead>
                                    <tr>
                                        <th data-priority="1">Date</th>
                                        <th data-priority="2">Employee ID</th>
                                        <th data-priority="3">Name</th>
                                        <th data-priority="4">Status</th>
                                        <th data-priority="5">Absence Reason</th>
                                        <th data-priority="6">Time In</th>
                                        <th data-priority="7">Break Start</th>
                                        <th data-priority="8">Break End</th>
                                        <th data-priority="9">Break Duration</th>
                                        <th data-priority="10">Time Out</th>
                                        <th data-priority="11">Total Working Time</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($attendances as $attendance)

                                        <tr>
                                            <td>{{ $attendance->attendance_date }}</td>
                                            <td>{{ $attendance->emp_id }}</td>
                                            <td>{{ $attendance->employee->name ?? 'Unknown' }}</td>
                                            <td>
                                                @if ($attendance->status == 1)
                                                    <span class="badge badge-success badge-pill">Present</span>
                                                @else
                                                    <span class="badge badge-danger badge-pill">Absent</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->status == 0)
                                                    <form method="POST" action="{{ route('attendance.update_reason', $attendance->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="absence_reason" value="{{ $attendance->absence_reason }}" class="form-control form-control-sm" style="width: 150px; display: inline;">
                                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                    </form>
                                                @else
                                                    N/A
                                                @endif
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
                                                    N/A
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
                                            <td>{{ $attendance->total_working_time_formatted ?? 'N/A' }} </td>
                                        </tr>

                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection


@section('script')
    <!-- Responsive-table-->
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
 
@endsection

@section('script')
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@endsection
