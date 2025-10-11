@extends('layouts.app')

@section('title', 'Post #'.$post->id)
@php(
    $summary = trim(Str::limit((string) $post->content, 140)) ?: 'Post #'.$post->id
)
@section('meta_description', $summary)
@section('og_title', 'Post #'.$post->id)
@section('og_type', 'article')
@section('canonical', route('posts.show', $post))
@section('meta_image', (!empty($post->images) && isset($post->images[0])) ? Storage::url($post->images[0]) : asset('opengraph.png'))

@section('content')
<div class="space-y-6 text-black">
    <x-ui.card padding="p-6" class="text-black">
        <div class="flex items-center gap-3 mb-4">
            @php($isAnon = $post->is_anonymous || !$post->user)
            @php($hue = $isAnon ? (crc32((string)$post->id) % 360) : null)
            @if($isAnon)
                <img src="{{ asset('anon-avatar.svg') }}" alt="avatar" class="h-12 w-12 rounded-full object-cover ring-1 ring-slate-200" style="color: hsl({{ $hue }}, 80%, 55%)">
            @else
                <img src="{{ $post->user->avatarUrl() }}" alt="avatar" class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200">
            @endif
            <div class="min-w-0">
                <div class="text-sm font-semibold truncate">{{ $isAnon ? __('Anonymous') : '@'.$post->user->username }}</div>
                <div class="text-xs text-black/70">{{ $post->created_at->diffForHumans() }}</div>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300" id="share-post" title="Share">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 10-3-3m0 3v7m0-7a3 3 0 11-3-3m3 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
                </button>
                <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300" id="copy-post" title="Copy link">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8a2 2 0 002-2V8m-6 8H8a2 2 0 01-2-2V8m6-4h6a2 2 0 012 2v6"/></svg>
                </button>
                <a href="{{ route('posts') }}" class="text-xs underline text-black/70 hover:text-black">Back to posts</a>
            </div>
        </div>
        @if($post->content)
            <p class="text-[13px] leading-relaxed whitespace-pre-line break-words">{{ $post->content }}</p>
        @endif
        @if(!empty($post->images))
            <div class="mt-3 grid grid-cols-2 gap-2 md:gap-3">
                @foreach($post->images as $img)
                    <div class="overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($img) }}" class="w-full h-64 object-cover"/>
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
                    miniToast(shareBtn, 'Copied!');
                }
            } catch(_) {}
        });
        copyBtn?.addEventListener('click', async function(){
            try {
                await navigator.clipboard.writeText(url);
                await trackShare();
                miniToast(copyBtn, 'Copied!');
            } catch(_) {}
        });
    })();
    </script>
@endsection
