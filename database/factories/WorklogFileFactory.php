<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorklogFile>
 */
class WorklogFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = fake()->uuid().'.txt';

        return [
            'filename' => $filename,
            'original_name' => fake()->word().'.txt',
            'file_path' => 'worklog-files/'.$filename,
            'file_size' => fake()->numberBetween(1000, 1000000),
            'mime_type' => 'text/plain',
            'worklog_id' => \App\Models\Worklog::factory(),
        ];
    }
}
