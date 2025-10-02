<?php

namespace App\Livewire\Timeline;

use App\Events\TimelinePostCreated;
use App\Jobs\GenerateAttachmentThumbnails;
use App\Models\TimelineAttachment;
use App\Models\TimelinePost;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public ?float $viewerLat = null;

    public ?float $viewerLng = null;

    public ?string $body = null;

    #[Validate(['required', 'numeric', 'between:-90,90'])]
    public ?float $lat = null;

    #[Validate(['required', 'numeric', 'between:-180,180'])]
    public ?float $lng = null;

    public ?string $place_name = null;

    /** @var array<int,\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $images = [];

    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:2000'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'place_name' => ['nullable', 'string', 'max:191'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function create(): void
    {
        $this->validate();

        if (! $this->body && count($this->images) === 0) {
            $this->addError('body', 'Please add text or an image.');

            return;
        }

        $post = TimelinePost::query()->create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'place_name' => $this->place_name,
            'visibility' => 'public',
        ]);

        foreach ($this->images as $img) {
            $path = $img->storePublicly('timeline', 'public');
            $att = TimelineAttachment::query()->create([
                'timeline_post_id' => $post->id,
                'disk' => 'public',
                'path' => $path,
                'mime' => $img->getMimeType(),
                'width' => null,
                'height' => null,
                'size_bytes' => $img->getSize(),
            ]);
            GenerateAttachmentThumbnails::dispatch($att->id);
        }

        $this->reset(['body', 'images']);
        event(new TimelinePostCreated($post->id));
        $this->dispatch('timeline:created');
    }

    public function getPostsProperty(): Paginator
    {
        return TimelinePost::query()
            ->with('attachments', 'user')
            ->latest()
            ->simplePaginate(10);
    }

    public function fetchLatest(): array
    {
        $post = TimelinePost::query()->with('attachments', 'user')->latest()->first();
        if (! $post) {
            return [];
        }

        return [
            'id' => $post->id,
            'body' => $post->body,
            'created_at_human' => $post->created_at->diffForHumans(),
            'place_name' => $post->place_name,
            'lat' => $post->lat,
            'lng' => $post->lng,
            'user' => [
                'username' => optional($post->user)->username,
            ],
            'attachments' => $post->attachments->map(fn ($a) => [
                'disk' => $a->disk,
                'path' => $a->path,
                'thumb' => $a->thumbnailPath(),
            ])->all(),
        ];
    }

    public function render()
    {
        return view('livewire.timeline.index', [
            'mapEnabled' => config('features.timeline_map', true),
        ])->title(__('messages.timeline').' · sar7ne')
            ->with([
                'meta_description' => __('messages.timeline_meta') ?? 'Live timeline of nearby anonymous posts from your community.',
                'og_type' => 'website',
                'canonical' => route('timeline.index'),
            ]);
    }
}
