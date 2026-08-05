<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id',
    'department_id',
    'specialization',
    'qualifications',
    'bio',
    'years_of_experience',
    'consultation_fee',
    'license_number',
    'status',
])]
class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'years_of_experience' => 'integer',
            'consultation_fee' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function secondaryDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_doctor')->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function scheduleExceptions(): HasMany
    {
        return $this->hasMany(DoctorScheduleException::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
