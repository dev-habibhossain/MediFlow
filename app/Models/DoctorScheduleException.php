<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'doctor_id',
    'exception_date',
    'type',
    'start_time',
    'end_time',
    'reason',
])]
class DoctorScheduleException extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'exception_date' => 'date',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
