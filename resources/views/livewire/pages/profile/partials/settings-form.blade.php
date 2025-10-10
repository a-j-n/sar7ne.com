<div class="space-y-8">
    <div>
        <label class="inline-flex items-center gap-3">
            <input type="checkbox" wire:model.defer="allow_public_messages" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/70 text-emerald-500 focus:ring-emerald-400" />
            <span class="text-sm text-black">{{ __('messages.allow_public_messages') }}</span>
        </label>
    </div>

    @include('livewire.pages.profile.partials.social-links')
</div>
