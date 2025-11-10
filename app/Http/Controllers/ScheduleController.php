<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleEmp;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
   
    public function index()
    {
     
        return view('admin.schedule')->with('schedules', Schedule::all());
        flash()->success('Success','Schedule has been created successfully !');

    }


    public function store(ScheduleEmp $request)
    {
        $request->validated();

        $schedule = new schedule;
        $schedule->slug = $request->slug;
        $schedule->name = $request->slug; // Use slug as name since form doesn't have name field
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->break_start = $request->break_start;
        $schedule->break_end = $request->break_end;
        $schedule->save();

        flash()->success('Success','Schedule has been created successfully !');
        return redirect()->route('schedule.index');

    }

    public function update(ScheduleEmp $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request['time_in'] = str_split($request->time_in, 5)[0];
        $request['time_out'] = str_split($request->time_out, 5)[0];

        $request->validated();

        $schedule->slug = $request->slug;
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->break_start = $request->break_start;
        $schedule->break_end = $request->break_end;
        $schedule->save();
        flash()->success('Success','Schedule has been Updated successfully !');
        return redirect()->route('schedule.index');

    }

  
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        flash()->success('Success','Schedule has been deleted successfully !');
        return redirect()->route('schedule.index');
    }
}
