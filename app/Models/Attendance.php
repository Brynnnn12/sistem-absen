<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['employee_id', 'date', 'check_in', 'check_out', 'status'])]
class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'check_in' => 'time',
        'check_out' => 'time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
