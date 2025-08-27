<?php

use App\Models\User;
use App\Models\Worklog;
use App\Models\WorklogFile;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Worklog Index', function () {
    it('displays worklogs for authenticated user', function () {
        $userWorklogs = Worklog::factory()->count(3)->create(['user_id' => $this->user->id]);
        $otherWorklogs = Worklog::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('worklogs.index'));

        $response->assertSuccessful();
        $response->assertInertia(
            fn ($page) => $page
                ->component('worklogs/Index')
                ->has('worklogs.data', 3)
        );
    });

    it('filters worklogs by date range', function () {
        $worklog1 = Worklog::factory()->create([
            'user_id' => $this->user->id,
            'log_date' => '2025-01-01',
        ]);
        $worklog2 = Worklog::factory()->create([
            'user_id' => $this->user->id,
            'log_date' => '2025-01-15',
        ]);
        $worklog3 = Worklog::factory()->create([
            'user_id' => $this->user->id,
            'log_date' => '2025-02-01',
        ]);

        $response = $this->actingAs($this->user)->get(route('worklogs.index', [
            'from_date' => '2025-01-01',
            'to_date' => '2025-01-31',
        ]));

        $response->assertSuccessful();
        $response->assertInertia(
            fn ($page) => $page
                ->component('worklogs/Index')
                ->has('worklogs.data', 2)
        );
    });

    it('searches worklogs by title and content', function () {
        $worklog1 = Worklog::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Laravel Development',
            'content' => 'Working on authentication',
        ]);
        $worklog2 = Worklog::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Database Migration',
            'content' => 'Created user table',
        ]);

        $response = $this->actingAs($this->user)->get(route('worklogs.index', [
            'search' => 'Laravel',
        ]));

        $response->assertSuccessful();
        $response->assertInertia(
            fn ($page) => $page
                ->component('worklogs/Index')
                ->has('worklogs.data', 1)
                ->where('worklogs.data.0.title', 'Laravel Development')
        );
    });

    it('requires authentication', function () {
        $response = $this->get(route('worklogs.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Worklog Creation', function () {
    it('shows create form for authenticated users', function () {
        $response = $this->actingAs($this->user)->get(route('worklogs.create'));

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) => $page->component('worklogs/Create'));
    });

    it('creates a new worklog with valid data', function () {
        $worklogData = [
            'title' => 'Test Worklog',
            'content' => 'This is a test worklog content.',
            'log_date' => '2025-08-19',
        ];

        $response = $this->actingAs($this->user)->post(route('worklogs.store'), $worklogData);

        $response->assertRedirect(route('worklogs.index'));
        $response->assertSessionHas('success', 'Worklog created successfully!');

        $this->assertDatabaseHas('worklogs', [
            'title' => 'Test Worklog',
            'content' => 'This is a test worklog content.',
            'user_id' => $this->user->id,
        ]);
    });

    it('validates required fields', function () {
        $response = $this->actingAs($this->user)->post(route('worklogs.store'), []);

        $response->assertSessionHasErrors(['title', 'content', 'log_date']);
    });

    it('validates title length', function () {
        $response = $this->actingAs($this->user)->post(route('worklogs.store'), [
            'title' => str_repeat('a', 256),
            'content' => 'Valid content',
            'log_date' => '2025-08-19',
        ]);

        $response->assertSessionHasErrors(['title']);
    });

    it('requires authentication for create form', function () {
        $response = $this->get(route('worklogs.create'));

        $response->assertRedirect(route('login'));
    });

    it('requires authentication for store', function () {
        $response = $this->post(route('worklogs.store'), [
            'title' => 'Test',
            'content' => 'Test content',
            'log_date' => '2025-08-19',
        ]);

        $response->assertRedirect(route('login'));
    });
});

describe('Worklog Display', function () {
    it('shows worklog for owner', function () {
        $worklog = Worklog::factory()->create(['user_id' => $this->user->id]);
        $files = WorklogFile::factory()->count(2)->create(['worklog_id' => $worklog->id]);

        $response = $this->actingAs($this->user)->get(route('worklogs.show', $worklog));

        $response->assertSuccessful();
        $response->assertInertia(
            fn ($page) => $page
                ->component('worklogs/Show')
                ->where('worklog.id', $worklog->id)
                ->has('worklog.files', 2)
        );
    });

    it('denies access to other users worklog', function () {
        $otherUser = User::factory()->create();
        $worklog = Worklog::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('worklogs.show', $worklog));

        $response->assertForbidden();
    });

    it('requires authentication', function () {
        $worklog = Worklog::factory()->create();

        $response = $this->get(route('worklogs.show', $worklog));

        $response->assertRedirect(route('login'));
    });
});

describe('Worklog Editing', function () {
    it('shows edit form for owner', function () {
        $worklog = Worklog::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('worklogs.edit', $worklog));

        $response->assertSuccessful();
        $response->assertInertia(
            fn ($page) => $page
                ->component('worklogs/Edit')
                ->where('worklog.id', $worklog->id)
        );
    });

    it('updates worklog with valid data', function () {
        $worklog = Worklog::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'log_date' => '2025-08-20',
        ];

        $response = $this->actingAs($this->user)->put(route('worklogs.update', $worklog), $updateData);

        $response->assertRedirect(route('worklogs.show', $worklog));
        $response->assertSessionHas('success', 'Worklog updated successfully!');

        $this->assertDatabaseHas('worklogs', [
            'id' => $worklog->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    });

    it('validates update data', function () {
        $worklog = Worklog::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put(route('worklogs.update', $worklog), [
            'title' => '',
            'content' => '',
            'log_date' => 'invalid-date',
        ]);

        $response->assertSessionHasErrors(['title', 'content', 'log_date']);
    });

    it('denies access to other users worklog', function () {
        $otherUser = User::factory()->create();
        $worklog = Worklog::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('worklogs.edit', $worklog));

        $response->assertForbidden();
    });

    it('requires authentication', function () {
        $worklog = Worklog::factory()->create();

        $response = $this->get(route('worklogs.edit', $worklog));

        $response->assertRedirect(route('login'));
    });
});

describe('Worklog Deletion', function () {
    it('deletes worklog for owner', function () {
        $worklog = Worklog::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('worklogs.destroy', $worklog));

        $response->assertRedirect(route('worklogs.index'));
        $response->assertSessionHas('success', 'Worklog deleted successfully!');

        $this->assertDatabaseMissing('worklogs', ['id' => $worklog->id]);
    });

    it('denies deletion of other users worklog', function () {
        $otherUser = User::factory()->create();
        $worklog = Worklog::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->delete(route('worklogs.destroy', $worklog));

        $response->assertForbidden();
        $this->assertDatabaseHas('worklogs', ['id' => $worklog->id]);
    });

    it('requires authentication', function () {
        $worklog = Worklog::factory()->create();

        $response = $this->delete(route('worklogs.destroy', $worklog));

        $response->assertRedirect(route('login'));
    });
});
