<?php

namespace App\Http\Resources;

use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Worklog
 */
class WorklogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'log_date' => $this->log_date,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'files' => WorklogFileResource::collection($this->whenLoaded('files')),
        ];
    }
}
