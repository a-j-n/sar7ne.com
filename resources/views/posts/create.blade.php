@extends('layouts.app')

@section('title', __('messages.posts.share_a_post'))

@section('content')
<div class="space-y-8">
    <x-ui.card padding="p-5 sm:p-7" class="relative overflow-hidden card-brand-gradient">
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-brand-orange/20 blur-2xl"></div>
        <div class="absolute -left-10 bottom-0 h-32 w-32 rounded-full bg-neon-mint/20 blur-2xl"></div>

        <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-4xl font-black leading-tight text-slate-900 dark:text-white">{{ __('messages.posts.share_a_post') }}</h1>
                <p class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed max-w-2xl break-words">
                    {{ __('messages.posts.share_intro') }}
                </p>
            </div>
            <a href="{{ route('posts') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('messages.posts.back_to_posts') }}
            </a>
        </div>
    </x-ui.card>

    <x-ui.card padding="p-5 sm:p-7" class="space-y-6">
        <form id="create-post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="flex items-start gap-3">
                <img src="{{ optional(auth()->user())->avatarUrl() ?? asset('anon-avatar.svg') }}" alt="avatar" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="h-12 w-12 rounded-2xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="flex-1 space-y-2">
                    <label for="content" class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">{{ __('messages.posts.whats_happening') }}</label>
                    <textarea id="content" name="content" rows="4" maxlength="500" class="w-full rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white/80 dark:bg-slate-900 text-[15px] leading-6 text-slate-900 dark:text-white px-4 py-3 placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/70 break-words" placeholder="{{ __('messages.posts.whats_happening') }}" required>{{ old('content') }}</textarea>
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span id="char-count">0/500</span>
                        <span>{{ __('messages.posts.up_to_images', ['count' => 4]) }}</span>
                    </div>
                    @error('content') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">{{ __('messages.posts.add_photos') }}</label>
                <div id="drop-zone" class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/60 px-4 py-6 text-center transition-colors">
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-3h6l2 3h4v12H3z"/></svg>
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('messages.posts.drag_drop_upload') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.posts.upload_hint') }}</p>
                    </div>
                    <input id="images" type="file" name="images[]" accept="image/png,image/jpeg,image/webp" multiple class="hidden">
                    <button type="button" id="images-trigger" class="mt-2 inline-flex items-center gap-2 rounded-xl bg-white/80 px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:text-white dark:ring-slate-700">{{ __('messages.posts.choose_files') }}</button>
                </div>
                @error('images') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
                @error('images.*') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
                <div id="image-previews" class="grid grid-cols-2 gap-3 md:gap-4"></div>
            </div>

            @auth
                <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200/80 dark:border-slate-700 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white bg-slate-50/70 dark:bg-slate-900/60">
                    <input type="checkbox" name="anonymous" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-orange focus:ring-brand-orange">
                    {{ __('messages.posts.anonymous') }}
                </label>
            @endauth

            <div class="flex flex-wrap gap-3">
                <x-ui.button type="submit" variant="primary" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('messages.posts.post') }}
                </x-ui.button>
                <a href="{{ route('posts') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 hover:border-slate-300 dark:border-slate-700 dark:text-slate-200 dark:hover:text-white dark:hover:border-slate-500">
                    {{ __('messages.cancel') }}
                </a>
            </div>

            @guest
                <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 text-sm text-amber-800">
                    Anonymous posts get a delete token on submission—save it if you want to remove the post later.
                </div>
            @endguest
        </form>
    </x-ui.card>
</div>

<script>
    (function(){
        const content = document.getElementById('content');
        const counter = document.getElementById('char-count');
        const input = document.getElementById('images');
        const trigger = document.getElementById('images-trigger');
        const drop = document.getElementById('drop-zone');
        const previews = document.getElementById('image-previews');
        const maxFiles = 4;

        const updateCounter = () => {
            if (!content || !counter) return;
            const len = content.value.length;
            counter.textContent = `${len}/500`;
            counter.className = len > 450 ? 'text-xs text-amber-600' : 'text-xs text-slate-500 dark:text-slate-400';
        };

        const refreshPreviews = () => {
            if (!input || !previews) return;
            previews.innerHTML = '';
            const files = Array.from(input.files || []).slice(0, maxFiles);
            files.forEach((file, index) => {
                const url = URL.createObjectURL(file);
                const card = document.createElement('div');
                card.className = 'relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/70 shadow-sm';
                card.innerHTML = `
                    <img src="${url}" alt="preview" class="h-28 w-full object-cover" loading="lazy">
                    <button type="button" class="absolute top-2 right-2 h-8 w-8 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white" data-remove="${index}" aria-label="Remove image">✕</button>
                `;
                previews.appendChild(card);
            });
        };

        const limitFiles = (files) => Array.from(files || []).slice(0, maxFiles);

        if (content){
            content.addEventListener('input', updateCounter);
            updateCounter();
        }
        trigger?.addEventListener('click', () => input?.click());
        drop?.addEventListener('click', () => input?.click());

        input?.addEventListener('change', () => {
            const limited = limitFiles(input.files);
            const dt = new DataTransfer();
            limited.forEach(f => dt.items.add(f));
            input.files = dt.files;
            refreshPreviews();
        });

        ['dragenter','dragover'].forEach(ev => drop?.addEventListener(ev, e => {
            e.preventDefault(); e.stopPropagation();
            drop.classList.add('ring-2','ring-brand-orange/40','bg-brand-orange/5');
        }));
        ['dragleave','drop'].forEach(ev => drop?.addEventListener(ev, e => {
            e.preventDefault(); e.stopPropagation();
            drop.classList.remove('ring-2','ring-brand-orange/40','bg-brand-orange/5');
        }));
        drop?.addEventListener('drop', e => {
            if (!input || !e.dataTransfer) return;
            const dt = new DataTransfer();
            limitFiles(e.dataTransfer.files).forEach(f => dt.items.add(f));
            input.files = dt.files;
            refreshPreviews();
        });

        previews?.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-remove]');
            if (!btn || !input) return;
            const removeIndex = parseInt(btn.getAttribute('data-remove'), 10);
            const dt = new DataTransfer();
            Array.from(input.files || []).forEach((file, index) => {
                if (index !== removeIndex) dt.items.add(file);
            });
            input.files = dt.files;
            refreshPreviews();
        });
    })();
</script>
@endsection
