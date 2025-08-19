<?php

namespace App\Http\Controllers;

use App\Models\Worklog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorklogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = $request->user()->worklogs()->with('files');

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('log_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('log_date', '<=', $request->to_date);
        }

        // Search by title or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $worklogs = $query->orderBy('log_date', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();

        return Inertia::render('Worklogs/Index', [
            'worklogs' => $worklogs,
            'filters' => $request->only(['search', 'from_date', 'to_date']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Worklogs/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'log_date' => ['required', 'date'],
        ]);

        $request->user()->worklogs()->create($validated);

        return redirect()->route('worklogs.index')
                        ->with('success', 'Worklog created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Worklog $worklog): Response
    {
        $this->authorize('view', $worklog);

        $worklog->load('files');

        return Inertia::render('Worklogs/Show', [
            'worklog' => $worklog,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worklog $worklog): Response
    {
        $this->authorize('update', $worklog);

        $worklog->load('files');

        return Inertia::render('Worklogs/Edit', [
            'worklog' => $worklog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worklog $worklog): RedirectResponse
    {
        $this->authorize('update', $worklog);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'log_date' => ['required', 'date'],
        ]);

        $worklog->update($validated);

        return redirect()->route('worklogs.show', $worklog)
                        ->with('success', 'Worklog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worklog $worklog): RedirectResponse
    {
        $this->authorize('delete', $worklog);

        $worklog->delete();

        return redirect()->route('worklogs.index')
                        ->with('success', 'Worklog deleted successfully!');
    }
}
