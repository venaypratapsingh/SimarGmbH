<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_id',
        'leave_date',
        'status',
        'reason',
        'leave_type',
        'duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'leave_date' => 'date',
    ];

    /**
     * Get the employee that owns the leave record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
