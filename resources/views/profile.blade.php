@extends('layouts.app')

@section('title', __('messages.profile').' · sar7ne')

@section('meta_description', $user->bio ?? __('messages.customise_world_sees_you'))
@section('meta_image', $user->avatarUrl())
@section('og_type', 'profile')
@section('canonical', route('profile'))

@section('content')
    <div class="space-y-10">
        <section class="flex flex-col gap-6 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl md:flex-row md:items-center">
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" class="h-24 w-24 rounded-3xl object-cover" />
            <div class="flex-1 space-y-2">
                <h1 class="text-2xl font-semibold">{{ "@".$user->username }}</h1>
                @if ($user->display_name)
                    <p class="text-sm text-slate-300">{{ $user->display_name }}</p>
                @endif
                <p class="text-xs text-slate-400">{{ __('messages.joined', ['date' => $user->created_at->format('M Y')]) }} · {{ __('messages.messages_received', ['count' => $user->total_messages_count]) }}</p>
                <div class="flex flex-wrap gap-3 text-xs text-slate-300">
                    <span class="rounded-full border border-white/10 px-3 py-1">{{ __('messages.provider_label') }}: {{ ucfirst($user->provider_type ?? '') }}</span>
                    @if ($url = $user->subdomainUrl())
                        <a href="{{ $url }}" class="rounded-full border border-white/10 px-3 py-1 transition hover:border-white/30" target="_blank" rel="noreferrer">{{ $url }}</a>
                    @endif
                    <a href="{{ route('profiles.show', $user) }}" class="rounded-full border border-white/10 px-3 py-1 transition hover:border-white/30">{{ __('messages.public_profile_preview') }}</a>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ url('/' . $user->username) }}" class="w-40 rounded border border-white/10 bg-white/10 px-2 py-1 text-xs text-slate-300" id="profile-link-short">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('profile-link-short').value)" class="rounded bg-white/10 px-2 py-1 text-xs hover:bg-white/20">Copy</button>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('profiles.show', $user) }}" class="w-40 rounded border border-white/10 bg-white/10 px-2 py-1 text-xs text-slate-300" id="profile-link-p">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('profile-link-p').value)" class="rounded bg-white/10 px-2 py-1 text-xs hover:bg-white/20">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">{{ __('messages.profile_settings') }}</h2>
            <p class="mt-1 text-xs text-slate-400">{{ __('messages.customise_world_sees_you') }}</p>

            <form class="mt-6 space-y-6" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="username" class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.username_label') }}</label>
                        <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required pattern="^[A-Za-z0-9_]+$" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                        <p class="text-xs text-slate-400">{{ __('messages.not_specified') }}</p>
                    </div>
                    <div class="space-y-2">
                        <label for="display_name" class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.display_name_label') }}</label>
                        <input id="display_name" name="display_name" type="text" value="{{ old('display_name', $user->display_name) }}" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="bio" class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.bio_label') }}</label>
                    <textarea id="bio" name="bio" rows="4" maxlength="280" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-xs text-slate-400">{{ __('messages.bio_help', ['max' => 280]) }}</p>
                </div>

                <div class="space-y-2">
                    <label for="avatar" class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.avatar_label') }}</label>
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                    <p class="text-xs text-slate-400">{{ __('messages.avatar_help', ['size' => '2MB']) }}</p>
                </div>

                <div class="space-y-2">
                    <label for="gender" class="text-xs uppercase tracking-wide text-slate-400">{{ __('messages.gender_label') }}</label>
                    <select id="gender" name="gender" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none">
                        @php
                            $genders = [
                                'male' => __('messages.gender_male'),
                                'female' => __('messages.gender_female'),
                                'non-binary' => __('messages.gender_non_binary'),
                                'other' => __('messages.gender_other'),
                                'prefer_not_to_say' => __('messages.gender_prefer_not'),
                            ];
                            $current = old('gender', $user->gender);
                        @endphp
                        <option value="" {{ $current === null || $current === '' ? 'selected' : '' }}>{{ __('messages.not_specified') }}</option>
                        @foreach ($genders as $key => $label)
                            <option value="{{ $key }}" {{ $current === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400">{{ __('messages.optional_choose_gender') }}</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-white px-5 py-3 text-sm font-semibold text-black transition hover:bg-slate-200">{{ __('messages.save_changes') }}</button>
                </div>
            </form>
        </section>

        <div class="mt-8 text-center text-xs text-slate-400">
            <a href="{{ route('privacy') }}" class="hover:text-white">{{ __('messages.privacy') }}</a>
        </div>
    </div>
@endsection
