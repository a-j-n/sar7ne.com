@extends('layouts.app')

@section('title', 'Inbox · sar7ne')
@section('meta_description', 'Your sar7ne inbox — view and manage anonymous messages you have received.')
@section('og_type', 'website')
@section('canonical', route('inbox'))

@section('content')
    <div class="space-y-8">
<section class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-xl">
            <h1 class="text-2xl font-semibold">Your inbox</h1>
            <p class="mt-2 text-sm text-slate-300">Anonymous messages you’ve received. You have <span class="font-semibold text-white">{{ $unreadCount }}</span> unread.</p>
        </section>

        <section class="space-y-4">
            @forelse ($messages as $message)
                <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2">
                            <p class="whitespace-pre-line text-sm text-slate-100">{{ $message->message_text }}</p>
                            <p class="text-xs text-slate-400">Received {{ $message->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-medium {{ $message->status === 'unread' ? 'bg-emerald-500/20 text-emerald-200' : 'bg-white/10 text-slate-300' }}">
                            {{ ucfirst($message->status) }}
                        </span>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3 text-xs">
                        <form method="POST" action="{{ route('inbox.messages.toggle-public', $message) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_public" value="{{ $message->is_public ? 0 : 1 }}">
                            <button class="rounded-xl border border-white/10 px-4 py-2 text-white transition hover:border-white/30">
                                {{ $message->is_public ? __('messages.make_private') : __('messages.make_public') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('inbox.messages.toggle-public', $message) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_public" value="{{ $message->is_public ? 0 : 1 }}">
                            <button class="rounded-xl border border-white/10 px-4 py-2 text-white transition hover:border-white/30">
                                {{ $message->is_public ? __('messages.make_private') : __('messages.make_public') }}
                            </button>
                        </form>
                        @if ($message->status === 'unread')
                            <form method="POST" action="{{ route('inbox.messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-xl border border-white/10 px-4 py-2 text-white transition hover:border-white/30">Mark as read</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('inbox.messages.unread', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-xl border border-white/10 px-4 py-2 text-white transition hover:border-white/30">Mark unread</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('inbox.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-2 text-red-200 transition hover:border-red-500/40">Delete</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-10 text-center text-sm text-slate-300">
                    Your inbox is waiting. Share your profile link to collect messages.
                </div>
            @endforelse
        </section>

        <div>
            {{ $messages->links() }}
        </div>
    </div>
@endsection
