<?php

namespace App\Http\Controllers;

use App\Actions\StoreWorklogAction;
use App\Actions\UpdateWorklogAction;
use App\Http\Requests\StoreWorklogRequest;
use App\Http\Requests\UpdateWorklogRequest;
use App\Http\Resources\WorklogResource;
use App\Models\Worklog;
use App\Models\WorklogFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class WorklogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $worklogs = $request->user()->worklogs()
            ->with('files')
            ->when($request->filled('from_date'), function ($query) use ($request) {
                return $query->whereDate('log_date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                return $query->whereDate('log_date', '<=', $request->to_date);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->query('search');

                return $query->where(function ($q) use ($search) {
                    $q->whereAny([
                        'title',
                        'content'
                    ], 'like', "%{$search}%");
                });
            })
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('worklogs/Index', [
            'worklogs' => WorklogResource::collection($worklogs),
            'filters' => $request->only(['search', 'from_date', 'to_date']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('worklogs/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws Throwable
     */
    public function store(StoreWorklogRequest $request, StoreWorklogAction $action): RedirectResponse
    {
        $validated = $request->validated();

        $action->execute(
            $request->user(),
            $validated,
            $request->hasFile('files') ? $request->file('files') : null
        );

        return to_route('worklogs.index')
            ->with('success', 'Worklog created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Worklog $worklog): Response
    {
        Gate::authorize('view', $worklog);

        $worklog->load('files');

        return inertia('worklogs/Show', [
            'worklog' => new WorklogResource($worklog),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worklog $worklog): Response
    {
        Gate::authorize('update', $worklog);

        $worklog->load('files');

        return inertia('worklogs/Edit', [
            'worklog' => new WorklogResource($worklog),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(UpdateWorklogRequest $request, Worklog $worklog, UpdateWorklogAction $action): RedirectResponse
    {
        Gate::authorize('update', $worklog);

        $validated = $request->validated();

        $action->execute(
            $worklog,
            $validated,
            $request->hasFile('files') ? $request->file('files') : null,
            $validated['remove_files'] ?? null
        );

        return to_route('worklogs.show', $worklog)
            ->with('success', 'Worklog updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worklog $worklog): RedirectResponse
    {
        Gate::authorize('delete', $worklog);

        // Delete associated files from storage
        foreach ($worklog->files as $file) {
            if (Storage::disk('local')->exists($file->file_path)) {
                Storage::disk('local')->delete($file->file_path);
            }
        }

        $worklog->delete();

        return to_route('worklogs.index')
            ->with('success', 'Worklog deleted.');
    }

    /**
     * Download a worklog file.
     */
    public function downloadFile(WorklogFile $worklogFile): BinaryFileResponse
    {
        Gate::authorize('view', $worklogFile->worklog);

        if (! Storage::disk('local')->exists($worklogFile->file_path)) {
            abort(404, 'File not found.');
        }

        return response()->download(
            Storage::disk('local')->path($worklogFile->file_path),
            $worklogFile->og_filename
        );
    }
}
