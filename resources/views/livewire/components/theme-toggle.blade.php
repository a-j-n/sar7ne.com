<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cookie;

new class extends Component {
    public string $theme = 'system';

    public function mount(): void
    {
        $this->theme = request()->cookie('theme', 'dark');
    }

    public function setTheme(string $theme): void
    {
        $available = ['light','dark','system'];
        if (! in_array($theme, $available, true)) {
            return;
        }

        $this->theme = $theme;

        // Persist cookie (1 year) so server renders match
        $minutes = 60 * 24 * 365;
        Cookie::queue(cookie('theme', $theme, $minutes, '/', null, request()->isSecure(), false, false, 'Lax'));

        // Apply immediately on the client to avoid reload
        $this->dispatch('theme-updated', theme: $theme);
    }
}; ?>

<div
    x-data="{
        theme: @entangle('theme').live,
    }"
    x-init="
        const apply = (t) => {
            const root = document.documentElement;
            const isDark = (t === 'dark') || (t === 'system' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            root.classList.toggle('dark', isDark);
            // Update theme-color meta for OS chrome
            const metaDark = document.querySelector('meta[name=\"theme-color\"][media*=\"dark\"]');
            const metaLight = document.querySelector('meta[name=\"theme-color\"][media*=\"light\"]');
            if (isDark) { if (metaDark) metaDark.content = '#05070d'; } else { if (metaLight) metaLight.content = '#ffffff'; }
        };
        apply(theme);
        // React to system changes when on system
        if (window.matchMedia) {
            const mq = window.matchMedia('(prefers-color-scheme: dark)');
            const update = () => { if (theme === 'system') apply('system'); };
            mq.addEventListener ? mq.addEventListener('change', update) : mq.addListener(update);
        }
        window.addEventListener('theme-updated', e => apply(e.detail.theme));
    "
    class="inline-flex items-center gap-2"
>
    <div class="inline-flex rounded-full border border-secondary dark:border-white/10 bg-primary p-1 text-xs font-medium shadow-sm">
        <button type="button" class="px-3 py-1.5 rounded-full transition {{ $theme === 'light' ? 'bg-gradient-orange-pink text-white shadow' : 'text-secondary dark:text-slate-300' }}" wire:click="setTheme('light')">{{ __('Light') }}</button>
        <button type="button" class="px-3 py-1.5 rounded-full transition {{ $theme === 'system' ? 'bg-gradient-orange-pink text-white shadow' : 'text-secondary dark:text-slate-300' }}" wire:click="setTheme('system')">{{ __('System') }}</button>
        <button type="button" class="px-3 py-1.5 rounded-full transition {{ $theme === 'dark' ? 'bg-gradient-orange-pink text-white shadow' : 'text-secondary dark:text-slate-300' }}" wire:click="setTheme('dark')">{{ __('Dark') }}</button>
    </div>
</div>

