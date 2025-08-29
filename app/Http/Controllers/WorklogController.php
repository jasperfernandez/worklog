<?php

namespace App\Http\Controllers;

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
                $search = $request->search;

                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
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
     */
    public function store(StoreWorklogRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create the worklog
        $worklog = $request->user()->worklogs()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'log_date' => $validated['log_date'],
        ]);

        // Handle file uploads if any
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('worklog-files', $filename, 'local');

                WorklogFile::create([
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'worklog_id' => $worklog->id,
                ]);
            }
        }

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
     */
    public function update(UpdateWorklogRequest $request, Worklog $worklog): RedirectResponse
    {
        Gate::authorize('update', $worklog);

        $validated = $request->validated();

        // Update worklog basic info
        $worklog->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'log_date' => $validated['log_date'],
        ]);

        // Handle file removals
        if (isset($validated['remove_files']) && is_array($validated['remove_files'])) {
            $filesToRemove = WorklogFile::whereIn('id', $validated['remove_files'])
                ->where('worklog_id', $worklog->id)
                ->get();

            foreach ($filesToRemove as $file) {
                // Delete from storage
                if (Storage::disk('local')->exists($file->file_path)) {
                    Storage::disk('local')->delete($file->file_path);
                }
                // Delete from database
                $file->delete();
            }
        }

        // Handle new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('worklog-files', $filename, 'local');

                WorklogFile::create([
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'worklog_id' => $worklog->id,
                ]);
            }
        }

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
            $worklogFile->original_name
        );
    }
}
