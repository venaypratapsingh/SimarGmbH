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
                                        <th data-priority="1">Employee ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Status</th>
                                        <th data-priority="1">Absence Reason</th>
                                        <th data-priority="1">Time In</th>
                                        <th data-priority="1">Break Start</th>
                                        <th data-priority="1">Break End</th>
                                        <th data-priority="1">Time Out</th>
                                        <th data-priority="1">Total Working Time</th>
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
                                                    <form method="POST" action="{{ route('attendance.update_reason', $attendance->id) }}" class="absence-form">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="absence_reason" class="absence-reason-input" value="{{ $attendance->absence_reason }}">
                                                        <div style="display:flex; align-items:center; gap:6px;">
                                                            {{-- Shows selected code prominently --}}
                                                            <span class="absence-selected-display" style="display:inline-block; min-width:32px; font-size:14px; font-weight:800; color:#fff; background:#007bff; border-radius:8px; padding:2px 8px; text-align:center; letter-spacing:1px;">{{ $attendance->absence_reason ?: '?' }}</span>
                                                            {{-- Vertical scrollable pill list --}}
                                                            <div style="display:flex; flex-direction:column; gap:2px; max-height:84px; overflow-y:auto; border:1px solid #b8d4f0; border-radius:8px; padding:3px 4px; background:linear-gradient(135deg,#f8fbff,#eaf2ff); box-shadow:0 2px 6px rgba(0,123,255,.12);">
                                                                @foreach(['K','U','UU','F','SA','SU'] as $code)
                                                                    <button  type="button" class="absence-code-btn {{ $attendance->absence_reason === $code ? 'active-code' : '' }}"
                                                                        data-code="{{ $code }}"
                                                                        style="cursor:pointer; font-size:11px; font-weight:700; padding:2px 9px; border-radius:10px; border:1px solid {{ $attendance->absence_reason === $code ? '#0056b3' : '#b8d4f0' }}; background:{{ $attendance->absence_reason === $code ? '#007bff' : '#fff' }}; color:{{ $attendance->absence_reason === $code ? '#fff' : '#007bff' }}; white-space:nowrap; outline:none; box-shadow:none;">
                                                                        {{ $code }}
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </form>
                                                @else
                                                    &mdash;
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
                                                    {{ $attendance->time_out ?? ($attendance->employee->schedules->first()->time_out ?? 'N/A') }}
                                                @endif
                                            </td>
                                            <td class="total-working-time-cell">{{ $attendance->total_working_time_formatted ?? 'N/A' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9" class="text-right font-weight-bold" style="background:#f8f9fa;">Total Working Hours:</td>
                                        <td style="background:#f8f9fa; font-weight:700; color:#007bff;" id="total-working-sum">
                                            @php
                                                $totalMinutes = 0;
                                                foreach($attendances as $a) {
                                                    if ($a->total_working_time) {
                                                        $parts = explode(':', $a->total_working_time);
                                                        $totalMinutes += (intval($parts[0] ?? 0) * 60) + intval($parts[1] ?? 0);
                                                    }
                                                }
                                                $totalHours = floor($totalMinutes / 60);
                                                $remainingMins = $totalMinutes % 60;
                                            @endphp
                                            {{ sprintf('%02d:%02d', $totalHours, $remainingMins) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection


@section('script')
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
    <style>
        .absence-code-btn:hover { background:#007bff !important; color:#fff !important; border-color:#0056b3 !important; }
        .absence-code-btn.active-code { background:#007bff !important; color:#fff !important; border-color:#0056b3 !important; }
        .absence-code-btn:focus { outline:none !important; box-shadow:none !important; }
    </style>
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });

            var unsavedForms = {};

            // Floating save bar injected once
            $('body').prepend(
                '<div id="absence-save-bar" style="display:none;position:fixed;top:0;left:0;right:0;z-index:9999;background:#fff3cd;border-bottom:2px solid #ffc107;padding:10px 20px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.2);">'
                + '<strong style="color:#856404;margin-right:12px;">&#9888; Unsaved absence reason changes</strong>'
                + '<button id="absence-save-all" class="btn btn-warning btn-sm" style="border-radius:6px;font-weight:600;">&#128190; Save All</button>'
                + '<button id="absence-discard-all" class="btn btn-outline-secondary btn-sm ml-2" style="border-radius:6px;">Discard</button>'
                + '</div>'
            );

            // Click on abbreviation button
            $(document).on('click', '.absence-code-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var code = $(this).data('code').toString().trim();
                var $form = $(this).closest('form.absence-form');
                if (!$form.length) return;

                // Fill hidden input
                $form.find('.absence-reason-input').val(code);

                // Update the badge display
                $form.find('.absence-selected-display').text(code);

                // Update active pill
                $form.find('.absence-code-btn').removeClass('active-code');
                $(this).addClass('active-code');

                // Track unsaved
                unsavedForms[$form.attr('action')] = $form;
                $('#absence-save-bar').slideDown(200);
            });

            // Save All via AJAX
            $(document).on('click', '#absence-save-all', function() {
                var forms = Object.values(unsavedForms);
                if (!forms.length) return;
                var $btn = $(this).prop('disabled', true).text('Saving...');
                var done = 0;
                forms.forEach(function($f) {
                    // Explicitly send only the absence_reason hidden input value
                    var absenceReason = $f.find('.absence-reason-input').val();
                    var csrfToken = $f.find('input[name="_token"]').val();
                    $.ajax({
                        url: $f.attr('action'),
                        method: 'POST',
                        data: {
                            '_token': csrfToken,
                            '_method': 'PUT',
                            'absence_reason': absenceReason
                        },
                        complete: function() {
                            done++;
                            if (done === forms.length) {
                                unsavedForms = {};
                                $('#absence-save-bar').slideUp(200);
                                $btn.prop('disabled', false).html('&#128190; Save All');
                            }
                        }
                    });
                });
            });

            // Discard
            $(document).on('click', '#absence-discard-all', function() {
                location.reload();
            });

            // Warn before leaving with unsaved changes
            window.addEventListener('beforeunload', function(e) {
                if (Object.keys(unsavedForms).length > 0) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved absence reason changes. Leave anyway?';
                }
            });
        });
    </script>
@endsection
