<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[Fillable(['employee_id', 'date', 'check_in', 'check_out', 'status'])]
class Attendance extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'date' => 'date',
    ];

    public function getCheckInAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    public function getCheckOutAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
