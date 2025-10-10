<div class="space-y-6 text-black">
    @include('livewire.pages.profile.partials.header', ['user' => $user])

    <x-ui.card padding="p-0">
        <div class="p-4 md:p-8 space-y-8">
            @include('livewire.pages.profile.partials.settings-form')
        </div>
    </x-ui.card>
</div>
