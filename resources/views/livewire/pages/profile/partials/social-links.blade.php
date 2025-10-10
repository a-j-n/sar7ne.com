<div class="border-t border-slate-200 dark:border-slate-700/60 pt-6 space-y-4">
    <h4 class="text-md font-semibold text-black mb-2">{{ __('messages.social_media_links') }}</h4>
    @php
        $fields = [
        ['key' => 'twitter', 'label' => __('messages.social_twitter'), 'placeholder' => __('messages.social_twitter_placeholder'), 'bg' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-500'],
        ['key' => 'instagram', 'label' => __('messages.social_instagram'), 'placeholder' => __('messages.social_instagram_placeholder'), 'bg' => 'bg-pink-50 dark:bg-pink-950/40 text-pink-500'],
        ['key' => 'tiktok', 'label' => __('messages.social_tiktok'), 'placeholder' => __('messages.social_tiktok_placeholder'), 'bg' => 'bg-gray-50 dark:bg-slate-900/70 text-gray-800 dark:text-gray-200'],
        ['key' => 'youtube', 'label' => __('messages.social_youtube'), 'placeholder' => __('messages.social_youtube_placeholder'), 'bg' => 'bg-red-50 dark:bg-red-950/40 text-red-500'],
        ['key' => 'linkedin', 'label' => __('messages.social_linkedin'), 'placeholder' => __('messages.social_linkedin_placeholder'), 'bg' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-500'],
        ['key' => 'github', 'label' => __('messages.social_github'), 'placeholder' => __('messages.social_github_placeholder'), 'bg' => 'bg-slate-50 dark:bg-slate-900/70 text-slate-800 dark:text-slate-200'],
        ['key' => 'website', 'label' => __('messages.social_website'), 'placeholder' => __('messages.social_website_placeholder'), 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600'],
        ];
    @endphp

    @foreach($fields as $f)
        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
            @php $hasValue = !empty($social_links[$f['key']] ?? null); @endphp
            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $f['bg'] }} {{ $hasValue ? 'ring-2 ring-emerald-400/60 shadow-sm text-emerald-600 dark:text-emerald-400' : '' }}">
                @switch($f['key'])
                    @case('twitter')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        @break
                    @case('instagram')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.928-.875 2.026-1.365 3.323-1.365s2.448.49 3.323 1.365c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.606c-.49 0-.928-.367-.928-.857 0-.49.438-.857.928-.857s.928.367.928.857c0 .49-.438.857-.928.857zm-3.323 9.606c-2.079 0-3.77-1.691-3.77-3.77s1.691-3.77 3.77-3.77 3.77 1.691 3.77 3.77-1.691 3.77-3.77 3.77z"/></svg>
                        @break
                    @case('tiktok')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        @break
                    @case('youtube')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        @break
                    @case('linkedin')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V24h-4V8zm7 0h3.8v2.2h.05c.53-1 1.84-2.2 3.78-2.2C20.4 8 24 10.3 24 15.3V24h-4v-7.5c0-1.8-.03-4.1-2.5-4.1-2.5 0-2.9 2-2.9 4v7.6h-4V8z"/></svg>
                        @break
                    @case('github')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.482 0-.237-.01-1.022-.015-1.855-2.782.604-3.369-1.192-3.369-1.192-.454-1.155-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.004.07 1.532 1.032 1.532 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.339-2.22-.253-4.555-1.112-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.269 2.75 1.026A9.564 9.564 0 0112 6.844a9.56 9.56 0 012.506.337c1.909-1.295 2.748-1.026 2.748-1.026.545 1.378.202 2.397.1 2.65.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.944.359.309.679.919.679 1.852 0 1.336-.012 2.414-.012 2.742 0 .267.18.578.688.48C19.138 20.194 22 16.44 22 12.017 22 6.484 17.523 2 12 2z" clip-rule="evenodd"/></svg>
                        @break
                    @case('website')
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.486 2 12s4.477 10 10 10 10-4.486 10-10S17.523 2 12 2zm6.93 6H17.1c-.33-1.08-.74-2.13-1.23-3.07A8.03 8.03 0 0118.93 8zM12 4c.9 0 2.27 1.64 3.03 4H8.97C9.73 5.64 11.1 4 12 4zM4.07 8A8.03 8.03 0 018.13 4.93 15.91 15.91 0 006.9 8H4.07zM4 12c0-.68.04-1.35.12-2h3.26c-.08.66-.13 1.33-.13 2s.05 1.34.13 2H4.12A16.2 16.2 0 014 12zm.07 4H6.9c.33 1.08.74 2.13 1.23 3.07A8.03 8.03 0 014.07 16zM12 20c-.9 0-2.27-1.64-3.03-4h6.06C14.27 18.36 12.9 20 12 20zm3.87-2.93c.49-.94.9-1.99 1.23-3.07h2.83A8.03 8.03 0 0115.87 17.07zM16.62 12c0 .67-.05 1.34-.13 2h-4.98c-.08-.66-.13-1.33-.13-2s.05-1.34.13-2h4.98c.08.66.13 1.33.13 2zM12 9c.24 0 .73.98.88 3-.15 2.02-.64 3-.88 3s-.73-.98-.88-3c.15-2.02.64-3 .88-3zM15.03 4.93A8.03 8.03 0 0119.93 8H17.1z"/></svg>
                        @break
                @endswitch
            </div>
            <div class="flex-1">
                <label class="text-xs font-medium uppercase tracking-wide {{ $hasValue ? 'text-emerald-600' : 'text-black' }}">{{ $f['label'] }}</label>
                <input type="url" wire:model.live.debounce.300ms="social_links.{{ $f['key'] }}" placeholder="{{ $f['placeholder'] }}" class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none {{ $hasValue ? 'border-emerald-300/60' : '' }}" />
            </div>
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="social_visibility.{{ $f['key'] }}" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400" />
                <span class="ml-2 text-xs {{ $hasValue ? 'text-emerald-600' : 'text-black' }}">{{ __('messages.visibility_public') }}</span>
            </label>
        </div>
    @endforeach
</div>
