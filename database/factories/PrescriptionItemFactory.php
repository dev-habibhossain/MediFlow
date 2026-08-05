<?php

namespace Database\Factories;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrescriptionItem>
 */
class PrescriptionItemFactory extends Factory
{
    protected $model = PrescriptionItem::class;

    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::factory(),
            'medication_name' => fake()->randomElement(['Amoxicillin 500mg', 'Paracetamol 500mg', 'Ibuprofen 400mg', 'Metformin 850mg', 'Omeprazole 20mg']),
            'dosage' => '1 tablet',
            'frequency' => 'Twice daily',
            'duration' => '7 days',
            'notes' => 'After meals',
        ];
    }
}
