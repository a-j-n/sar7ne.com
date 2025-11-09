@php($users = $users ?? collect())
@php($q = $q ?? '')

<div class="space-y-12">
    <!-- Hero Section -->
    <x-ui.card padding="p-4 sm:p-6 md:p-8" class="relative overflow-hidden card-brand-gradient animate-fade-in-up">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
        <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-brand-orange/20 opacity-60 glow-brand-orange"></div>
        <div class="absolute -bottom-6 -left-6 h-32 w-32 rounded-full bg-neon-mint/20 opacity-40 glow-neon-mint"></div>
        
        <div class="relative">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-2xl bg-gradient-orange-pink flex items-center justify-center glow-brand-orange">
                    <svg class="h-6 w-6 md:hidden text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 truncate">{{ __('messages.homepage_discover_title') }}</h1>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 truncate">{{ __('messages.browse_profiles_desc') }}</p>
                </div>
            </div>

            <!-- Enhanced Search Form -->
            <div class="mt-6 sm:mt-8 animate-slide-in-left">
                <div class="relative">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 rounded-2xl border border-slate-300 dark:border-slate-700/60  dark:bg-slate-900/70 p-2 sm:p-2.5 focus-within:ring-2 focus-within:ring-brand-orange focus-within:border-brand-orange transition-all duration-200 shadow-lg backdrop-blur-sm max-w-full" role="search">
                        <div class="flex-1 flex items-center gap-2 sm:gap-3 min-w-0">
                            <div class="pl-2 sm:pl-3 shrink-0">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <x-ui.input type="text" wire:model.live.debounce.300ms="q" placeholder="{{ __('messages.search_by_username') }}" class="px-2 py-2.5 sm:py-3 text-sm sm:text-base w-full" />
                        </div>
                        <div class="flex items-center gap-2 sm:pl-1 shrink-0">
                            @if(!empty($q))
                                <button wire:click="$set('q', '')" class="inline-flex items-center gap-1 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/70 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('messages.clear') }}
                                </button>
                            @endif
                            <div class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2 btn-brand-primary">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                {{ __('messages.search') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Top Content Section -->
    <section class="space-y-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <!-- Top Profiles Preview -->
            <x-ui.card class="animate-fade-in-up" padding="p-0">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/60">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.top_profiles') }}</h2>
                    <a href="{{ route('explore') }}" class="text-xs font-medium text-brand-orange hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                @php($topUsers = \App\Models\User::query()->latest()->limit(6)->get())
                <div class="p-5 grid gap-4 sm:grid-cols-2">
                    @forelse($topUsers as $u)
                        <a href="{{ route('profiles.show', $u) }}" class="group flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-800/60 p-3 hover:border-brand-orange/40 hover:bg-brand-orange/5 transition">
                            <img src="{{ $u->avatarUrl() }}" alt="{{ '@'.$u->username }}" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700/60">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ '@'.$u->username }}</div>
                                @if($u->display_name)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $u->display_name }}</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.no_profiles_found') }}</div>
                    @endforelse
                </div>
            </x-ui.card>

            <!-- Top Posts Preview -->
            <x-ui.card class="animate-fade-in-up" padding="p-0" style="animation-delay: 80ms">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/60">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.top_posts') }}</h2>
                    <a href="{{ route('posts') }}" class="text-xs font-medium text-brand-orange hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                @php($topPosts = \App\Models\Post::query()->with('user')->withCount(['likes','comments'])->latest()->limit(6)->get())
                <div class="divide-y divide-slate-200 dark:divide-slate-800/60">
                    @forelse($topPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block group px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                            <div class="flex items-start gap-3">
                                <img src="{{ $post->user?->avatarUrl() ?? asset('anon-avatar.svg') }}" alt="{{ '@'.($post->user?->username ?? 'anon') }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700/60">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="font-medium text-slate-700 dark:text-slate-200">{{ '@'.($post->user?->username ?? __('Anonymous')) }}</span>
                                        <span aria-hidden="true">•</span>
                                        <time datetime="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</time>
                                    </div>
                                    @if($post->content)
                                        <p class="mt-1 text-sm text-slate-800 dark:text-slate-200 line-clamp-2">{{ $post->content }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                                        <span class="inline-flex items-center gap-1">❤ {{ $post->likes_count }}</span>
                                        <span class="inline-flex items-center gap-1">💬 {{ $post->comments_count }}</span>
                                        @if(is_array($post->images) && count($post->images))
                                            <span class="ml-2 inline-flex items-center gap-1 rounded-full border border-slate-200 dark:border-slate-700/60 px-2 py-0.5 text-[10px] text-slate-600 dark:text-slate-300 bg-white/60 dark:bg-slate-800/60">📷 {{ count($post->images) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <svg class="h-4 w-4 mt-1 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 118.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
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
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-5 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 012 2v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14L21 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 10v10a2 2 0 01-2 2H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('messages.sign_in_to_start') }}
                </a>
            </div>
        @endguest
    </section>

    <!-- Users Section -->
    <section>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                {{ !empty($q) ? __('messages.search_results') : __('messages.featured_creators') }}
            </h2>
            @if(empty($q))
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.refreshed_on_each_visit') }}</span>
            @endif
        </div>

        @if($users->isEmpty())
            <x-ui.card class="text-center" padding="p-12">
                <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ __('messages.no_profiles_found') }}</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400">Try adjusting your search terms or check back later for new profiles.</p>
            </x-ui.card>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($users as $user)
                    <x-ui.card class="group hover:border-slate-300 dark:hover:border-slate-600/60 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 animate-fade-in-up" style="animation-delay: {{ ($loop->index % 12) * 40 }}ms">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ '@'.$user->username }} avatar" width="48" height="48" loading="lazy" decoding="async" class="h-12 w-12 rounded-2xl object-cover border border-slate-200 dark:border-slate-700/60 group-hover:border-brand-orange/40 transition-colors" />
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
                                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                    {{ $user->bio }}
                                </p>
                            </div>
                        @endif
                        
                        <div class="flex gap-2">
                            <a href="{{ route('profiles.show', $user) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700/60 px-3 py-2.5 text-sm font-medium text-slate-900 dark:text-white transition-all hover:border-brand-orange/40 hover:text-brand-orange hover:bg-brand-orange/10 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange min-w-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ __('messages.visit_profile') }}
                            </a>
                            <a href="{{ route('profiles.show', $user) }}#message" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-orange-pink px-3 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl hover:scale-105 focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2 min-w-0">
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
