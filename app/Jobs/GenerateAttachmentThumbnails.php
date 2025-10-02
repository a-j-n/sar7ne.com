<?php

namespace App\Jobs;

use App\Models\TimelineAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class GenerateAttachmentThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $attachmentId) {}

    public function handle(): void
    {
        if (! config('features.thumbnails')) {
            return;
        }

        $att = TimelineAttachment::query()->find($this->attachmentId);
        if (! $att) {
            return;
        }

        if (! str_starts_with($att->mime, 'image/')) {
            return;
        }

        $disk = Storage::disk($att->disk);
        $source = $att->path;
        if (! $disk->exists($source)) {
            return;
        }

        $image = Image::read($disk->path($source));
        // Create a simple 600px wide thumbnail maintaining aspect ratio
        $thumb = clone $image;
        $thumb->scaleDown(width: 600);

        $thumbPath = preg_replace('/(\.[^.]+)$/', '-thumb$1', $source);
        $disk->put($thumbPath, (string) $thumb->encodeByMediaType($att->mime));
    }
}
