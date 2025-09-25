<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

it('renders the explore volt component and contains the search form', function () {
    // Ensure a deterministic locale
    app()->setLocale('en');

    // Render the Volt component and assert the server-rendered output contains expected structural elements
    Volt::test('pages.explore', [
        'searchTerm' => '',
        'trendingUsers' => collect(),
        'featuredUsers' => collect(),
    ])
        ->assertSee('role="search"', escape: false)
        ->assertSee('rounded-3xl');
});
