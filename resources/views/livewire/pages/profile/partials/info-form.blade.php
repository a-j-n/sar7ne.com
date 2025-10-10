<div class="space-y-8">
    @php(/** @var \App\Models\User $user */ null)
    <form wire:submit.prevent="$refresh" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.display_name') }}</label>
                <input type="text" wire:model.live.debounce.300ms="display_name" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="{{ __('messages.display_name_placeholder') }}" />
                @error('display_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.username') }}</label>
                <input type="text" wire:model.live.debounce.300ms="username" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="{{ __('messages.username_placeholder') }}" />
                @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.bio') }}</label>
            <textarea wire:model.live.debounce.300ms="bio" rows="3" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="{{ __('messages.bio_placeholder') }}"></textarea>
            @error('bio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.gender') }}</label>
            <select wire:model.defer="gender" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
                <option value="">—</option>
                <option value="male">{{ __('messages.gender_male') }}</option>
                <option value="female">{{ __('messages.gender_female') }}</option>
                <option value="non-binary">{{ __('messages.gender_non_binary') }}</option>
                <option value="other">{{ __('messages.gender_other') }}</option>
                <option value="prefer_not_to_say">{{ __('messages.gender_prefer_not') }}</option>
            </select>
        </div>
    </form>
</div>
