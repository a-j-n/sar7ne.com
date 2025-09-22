@extends('layouts.app')

@section('title', '@'.$user->username.' · sar7ne')

@section('content')
    @php
        $messageRoute = route('profiles.message', $user);
        if (Route::currentRouteName() === 'profiles.show.subdomain') {
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
                <p class="text-sm text-slate-300">{{ $user->bio ?? 'Drop an anonymous message and make their day.' }}</p>
                <p class="text-xs text-slate-500">{{ $user->total_messages_count }} messages received • powered by sar7ne</p>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">Send an anonymous message</h2>
            <p class="mt-1 text-xs text-slate-400">Your identity won’t be shared. Keep it kind.</p>

            <form class="mt-6 space-y-4" method="POST" action="{{ $messageRoute }}">
                @csrf
                <textarea id="message" name="message_text" rows="5" maxlength="500" placeholder="Write something thoughtful..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-white/40 focus:outline-none">{{ old('message_text') }}</textarea>
                <button type="submit" class="w-full rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-black transition hover:bg-slate-200">Send anonymously</button>
            </form>

            <p class="mt-3 text-center text-[11px] tracking-wide text-slate-500">We rate-limit to keep spam away. Be nice ✌️</p>
        </section>
    </div>
@endsection
