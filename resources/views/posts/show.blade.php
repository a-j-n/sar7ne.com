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
    @include('posts.partials.card', ['post' => $post])
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
