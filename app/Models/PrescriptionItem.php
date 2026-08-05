<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'prescription_id',
    'medication_name',
    'dosage',
    'frequency',
    'duration',
    'notes',
])]
class PrescriptionItem extends Model
{
    use HasFactory;

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }
}
