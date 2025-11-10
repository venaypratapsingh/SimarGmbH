<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BiometricDeviceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CheckController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('attended/{user_id}', [AttendanceController::class, 'attended'])->name('attended');
Route::get('attended-before/{user_id}', [AttendanceController::class, 'attendedBefore'])->name('attendedBefore');

Auth::routes(['register' => false, 'reset' => false]);

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'de'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang');

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
    Route::resource('employees', EmployeeController::class);
    // Employee export routes
    Route::get('employees/export/csv', [EmployeeController::class, 'exportCSV'])->name('employees.export.csv');
    Route::get('employees/export/excel', [EmployeeController::class, 'exportExcel'])->name('employees.export.excel');
    Route::get('employees/export/pdf', [EmployeeController::class, 'exportPDF'])->name('employees.export.pdf');
    
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::put('attendance/update_reason/{id}', [AttendanceController::class, 'updateReason'])->name('attendance.update_reason');
    // Attendance export routes
    Route::get('attendance/export/csv', [AttendanceController::class, 'exportCSV'])->name('attendance.export.csv');
    Route::get('attendance/export/excel', [AttendanceController::class, 'exportExcel'])->name('attendance.export.excel');
    Route::get('attendance/export/pdf', [AttendanceController::class, 'exportPDF'])->name('attendance.export.pdf');
    
    Route::get('admin', [AdminController::class, 'index'])->name('admin');
    Route::resource('schedule', ScheduleController::class);
    Route::get('check', [CheckController::class, 'index'])->name('check');
    Route::get('sheet-report', [CheckController::class, 'sheetReport'])->name('sheet-report');
    Route::get('sheet-report/export/pdf', [CheckController::class, 'exportPDF'])->name('sheet-report.export.pdf');
    Route::post('check-store', [CheckController::class, 'CheckStore'])->name('check_store');
    
    // Fingerprint Devices
    Route::resource('finger_device', BiometricDeviceController::class);
    Route::delete('finger_device/destroy', [BiometricDeviceController::class, 'massDestroy'])->name('finger_device.massDestroy');
    Route::get('finger_device/{fingerDevice}/employees/add', [BiometricDeviceController::class, 'addEmployee'])->name('finger_device.add.employee');
    Route::get('finger_device/{fingerDevice}/get/attendance', [BiometricDeviceController::class, 'getAttendance'])->name('finger_device.get.attendance');
    Route::get('finger_device/clear/attendance', [BiometricDeviceController::class, 'clearAttendance'])->name('finger_device.clear.attendance');
});

Route::group(['middleware' => ['auth']], function () {
    // Add authenticated user routes here
});

// Route::get('/attendance/assign', function () {
//     return view('attendance_leave_login');
// })->name('attendance.login');

// Route::post('/attendance/assign', '\App\Http\Controllers\AttendanceController@assign')->name('attendance.assign');


// Route::get('/leave/assign', function () {
//     return view('attendance_leave_login');
// })->name('leave.login');

// Route::post('/leave/assign', '\App\Http\Controllers\LeaveController@assign')->name('leave.assign');


// Route::get('{any}', 'App\Http\Controllers\VeltrixController@index');
