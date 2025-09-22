@extends('layouts.app')

@section('title', 'Profile · sar7ne')

@section('content')
    <div class="space-y-10">
        <section class="flex flex-col gap-6 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl md:flex-row md:items-center">
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" class="h-24 w-24 rounded-3xl object-cover" />
            <div class="flex-1 space-y-2">
                <h1 class="text-2xl font-semibold">{{ "@".$user->username }}</h1>
                @if ($user->display_name)
                    <p class="text-sm text-slate-300">{{ $user->display_name }}</p>
                @endif
                <p class="text-xs text-slate-400">Joined {{ $user->created_at->format('M Y') }} · {{ $user->total_messages_count }} messages received</p>
                <div class="flex flex-wrap gap-3 text-xs text-slate-300">
                    <span class="rounded-full border border-white/10 px-3 py-1">Provider: {{ ucfirst($user->provider_type) }}</span>
                    @if ($url = $user->subdomainUrl())
                        <a href="{{ $url }}" class="rounded-full border border-white/10 px-3 py-1 transition hover:border-white/30" target="_blank" rel="noreferrer">{{ $url }}</a>
                    @endif
                    <a href="{{ route('profiles.show', $user) }}" class="rounded-full border border-white/10 px-3 py-1 transition hover:border-white/30">Public profile preview</a>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">Profile settings</h2>
            <p class="mt-1 text-xs text-slate-400">Customise how the world sees you on sar7ne.</p>

            <form class="mt-6 space-y-6" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="username" class="text-xs uppercase tracking-wide text-slate-400">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required pattern="^[A-Za-z0-9_]+$" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                        <p class="text-xs text-slate-400">Lowercase letters, numbers, underscores only.</p>
                    </div>
                    <div class="space-y-2">
                        <label for="display_name" class="text-xs uppercase tracking-wide text-slate-400">Display name</label>
                        <input id="display_name" name="display_name" type="text" value="{{ old('display_name', $user->display_name) }}" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="bio" class="text-xs uppercase tracking-wide text-slate-400">Bio</label>
                    <textarea id="bio" name="bio" rows="4" maxlength="280" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-xs text-slate-400">Share a short vibe for your visitors (max 280 characters).</p>
                </div>

                <div class="space-y-2">
                    <label for="avatar" class="text-xs uppercase tracking-wide text-slate-400">Avatar</label>
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none" />
                    <p class="text-xs text-slate-400">Square PNG/JPG, up to 2MB.</p>
                </div>

                <div class="space-y-2">
                    <label for="gender" class="text-xs uppercase tracking-wide text-slate-400">Gender</label>
                    <select id="gender" name="gender" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm focus:border-white/40 focus:outline-none">
                        @php
                            $genders = [
                                'male' => 'Male',
                                'female' => 'Female',
                                'non-binary' => 'Non-binary',
                                'other' => 'Other',
                                'prefer_not_to_say' => 'Prefer not to say',
                            ];
                            $current = old('gender', $user->gender);
                        @endphp
                        <option value="" {{ $current === null || $current === '' ? 'selected' : '' }}>— Not specified —</option>
                        @foreach ($genders as $key => $label)
                            <option value="{{ $key }}" {{ $current === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400">Optional — choose how you'd like to describe your gender.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-white px-5 py-3 text-sm font-semibold text-black transition hover:bg-slate-200">Save changes</button>
                </div>
            </form>
        </section>
    </div>
@endsection
