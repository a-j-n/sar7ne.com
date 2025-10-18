@php(/** @var \App\Models\Post $post */ null)
@php($isAnon = $post->is_anonymous || !$post->user)
@php($hue = $isAnon ? (crc32((string)$post->id) % 360) : null)
@php($liked = auth()->check() ? $post->likes()->where('user_id', auth()->id())->exists() : false)
@php($likeCount = $post->likes()->count())

<a href="{{ route('posts.show', $post) }}" class="block focus:outline-none focus:ring-2 focus:ring-emerald-300 rounded-xl">
<x-ui.card id="post-{{ $post->id }}" padding="p-0" class="text-black transition-all duration-200 hover:shadow-lg/60 hover:border-emerald-200/60 overflow-hidden cursor-pointer">
    <div class="p-4 md:p-5">
        <div class="flex items-start gap-3 mb-3">
            @if($isAnon)
                <img src="{{ asset('anon-avatar.svg') }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200" style="color: hsl({{ $hue }}, 80%, 55%)">
            @else
                <img src="{{ $post->user->avatarUrl() }}" alt="avatar" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200">
            @endif
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <div class="text-sm font-semibold truncate">{{ $isAnon ? __('Anonymous') : '@'.$post->user->username }}</div>
                    <span class="text-[11px] text-black/50">· {{ $post->created_at->diffForHumans() }}</span>
                </div>
                @if(!$isAnon && $post->user?->display_name)
                    <div class="text-[11px] text-black/60 truncate">{{ $post->user->display_name }}</div>
                @endif
            </div>
        </div>

        @if($post->content)
            <div class="text-[15px] leading-6 text-slate-900 px-0 md:px-0">{!! nl2br(e($post->content)) !!}</div>
        @endif

        @if(is_array($post->images) && count($post->images))
            <div class="mt-3 grid grid-cols-2 gap-2 md:gap-3">
                @foreach($post->images as $img)
                    <a href="{{ route('posts.show', $post) }}" class="block group overflow-hidden rounded-xl border border-slate-200">
                        <img src="{{ Storage::url($img) }}" alt="" class="h-36 w-full object-cover transition-transform duration-200 group-hover:scale-[1.02]" />
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="px-4 md:px-5 py-3 border-t border-slate-100 bg-white/50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            @auth
                @if($post->user_id === auth()->id())
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger" size="xs" class="!px-2.5 !py-1" data-confirm-delete>
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('messages.delete') }}
                        </x-ui.button>
                    </form>
                @endif
            @endauth
            @guest
                @if($isAnon)
                    <button type="button" class="text-xs text-black/70 hover:text-black underline" data-open-delete="post-{{ $post->id }}">{{ __('messages.delete') }}…</button>
                @endif
            @endguest
        </div>
        <div class="flex items-center gap-2">
            <button type="button" data-like-post="{{ $post->id }}" aria-pressed="{{ $liked ? 'true' : 'false' }}" class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-1 text-[11px] transition-colors {{ $liked ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-black/70 hover:text-black hover:border-slate-300' }}" title="{{ $liked ? __('messages.unlike') : __('messages.like') }}">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
            </button>
            @php($commentCount = isset($post->comments_count) ? $post->comments_count : $post->comments()->count())
            <a href="{{ route('posts.show', $post) }}#comments" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" title="{{ __('messages.comments.title') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m7-2a9 9 0 11-18 0 9 0 0118 0z"/></svg>
            </a>
            <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" data-share-post="{{ $post->id }}" title="{{ __('messages.posts.share') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 10-3-3m0 3v7m0-7a3 3 0 11-3-3m3 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
            </button>
            <button type="button" class="rounded-lg border border-slate-200 px-2 py-1 text-[11px] text-black/70 hover:text-black hover:border-slate-300 focus-visible:outline focus-visible:ring-2 focus-visible:ring-emerald-300" data-copy-post="{{ $post->id }}" title="{{ __('messages.posts.copy_link') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8a2 2 0 002-2V8m-6 8H8a2 2 0 01-2-2V8m6-4h6a2 2 0 012 2v6"/></svg>
            </button>
        </div>
    </div>
</x-ui.card>
</a>
