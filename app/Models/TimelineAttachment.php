<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineAttachment extends Model
{
    /** @use HasFactory<\Database\Factories\TimelineAttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'timeline_post_id', 'disk', 'path', 'mime', 'width', 'height', 'size_bytes',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(TimelinePost::class, 'timeline_post_id');
    }

    public function thumbnailPath(): ?string
    {
        if (! str_starts_with($this->mime, 'image/')) {
            return null;
        }

        return preg_replace('/(\.[^.]+)$/', '-thumb$1', $this->path);
    }
}
