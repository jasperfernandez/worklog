<?php

use App\Models\Worklog;

describe('Worklog Model', function () {
    it('has fillable attributes', function () {
        $worklog = new Worklog;

        expect($worklog->getFillable())->toBe([
            'title',
            'content',
            'log_date',
            'user_id',
        ]);
    });

    it('casts log_date to date', function () {
        $worklog = new Worklog;

        expect($worklog->getCasts())->toHaveKey('log_date');
    });
});
