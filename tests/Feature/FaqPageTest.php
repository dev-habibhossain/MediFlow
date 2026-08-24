<?php

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('faq page renders with database-driven faqs grouped by category', function () {
    Faq::factory()->create(['category' => 'booking', 'is_published' => true]);
    Faq::factory()->create(['category' => 'billing', 'is_published' => true]);
    Faq::factory()->create(['category' => 'booking', 'is_published' => false]);

    $this->get(route('faq'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Faq')
            ->has('faqs')
            ->has('faqs.booking', 1)
            ->has('faqs.billing', 1)
            ->missing('faqs.records')
        );
});

test('unpublished faqs are excluded from the faq page', function () {
    Faq::factory()->create(['category' => 'policies', 'is_published' => false]);

    $this->get(route('faq'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Faq')
            ->missing('faqs.policies')
        );
});

test('homepage includes top 4 published faqs', function () {
    Faq::factory()->count(6)->create(['is_published' => true, 'sort_order' => 1]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('faqs', 4)
        );
});

test('homepage faqs are ordered by sort_order', function () {
    Faq::factory()->create(['question' => 'Second', 'sort_order' => 2, 'is_published' => true]);
    Faq::factory()->create(['question' => 'First', 'sort_order' => 1, 'is_published' => true]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('faqs', 2)
            ->where('faqs.0.question', 'First')
            ->where('faqs.1.question', 'Second')
        );
});
