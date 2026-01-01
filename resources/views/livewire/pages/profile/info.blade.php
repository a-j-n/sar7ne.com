@guest
    <x-auth.login-required 
        :title="__('messages.sign_in_to_view_profile') ?? 'Sign in to view your profile'"
        :message="__('messages.login_required_profile') ?? 'Profile details are private to you. Please sign in to manage your information.'"
        illustration="/illustrations/lock-profile.svg"
    />
@else
<div class="space-y-6 text-black">
    @include('livewire.pages.profile.partials.header', ['user' => $user])

    <x-ui.card padding="p-0">
        <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr] p-4 md:p-8">
            <div class="space-y-4">
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400 font-semibold">{{ __('messages.profile_information') }}</p>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('messages.customise_world_sees_you') }}</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('messages.register_description') }}</p>
                </div>
                @include('livewire.pages.profile.partials.info-form')
            </div>

            <div class="space-y-3">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/70 p-4 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ __('messages.profile_links') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('messages.full_link') }}</p>
                                <div class="mt-1 font-mono text-xs text-slate-800 dark:text-slate-100 truncate">{{ route('profiles.show', $user) }}</div>
                            </div>
                            <x-ui.copy-button :text="route('profiles.show', $user)" class="text-brand-orange hover:text-brand-orange/80" />
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('messages.short_link') }}</p>
                                <div class="mt-1 font-mono text-xs text-slate-800 dark:text-slate-100 truncate">{{ url('/'.$user->username) }}</div>
                            </div>
                            <x-ui.copy-button :text="url('/'.$user->username)" class="text-brand-orange hover:text-brand-orange/80" />
                        </div>
                        @if($url = $user->subdomainUrl())
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('messages.custom_domain') }}</p>
                                    <div class="mt-1 font-mono text-xs text-slate-800 dark:text-slate-100 truncate">{{ $url }}</div>
                                </div>
                                <x-ui.copy-button :text="$url" class="text-brand-orange hover:text-brand-orange/80" />
                            </div>
                        @endif
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white/70 dark:bg-slate-900/70 p-4 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ __('messages.respectful_guidelines') }}</h3>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li>• {{ __('messages.anonymous_by_default') }}</li>
                        <li>• {{ __('messages.rate_limit_notice') }}</li>
                        <li>• {{ __('messages.identity_notice') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
@endguest
