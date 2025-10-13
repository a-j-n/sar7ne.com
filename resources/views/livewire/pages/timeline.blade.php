@extends('layouts.app')

@section('title', 'Timeline')

@section('content')
<div class="space-y-8">
    <x-ui.card padding="p-6" class="text-black">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-medium text-black uppercase">Post</label>
                <x-ui.textarea name="content" rows="3" maxlength="500" class="mt-1" placeholder="Share something... (max 500 chars)"></x-ui.textarea>
            </div>
            <div>
                <label class="text-xs font-medium text-black uppercase">Images (up to 4)</label>
                <x-ui.input type="file" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="mt-1" />
            </div>
            @auth
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="anonymous" value="1" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400">
                <span class="text-sm text-black">Post as Anonymous</span>
            </label>
            @endauth
            <div class="flex justify-end">
                <x-ui.button type="submit" variant="primary" size="sm">Post</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    @php($posts = \App\Models\Post::query()->latest()->whereNull('deleted_at')->with('user')->limit(20)->get())

    <div class="grid gap-4">
        @foreach($posts as $post)
            <x-ui.card padding="p-4" class="text-black">
                <div class="flex items-center gap-3 mb-3">
                    @php($isAnon = $post->is_anonymous || !$post->user)
                    <img src="{{ $isAnon ? asset('default-avatar.png') : $post->user->avatarUrl() }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover">
                    <div>
                        <div class="text-sm font-semibold">{{ $isAnon ? __('Anonymous') : '@'.$post->user->username }}</div>
                        <div class="text-xs text-black/60">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="ml-auto">
                        @auth
                            @if($post->user_id === auth()->id())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete') }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="xs">{{ __('messages.delete') }}</x-ui.button>
                                </form>
                            @endif
                        @endauth
                        @guest
                            @if($isAnon)
                                <details>
                                    <summary class="text-xs cursor-pointer">{{ __('messages.delete') }}</summary>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="mt-2 space-y-2">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="delete_token" placeholder="Delete token" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-xs text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
                                        <x-ui.button type="submit" variant="danger" size="xs">{{ __('messages.delete') }}</x-ui.button>
                                    </form>
                                </details>
                            @endif
                        @endguest
                    </div>
                </div>
                @if($post->content)
                    <p class="text-sm text-black/90 whitespace-pre-line">{{ $post->content }}</p>
                @endif
                @if(!empty($post->images))
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        @foreach($post->images as $img)
                            @php($src = Storage::url($img))
                            <img src="{{ $src }}" data-gallery-group="timeline-post-{{ $post->id }}" data-gallery-src="{{ $src }}" class="w-full h-36 object-cover rounded-lg cursor-zoom-in"/>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        @endforeach
    </div>
</div>
@endsection
