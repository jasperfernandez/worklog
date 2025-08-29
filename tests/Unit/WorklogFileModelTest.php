<?php

use App\Models\WorklogFile;

describe('WorklogFile Model', function () {
    it('has fillable attributes', function () {
        $worklogFile = WorklogFile::factory()->make();

        expect($worklogFile->getFillable())->toBe([
            'filename',
            'og_filename',
            'file_path',
            'file_size',
            'mime_type',
            'worklog_id',
        ]);
    });

    it('calculates human readable file size', function () {
        $worklogFile = WorklogFile::factory()->make(['file_size' => 1024]);

        expect($worklogFile->human_file_size)->toBe('1024 B');
    });
});
