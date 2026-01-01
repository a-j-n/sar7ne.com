@extends('layouts.app')

@php
    $authorUsername = $post->user?->username;
    $isAnonymous = $post->is_anonymous || !$authorUsername;
    $title = $isAnonymous
        ? __('messages.posts_title').' · '.__('messages.posts.anonymous')
        : __('messages.posts_title').' · @'.$authorUsername;
    $summary = trim((string) \Illuminate\Support\Str::limit((string) ($post->content ?? __('messages.meta_description')), 160));
    $metaImage = (!empty($post->images) && isset($post->images[0]))
        ? Storage::url($post->images[0])
        : asset('opengraph.png');
    $published = optional($post->created_at)->toAtomString();
    $updated = optional($post->updated_at)->toAtomString();
    $canonicalUrl = route('posts.show', $post);
@endphp

@section('title', $title)
@section('meta_description', $summary)
@section('og_title', $title)
@section('og_type', 'article')
@section('canonical', $canonicalUrl)
@section('meta_image', $metaImage)

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $summary,
            'datePublished' => $published,
            'dateModified' => $updated,
            'url' => $canonicalUrl,
            'image' => $metaImage,
            'author' => [
                '@type' => 'Person',
                'name' => $isAnonymous ? __('messages.posts.anonymous') : '@'.$authorUsername,
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<div class="space-y-6 text-black">
    @include('posts.partials.card', ['post' => $post])
    <div id="comments" class="space-y-4">
        <h2 class="text-lg font-semibold">{{ __('messages.comments.title') }}</h2>
        @auth
        <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="space-y-2" id="commentForm">
            @csrf
            <x-ui.textarea id="commentContent" name="content" rows="3" maxlength="1000" class="w-full resize-y" placeholder="{{ __('messages.comments.write_placeholder') }}"></x-ui.textarea>
            <div class="flex items-center justify-between">
                <div class="text-xs text-black/50" id="commentCounter">0/1000</div>
                <x-ui.button id="commentSubmit" type="submit" variant="primary" disabled>{{ __('messages.comments.comment') }}</x-ui.button>
            </div>
            @error('content') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </form>
        @else
        <div class="text-sm text-black/70">{!! str_replace(':link', '<a href="'.route('login').'" class="text-emerald-600 underline">'.__('messages.login').'</a>', __('messages.comments.sign_in_to_comment')) !!}</div>
        @endauth

        @php($comments = $post->comments()->with('user')->latest()->get())
        <div class="space-y-3">
            @forelse ($comments as $comment)
                <x-ui.card padding="p-3 md:p-4" class="text-black">
                    <div class="flex gap-3">
                        <img src="{{ $comment->user->avatarUrl() ?? asset('anon-avatar.svg') }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-slate-200" alt="avatar">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $comment->user->name }}</div>
                                    <span class="text-[11px] text-black/50">· {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                @auth
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-700 underline">{{ __('messages.comments.delete') }}</button>
                                        </form>
                                    @endcan
                                @endauth
                            </div>
                            <div class="mt-1 text-[15px] leading-6 whitespace-pre-line">{{ $comment->content }}</div>
                        </div>
                    </div>
                </x-ui.card>
            @empty
                <x-ui.card padding="p-4" class="text-black/70 text-sm">{{ __('messages.comments.no_comments_yet') }}</x-ui.card>
            @endforelse
        </div>
    </div>
</div>
<script>
    (function(){
        const id = {{ $post->id }};
        const url = window.location.origin + '/posts/' + id;
        const shareBtn = document.getElementById('share-post');
        const copyBtn = document.getElementById('copy-post');
        async function trackShare(){
            try { await fetch(`/posts/${id}/share`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }); } catch(_) {}
        }
            function miniToast(target, text){
                const span = document.createElement('span');
                span.textContent = text;
            span.className = 'ml-2 text-[11px] text-emerald-600';
            target.after(span);
            setTimeout(()=> span.remove(), 1500);
        }
        shareBtn?.addEventListener('click', async function(){
            try {
                if (navigator.share){
                    await trackShare();
                    await navigator.share({ title: 'Post #'+id, url });
                } else {
                    await navigator.clipboard.writeText(url);
                    await trackShare();
                    miniToast(shareBtn, '{{ __('messages.posts.copied') }}');
                }
            } catch(_) {}
        });
        copyBtn?.addEventListener('click', async function(){
            try {
                await navigator.clipboard.writeText(url);
                await trackShare();
                miniToast(copyBtn, '{{ __('messages.posts.copied') }}');
            } catch(_) {}
        });
    })();
</script>
<script>
    (function(){
        const hash = window.location.hash;
        const comments = document.getElementById('comments');
        if (hash === '#comments' && comments) {
            comments.scrollIntoView({ behavior: 'smooth', block: 'start' });
            const ta = document.getElementById('commentContent');
            if (ta) { ta.focus(); }
        }

        const ta = document.getElementById('commentContent');
        const counter = document.getElementById('commentCounter');
        const submit = document.getElementById('commentSubmit');
        function update() {
            if (!ta || !counter || !submit) { return; }
            const len = (ta.value || '').length;
            counter.textContent = `${len}/1000`;
            submit.disabled = (len === 0 || len > 1000);
        }
        ta?.addEventListener('input', update);
        update();
    })();
</script>
@endsection
