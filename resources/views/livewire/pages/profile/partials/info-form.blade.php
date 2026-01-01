<div class="space-y-8">
    @php(/** @var \App\Models\User $user */ null)
    <form wire:submit.prevent="saveBasic" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.display_name') }}</label>
                <x-ui.input type="text" wire:model.live.debounce.300ms="display_name" class="mt-1" placeholder="{{ __('messages.display_name_placeholder') }}" />
                <p class="mt-1 text-xs text-slate-500">{{ __('messages.display_name_label') }}</p>
                @error('display_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.username') }}</label>
                <x-ui.input type="text" wire:model.live.debounce.300ms="username" class="mt-1" placeholder="{{ __('messages.username_placeholder') }}" />
                <p class="mt-1 text-xs text-slate-500">{{ __('messages.username_label') }}</p>
                @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.bio') }}</label>
            <x-ui.textarea wire:model.live.debounce.300ms="bio" rows="4" class="mt-1" maxlength="280" placeholder="{{ __('messages.bio_placeholder') }}"></x-ui.textarea>
            <div class="mt-1 flex items-center justify-between text-xs text-slate-500">
                <span>{{ __('messages.bio_help', ['max' => 280]) }}</span>
                <span>{{ strlen($bio ?? '') }}/280</span>
            </div>
            @error('bio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-medium text-black uppercase tracking-wide">{{ __('messages.avatar_label') }}</label>
            <input type="file" wire:model="avatar" accept="image/*" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            <p class="mt-1 text-xs text-slate-500">{{ __('messages.avatar_help', ['size' => '5MB']) }}</p>
            @error('avatar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            @if($avatar)
                <div class="mt-3 inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 shadow-sm">
                    <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 011.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>{{ __('messages.avatar_label') }} ready to upload</span>
                </div>
            @endif
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
        <div class="pt-2 flex justify-end">
            <x-ui.button type="submit" variant="primary">
                {{ __('messages.save') ?? 'Save' }}
            </x-ui.button>
        </div>
    </form>
</div>
