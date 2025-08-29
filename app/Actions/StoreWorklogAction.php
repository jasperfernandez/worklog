<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Worklog;
use App\Models\WorklogFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreWorklogAction
{
    /**
     * Store a newly created worklog with files.
     *
     * @param array<string, mixed> $data
     * @param array<UploadedFile>|null $files
     * @throws Throwable
     */
    public function execute(User $user, array $data, ?array $files = null): Worklog
    {
        return DB::transaction(function () use ($user, $data, $files) {
            $worklog = $user->worklogs()->create([
                'title' => $data['title'],
                'content' => $data['content'],
                'log_date' => $data['log_date'],
            ]);

            if ($files) {
                $this->storeFiles($worklog, $files);
            }

            return $worklog;
        });
    }

    /**
     * Store uploaded files for the worklog.
     *
     * @param  array<UploadedFile>  $files
     */
    private function storeFiles(Worklog $worklog, array $files): void
    {
        foreach ($files as $file) {
            $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $filePath = $file->storeAs('worklog-files', $filename, 'local');

            WorklogFile::create([
                'filename' => $filename,
                'og_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'worklog_id' => $worklog->id,
            ]);
        }
    }
}
