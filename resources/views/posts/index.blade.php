@extends('layouts.app')

@section('title', __('messages.posts_title'))

@section('content')
@php
    $postsQuery = \App\Models\Post::query()->whereNull('deleted_at');
    $posts = (clone $postsQuery)->latest()->with(['user'])->withCount('comments')->limit(20)->get();
    $postCount = (clone $postsQuery)->count();
    $todayCount = (clone $postsQuery)->whereDate('created_at', now()->toDateString())->count();
@endphp

<div class="space-y-8">
    <x-ui.card padding="p-5 sm:p-7" class="relative overflow-hidden card-brand-gradient">
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-brand-orange/20 blur-2xl"></div>
        <div class="absolute -left-10 bottom-0 h-32 w-32 rounded-full bg-neon-mint/20 blur-2xl"></div>

        <div class="relative grid gap-6 lg:grid-cols-[1.1fr,0.9fr] items-start">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/40 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.08em] text-brand-orange shadow-sm dark:border-slate-700/70 dark:bg-slate-900/60">
                    <span class="h-2 w-2 rounded-full bg-brand-orange animate-pulse"></span>
                    {{ __('messages.posts_title') }}
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-slate-900 dark:text-white">{{ __('messages.posts_title') }}</h1>
                    <p class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed max-w-2xl break-words">
                        Share updates or anonymous drops. Long posts wrap cleanly on every device, and media stays contained.
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 max-w-2xl">
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts.total_posts') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($postCount) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.posts.across_community') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts.today_posts') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($todayCount) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.posts.new_drops') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts.stay_kind') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">🧡</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.posts.keep_it_thoughtful') }}</p>
                    </div>
                </div>
            </div>

            <div class="relative rounded-2xl border border-white/60 bg-white/80 p-4 shadow-xl backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts.quick_tip') }}</p>
                        <p class="text-base font-semibold text-slate-900 dark:text-white">{{ __('messages.posts.pin_best_post') }}</p>
                    </div>
                    <span class="rounded-full bg-brand-orange/10 px-2 py-1 text-[10px] font-semibold text-brand-orange">{{ __('messages.live_now') }}</span>
                </div>
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed break-words">
                    {{ __('messages.posts.tip_body') }}
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('messages.posts.post') }}
                    </a>
                    <a href="#feed" class="inline-flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('messages.posts.jump_to_feed') }}
                    </a>
                </div>
            </div>
        </div>
    </x-ui.card>

    <div class="flex items-center justify-between">
        <div class="text-sm text-slate-600 dark:text-slate-400">
            {{ number_format($posts->count()) }} {{ __('messages.posts_title') }} · {{ number_format($postCount) }} total
        </div>
        <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('messages.posts.post') }}
        </a>
    </div>

    <div id="feed" class="grid gap-4 md:gap-5">
        @foreach($posts as $post)
            @include('posts.partials.card', ['post' => $post])
        @endforeach
    </div>

    <a
        href="{{ route('posts.create') }}"
        class="fixed z-[9980]
               bottom-[calc(1.25rem+env(safe-area-inset-bottom))] right-[calc(1.25rem+env(safe-area-inset-right))]
               md:bottom-[calc(1.75rem+env(safe-area-inset-bottom))] md:right-[calc(1.75rem+env(safe-area-inset-right))]
               h-12 w-12 md:h-14 md:w-14 rounded-full
               bg-gradient-orange-pink text-white shadow-lg shadow-brand-orange/30
               hover:shadow-xl hover:translate-y-[-2px]
               focus-visible:outline focus-visible:ring-4 focus-visible:ring-brand-orange/40
               flex items-center justify-center transition-transform duration-200 ease-out"
        aria-label="{{ __('messages.posts.post') }}"
    >
        <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="sr-only">{{ __('messages.posts.post') }}</span>
    </a>

    <script>
        (function(){
            async function trackShare(id){
                try { await fetch(`/posts/${id}/share`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }); } catch(_) {}
            }
            function postUrl(id){ return window.location.origin + '/posts/' + id; }
            function showMiniToast(el, text){
                const span = document.createElement('span');
                span.textContent = text;
                span.className = 'ml-2 text-[11px] text-emerald-600';
                el.after(span);
                setTimeout(()=> span.remove(), 1500);
            }
            document.addEventListener('click', async function(e){
                const shareBtn = e.target.closest('[data-share-post]');
                const copyBtn = e.target.closest('[data-copy-post]');
                if (shareBtn){
                    const id = shareBtn.getAttribute('data-share-post');
                    const url = postUrl(id);
                    try {
                        if (navigator.share){
                            await trackShare(id);
                            await navigator.share({ title: 'Post #' + id, url });
                        } else {
                            await navigator.clipboard.writeText(url);
                            await trackShare(id);
                            showMiniToast(shareBtn, 'Copied!');
                        }
                    } catch(_) {}
                }
                if (copyBtn){
                    const id = copyBtn.getAttribute('data-copy-post');
                    const url = postUrl(id);
                    try {
                        await navigator.clipboard.writeText(url);
                        await trackShare(id);
                        showMiniToast(copyBtn, 'Copied!');
                    } catch(_) {}
                }
            });
        })();
    </script>

    <!-- Delete token modals -->
    @guest
        @foreach($posts as $post)
            @php($isAnon = $post->is_anonymous || !$post->user)
            @if($isAnon)
                <div id="modal-post-{{ $post->id }}" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 p-4">
                    <div class="w-full max-w-sm rounded-xl bg-white text-black shadow-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold">{{ __('messages.delete') }}</h3>
                            <button type="button" class="text-slate-500 hover:text-slate-700" data-close-delete="post-{{ $post->id }}">✕</button>
                        </div>
                        <p class="text-xs text-black/70 mb-3">Enter the delete token you received when creating this post.</p>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('DELETE')
                            <x-ui.input type="text" name="delete_token" placeholder="Delete token" required />
                            <div class="flex justify-end gap-2 pt-1">
                                <x-ui.button type="button" variant="outline" size="sm" data-close-delete="post-{{ $post->id }}">{{ __('messages.cancel') }}</x-ui.button>
                                <x-ui.button type="submit" variant="danger" size="sm">{{ __('messages.delete') }}</x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
        <script>
            (function(){
                document.querySelectorAll('[data-open-delete]').forEach(btn => {
                    btn.addEventListener('click', function(){
                        const id = this.getAttribute('data-open-delete');
                        const modal = document.getElementById('modal-' + id);
                        if (modal){ modal.classList.remove('hidden'); modal.classList.add('flex'); }
                    });
                });
                document.querySelectorAll('[data-close-delete]').forEach(btn => {
                    btn.addEventListener('click', function(){
                        const id = this.getAttribute('data-close-delete');
                        const modal = document.getElementById('modal-' + id);
                        if (modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); }
                    });
                });
                document.addEventListener('keydown', function(e){
                    if (e.key === 'Escape'){
                        document.querySelectorAll('[id^="modal-post-"]').forEach(m => { m.classList.add('hidden'); m.classList.remove('flex'); });
                    }
                });
                document.addEventListener('click', function(e){
                    const modal = e.target.closest('[id^="modal-post-"]');
                    if (modal && e.target === modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); }
                });
            })();
        </script>
    @endguest
    <script>
        (function(){
            async function toggleLike(postId, button){
                if (!postId || !button) return;
                if (button.dataset.loading === '1') return;
                button.dataset.loading = '1';
                const iconLiked = 'border-emerald-300 bg-emerald-50 text-emerald-700';
                const iconDefault = 'border-slate-200 text-black/70 hover:text-black hover:border-slate-300';
                try {
                    const res = await fetch(`/posts/${postId}/like`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    const data = await res.json();
                    if (res.status === 401 && data && data.requires_auth){
                        const modal = document.getElementById('likeLoginModal');
                        modal?.classList.remove('hidden');
                        modal?.classList.add('flex');
                        return;
                    }
                    const liked = !!data.liked;
                    const count = data.count ?? 0;
                    const label = button.querySelector('[data-like-text]');
                    const countEl = button.querySelector('[data-like-count]');
                    if (liked){
                        button.classList.remove(...iconDefault.split(' '));
                        button.classList.add(...iconLiked.split(' '));
                        if (label) { label.textContent = '{{ __('messages.unlike') }}'; }
                        button.setAttribute('aria-pressed', 'true');
                    } else {
                        button.classList.remove(...iconLiked.split(' '));
                        button.classList.add(...iconDefault.split(' '));
                        if (label) { label.textContent = '{{ __('messages.like') }}'; }
                        button.setAttribute('aria-pressed', 'false');
                    }
                    if (countEl){ countEl.textContent = count; }
                } catch (e) {
                } finally {
                    button.dataset.loading = '0';
                }
            }
            document.addEventListener('click', function(e){
                const btn = e.target.closest('[data-like-post]');
                if (btn){ e.preventDefault(); toggleLike(btn.getAttribute('data-like-post'), btn); }
            });
        })();
    </script>
</div>
@endsection
