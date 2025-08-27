<?php

use App\Models\WorklogFile;

describe('WorklogFile Model', function () {
    it('has fillable attributes', function () {
        $worklogFile = new WorklogFile;

        expect($worklogFile->getFillable())->toBe([
            'filename',
            'original_name',
            'file_path',
            'file_size',
            'mime_type',
            'worklog_id',
        ]);
    });

    it('calculates human readable file size', function () {
        $worklogFile = new WorklogFile;
        $worklogFile->file_size = 1024;

        expect($worklogFile->human_file_size)->toBe('1024 B');
    });
});
