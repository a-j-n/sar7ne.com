@extends('layouts.app')

@section('title', __('messages.explore').' · sar7ne')
@section('meta_description', __('messages.browse_profiles_desc'))
@section('og_type', 'website')
@section('canonical', route('explore'))

@section('content')
    <div class="space-y-10">
        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h1 class="text-2xl font-semibold">{{ __('messages.homepage_discover_title') }}</h1>
            <p class="mt-2 text-sm text-slate-300">{{ __('messages.browse_profiles_desc') }}</p>

            <form action="{{ route('explore') }}" method="GET" role="search" aria-label="{{ __('messages.search_creators') }}" class="mt-6 flex gap-2 rounded-2xl border border-white/10 bg-white/5 p-2">
                <label for="q" class="sr-only">{{ __('messages.search_creators') }}</label>
                <input id="q" type="search" name="q" value="{{ $searchTerm }}" placeholder="{{ __('messages.search_by_username') }}" class="w-full rounded-xl bg-transparent px-4 text-sm outline-none placeholder:text-slate-400" />
                @if (!empty($searchTerm))
                    <a href="{{ route('explore') }}" class="rounded-xl px-4 py-2 text-xs font-medium text-slate-300 transition hover:text-white">{{ __('messages.clear') }}</a>
                @endif
                <button type="submit" class="rounded-xl bg-white px-4 py-2 text-xs font-semibold text-black transition hover:bg-slate-200">{{ __('messages.search') }}</button>
            </form>
        </section>

        @if ($trendingUsers->isNotEmpty())
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('messages.trending_profiles') }}</h2>
                    <span class="text-xs text-slate-400">{{ __('messages.picked_by_engagement') }}</span>
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
                                    <p class="mt-1 text-xs text-slate-400">{{ $user->total_messages_count }} {{ __('messages.messages_received', ['count' => $user->total_messages_count]) }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2 text-xs">
                                <a href="{{ route('profiles.show', $user) }}" class="flex-1 rounded-xl border border-white/10 px-3 py-2 text-center font-medium text-white transition hover:border-white/40">{{ __('messages.view_profile') }} <span class="sr-only">{{ '@'.$user->username }}</span></a>
                                <a href="{{ route('profiles.show', $user) }}#message" class="flex-1 rounded-xl bg-white px-3 py-2 text-center font-semibold text-black transition hover:bg-slate-200">{{ __('messages.message') }} <span class="sr-only">{{ '@'.$user->username }}</span></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">
                    {{ !empty($searchTerm) ? __('messages.search_results') : __('messages.featured_creators') }}
                </h2>
                @if (empty($searchTerm))
                    <span class="text-xs text-slate-400">{{ __('messages.refreshed_on_each_visit') }}</span>
                @endif
            </div>

            @if ($featuredUsers->isEmpty())
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-10 text-center text-sm text-slate-300">
                    {{ __('messages.no_profiles_found') }}
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
                            <p class="mt-3 h-12 overflow-hidden text-ellipsis text-xs text-slate-400">{{ $user->bio ?? __('messages.anonymous_vibes_waiting') }}</p>
                            <a href="{{ route('profiles.show', $user) }}" class="mt-4 inline-flex w-full justify-center rounded-xl border border-white/10 px-4 py-2 text-xs font-medium text-white transition hover:border-white/30">{{ __('messages.visit_profile') }} <span class="sr-only">{{ '@'.$user->username }}</span></a>
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
