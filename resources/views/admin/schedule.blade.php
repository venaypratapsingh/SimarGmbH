@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">{{ __('global.schedules') }}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.schedule') }}</a></li>
            
            
        </ol>
    </div>
@endsection
@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>{{ __('global.add') }}</a>
@endsection

@section('content')
@include('includes.flash')

<!--Show Validation Errors here-->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!--End showing Validation Errors here-->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                <thead>
                                    <tr>
                                        <th data-priority="1">ID</th>
                                        <th data-priority="2">{{ __('global.shift') }}</th>
                                        <th data-priority="3">{{ __('global.time_in') }}</th>
                                        <th data-priority="4">{{ __('global.time_out') }}</th>
                                        <th data-priority="5">{{ __('global.break_start') }}</th>
                                        <th data-priority="6">{{ __('global.break_end') }}</th>
                                        <th data-priority="7">{{ __('global.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedules as $schedule)
                                        <tr>
                                            <td> {{ $schedule->id }} </td>
                                            <td> {{ $schedule->slug }} </td>
                                            <td> {{ $schedule->time_in }} </td>
                                            <td> {{ $schedule->time_out }} </td>
                                            <td> {{ $schedule->break_start ?? 'N/A' }} </td>
                                            <td> {{ $schedule->break_end ?? 'N/A' }} </td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#edit{{ $schedule->id }}"
                                                    class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i>
                                                    {{ __('global.edit') }}</button>
                                                <button type="button" data-toggle="modal" data-target="#delete{{ $schedule->id }}"
                                                    class="btn btn-danger btn-sm delete btn-flat"><i
                                                        class='fa fa-trash'></i> {{ __('global.delete') }}</button>
                                            </td>
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

    @foreach ($schedules as $schedule)
        @include('includes.edit_delete_schedule', ['schedule' => $schedule])
    @endforeach

    @include('includes.add_schedule')

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
