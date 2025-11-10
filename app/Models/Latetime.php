<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Latetime extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id', 'date', 'time', 'reason'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
