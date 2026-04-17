<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('profile updates allow keeping the current username', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'username' => 'same-handle',
        'bio' => 'Before',
    ]);

    $this->actingAs($user);

    Livewire::test('yourbase')
        ->set('name', 'Updated Name')
        ->set('username', 'same-handle')
        ->set('bio', 'After')
        ->call('updateProfile')
        ->assertHasNoErrors();

    expect($user->refresh())
        ->name->toBe('Updated Name')
        ->username->toBe('same-handle')
        ->bio->toBe('After');
});

test('links can change icons to any font awesome class string', function () {
    $user = User::factory()->create();

    $link = $user->links()->create([
        'title' => 'Portfolio',
        'url' => 'https://example.com/portfolio',
        'icon' => 'fa-solid fa-link',
        'order' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test('yourbase')
        ->set('linkForms.saved-'.$link->id.'.icon', 'fa-brands fa-github')
        ->call('saveLinks')
        ->assertHasNoErrors();

    expect($user->refresh()->links()->first()?->icon)->toBe('fa-brands fa-github');
});

test('updating an icon re-renders the current font awesome classes immediately', function () {
    $user = User::factory()->create();

    $link = $user->links()->create([
        'title' => 'Portfolio',
        'url' => 'https://example.com/portfolio',
        'icon' => 'fa-solid fa-link',
        'order' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test('yourbase')
        ->set('linkForms.saved-'.$link->id.'.icon', 'fa-brands fa-github')
        ->assertSeeHtml('fa-brands fa-github');
});

test('preview reflects the public profile layout without repeating the handle inside the card', function () {
    $user = User::factory()->create([
        'name' => 'Preview Person',
        'username' => 'preview-person',
        'bio' => 'A short preview bio.',
    ]);

    $user->links()->create([
        'title' => 'Portfolio',
        'url' => 'https://example.com/portfolio',
        'icon' => 'fa-solid fa-link',
        'order' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    $html = Livewire::test('yourbase')->html();

    expect(substr_count($html, '@preview-person'))->toBe(1)
        ->and($html)->toContain('A short preview bio.')
        ->and($html)->toContain('Portfolio')
        ->and($html)->toContain('radial-gradient(circle at top, color-mix(in srgb, var(--yb-accent) 20%, transparent), transparent 34%)');
});

test('links can be reordered through the drag sort handler', function () {
    $user = User::factory()->create();

    $firstLink = $user->links()->create([
        'title' => 'First',
        'url' => 'https://example.com/first',
        'icon' => 'fa-solid fa-link',
        'order' => 1,
        'is_active' => true,
    ]);

    $secondLink = $user->links()->create([
        'title' => 'Second',
        'url' => 'https://example.com/second',
        'icon' => 'fa-solid fa-briefcase',
        'order' => 2,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test('yourbase')
        ->call('sortLinks', 'saved-'.$secondLink->id, 0)
        ->call('saveLinks')
        ->assertHasNoErrors();

    $savedLinks = $user->refresh()
        ->links()
        ->orderBy('order')
        ->pluck('id')
        ->all();

    expect($savedLinks)->toBe([$secondLink->id, $firstLink->id]);
});

test('saving links updates rows in their current array order', function () {
    $user = User::factory()->create();

    $firstLink = $user->links()->create([
        'title' => 'Portfolio',
        'url' => 'https://example.com/portfolio',
        'icon' => 'fa-solid fa-briefcase',
        'order' => 1,
        'is_active' => true,
    ]);

    $secondLink = $user->links()->create([
        'title' => 'Newsletter',
        'url' => 'https://example.com/newsletter',
        'icon' => 'fa-regular fa-newspaper',
        'order' => 2,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test('yourbase')
        ->set('linkForms', [
            'saved-'.$secondLink->id => [
                'id' => $secondLink->id,
                'local_key' => 'saved-'.$secondLink->id,
                'title' => 'Latest Newsletter',
                'url' => 'https://example.com/latest-newsletter',
                'icon' => 'fa-solid fa-bullhorn',
                'is_active' => false,
            ],
            'new-test-link' => [
                'id' => null,
                'local_key' => 'new-test-link',
                'title' => 'Book a Call',
                'url' => 'https://example.com/call',
                'icon' => 'fa-regular fa-calendar',
                'is_active' => true,
            ],
            'saved-'.$firstLink->id => [
                'id' => $firstLink->id,
                'local_key' => 'saved-'.$firstLink->id,
                'title' => 'Main Portfolio',
                'url' => 'https://example.com/work',
                'icon' => 'fa-solid fa-rocket',
                'is_active' => true,
            ],
        ])
        ->set('linkOrder', [
            'saved-'.$secondLink->id,
            'new-test-link',
            'saved-'.$firstLink->id,
        ])
        ->call('saveLinks')
        ->assertHasNoErrors();

    expect($user->refresh()->links()->count())->toBe(3);

    $savedLinks = $user->links()->orderBy('order')->get()->map(fn (Link $link) => [
        'title' => $link->title,
        'url' => $link->url,
        'icon' => $link->icon,
        'order' => $link->order,
        'is_active' => $link->is_active,
    ])->all();

    expect($savedLinks)->toBe([
        [
            'title' => 'Latest Newsletter',
            'url' => 'https://example.com/latest-newsletter',
            'icon' => 'fa-solid fa-bullhorn',
            'order' => 1,
            'is_active' => false,
        ],
        [
            'title' => 'Book a Call',
            'url' => 'https://example.com/call',
            'icon' => 'fa-regular fa-calendar',
            'order' => 2,
            'is_active' => true,
        ],
        [
            'title' => 'Main Portfolio',
            'url' => 'https://example.com/work',
            'icon' => 'fa-solid fa-rocket',
            'order' => 3,
            'is_active' => true,
        ],
    ]);
});
