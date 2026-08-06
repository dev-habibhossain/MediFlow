<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'key',
    'value',
    'type',
    'updated_by',
])]
class Setting extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'value' => 'string',
        ];
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
