<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

it('renders and switches theme without reload', function () {
    $component = Volt::test('components.theme-toggle');

    $component->assertSee('Light')
        ->assertSee('System')
        ->assertSee('Dark');

    $component->call('setTheme', 'dark')
        ->assertSet('theme', 'dark');
});
