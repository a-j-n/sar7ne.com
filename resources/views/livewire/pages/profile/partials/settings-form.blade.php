<div class="space-y-8">
    <div>
        <label class="inline-flex items-center gap-3">
            <x-ui.checkbox wire:model.defer="allow_public_messages" />
            <span class="text-sm text-black">{{ __('messages.allow_public_messages') }}</span>
        </label>
    </div>

    <div>
        <label class="inline-flex items-center gap-3">
            <x-ui.checkbox wire:model.defer="reduce_motion" />
            <span class="text-sm text-black">{{ __('messages.reduce_motion') }}</span>
        </label>
        <p class="mt-1 text-xs text-black/70">{{ __('messages.reduce_motion_help') }}</p>
    </div>

    @include('livewire.pages.profile.partials.social-links')
</div>
