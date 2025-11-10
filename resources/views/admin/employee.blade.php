@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Employees</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees List</a></li>
  
    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>
        
<div class="btn-group ml-2">
    <button type="button" class="btn btn-success btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-download mr-2"></i>Export
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('employees.export.csv') }}"><i class="mdi mdi-file-delimited-outline mr-2"></i>CSV</a>
        <a class="dropdown-item" href="{{ route('employees.export.excel') }}"><i class="mdi mdi-file-excel-outline mr-2"></i>Excel</a>
        <a class="dropdown-item" href="{{ route('employees.export.pdf') }}"><i class="mdi mdi-file-pdf-outline mr-2"></i>PDF</a>
    </div>
</div>

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
                                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                                    <thead>
                                                    <tr>
                                                        <th data-priority="1">Employee ID</th>
                                                        <th data-priority="2">Name</th>
                                                        <th data-priority="3">Restaurant</th>
                                                        <!-- Removed Email Column -->
                                                        <!-- <th data-priority="4">Email</th> -->
                                                        <th data-priority="5">Schedule</th>
                                                        <th data-priority="6">Member Since</th>
                                                        <th data-priority="7">Actions</th>
                                                     
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $employees as $employee)

                                                        <tr>
                                                            <td>{{$employee->id}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->restaurant}}</td>
                                                            <!-- Removed Email Data -->
                                                            <!-- <td>{{$employee->email}}</td> -->
                                                            <td>
                                                                @if(isset($employee->schedules->first()->slug))
                                                                {{$employee->schedules->first()->slug}}
                                                                @endif
                                                            </td>
                                                            <td>{{$employee->created_at}}</td>
                                                            <td>
                        
                                                                <a href="#edit{{$employee->name}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                                                <a href="#delete{{$employee->name}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
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
                                    

@foreach( $employees as $employee)
@include('includes.edit_delete_employee')
@endforeach

@include('includes.add_employee')

@endsection


@section('script')
<!-- Responsive-table-->

@endsection