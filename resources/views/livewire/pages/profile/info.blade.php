@guest
    <x-auth.login-required 
        :title="__('messages.sign_in_to_view_profile') ?? 'Sign in to view your profile'"
        :message="__('messages.login_required_profile') ?? 'Profile details are private to you. Please sign in to manage your information.'"
        illustration="/illustrations/lock-profile.svg"
    />
@else
<div class="space-y-6 text-black">
    @include('livewire.pages.profile.partials.header', ['user' => $user])

    <x-ui.card padding="p-0">
        <div class="p-4 md:p-8">
            @include('livewire.pages.profile.partials.info-form')
        </div>
    </x-ui.card>
</div>
@endguest
