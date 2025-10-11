@extends('layouts.app')

@section('title', __('messages.posts'))

@section('content')
<div class="space-y-8">
    <x-ui.card padding="p-6" class="text-black">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-medium text-black uppercase">Post</label>
                <textarea name="content" rows="3" maxlength="500" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="Share something... (max 500 chars)"></textarea>
            </div>
            <div>
                <label class="text-xs font-medium text-black uppercase">Images (up to 4)</label>
                <input type="file" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
            </div>
            @auth
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="anonymous" value="1" class="h-4 w-4 rounded border-slate-300 bg-slate-50 text-emerald-500 focus:ring-emerald-400">
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
            <x-ui.card id="post-{{ $post->id }}" padding="p-4" class="text-black transition-all duration-200 hover:shadow-lg hover:border-brand-orange/30 animate-fade-in-up" style="animation-delay: {{ ($loop->index % 12) * 60 }}ms">
                <div class="flex items-center gap-3 mb-3">
                    @php($isAnon = $post->is_anonymous || !$post->user)
                    @php($hue = $isAnon ? (crc32((string)$post->id) % 360) : null)
                    @if($isAnon)
                        <img src="{{ asset('anon-avatar.svg') }}" alt="avatar" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200" style="color: hsl({{ $hue }}, 80%, 55%)">
                    @else
                        <img src="{{ $post->user->avatarUrl() }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200">
                    @endif
                    <div class="min-w-0">
                        <div class="text-sm font-semibold truncate">{{ $isAnon ? __('Anonymous') : '@'.$post->user->username }}</div>
                        <div class="text-xs text-black/60">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <!-- Share / Copy actions -->
                        <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300" data-share-post="{{ $post->id }}" title="Share">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 10-3-3m0 3v7m0-7a3 3 0 11-3-3m3 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
                        </button>
                        <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300" data-copy-post="{{ $post->id }}" title="Copy link">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8a2 2 0 002-2V8m-6 8H8a2 2 0 01-2-2V8m6-4h6a2 2 0 012 2v6"/></svg>
                        </button>
                        @auth
                            @if($post->user_id === auth()->id())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete') }}?')" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="xs">
                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        {{ __('messages.delete') }}
                                    </x-ui.button>
                                </form>
                            @endif
                        @endauth
                        @guest
                            @if($isAnon)
                                <button type="button" class="text-xs text-black/70 hover:text-black underline" data-open-delete="post-{{ $post->id }}">{{ __('messages.delete') }}…</button>
                            @endif
                        @endguest
                    </div>
                </div>
                @if($post->content)
                    <a href="{{ route('posts.show', $post) }}" class="block group/link">
                        <p class="text-[13px] leading-relaxed text-black/90 whitespace-pre-line break-words group-hover/link:underline">{{ $post->content }}</p>
                    </a>
                @endif
                @if(!empty($post->images))
                    <a href="{{ route('posts.show', $post) }}" class="block mt-3">
                        <div class="grid grid-cols-2 gap-2 md:gap-3">
                            @foreach($post->images as $img)
                                <div class="overflow-hidden rounded-lg">
                                    <img src="{{ Storage::url($img) }}" class="w-full h-36 object-cover transition-transform duration-200 hover:scale-105"/>
                                </div>
                            @endforeach
                        </div>
                    </a>
                @endif
            </x-ui.card>
        @endforeach
    </div>

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
                            <input type="text" name="delete_token" placeholder="Delete token" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" required>
                            <div class="flex justify-end gap-2 pt-1">
                                <x-ui.button type="button" variant="outline" size="sm" data-close-delete="post-{{ $post->id }}">Cancel</x-ui.button>
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
                    // Close when clicking backdrop (element itself, not the inner card)
                    if (modal && e.target === modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); }
                });
            })();
        </script>
    @endguest
</div>
@endsection
