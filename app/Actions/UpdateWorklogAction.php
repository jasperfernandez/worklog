<?php

namespace App\Actions;

use App\Models\Worklog;
use App\Models\WorklogFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateWorklogAction
{
    /**
     * Update an existing worklog with files.
     *
     * @param array<string, mixed> $data
     * @param array<UploadedFile>|null $files
     * @param array<int>|null $removeFiles
     * @throws Throwable
     */
    public function execute(Worklog $worklog, array $data, ?array $files = null, ?array $removeFiles = null): Worklog
    {
        return DB::transaction(function () use ($worklog, $data, $files, $removeFiles) {
            $worklog->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'log_date' => $data['log_date'],
            ]);

            if ($removeFiles) {
                $this->removeFiles($worklog, $removeFiles);
            }

            if ($files) {
                $this->storeFiles($worklog, $files);
            }

            return $worklog->fresh(['files']);
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
                'worklog_id' => $worklog->id,
                'filename' => $filename,
                'og_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }
    }

    /**
     * Remove specified files from the worklog.
     *
     * @param  array<int>  $fileIds
     */
    private function removeFiles(Worklog $worklog, array $fileIds): void
    {
        $filesToRemove = $worklog->files()->whereIn('id', $fileIds)->get();

        foreach ($filesToRemove as $file) {
            // Delete the physical file from storage
            if (Storage::disk('local')->exists($file->file_path)) {
                Storage::disk('local')->delete($file->file_path);
            }

            // Delete the database record
            $file->delete();
        }
    }
}
