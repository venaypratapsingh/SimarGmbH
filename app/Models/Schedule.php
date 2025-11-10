<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name', 'time_in', 'time_out', 'break_start', 'break_end'];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function employees()
    {
        return $this->belongsToMany('App\Models\Employee', 'schedule_employees', 'schedule_id', 'emp_id');
    }
}