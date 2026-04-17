<?php

use App\Models\Link;
use App\Models\LinkClick;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('analytics page shows filtered totals and summaries', function () {
    $user = User::factory()->create();
    $link = Link::query()->create([
        'user_id' => $user->id,
        'title' => 'Portfolio',
        'url' => 'https://example.com/portfolio',
        'order' => 1,
        'is_active' => true,
    ]);
    $inactiveLink = Link::query()->create([
        'user_id' => $user->id,
        'title' => 'Archive',
        'url' => 'https://example.com/archive',
        'order' => 2,
        'is_active' => false,
    ]);

    ProfileView::query()->create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Mozilla/5.0 Chrome/124.0',
        'country' => 'US',
        'viewed_at' => now()->subDays(2),
    ]);
    ProfileView::query()->create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.2',
        'user_agent' => 'Mozilla/5.0 Safari/605.1.15',
        'country' => 'US',
        'viewed_at' => now()->subDays(40),
    ]);

    LinkClick::query()->create([
        'link_id' => $link->id,
        'ip_address' => '127.0.0.3',
        'user_agent' => 'Mozilla/5.0 Chrome/124.0',
        'country' => 'US',
        'clicked_at' => now()->subDays(1),
    ]);
    LinkClick::query()->create([
        'link_id' => $link->id,
        'ip_address' => '127.0.0.4',
        'user_agent' => 'Mozilla/5.0 Chrome/124.0',
        'country' => 'US',
        'clicked_at' => now()->subDays(35),
    ]);
    LinkClick::query()->create([
        'link_id' => $inactiveLink->id,
        'ip_address' => '127.0.0.5',
        'user_agent' => 'Mozilla/5.0 Safari/605.1.15',
        'country' => 'US',
        'clicked_at' => now()->subDays(120),
    ]);

    $this->actingAs($user)
        ->get(route('analytics', ['range' => '7d']))
        ->assertOk()
        ->assertViewIs('analytics')
        ->assertSee('View Total')
        ->assertSee('Click Total')
        ->assertSee('Click Through Rate')
        ->assertSee('Active Links Total')
        ->assertSee('Clicks Over Time')
        ->assertSee('Clicks Per Link')
        ->assertSee('User Agent Summary')
        ->assertSee('Portfolio')
        ->assertSee('Chrome')
        ->assertSee('100.0%')
        ->assertSee('>1<', false)
        ->assertDontSee('Safari');

    $this->actingAs($user)
        ->get(route('analytics', ['range' => 'all']))
        ->assertOk()
        ->assertSee('Safari')
        ->assertSee('150.0%')
        ->assertSee('Archive');
});

test('analytics page accepts a custom date range', function () {
    $user = User::factory()->create();
    $link = Link::query()->create([
        'user_id' => $user->id,
        'title' => 'Docs',
        'url' => 'https://example.com/docs',
        'order' => 1,
        'is_active' => true,
    ]);

    ProfileView::query()->create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.10',
        'user_agent' => 'Mozilla/5.0 Safari/605.1.15',
        'country' => 'US',
        'viewed_at' => now()->subDays(40),
    ]);
    ProfileView::query()->create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.11',
        'user_agent' => 'Mozilla/5.0 Chrome/124.0',
        'country' => 'US',
        'viewed_at' => now()->subDays(5),
    ]);

    LinkClick::query()->create([
        'link_id' => $link->id,
        'ip_address' => '127.0.0.12',
        'user_agent' => 'Mozilla/5.0 Chrome/124.0',
        'country' => 'US',
        'clicked_at' => now()->subDays(35),
    ]);

    $startDate = now()->subDays(45)->toDateString();
    $endDate = now()->subDays(30)->toDateString();

    $this->actingAs($user)
        ->get(route('analytics', [
            'range' => 'custom',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]))
        ->assertOk()
        ->assertSee('Custom')
        ->assertSee('Custom range')
        ->assertSee($startDate, false)
        ->assertSee($endDate, false)
        ->assertSee('Safari')
        ->assertSee('100.0%')
        ->assertSee('Chrome');
});
