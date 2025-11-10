<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        Schedule::create(['slug' => 'morning', 'name' => 'Morning Shift', 'time_in' => '09:00:00', 'time_out' => '17:00:00']);
        Schedule::create(['slug' => 'evening', 'name' => 'Evening Shift', 'time_in' => '17:00:00', 'time_out' => '01:00:00']);
    }
}