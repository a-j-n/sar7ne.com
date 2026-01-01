<x-ui.card class="border border-slate-200 dark:border-slate-700/60 shadow-none" padding="p-0">
    <div class="relative overflow-hidden rounded-2xl">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-sky-500/10 to-fuchsia-500/10 dark:from-emerald-400/10 dark:via-sky-400/10 dark:to-fuchsia-400/10"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center p-4">
            <div class="relative">
                <div class="absolute -inset-2 rounded-2xl bg-gradient-orange-pink blur-xl opacity-50"></div>
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('default-avatar.svg') }}';" class="relative h-20 w-20 rounded-2xl object-cover ring-2 ring-white/70 dark:ring-slate-800 shadow-lg" />
            </div>
            <div class="flex-1 min-w-0 space-y-1">
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white truncate">{{ "@".$user->username }}</h1>
                @if ($user->display_name)
                    <p class="text-sm text-slate-600 dark:text-slate-300 truncate">{{ $user->display_name }}</p>
                @endif
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-slate-700 dark:text-slate-300">
                    <div class="flex items-center gap-2 rounded-xl bg-white/70 px-3 py-2 dark:bg-slate-900/60 border border-white/60 dark:border-slate-700/60">
                        <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/><path d="M18 9H2v6a2 2 0 002 2h12a2 2 0 002-2V9z"/></svg>
                        <span class="font-medium">{{ __('messages.joined', ['date' => $user->created_at->format('M Y')]) }}</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-white/70 px-3 py-2 dark:bg-slate-900/60 border border-white/60 dark:border-slate-700/60">
                        <svg class="h-3.5 w-3.5 text-sky-500" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v9.5a.5.5 0 01-.79.407L13 11.5l-4.21 3.407A.5.5 0 018 14.5V11L2.79 14.907A.5.5 0 012 14.5V5z"/></svg>
                        <span class="font-medium">{{ __('messages.messages_received', ['count' => $user->total_messages_count ?? 0]) }}</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-white/70 px-3 py-2 dark:bg-slate-900/60 border border-white/60 dark:border-slate-700/60">
                        <svg class="h-3.5 w-3.5 text-fuchsia-500" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a1 1 0 01.894.553l1.764 3.528 3.895.566a1 1 0 01.554 1.706l-2.818 2.748.666 3.878a1 1 0 01-1.45 1.054L10 15.347l-3.505 1.846a1 1 0 01-1.45-1.054l.666-3.878L2.893 9.353a1 1 0 01.554-1.706l3.895-.566 1.764-3.528A1 1 0 0110 3z"/></svg>
                        <span class="font-medium">{{ __('messages.public_messages_count', ['count' => $user->public_messages_count ?? 0]) }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2 md:self-start">
                <a href="{{ route('profiles.show', $user) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700/60 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-900/60 transition">
                    {{ __('messages.view_public_profile') ?? 'View profile' }}
                </a>
                <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800/70 p-1">
                    <a href="{{ route('profile.info') }}" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('profile.info') || request()->routeIs('profile') ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">{{ __('messages.tab_info') }}</a>
                    <a href="{{ route('profile.settings') }}" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('profile.settings') ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">{{ __('messages.tab_settings') }}</a>
                </div>
                <x-ui.copy-button :text="route('profiles.show', $user)" class="text-brand-orange hover:text-brand-orange/80 text-xs inline-flex justify-center">
                    {{ __('messages.copy_link') }}
                </x-ui.copy-button>
            </div>
        </div>
    </div>
</x-ui.card>
