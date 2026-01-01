@php($users = $users ?? collect())
@php($topUsers = $topUsers ?? collect())
@php($topPosts = $topPosts ?? collect())
@php($stats = $stats ?? ['users' => 0, 'posts' => 0, 'messages' => 0])
@php($q = $q ?? '')

<div class="space-y-12">
    <x-ui.card padding="p-4 sm:p-6 lg:p-8" class="relative overflow-hidden card-brand-gradient animate-fade-in-up">
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
        <div class="absolute -right-14 -top-10 h-48 w-48 rounded-full bg-brand-orange/25 blur-3xl"></div>
        <div class="absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-neon-mint/25 blur-3xl"></div>

        <div class="relative grid items-center gap-8 lg:grid-cols-[1.05fr,0.95fr]">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/40 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.08em] text-brand-orange shadow-sm dark:border-slate-700/70 dark:bg-slate-900/60">
                    <span class="h-2 w-2 rounded-full bg-brand-orange animate-pulse"></span>
                    {{ __('messages.explore') }}
                </div>

                <div class="space-y-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight text-slate-900 dark:text-white">
                        {{ __('messages.homepage_discover_title') }}
                    </h1>
                    <p class="text-base sm:text-lg text-slate-700 dark:text-slate-300 max-w-2xl leading-relaxed break-words">
                        {{ __('messages.browse_profiles_desc') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-white/60 dark:bg-slate-800/70 dark:text-slate-100 dark:ring-slate-700">🔒 Anonymous by default</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-white/60 dark:bg-slate-800/70 dark:text-slate-100 dark:ring-slate-700">✨ Shareable profile link</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-white/60 dark:bg-slate-800/70 dark:text-slate-100 dark:ring-slate-700">🛡️ Protected from spam</span>
                </div>

                <div class="grid max-w-2xl grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">Creators</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['users'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">profiles live</p>
                    </div>
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts_title') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['posts'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">shared stories</p>
                    </div>
                    <div class="rounded-2xl border border-white/60 bg-white/70 px-4 py-3 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.inbox') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['messages'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">anonymous drops</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-orange-pink px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ __('messages.profile') }}
                        </a>
                        <a href="{{ route('inbox') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-900 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v12H5.17L4 17.17V4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 6l-10 7L2 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ __('messages.inbox') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-orange-pink px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ __('messages.join_sar7ne') }}
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/70 bg-white/70 px-5 py-3 text-sm font-semibold text-slate-900 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 012 2v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14L21 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 10v10a2 2 0 01-2 2H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ __('messages.sign_in') }}
                        </a>
                    @endauth
                </div>

                <div class="pt-1">
                    <div class="flex flex-col gap-2 rounded-2xl border border-slate-200/80 bg-white/80 p-3 shadow-lg backdrop-blur focus-within:border-brand-orange focus-within:ring-2 focus-within:ring-brand-orange dark:border-slate-700 dark:bg-slate-900/70" role="search" aria-label="{{ __('messages.search') }}">
                        <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('messages.search_creators') }}
                        </label>
                        <div class="flex flex-col sm:flex-row items-stretch gap-2 sm:gap-3">
                            <x-ui.input type="text" wire:model.live.debounce.300ms="q" placeholder="{{ __('messages.search_by_username') }}" class="w-full rounded-xl border border-slate-200/80 px-3 py-2.5 text-sm sm:text-base placeholder:text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-400" aria-label="{{ __('messages.search_by_username') }}" />
                            <div class="flex items-center gap-2 sm:gap-3 sm:justify-end">
                                @if(!empty($q))
                                    <button wire:click="$set('q', '')" class="inline-flex items-center gap-1 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 transition hover:text-slate-900 hover:bg-slate-100 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800/70">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('messages.clear') }}
                                    </button>
                                @endif
                                <span class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-4 py-2.5 text-sm font-semibold text-white shadow-lg">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('messages.search') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-6 rounded-[32px] bg-gradient-to-br from-white/60 via-brand-orange/10 to-slate-900/10 blur-2xl dark:from-slate-800/30 dark:via-brand-orange/10 dark:to-slate-900/60"></div>
                <div class="relative rounded-3xl border border-white/50 bg-white/80 p-6 shadow-2xl backdrop-blur-lg dark:border-slate-800 dark:bg-slate-900/70">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase text-slate-500 dark:text-slate-400">Spotlight</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">People receiving new drops</p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/40 dark:text-green-200">
                            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                            Live
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse($topUsers->take(3) as $u)
                            <a href="{{ route('profiles.show', $u) }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white/80 px-3 py-2.5 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-orange/50 hover:shadow-lg dark:border-slate-800 dark:bg-slate-800/60">
                                <img src="{{ $u->avatarUrl() }}" alt="{{ '@'.$u->username }}" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700/60">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ '@'.$u->username }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        {{ $u->display_name ?: __('messages.anonymous_vibes_waiting') }}
                                    </p>
                                </div>
                                <svg class="h-4 w-4 text-slate-400 transition group-hover:text-slate-700 dark:group-hover:text-slate-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 118.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            </a>
                        @empty
                            <p class="text-sm text-slate-600 dark:text-slate-400">Stay tuned — new profiles drop soon.</p>
                        @endforelse
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @forelse($topPosts->take(2) as $post)
                            <a href="{{ route('posts.show', $post) }}" class="group rounded-2xl border border-slate-200/80 bg-white/80 p-3 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-orange/50 hover:shadow-lg dark:border-slate-800 dark:bg-slate-800/60">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ '@'.($post->user?->username ?? __('messages.posts.anonymous')) }}</span>
                                    <span aria-hidden="true">•</span>
                                    <time datetime="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</time>
                                </div>
                                @if($post->content)
                                    <p class="mt-2 text-sm text-slate-800 dark:text-slate-100 line-clamp-3 break-words">{{ $post->content }}</p>
                                @endif
                                <div class="mt-3 flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="inline-flex items-center gap-1">❤ {{ $post->likes_count }}</span>
                                    <span class="inline-flex items-center gap-1">💬 {{ $post->comments_count }}</span>
                                    @if(is_array($post->images) && count($post->images))
                                        <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 dark:border-slate-700/60 px-2 py-0.5 text-[10px] text-slate-600 dark:text-slate-300 bg-white/60 dark:bg-slate-800/60">📷 {{ count($post->images) }}</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 text-sm text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-400">
                                {{ __('messages.no_posts_yet') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <section class="grid gap-4 sm:gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-5 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
            <div class="flex items-start gap-3">
                <div class="h-11 w-11 rounded-xl bg-brand-orange/15 text-brand-orange flex items-center justify-center shadow-inner">⚡</div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Instant setup</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Search, message, and go live with your profile link in seconds.</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-5 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
            <div class="flex items-start gap-3">
                <div class="h-11 w-11 rounded-xl bg-neon-mint/15 text-emerald-600 flex items-center justify-center shadow-inner">🔐</div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Stay anonymous</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Safety tools and rate limits keep conversations kind and spam-free.</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-5 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
            <div class="flex items-start gap-3">
                <div class="h-11 w-11 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-inner dark:bg-indigo-900/40 dark:text-indigo-200">📣</div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Share anywhere</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Drop your link across socials, collect replies, and feature the best ones.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-8">
        <div class="grid gap-6 xl:grid-cols-[1.05fr,0.95fr]">
            <x-ui.card class="animate-fade-in-up" padding="p-0">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/80 dark:border-slate-800">
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.top_profiles') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.trending_profiles') }}</h2>
                    </div>
                    <a href="{{ route('explore') }}" class="text-xs font-medium text-brand-orange hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <div class="p-5 grid gap-4 sm:grid-cols-2">
                    @forelse($topUsers as $u)
                            <a href="{{ route('profiles.show', $u) }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white/80 p-3 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-orange/50 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900/70">
                            <img src="{{ $u->avatarUrl() }}" alt="{{ '@'.$u->username }}" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="h-11 w-11 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700/60">
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ '@'.$u->username }}</div>
                                @if($u->display_name)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $u->display_name }}</div>
                                @endif
                            </div>
                            <span class="rounded-full bg-brand-orange/10 px-2 py-1 text-[10px] font-semibold text-brand-orange">Featured</span>
                        </a>
                    @empty
                        <div class="p-6 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.no_profiles_found') }}</div>
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card class="animate-fade-in-up" padding="p-0" style="animation-delay: 80ms">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/80 dark:border-slate-800">
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400">{{ __('messages.posts_title') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.top_posts') }}</h2>
                    </div>
                    <a href="{{ route('posts') }}" class="text-xs font-medium text-brand-orange hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <div class="divide-y divide-slate-200/80 dark:divide-slate-800">
                    @forelse($topPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block group px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                            <div class="flex items-start gap-3">
                                <img src="{{ $post->user?->avatarUrl() ?? asset('anon-avatar.svg') }}" alt="{{ '@'.($post->user?->username ?? 'anon') }}" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700/60">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="font-medium text-slate-700 dark:text-slate-200">{{ '@'.($post->user?->username ?? __('messages.posts.anonymous')) }}</span>
                                        <span aria-hidden="true">•</span>
                                        <time datetime="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</time>
                                    </div>
                                    @if($post->content)
                                        <p class="mt-1 text-sm text-slate-800 dark:text-slate-200 line-clamp-3 break-words">{{ $post->content }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                                        <span class="inline-flex items-center gap-1">❤ {{ $post->likes_count }}</span>
                                        <span class="inline-flex items-center gap-1">💬 {{ $post->comments_count }}</span>
                                        @if(is_array($post->images) && count($post->images))
                                            <span class="ml-2 inline-flex items-center gap-1 rounded-full border border-slate-200 dark:border-slate-700/60 px-2 py-0.5 text-[10px] text-slate-600 dark:text-slate-300 bg-white/60 dark:bg-slate-800/60">📷 {{ count($post->images) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <svg class="h-4 w-4 mt-1 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 118.707 5.293l4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.no_posts_yet') }}</div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        @guest
            <div class="flex justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 012 2v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14L21 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 10v10a2 2 0 01-2 2H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('messages.sign_in_to_start') }}
                </a>
            </div>
        @endguest
    </section>

    <section>
        <div class="mb-5 sm:mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                {{ !empty($q) ? __('messages.search_results') : __('messages.featured_creators') }}
            </h2>
            @if(empty($q))
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.refreshed_on_each_visit') }}</span>
            @endif
        </div>

        @if($users->isEmpty())
            <x-ui.card class="text-center" padding="p-12">
                <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center">
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">{{ __('messages.no_profiles_found') }}</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400">Try adjusting your search terms or check back later for new profiles.</p>
            </x-ui.card>
        @else
            <div class="grid grid-cols-1 gap-5 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                @foreach($users as $user)
                    <x-ui.card class="group hover:border-slate-300 dark:hover:border-slate-600 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 animate-fade-in-up" style="animation-delay: {{ ($loop->index % 12) * 40 }}ms">
                        <div class="mb-4 flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ '@'.$user->username }} avatar" width="48" height="48" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="h-12 w-12 rounded-2xl object-cover border border-slate-200/80 dark:border-slate-700 group-hover:border-brand-orange/50 transition-colors" />
                                <div class="absolute -bottom-0.5 -right-0.5 h-4 w-4 rounded-full bg-green-500 border-2 border-white dark:border-slate-900"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ '@'.$user->username }}</p>
                                @if($user->display_name)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->display_name }}</p>
                                @endif
                            </div>
                        </div>

                        @if($user->bio)
                            <div class="mb-4">
                                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed break-words">
                                    {{ $user->bio }}
                                </p>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('profiles.show', $user) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300/80 dark:border-slate-700 px-3 py-2.5 text-sm font-medium text-slate-900 dark:text-white transition-colors hover:border-brand-orange/50 hover:text-brand-orange hover:bg-brand-orange/10 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange min-w-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ __('messages.visit_profile') }}
                            </a>
                            <a href="{{ route('profiles.show', $user) }}#message" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-orange-pink px-3 py-2.5 text-sm font-semibold text-white shadow-lg transition-transform hover:shadow-lg hover:brightness-110 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2 min-w-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                {{ __('messages.message') }}
                            </a>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @endif
    </section>
</div>
