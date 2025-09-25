<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View as ViewContract;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PublicProfile extends Component
{
    public User $user;

    public $publicMessages;

    public function mount(User $user): void
    {
        $this->user = $user->loadCount([
            'receivedMessages as total_messages_count',
            'publicMessages as public_messages_count',
        ]);

        $this->publicMessages = $this->user->allow_public_messages
            ? $this->user->publicMessages()->select(['id', 'message_text', 'image_path', 'created_at'])->get()
            : collect();
    }

    public function render(): ViewContract
    {
        return view('livewire/pages/public/profile', [
            'user' => $this->user,
            'publicMessages' => collect($this->publicMessages),
        ])->withoutMiddleware(\Illuminate\View\Middleware\ShareErrorsFromSession::class)
            ->title('@'.$this->user->username.' · sar7ne')
            ->with([
                'meta_description' => $this->user->bio ?? __('messages.drop_anonymous_message'),
                'meta_image' => $this->user->avatarUrl(),
                'og_type' => 'profile',
                'canonical' => url()->current(),
            ]);
    }
}
