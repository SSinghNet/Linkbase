<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public profile page renders active links with the saved theme', function () {
    $user = User::factory()->create([
        'name' => 'Taylor Otwell',
        'username' => 'taylor',
        'bio' => 'Laravel, products, and a few things worth bookmarking.',
        'theme' => [
            'accent' => '#F97316',
            'background' => '#431407',
            'surface' => '#7C2D12',
            'text' => '#FFEDD5',
            'button_style' => 'soft',
        ],
    ]);

    $user->links()->createMany([
        [
            'title' => 'Main Site',
            'url' => 'https://example.com',
            'icon' => 'fa-solid fa-globe',
            'order' => 1,
            'is_active' => true,
        ],
        [
            'title' => 'Newsletter',
            'url' => 'https://example.com/newsletter',
            'icon' => 'fa-regular fa-envelope',
            'order' => 2,
            'is_active' => true,
        ],
        [
            'title' => 'Hidden Draft',
            'url' => 'https://example.com/draft',
            'icon' => 'fa-solid fa-lock',
            'order' => 3,
            'is_active' => false,
        ],
    ]);

    $response = $this->get('/u/'.$user->username);

    $response->assertOk();
    $response->assertSee('Taylor Otwell');
    $response->assertSee('@taylor');
    $response->assertSee('Laravel, products, and a few things worth bookmarking.');
    $response->assertSee('Main Site');
    $response->assertSee('Newsletter');
    $response->assertDontSee('Hidden Draft');
    $response->assertSee('--yb-accent: #F97316', false);
    $response->assertSee('radial-gradient(circle at top, color-mix(in srgb, var(--yb-accent) 22%, transparent), transparent 32%)', false);
    $response->assertSee('https://example.com/newsletter', false);
});
