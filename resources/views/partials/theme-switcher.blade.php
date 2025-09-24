<div x-data="{ open: false }" class="relative">
    @php $theme = request()->cookie('theme', 'system'); @endphp

    <!-- Trigger Button -->
    <button
        @click="open = !open"
        type="button"
        class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900 transition-all duration-200"
        aria-label="Change theme"
    >
        <span class="sr-only">Change theme</span>
        <!-- Dynamic icon based on current theme -->
        @if($theme === 'light')
            <!-- Sun icon -->
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        @elseif($theme === 'dark')
            <!-- Moon icon -->
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        @else
            <!-- System/Auto icon -->
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white dark:bg-slate-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 border border-slate-200 dark:border-white/10 backdrop-blur-sm z-50"
        style="display: none;"
    >
        <div class="p-2">
            <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                {{ __('Theme') }}
            </div>
            
            <!-- Light Theme Option -->
            <a href="{{ route('theme.switch', 'light') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ $theme === 'light' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 font-medium' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <div class="flex h-6 w-6 items-center justify-center rounded-full {{ $theme === 'light' ? 'bg-emerald-200 dark:bg-emerald-800' : 'bg-slate-100 dark:bg-white/10' }}">
                    <svg class="h-3.5 w-3.5 {{ $theme === 'light' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">{{ __('Light') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('Always light theme') }}</div>
                </div>
                @if($theme === 'light')
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>

            <!-- System Theme Option -->
            <a href="{{ route('theme.switch', 'system') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ $theme === 'system' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 font-medium' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <div class="flex h-6 w-6 items-center justify-center rounded-full {{ $theme === 'system' ? 'bg-emerald-200 dark:bg-emerald-800' : 'bg-slate-100 dark:bg-white/10' }}">
                    <svg class="h-3.5 w-3.5 {{ $theme === 'system' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">{{ __('System') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('Follow system preference') }}</div>
                </div>
                @if($theme === 'system')
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>

            <!-- Dark Theme Option -->
            <a href="{{ route('theme.switch', 'dark') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ $theme === 'dark' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 font-medium' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <div class="flex h-6 w-6 items-center justify-center rounded-full {{ $theme === 'dark' ? 'bg-emerald-200 dark:bg-emerald-800' : 'bg-slate-100 dark:bg-white/10' }}">
                    <svg class="h-3.5 w-3.5 {{ $theme === 'dark' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">{{ __('Dark') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('Always dark theme') }}</div>
                </div>
                @if($theme === 'dark')
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
        </div>
    </div>

    <!-- Fallback for no JavaScript -->
    <noscript>
        <div class="absolute right-0 top-full mt-2 flex gap-1 rounded-lg bg-white dark:bg-slate-800 p-1 shadow-lg">
            <a href="{{ route('theme.switch', 'light') }}" class="px-2 py-1 text-xs {{ $theme === 'light' ? 'font-semibold' : '' }}">Light</a>
            <a href="{{ route('theme.switch', 'system') }}" class="px-2 py-1 text-xs {{ $theme === 'system' ? 'font-semibold' : '' }}">System</a>
            <a href="{{ route('theme.switch', 'dark') }}" class="px-2 py-1 text-xs {{ $theme === 'dark' ? 'font-semibold' : '' }}">Dark</a>
        </div>
    </noscript>
</div>
