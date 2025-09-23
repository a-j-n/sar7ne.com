@extends('layouts.app')

@section('title', '@'.$user->username.' · sar7ne')

@section('meta_description', $user->bio ?? __('messages.drop_anonymous_message'))
@section('meta_image', $user->avatarUrl())
@section('og_title', '@'.$user->username.' · sar7ne')
@section('og_type', 'profile')
@section('canonical', url()->current())

@section('content')
    @php
        $messageRoute = route('profiles.message', $user);
        if (request()->routeIs('profiles.show.subdomain')) {
            $messageRoute = route('profiles.message.subdomain', ['username' => $user->username]);
        }
    @endphp

    <div class="space-y-10">
        <section class="flex flex-col gap-6 rounded-3xl border border-white/10 bg-white/5 p-6 text-center shadow-xl md:flex-row md:items-center md:text-left">
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" class="mx-auto h-28 w-28 rounded-3xl object-cover md:mx-0" />
            <div class="flex-1 space-y-2">
                <h1 class="text-3xl font-semibold">{{ "@".$user->username }}</h1>
                @if ($user->display_name)
                    <p class="text-sm text-slate-300">{{ $user->display_name }}</p>
                @endif
                <p class="text-sm text-slate-300">{{ $user->bio ?? __('messages.drop_anonymous_message') }}</p>
                <p class="text-xs text-slate-500">{{ __('messages.messages_received', ['count' => $user->total_messages_count]) }} • {{ __('messages.powered_by', ['app' => config('app.name')]) }}</p>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">{{ __('messages.send_anonymous_message') }}</h2>
            <p class="mt-1 text-xs text-slate-400">{{ __('messages.identity_notice') }}</p>

            <form class="mt-6 space-y-4" method="POST" action="{{ $messageRoute }}">
                @csrf
                <textarea id="message" name="message_text" rows="5" maxlength="500" placeholder="{{ __('messages.textarea_placeholder') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-white/40 focus:outline-none">{{ old('message_text') }}</textarea>
                <button type="submit" class="w-full rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-black transition hover:bg-slate-200">{{ __('messages.send_anonymously') }}</button>
            </form>

            <p class="mt-3 text-center text-[11px] tracking-wide text-slate-500">{{ __('messages.rate_limit_notice') }}</p>
        </section>

        {{-- JSON-LD for the public profile to help search engines understand this is a person/profile --}}
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $user->display_name ?? '@'.$user->username,
            'url' => url()->current(),
            'image' => $user->avatarUrl(),
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
        </script>
    </div>
@endsection
