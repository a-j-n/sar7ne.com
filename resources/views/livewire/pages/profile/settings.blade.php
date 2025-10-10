<div class="space-y-6" wire:ignore.self>
    <!-- Profile Header Card -->
    <x-ui.card class="border border-slate-200 dark:border-slate-700/60 shadow-none" padding="p-0">
        <div class="relative overflow-hidden rounded-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-sky-500/10 to-fuchsia-500/10 dark:from-emerald-400/10 dark:via-sky-400/10 dark:to-fuchsia-400/10"></div>
            <div class="relative flex flex-col gap-4 md:flex-row md:items-center p-4">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" loading="lazy" class="h-20 w-20 rounded-2xl object-cover ring-1 ring-slate-200/80 dark:ring-slate-700/80" />
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-slate-900 dark:text-white">{{ "@".$user->username }}</h1>
                    @if ($user->display_name)
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $user->display_name }}</p>
                    @endif
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-600 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 dark:bg-slate-900/60 px-2 py-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/><path d="M18 9H2v6a2 2 0 002 2h12a2 2 0 002-2V9z"/></svg>
                            {{ __('messages.joined', ['date' => $user->created_at->format('M Y')]) }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 dark:bg-slate-900/60 px-2 py-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v9.5a.5.5 0 01-.79.407L13 11.5l-4.21 3.407A.5.5 0 018 14.5V11L2.79 14.907A.5.5 0 012 14.5V5z"/></svg>
                            {{ __('messages.messages_received', ['count' => $user->total_messages_count]) }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 dark:bg-slate-900/60 px-2 py-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a1 1 0 01.894.553l1.764 3.528 3.895.566a1 1 0 01.554 1.706l-2.818 2.748.666 3.878a1 1 0 01-1.45 1.054L10 15.347l-3.505 1.846a1 1 0 01-1.45-1.054l.666-3.878L2.893 9.353a1 1 0 01.554-1.706l3.895-.566 1.764-3.528A1 1 0 0110 3z"/></svg>
                            {{ __('messages.public_messages_count', ['count' => $user->public_messages_count ?? 0]) }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2 md:self-start">
                    <a href="{{ route('profiles.show', $user) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700/60 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-900/60 transition">
                        {{ __('messages.view_public_profile') ?? 'View profile' }}
                    </a>
                    <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 text-sm transition">
                        <span wire:loading.remove>{{ __('messages.save') }}</span>
                        <span wire:loading>{{ __('messages.saving') ?? 'Saving…' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Tabbed Interface -->
    <x-ui.card padding="p-0">
        <x-ui.tabs default-tab="info">
            <!-- Tab Navigation -->
            <div class="px-4 md:px-8 pt-4 md:pt-8 pb-2 md:pb-4">
                <x-ui.tab-list>
                    <x-ui.tab-trigger value="info">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('messages.tab_info') }}
                    </x-ui.tab-trigger>
                    <x-ui.tab-trigger value="settings">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('messages.tab_settings') }}
                    </x-ui.tab-trigger>
                </x-ui.tab-list>
            </div>

            <!-- Info Tab Content -->
            <x-ui.tab-content value="info" class="px-4 md:px-8 pb-6 md:pb-8">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('messages.profile_information') }}</h3>
                        <div class="grid gap-3 md:gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400 font-medium">{{ __('messages.username_label') }}</label>
                                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60">
                                    <span class="text-sm text-slate-900 dark:text-white font-mono">{{ "@".$user->username }}</span>
                                </div>
                            </div>
                            @if($user->display_name)
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400 font-medium">{{ __('messages.display_name_label') }}</label>
                                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60">
                                    <span class="text-sm text-slate-900 dark:text-white">{{ $user->display_name }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if($user->bio)
                        <div class="space-y-2 mt-4">
                            <label class="text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400 font-medium">{{ __('messages.bio_label') }}</label>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60">
                                <p class="text-sm text-slate-900 dark:text-white">{{ $user->bio }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Profile Links Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('messages.profile_links') }}</h3>
                        <div class="space-y-4">
                            <!-- Short Link -->
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-950/40 dark:to-blue-950/40 border border-emerald-200 dark:border-emerald-700/40">
                                <div class="flex-1">
                                    <label class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-300 font-medium">{{ __('messages.short_link') }}</label>
                                    <div class="mt-1 font-mono text-sm text-slate-900 dark:text-white">{{ url('/' . $user->username) }}</div>
                                </div>
                                <x-ui.copy-button :text="url('/' . $user->username)" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300" />
                            </div>

                            <!-- Full Link -->
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-950/40 dark:to-purple-950/40 border border-blue-200 dark:border-blue-700/40">
                                <div class="flex-1">
                                    <label class="text-xs uppercase tracking-wide text-blue-700 dark:text-blue-300 font-medium">{{ __('messages.full_link') }}</label>
                                    <div class="mt-1 font-mono text-sm text-slate-900 dark:text-white">{{ route('profiles.show', $user) }}</div>
                                </div>
                                <x-ui.copy-button :text="route('profiles.show', $user)" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" />
                            </div>

                            @if($url = $user->subdomainUrl())
                            <!-- Subdomain Link -->
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-950/40 dark:to-pink-950/40 border border-purple-200 dark:border-purple-700/40">
                                <div class="flex-1">
                                    <label class="text-xs uppercase tracking-wide text-purple-700 dark:text-purple-300 font-medium">{{ __('messages.custom_domain') }}</label>
                                    <div class="mt-1 font-mono text-sm text-slate-900 dark:text-white">{{ $url }}</div>
                                </div>
                                <x-ui.copy-button :text="$url" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300" />
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex gap-3">
                        <x-ui.button variant="outline" size="md" onclick="window.open('{{ route('profiles.show', $user) }}', '_blank')">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ __('messages.public_profile_preview') }}
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.tab-content>

            <!-- Settings Tab Content -->
            <x-ui.tab-content value="settings" class="px-4 md:px-8 pb-6 md:pb-8">
                <section class="mb-8 rounded-3xl border border-slate-200/80 dark:border-white/10 bg-gradient-to-br from-white via-white to-[#f8fbff] dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 p-6 shadow-inner">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-xl space-y-1">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('messages.experience_preferences') }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('messages.adjust_theme_language_preferences') }}</p>
                        </div>
                        <div class="flex flex-col gap-4 md:flex-row">
                            @include('partials.theme-switcher')
                            @include('partials.language-switcher')
                        </div>
                    </div>
                </section>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('messages.profile_settings') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">{{ __('messages.customise_world_sees_you') }}</p>
                        
                        <div class="grid gap-6 md:grid-cols-2">
                            <x-ui.input 
                                id="username" 
                                wire:model.live.debounce.300ms="username" 
                                :label="__('messages.username_label')"
                                :error="$errors->first('username')"
                                required 
                                pattern="^[A-Za-z0-9_]+$"
                            />
                            
                            <x-ui.input 
                                id="display_name" 
                                wire:model.live.debounce.300ms="display_name" 
                                :label="__('messages.display_name_label')"
                            />
                        </div>

                        <x-ui.textarea 
                            id="bio" 
                            wire:model.live.debounce.300ms="bio" 
                            :label="__('messages.bio_label')"
                            :help="__('messages.bio_help', ['max' => 280])"
                            rows="4" 
                            maxlength="280"
                        />

                        <div class="space-y-2">
                            <label for="avatar" class="text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400 font-medium">{{ __('messages.avatar_label') }}</label>
                            <input id="avatar" type="file" wire:model="avatar" accept="image/*" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" alt="{{ __('messages.avatar_preview_alt') }}" loading="lazy" class="mt-3 h-16 w-16 rounded-full object-cover" />
                            @endif
                            @error('avatar') <p class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.avatar_help', ['size' => '2MB']) }}</p>
                        </div>

                        <div class="space-y-2">
                            <label for="gender" class="text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400 font-medium">{{ __('messages.gender_label') }}</label>
                            <select id="gender" wire:model.defer="gender" class="w-full rounded-xl border border-slate-300 dark:border-slate-700/60 bg-white dark:bg-slate-900/60 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-emerald-400 dark:focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition-all duration-200">
                                <option value="">{{ __('messages.not_specified') }}</option>
                                <option value="male">{{ __('messages.gender_male') }}</option>
                                <option value="female">{{ __('messages.gender_female') }}</option>
                                <option value="non-binary">{{ __('messages.gender_non_binary') }}</option>
                                <option value="other">{{ __('messages.gender_other') }}</option>
                                <option value="prefer_not_to_say">{{ __('messages.gender_prefer_not') }}</option>
                            </select>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.optional_choose_gender') }}</p>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="border-t border-slate-200 dark:border-slate-700/60 pt-6">
                        <h4 class="text-md font-semibold text-slate-900 dark:text-white mb-4">{{ __('messages.social_media_links') }}</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">{{ __('messages.social_media_links_help') }}</p>
                        
                        <div class="space-y-4">
                            <!-- Twitter -->
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('messages.social_twitter') }}</label>
                                    <input 
                                        type="url" 
                                        wire:model.live.debounce.300ms="social_links.twitter"
                                        placeholder="{{ __('messages.social_twitter_placeholder') }}"
                                        class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.defer="social_visibility.twitter"
                                        class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400"
                                    />
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.visibility_public') }}</span>
                                </label>
                            </div>

                            <!-- Instagram -->
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 dark:bg-pink-950/40 text-pink-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.928-.875 2.026-1.365 3.323-1.365s2.448.49 3.323 1.365c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.606c-.49 0-.928-.367-.928-.857 0-.49.438-.857.928-.857s.928.367.928.857c0 .49-.438.857-.928.857zm-3.323 9.606c-2.079 0-3.77-1.691-3.77-3.77s1.691-3.77 3.77-3.77 3.77 1.691 3.77 3.77-1.691 3.77-3.77 3.77z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('messages.social_instagram') }}</label>
                                    <input 
                                        type="url" 
                                        wire:model.live.debounce.300ms="social_links.instagram"
                                        placeholder="{{ __('messages.social_instagram_placeholder') }}"
                                        class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.defer="social_visibility.instagram"
                                        class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400"
                                    />
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.visibility_public') }}</span>
                                </label>
                            </div>

                            <!-- TikTok -->
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-900/70 text-gray-800 dark:text-gray-200">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('messages.social_tiktok') }}</label>
                                    <input 
                                        type="url" 
                                        wire:model.live.debounce.300ms="social_links.tiktok"
                                        placeholder="{{ __('messages.social_tiktok_placeholder') }}"
                                        class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.defer="social_visibility.tiktok"
                                        class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400"
                                    />
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.visibility_public') }}</span>
                                </label>
                            </div>

                            <!-- YouTube -->
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-950/40 text-red-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('messages.social_youtube') }}</label>
                                    <input 
                                        type="url" 
                                        wire:model.live.debounce.300ms="social_links.youtube"
                                        placeholder="{{ __('messages.social_youtube_placeholder') }}"
                                        class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.defer="social_visibility.youtube"
                                        class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400"
                                    />
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.visibility_public') }}</span>
                                </label>
                            </div>

                            <!-- Website -->
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:border-slate-300 dark:hover:border-slate-600/60 transition-colors">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('messages.social_website') }}</label>
                                    <input 
                                        type="url" 
                                        wire:model.live.debounce.300ms="social_links.website"
                                        placeholder="{{ __('messages.social_website_placeholder') }}"
                                        class="w-full mt-1 bg-transparent text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.defer="social_visibility.website"
                                        class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400"
                                    />
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">{{ __('messages.visibility_public') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="border-t border-slate-200 dark:border-white/10 pt-6">
                        <h4 class="text-md font-semibold text-slate-900 dark:text-white mb-4">{{ __('messages.privacy_settings') }}</h4>
                        <div class="space-y-4">
                            <label class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition-colors cursor-pointer">
                                <input type="checkbox" wire:model.defer="allow_public_messages" class="mt-0.5 h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-white dark:bg-slate-900/70 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="text-sm font-medium text-slate-900 dark:text-white">{{ __('messages.allow_public_messages') }}</span>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('messages.allow_public_messages_help') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-200 dark:border-slate-700/60">
                        <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="save,avatar">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span wire:loading.remove wire:target="save">{{ __('messages.save_changes') }}</span>
                            <span wire:loading wire:target="save">{{ __('messages.saving') }}</span>
                        </x-ui.button>
                        <span class="text-xs text-slate-500 dark:text-slate-400" wire:dirty>{{ __('messages.unsaved_changes') }}</span>
                    </div>
                </form>
            </x-ui.tab-content>
        </x-ui.tabs>
    </x-ui.card>

    <!-- Footer -->
    <div class="text-center">
        <a href="{{ route('privacy') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors">
            {{ __('messages.privacy') }}
        </a>
    </div>
</div>
