<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('verified users can view the yourbase page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('yourbase'))
        ->assertOk()
        ->assertViewIs('yourbase');
});

test('verified users can view the analytics page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('analytics'))
        ->assertOk()
        ->assertViewIs('analytics');
});
