@php
    $items = $items ?? [
        ['label' => __('messages.explore'), 'href' => route('explore'), 'active' => request()->routeIs('explore'), 'icon' => 'explore', 'aria' => __('messages.explore')],
        ['label' => __('messages.inbox'), 'href' => route('inbox'), 'active' => request()->routeIs('inbox*'), 'icon' => 'inbox', 'aria' => __('messages.inbox')],
        ['label' => __('messages.posts_title'), 'href' => route('posts'), 'active' => request()->routeIs('posts*'), 'icon' => 'posts', 'aria' => __('messages.posts_title')],
        ['label' => __('messages.profile'), 'href' => route('profile'), 'active' => request()->routeIs('profile*'), 'icon' => 'profile', 'aria' => __('messages.profile')],
    ];
@endphp

<nav class="fixed inset-x-0 top-0 z-50 bg-primary pt-[env(safe-area-inset-top)] shadow-sm">
    <div class="mx-auto flex w-full max-w-4xl items-center justify-center px-3">
        <div class="grid grid-cols-4 w-full max-w-md bg-primary overflow-hidden">
            @foreach ($items as $index => $item)
                <a href="{{ $item['href'] }}" aria-label="{{ $item['aria'] }}" class="relative flex flex-col items-center justify-center gap-0.5 py-2 text-[11px] font-medium transition-all duration-200 {{ $item['active'] ? 'text-brand-orange bg-brand-orange/5' : 'text-gray-600 hover:text-brand-orange dark:text-gray-400 dark:hover:text-brand-orange' }}">
                    @if($item['active'])
                        <span class="absolute inset-x-4 top-0 h-0.5 rounded-full bg-gradient-orange-pink"></span>
                    @endif
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl {{ $item['active'] ? 'bg-gradient-orange-pink text-white shadow glow-brand-orange' : 'bg-gray-100 dark:bg-slate-800/70 text-gray-600 dark:text-gray-300' }}">
                        @switch($item['icon'])
                            @case('explore')
                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                @break
                            @case('inbox')
                                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M8 10h8M8 14h5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 19l3.5-3H18a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @break
                            @case('posts')
                                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                @break
                            @case('profile')
                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @break
                        @endswitch
                    </span>
                    <span class="leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
        
    </div>
    <div class="border-b border-primary/80"></div>
</nav>
