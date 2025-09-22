@extends('layouts.app')

@section('title', 'Explore · sar7ne')
@section('meta_description', 'Browse and discover creators on sar7ne. Find trending profiles and send anonymous messages.')
@section('og_type', 'website')
@section('canonical', route('explore'))

{{-- Preconnect removed from head to avoid Blade analyzer warnings; it's optional for performance --}}

@section('content')
    <div class="space-y-10">
        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h1 class="text-2xl font-semibold">Discover anonymous vibes</h1>
            <p class="mt-2 text-sm text-slate-300">Browse creators, friends, and trending profiles on sar7ne.</p>

            <form action="{{ route('explore') }}" method="GET" role="search" aria-label="Search creators" class="mt-6 flex gap-2 rounded-2xl border border-white/10 bg-white/5 p-2">
                <label for="q" class="sr-only">Search creators</label>
                <input id="q" type="search" name="q" value="{{ $searchTerm }}" placeholder="Search by username" class="w-full rounded-xl bg-transparent px-4 text-sm outline-none placeholder:text-slate-400" />
                @if (!empty($searchTerm))
                    <a href="{{ route('explore') }}" class="rounded-xl px-4 py-2 text-xs font-medium text-slate-300 transition hover:text-white">Clear</a>
                @endif
                <button type="submit" class="rounded-xl bg-white px-4 py-2 text-xs font-semibold text-black transition hover:bg-slate-200">Search</button>
            </form>
        </section>

        @if ($trendingUsers->isNotEmpty())
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Trending profiles</h2>
                    <span class="text-xs text-slate-400">Picked by engagement</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($trendingUsers as $user)
                        <div class="group rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10">
                            <div class="flex items-center gap-4">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ '@'.$user->username }} avatar" width="56" height="56" loading="lazy" decoding="async" class="h-14 w-14 rounded-2xl object-cover" />
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ '@'.$user->username }}</p>
                                    @if ($user->display_name)
                                        <p class="text-xs text-slate-400">{{ $user->display_name }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-slate-400">{{ $user->total_messages_count }} messages</p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2 text-xs">
                                <a href="{{ route('profiles.show', $user) }}" class="flex-1 rounded-xl border border-white/10 px-3 py-2 text-center font-medium text-white transition hover:border-white/40">View profile <span class="sr-only">{{ '@'.$user->username }}</span></a>
                                <a href="{{ route('profiles.show', $user) }}#message" class="flex-1 rounded-xl bg-white px-3 py-2 text-center font-semibold text-black transition hover:bg-slate-200">Message <span class="sr-only">{{ '@'.$user->username }}</span></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">
                    {{ !empty($searchTerm) ? 'Search results' : 'Featured creators' }}
                </h2>
                @if (empty($searchTerm))
                    <span class="text-xs text-slate-400">Refreshed on each visit</span>
                @endif
            </div>

            @if ($featuredUsers->isEmpty())
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-10 text-center text-sm text-slate-300">
                    No profiles found — try another search.
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredUsers as $user)
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10">
                            <div class="flex items-center gap-4">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ '@'.$user->username }} avatar" width="48" height="48" loading="lazy" decoding="async" class="h-12 w-12 rounded-2xl object-cover" />
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ '@'.$user->username }}</p>
                                    @if ($user->display_name)
                                        <p class="text-xs text-slate-400">{{ $user->display_name }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="mt-3 h-12 overflow-hidden text-ellipsis text-xs text-slate-400">{{ $user->bio ?? 'Anonymous vibes waiting to be discovered.' }}</p>
                            <a href="{{ route('profiles.show', $user) }}" class="mt-4 inline-flex w-full justify-center rounded-xl border border-white/10 px-4 py-2 text-xs font-medium text-white transition hover:border-white/30">Visit profile <span class="sr-only">{{ '@'.$user->username }}</span></a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Structured data for search engines: list featured and trending profiles --}}
        @php
            $itemList = [];
            $position = 1;
            foreach ([$trendingUsers, $featuredUsers] as $collection) {
                foreach ($collection as $u) {
                    $itemList[] = [
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'url' => route('profiles.show', $u),
                        'name' => $u->display_name ?? '@'.$u->username,
                    ];
                }
            }
        @endphp

        @if (!empty($itemList))
            <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'itemListElement' => $itemList,
            ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endif
    </div>
@endsection
