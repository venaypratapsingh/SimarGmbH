<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Http\Requests\EmployeeRec;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class EmployeeController extends Controller
{
   


    public function create()
{
    $schedules = Schedule::all(); // Fetch all schedules
    return view('admin.add_employee', compact('schedules'));
}

    public function index()
    {
        
        return view('admin.employee')->with(['employees'=> Employee::all(), 'schedules'=>Schedule::all()]);
    }

    public function store(EmployeeRec $request)
    {
        $request->validated();

        // Use database transaction for data integrity
        \DB::beginTransaction();

        try {
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->restaurant = $request->restaurant;
            $employee->member_since = $request->member_since;
            $employee->save();

            if($request->schedule){
                $schedule = Schedule::whereSlug($request->schedule)->first();
                if ($schedule) {
                    $employee->schedules()->attach($schedule);
                }
            }

            \DB::commit();

            flash()->success('Success','Employee Record has been created successfully !');
            return redirect()->route('employees.index')->with('success');

        } catch (\Exception $e) {
            \DB::rollback();
            flash()->error('Error', 'An error occurred while creating the employee record.');
            return redirect()->back()->withInput();
        }
    }

 
    public function update(EmployeeRec $request, Employee $employee)
    {
        $request->validated();

        // Use database transaction for data integrity
        \DB::beginTransaction();

        try {
            $employee->name = $request->name;
            $employee->restaurant = $request->restaurant;
            $employee->member_since = $request->member_since;
            $employee->save();

            if ($request->schedule) {
                $employee->schedules()->detach();
                $schedule = Schedule::whereSlug($request->schedule)->first();
                if ($schedule) {
                    $employee->schedules()->attach($schedule);
                }
            }

            \DB::commit();

            flash()->success('Success','Employee Record has been Updated successfully !');
            return redirect()->route('employees.index')->with('success');

        } catch (\Exception $e) {
            \DB::rollback();
            flash()->error('Error', 'An error occurred while updating the employee record.');
            return redirect()->back()->withInput();
        }
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('employees.index')->with('success');
    }

    /**
     * Export employees to CSV
     */
    public function exportCSV()
    {
        return Excel::download(new EmployeeExport, 'employees_' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Export employees to Excel
     */
    public function exportExcel()
    {
        return Excel::download(new EmployeeExport, 'employees_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export employees to PDF
     */
    public function exportPDF()
    {
        $employees = Employee::with('schedules')->get();
        $pdf = PDF::loadView('admin.exports.employees_pdf', compact('employees'));
        return $pdf->download('employees_' . date('Y-m-d') . '.pdf');
    }
}
