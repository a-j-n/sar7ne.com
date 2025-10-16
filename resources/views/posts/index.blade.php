@extends('layouts.app')

@section('title', __('messages.posts_title'))

@section('content')
<div class="space-y-8">
    <x-ui.card padding="p-0" class="text-black hidden">
        <!-- Hidden stub so existing JS selectors remain valid; actual UI uses floating button + sheet -->
        <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 hidden">
            @csrf
            <div class="flex items-start gap-3">
                <img src="{{ optional(auth()->user())->avatarUrl() ?? asset('anon-avatar.svg') }}" alt="avatar" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                <div class="flex-1">
                    <x-ui.textarea id="postContent" name="content" rows="1" maxlength="500" class="w-full resize-none rounded-2xl px-4 py-3 text-[15px]" placeholder="{{ __('messages.posts.whats_happening') }}">{{ old('content') }}</x-ui.textarea>
                    @error('content') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                    <div id="imagePreview" class="mt-3 grid grid-cols-2 gap-2 md:gap-3"></div>
                </div>
            </div>

            <div id="dropZone" class="pl-13 -mt-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <label for="imageInput" class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[13px] text-emerald-700 bg-emerald-50 hover:bg-emerald-100 cursor-pointer border border-emerald-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-3h6l2 3h4v12H3z"/></svg>
                            {{ __('messages.posts.add_photos') }}
                        </label>
                        <input id="imageInput" type="file" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="hidden">
                        <span id="imageHelp" class="text-xs text-black/50">{{ __('messages.posts.up_to_images', ['count' => 4]) }}</span>
                        @auth
                            <label class="ml-2 inline-flex items-center gap-2 select-none relative">
                                <input type="checkbox" name="anonymous" value="1" class="peer h-4 w-7 appearance-none rounded-full bg-slate-200 outline-none transition-colors duration-200 peer-checked:bg-emerald-500 relative">
                                <span class="pointer-events-none absolute ml-[18px] h-4 w-4 rounded-full bg-white shadow -translate-x-4 peer-checked:translate-x-0 transition-transform duration-200"></span>
                                <span class="text-sm text-black pl-8">{{ __('messages.posts.anonymous') }}</span>
                            </label>
                        @endauth
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="charCount" class="text-xs text-black/50">0/500</span>
                        <x-ui.button id="submitBtn" type="submit" variant="primary" size="sm" disabled>
                            <span id="submitText">{{ __('messages.posts.post') }}</span>
                        </x-ui.button>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="button" id="discardDraft" class="text-xs text-black/60 hover:text-black underline">{{ __('messages.posts.discard_draft') }}</button>
                </div>
            </div>
        </form>

        <script>
            (function () {
              // Sheet open/close behavior
              function bindSheetEvents(){
                const sheet = document.getElementById('createPostSheet');
                const openBtn = document.getElementById('openCreatePost');
                const closeBtn = document.getElementById('closeCreatePost');
                const backdropSelector = '[data-sheet-backdrop]';
                const panel = sheet?.querySelector('[data-sheet-panel]');
                const backdrop = sheet?.querySelector(backdropSelector);
                const rebindInputs = () => {
                  // Re-select active nodes (prefer sheet controls)
                  active.content = document.getElementById('postContentSheet') ?? document.getElementById('postContent');
                  active.charCount = document.getElementById('charCountSheet') ?? document.getElementById('charCount');
                  active.imageInput = document.getElementById('imageInputSheet') ?? document.getElementById('imageInput');
                  active.imagePreview = document.getElementById('imagePreviewSheet') ?? document.getElementById('imagePreview');
                  active.dropZone = document.getElementById('dropZoneSheet') ?? document.getElementById('dropZone');
                  active.submitBtn = document.getElementById('submitBtnSheet') ?? document.getElementById('submitBtn');
                  active.submitText = document.getElementById('submitTextSheet') ?? document.getElementById('submitText');
                  active.form = document.getElementById('postFormSheet') ?? document.getElementById('postForm');

                  // Ensure listeners are attached once
                  if (active._bound) { return; }
                  active._bound = true;
                  active.content?.addEventListener('input', () => { updateCounter(); saveDraft(); });
                  // Bind image input change
                  active.imageInput?.addEventListener('change', refreshPreviewsFromInput);
                };
                function openSheet(){
                  if (!sheet) return;
                  sheet.classList.remove('hidden');
                  sheet.setAttribute('aria-hidden', 'false');
                  document.body.style.overflow = 'hidden';
                  requestAnimationFrame(() => {
                    panel?.classList.remove('translate-y-full');
                    panel?.classList.add('translate-y-0');
                    panel?.classList.remove('opacity-0');
                    panel?.classList.add('opacity-100');
                    backdrop?.classList.remove('opacity-0');
                    backdrop?.classList.add('opacity-100');
                  });
                  setTimeout(()=> {
                    rebindInputs();
                    active.content?.focus();
                    updateCounter();
                  }, 120);
                }
                function closeSheet(){
                  if (!sheet) return;
                  panel?.classList.remove('translate-y-0');
                  panel?.classList.add('translate-y-full');
                  panel?.classList.remove('opacity-100');
                  panel?.classList.add('opacity-0');
                  backdrop?.classList.remove('opacity-100');
                  backdrop?.classList.add('opacity-0');
                  setTimeout(() => {
                    sheet.classList.add('hidden');
                    sheet.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                  }, 300);
                }
                // Direct listeners if elements exist now
                openBtn?.addEventListener('click', openSheet);
                closeBtn?.addEventListener('click', closeSheet);
                sheet?.addEventListener('click', (e) => { if (e.target.matches(backdropSelector)) closeSheet(); });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSheet(); });
                // Delegated fallback if button gets re-rendered
                document.addEventListener('click', (e) => {
                  if (e.target.closest('#openCreatePost')) { openSheet(); }
                });
                // Expose for other handlers
                window.__openCreatePostSheet = openSheet;
                window.__closeCreatePostSheet = closeSheet;
              }
              if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bindSheetEvents);
              } else {
                bindSheetEvents();
              }

              // Prefer controls inside the sheet; fallback to hidden stub for compatibility
              // Active control refs container (gets re-bound when sheet opens)
              const active = {};
              const content = () => active.content;
              const charCount = () => active.charCount;
              const imageInput = () => active.imageInput;
              const imagePreview = () => active.imagePreview;
              const dropZone = () => active.dropZone;
              const submitBtn = () => active.submitBtn;
              const submitText = () => active.submitText;
              const form = () => active.form;
              const DRAFT_KEY = 'posts:draft';

              function updateCounter() {
                const el = content();
                const cc = charCount();
                if (!el || !cc) { return; }
                const len = el.value.length;
                cc.textContent = `${len}/500`;
                cc.className = len > 450 ? 'text-xs text-yellow-600' : 'text-xs text-black/50';
                updateSubmitState();
              }

              function updateSubmitState() {
                const el = content();
                const ip = imageInput();
                const pv = imagePreview();
                const btn = submitBtn();
                if (!btn) { return; }
                const hasText = !!el && el.value.trim().length > 0;
                const hasImages = (!!pv && pv.children.length > 0) || (!!ip && ip.files && ip.files.length > 0);
                btn.disabled = !(hasText || hasImages);
              }

              function addPreview(file, index) {
                const url = URL.createObjectURL(file);
                const wrapper = document.createElement('div');
                wrapper.className = 'relative overflow-hidden rounded-lg group';
                wrapper.innerHTML = `
                  <img src="${url}" class="w-full h-24 md:h-28 object-cover">
                  <button type="button" class="absolute top-1 right-1 bg-white/90 text-black rounded-full w-6 h-6 flex items-center justify-center shadow hover:bg-white" title="Remove">✕</button>
                `;
                const btn = wrapper.querySelector('button');
                btn.addEventListener('click', () => {
                  const dt = new DataTransfer();
                  const ip = imageInput();
                  Array.from(ip.files).forEach((f, i) => { if (i !== index) dt.items.add(f); });
                  ip.files = dt.files;
                  wrapper.remove();
                  updateSubmitState();
                });
                imagePreview()?.appendChild(wrapper);
              }

              function refreshPreviewsFromInput(){
                const pv = imagePreview();
                const ip = imageInput();
                if (!pv || !ip) { return; }
                pv.innerHTML = '';
                const files = Array.from(ip.files || []).slice(0, 4);
                files.forEach((f, i) => addPreview(f, i));
                updateSubmitState();
              }

              function addFiles(files){
                const ip = imageInput();
                if (!ip) { return; }
                const current = Array.from(ip.files || []);
                const dt = new DataTransfer();
                const combined = current.concat(Array.from(files));
                combined.slice(0,4).forEach(f => dt.items.add(f));
                ip.files = dt.files;
                refreshPreviewsFromInput();
              }

              // Initial bind to existing nodes (if any), will be re-bound on open
              (function initialBind(){
                active.content = document.getElementById('postContentSheet') ?? document.getElementById('postContent');
                active.charCount = document.getElementById('charCountSheet') ?? document.getElementById('charCount');
                active.imageInput = document.getElementById('imageInputSheet') ?? document.getElementById('imageInput');
                active.imagePreview = document.getElementById('imagePreviewSheet') ?? document.getElementById('imagePreview');
                active.dropZone = document.getElementById('dropZoneSheet') ?? document.getElementById('dropZone');
                active.submitBtn = document.getElementById('submitBtnSheet') ?? document.getElementById('submitBtn');
                active.submitText = document.getElementById('submitTextSheet') ?? document.getElementById('submitText');
                active.form = document.getElementById('postFormSheet') ?? document.getElementById('postForm');
                active.content?.addEventListener('input', () => { updateCounter(); saveDraft(); });
              })();

              imageInput()?.addEventListener('change', refreshPreviewsFromInput);

              // Delegated safety net for late-bound input
              document.addEventListener('change', function (e) {
                const t = e.target;
                if (t && t.matches && t.matches('#imageInputSheet, #imageInput')) {
                  refreshPreviewsFromInput();
                }
              });

              // Drag & drop
              ['dragenter','dragover'].forEach(ev => dropZone()?.addEventListener(ev, e => {
                e.preventDefault(); e.stopPropagation();
                dropZone()?.classList.add('ring-2','ring-emerald-400/50','bg-emerald-50');
              }));
              ['dragleave','drop'].forEach(ev => dropZone()?.addEventListener(ev, e => {
                e.preventDefault(); e.stopPropagation();
                dropZone()?.classList.remove('ring-2','ring-emerald-400/50','bg-emerald-50');
              }));
              dropZone()?.addEventListener('drop', e => {
                if (e.dataTransfer && e.dataTransfer.files?.length){
                  addFiles(e.dataTransfer.files);
                }
              });

              // Draft autosave (content only)
              function saveDraft(){
                const data = { content: content.value };
                try { localStorage.setItem(DRAFT_KEY, JSON.stringify(data)); } catch(_) {}
              }
              function loadDraft(){
                try {
                  const raw = localStorage.getItem(DRAFT_KEY);
                  if (!raw) return;
                  const data = JSON.parse(raw);
                  if (data && typeof data.content === 'string' && !content.value){
                    content.value = data.content;
                  }
                } catch(_) {}
              }
              function clearDraft(){
                try { localStorage.removeItem(DRAFT_KEY); } catch(_) {}
              }
              loadDraft();
              updateCounter();
              // Already handled by initialBind and rebindInputs()

              form()?.addEventListener('submit', () => {
                const btn = submitBtn();
                const st = submitText();
                if (btn) { btn.disabled = true; }
                if (st) { st.textContent = 'Posting…'; }
                clearDraft();
                // After submit, close sheet to feel responsive (server will redirect)
                setTimeout(() => { closeSheet(); }, 0);
              });

              // Discard draft handler
              document.getElementById('discardDraft')?.addEventListener('click', () => {
                content.value = '';
                if (imagePreview){ imagePreview.innerHTML = ''; }
                if (imageInput){ imageInput.value = ''; }
                clearDraft();
                updateCounter();
              });
            })();
        </script>
    </x-ui.card>

    <!-- Floating Create Post Button (Top-Left) -->
    <button
        type="button"
        id="openCreatePost"
        class="fixed top-7 left-5 z-[9980]
               h-12 w-12 md:h-14 md:w-14 rounded-full
               bg-emerald-600 text-white shadow-lg shadow-emerald-600/30
               hover:bg-emerald-700
               focus:outline-none focus:ring-4 focus:ring-emerald-400/30
               flex items-center justify-center group
               transition-transform duration-200 ease-out
               hover:scale-[1.04] active:scale-[0.98]
               before:content-[''] before:absolute before:inset-0 before:rounded-full before:bg-emerald-400/0 hover:before:bg-emerald-400/10 before:transition-colors before:duration-200"
        aria-label="{{ __('messages.posts.post') }}"
    >
        <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 whitespace-nowrap text-[11px] font-medium text-black/70 bg-white/80 backdrop-blur px-2 py-0.5 rounded-full shadow-sm opacity-0 translate-y-1 transition-all duration-200 group-hover:opacity-100 group-hover:translate-y-0 pointer-events-none hidden md:block">
            {{ __('messages.posts.post') }}
        </span>
    </button>

    @php($posts = \App\Models\Post::query()->latest()->whereNull('deleted_at')->with('user')->limit(20)->get())

    <div class="grid gap-4">
        @foreach($posts as $post)
            <x-ui.card id="post-{{ $post->id }}" padding="p-0" class="text-black transition-all duration-200 hover:shadow-lg/60 hover:border-emerald-200/60 animate-fade-in-up overflow-hidden" style="animation-delay: {{ ($loop->index % 12) * 60 }}ms">
                <div class="p-4 md:p-5">
                <div class="flex items-start gap-3 mb-3">
                    @php($isAnon = $post->is_anonymous || !$post->user)
                    @php($hue = $isAnon ? (crc32((string)$post->id) % 360) : null)
                    @if($isAnon)
                        <img src="{{ asset('anon-avatar.svg') }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200" style="color: hsl({{ $hue }}, 80%, 55%)">
                    @else
                        <img src="{{ $post->user->avatarUrl() }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200">
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
                        @php($liked = auth()->check() ? $post->likes()->where('user_id', auth()->id())->exists() : false)
                        @php($likeCount = $post->likes()->count())
                        <button type="button" data-like-post="{{ $post->id }}" aria-pressed="{{ $liked ? 'true' : 'false' }}" class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-1 text-[11px] transition-colors {{ $liked ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-black/70 hover:text-black hover:border-slate-300' }}">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            <span data-like-text>{{ $liked ? __('messages.unlike') : __('messages.like') }}</span>
                            <span data-like-count>{{ $likeCount }}</span>
                        </button>
                        <!-- Share / Copy actions -->
                        <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" data-share-post="{{ $post->id }}" title="Share">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 10-3-3m0 3v7m0-7a3 3 0 11-3-3m3 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
                        </button>
                        <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" data-copy-post="{{ $post->id }}" title="Copy link">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8a2 2 0 002-2V8m-6 8H8a2 2 0 01-2-2V8m6-4h6a2 2 0 012 2v6"/></svg>
                        </button>
                        @auth
                            @if($post->user_id === auth()->id())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete') }}?')" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="xs" class="!px-2.5 !py-1">
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
                                    <img src="{{ Storage::disk('spaces')->url($img) }}" class="w-full h-36 object-cover transition-transform duration-200 hover:scale-105"/>
                                </div>
                            @endforeach
                        </div>
                    </a>
                @endif
                @if($post->content)
                    <div class="px-4 md:px-5 pb-3 text-[15px] leading-6 text-slate-900">{!! nl2br(e($post->content)) !!}</div>
                @endif
                @if(is_array($post->images) && count($post->images))
                    <div class="px-4 md:px-5 pb-4 grid grid-cols-2 gap-2 md:gap-3">
                        @foreach($post->images as $img)
                            <a href="{{ route('posts.show', $post) }}" class="block group overflow-hidden rounded-xl border border-slate-200">
                                <img src="{{ Storage::url($img) }}" alt="" class="h-36 w-full object-cover transition-transform duration-200 group-hover:scale-[1.02]" />
                            </a>
                        @endforeach
                    </div>
                @endif
                </div>
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
                            <x-ui.input type="text" name="delete_token" placeholder="Delete token" required />
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
                        label.textContent = '{{ __('messages.unlike') }}';
                        button.setAttribute('aria-pressed', 'true');
                    } else {
                        button.classList.remove(...iconLiked.split(' '));
                        button.classList.add(...iconDefault.split(' '));
                        label.textContent = '{{ __('messages.like') }}';
                        button.setAttribute('aria-pressed', 'false');
                    }
                    if (countEl){ countEl.textContent = count; }
                } catch (e) {
                    // ignore
                } finally {
                    button.dataset.loading = '0';
                }
            }
            document.addEventListener('click', function(e){
                const btn = e.target.closest('[data-like-post]');
                if (btn){ e.preventDefault(); toggleLike(btn.getAttribute('data-like-post'), btn); }
            });
            document.querySelector('[data-close-like-modal]')?.addEventListener('click', function(){
                const modal = document.getElementById('likeLoginModal');
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            });
            document.getElementById('likeLoginModal')?.addEventListener('click', function(e){
                if (e.target === this){ this.classList.add('hidden'); this.classList.remove('flex'); }
            });
        })();
    </script>
</div>
@endsection

@push('body-end')
<!-- Global bottom sheet mounted at body end -->
<div id="createPostSheet" class="fixed inset-0 z-[9999] hidden pointer-events-none" aria-hidden="true">
    <div data-sheet-backdrop class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-200"></div>
    <div class="absolute inset-x-0 bottom-0 w-full max-h-[85vh] bg-white shadow-2xl border-t border-slate-200 flex flex-col translate-y-full opacity-0 transition-[transform,opacity] duration-300 ease-out pointer-events-auto rounded-t-2xl md:rounded-t-3xl md:max-w-2xl md:mx-auto will-change-transform" data-sheet-panel>
        <div class="flex items-center justify-between p-4 border-b border-slate-200 rounded-t-2xl md:rounded-t-3xl">
            <h3 class="text-sm font-semibold">{{ __('messages.posts.post') }}</h3>
            <button type="button" id="closeCreatePost" class="rounded-md p-1.5 text-slate-600 hover:bg-slate-100">✕</button>
        </div>
        <div class="p-4 overflow-y-auto">
            <!-- We reuse the same form fields by targeting inputs by ID -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="postFormSheet">
                @csrf
                <div class="flex items-start gap-3">
                    <img src="{{ optional(auth()->user())->avatarUrl() ?? asset('anon-avatar.svg') }}" alt="avatar" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                    <div class="flex-1">
                    <x-ui.textarea id="postContentSheet" name="content" rows="1" maxlength="500" class="w-full resize-none rounded-2xl px-4 py-3 text-[15px]" placeholder="{{ __('messages.posts.whats_happening') }}">{{ old('content') }}</x-ui.textarea>
                        @error('content') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                        <div id="imagePreviewSheet" class="mt-3 grid grid-cols-2 gap-2 md:gap-3"></div>
                    </div>
                </div>

                <div id="dropZoneSheet" class="pl-13 -mt-2 space-y-3">
                    <div>
                        <label for="imageInputSheet" class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[13px] text-emerald-700 bg-emerald-50 hover:bg-emerald-100 cursor-pointer border border-emerald-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-3h6l2 3h4v12H3z"/></svg>
                            {{ __('messages.posts.add_photos') }}
                        </label>
                        <input id="imageInputSheet" type="file" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="hidden">
                        <div class="mt-1">
                            <span id="imageHelpSheet" class="text-xs text-black/50">{{ __('messages.posts.up_to_images', ['count' => 4]) }}</span>
                        </div>
                    </div>
                    @auth
                    <div>
                        <label class="inline-flex items-center gap-2 select-none relative">
                            <input type="checkbox" name="anonymous" value="1" class="peer h-4 w-7 appearance-none rounded-full bg-slate-200 outline-none transition-colors duration-200 peer-checked:bg-emerald-500 relative">
                            <span class="pointer-events-none absolute ml-[18px] h-4 w-4 rounded-full bg-white shadow -translate-x-4 peer-checked:translate-x-0 transition-transform duration-200"></span>
                            <span class="text-sm text-black pl-8">{{ __('messages.posts.anonymous') }}</span>
                        </label>
                    </div>
                    @endauth
                    <div class="flex items-center justify-between">
                        <span id="charCountSheet" class="text-xs text-black/50">0/500</span>
                        <x-ui.button id="submitBtnSheet" type="submit" variant="primary" size="sm" disabled>
                            <span id="submitTextSheet">{{ __('messages.posts.post') }}</span>
                        </x-ui.button>
                    </div>
                    <div>
                        <button type="button" id="discardDraft" class="text-xs text-black/60 hover:text-black underline">{{ __('messages.posts.discard_draft') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="likeLoginModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60">
    <div class="bg-white rounded-xl p-4 w-80 shadow-xl text-center">
        <p class="text-sm text-black mb-3">{{ __('messages.login_to_like') }}</p>
        <div class="flex items-center justify-center gap-2">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 text-white px-3 py-1.5 text-sm">{{ __('messages.login') }}</a>
            <button type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-black" data-close-like-modal>OK</button>
        </div>
    </div>
</div>
@endpush
