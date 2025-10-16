@extends('layouts.app')

@section('title', 'Post #'.$post->id)
@php(
    $summary = trim(Str::limit((string) $post->content, 140)) ?: 'Post #'.$post->id
)
@section('meta_description', $summary)
@section('og_title', 'Post #'.$post->id)
@section('og_type', 'article')
@section('canonical', route('posts.show', $post))
@section('meta_image', (!empty($post->images) && isset($post->images[0])) ? Storage::disk('spaces')->url($post->images[0]) : asset('opengraph.png'))

@section('content')
<div class="space-y-6 text-black">
    <x-ui.card padding="p-0" class="text-black overflow-hidden hover:shadow-lg/60 transition-shadow">
        <div class="p-5 flex items-start gap-3 border-b border-slate-100">
            @php($isAnon = $post->is_anonymous || !$post->user)
            @php($hue = $isAnon ? (crc32((string)$post->id) % 360) : null)
            @if($isAnon)
                <img src="{{ asset('anon-avatar.svg') }}" alt="avatar" class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200" style="color: hsl({{ $hue }}, 80%, 55%)">
            @else
                <img src="{{ $post->user->avatarUrl() }}" alt="avatar" class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200">
            @endif
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <div class="text-sm font-semibold truncate">{{ $isAnon ? __('Anonymous') : '@'.$post->user->username }}</div>
                    <span class="text-[11px] text-black/50">· {{ $post->created_at->diffForHumans() }}</span>
                </div>
                @if(!$isAnon && $post->user?->display_name)
                    <div class="text-[11px] text-black/60 truncate">{{ $post->user->display_name }}</div>
                @endif
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" id="share-post" title="{{ __('messages.posts.share') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 10-3-3m0 3v7m0-7a3 3 0 11-3-3m3 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
                </button>
                <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" id="copy-post" title="{{ __('messages.posts.copy_link') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8a2 2 0 002-2V8m-6 8H8a2 2 0 01-2-2V8m6-4h6a2 2 0 012 2v6"/></svg>
                </button>
                <a href="{{ route('posts') }}" class="text-xs underline text-black/70 hover:text-black">{{ __('messages.posts.back_to_posts') }}</a>
            </div>
        </div>
        @if($post->content)
            <div class="px-5 py-4 text-[15px] leading-6 text-slate-900">{!! nl2br(e($post->content)) !!}</div>
        @endif
        @if(!empty($post->images))
            <div class="px-5 pb-5 grid grid-cols-2 gap-2 md:gap-3">
                @php($group = 'post-'.$post->id)
                @foreach($post->images as $img)
                    @php($src = Storage::disk('spaces')->url($img))
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <img src="{{ $src }}"
                             data-gallery-group="{{ $group }}"
                             data-gallery-src="{{ $src }}"
                             class="w-full h-64 object-cover cursor-zoom-in transition-transform duration-200 hover:scale-[1.02]"
                             alt="Post image"/>
                    </div>
                @endforeach
            </div>
        @endif
    </x-ui.card>
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
@endsection
