<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['booking', 'records', 'billing', 'policies']),
            'question' => fake()->sentence().'?',
            'answer' => fake()->paragraph(),
            'keywords' => implode(' ', fake()->words(5)),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_published' => true,
        ];
    }

    /**
     * Mark the FAQ as unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
