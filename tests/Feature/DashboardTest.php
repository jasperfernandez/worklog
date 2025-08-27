<?php

use App\Models\User;
use App\Models\Worklog;

it('displays recent worklogs on dashboard for authenticated user', function () {
    $user = User::factory()->create();

    // Create some worklogs for the user
    $worklogs = Worklog::factory(3)->create([
        'user_id' => $user->id,
        'log_date' => now()->toDateString(),
    ]);

    // Create worklogs for another user (should not appear)
    $otherUser = User::factory()->create();
    Worklog::factory(2)->create([
        'user_id' => $otherUser->id,
        'log_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertInertia(function ($page) use ($user) {
        $page->component('Dashboard')
            ->has('recentWorklogs', 3)
            ->where('recentWorklogs.0.user_id', $user->id);
    });
});

it('shows empty state when user has no worklogs', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertInertia(function ($page) {
        $page->component('Dashboard')
            ->has('recentWorklogs', 0);
    });
});

it('limits recent worklogs to 5 entries', function () {
    $user = User::factory()->create();

    // Create 7 worklogs
    Worklog::factory(7)->create([
        'user_id' => $user->id,
        'log_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertInertia(function ($page) {
        $page->component('Dashboard')
            ->has('recentWorklogs', 5); // Should only show 5
    });
});

it('requires authentication to access dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
