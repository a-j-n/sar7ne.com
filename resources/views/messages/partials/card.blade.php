@php(/** @var object $message */ null)
@props([
    'canToggle' => true,
    'canModifyStatus' => true,
    'canDelete' => true,
])
@php($isUnread = ($message->status ?? null) === 'unread')
@php($hasImage = !empty($message->image_path ?? null))

<x-ui.card class="group hover:border-brand-orange/40 hover:shadow-lg transition-all duration-200 {{ $isUnread ? 'border-brand-orange/30 bg-gradient-to-r from-brand-orange/5 to-transparent' : '' }}">
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1 space-y-3">
            <!-- Message Content -->
            <div class="relative">
                @if($isUnread)
                    <div class="absolute -left-4 top-0 w-1 h-full bg-gradient-orange-pink rounded-full"></div>
                @endif
                <p class="whitespace-pre-line text-sm leading-relaxed text-slate-900 dark:text-slate-100 {{ $isUnread ? 'font-medium' : '' }}">{{ $message->message_text }}</p>

                @if($hasImage)
                    @php($src = Storage::url($message->image_path))
                    <div class="mt-3">
                        <img src="{{ $src }}" data-gallery-group="inbox" data-gallery-src="{{ $src }}" alt="Message image" class="max-w-full h-48 object-cover rounded-lg cursor-zoom-in hover:opacity-80 transition-opacity">
                    </div>
                @endif
            </div>

            <!-- Meta (time) -->
            <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('messages.received_at') }} {{ $message->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        
    </div>

    <!-- Footer: Public toggle/badge + actions -->
    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700/60 flex items-center justify-between">
        <div>
            @if($canToggle)
                <button type="button" wire:click="togglePublic({{ $message->id }})" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs rounded-lg border transition {{ $message->is_public ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-800' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ $message->is_public ? __('messages.make_private') : __('messages.make_public') }}</span>
                </button>
            @else
                @if($message->is_public)
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ __('messages.public') }}
                    </span>
                @endif
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($canModifyStatus)
                @if($isUnread)
                    <x-ui.button size="sm" variant="outline" wire:click="markRead({{ $message->id }})" class="text-xs">{{ __('messages.mark_as_read') }}</x-ui.button>
                @else
                    <x-ui.button size="sm" variant="outline" wire:click="markUnread({{ $message->id }})" class="text-xs">{{ __('messages.mark_as_unread') }}</x-ui.button>
                @endif
            @endif
            @if($canDelete)
                <x-ui.button variant="danger" size="sm" wire:click="destroy({{ $message->id }})" class="text-xs" data-confirm-delete>
                    {{ __('messages.delete') }}
                </x-ui.button>
            @endif
        </div>
    </div>
</x-ui.card>
