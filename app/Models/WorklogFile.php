<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\WorklogFileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $filename
 * @property-read string $original_name
 * @property-read string $file_path
 * @property-read int $file_size
 * @property-read string $mime_type
 * @property-read int $worklog_id
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
class WorklogFile extends Model
{
    /** @use HasFactory<WorklogFileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'worklog_id',
    ];

    /**
     * Get the worklog that owns the file.
     */
    public function worklog(): BelongsTo
    {
        return $this->belongsTo(Worklog::class);
    }

    /**
     * Get the human-readable file size.
     */
    protected function humanFileSize(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->formatFileSize($this->file_size),
        );
    }

    /**
     * Format file size in human-readable format.
     */
    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
