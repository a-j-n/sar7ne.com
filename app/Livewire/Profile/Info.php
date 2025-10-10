<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Info extends Component
{
    public function render()
    {
        /** @var Authenticatable&\App\Models\User $user */
        $user = Auth::user();

        $counts = Cache::remember("user:counts:{$user->id}", 60, function () use ($user) {
            $userCounts = $user->loadCount([
                'receivedMessages as total_messages_count',
                'publicMessages as public_messages_count',
            ]);

            return [
                'total_messages_count' => $userCounts->total_messages_count,
                'public_messages_count' => $userCounts->public_messages_count,
            ];
        });

        return view('livewire/pages/profile/info', [
            'user' => $user,
            'total_messages_count' => $counts['total_messages_count'] ?? 0,
            'public_messages_count' => $counts['public_messages_count'] ?? 0,
        ])->title(__('messages.profile').' · sar7ne')
            ->with([
                'meta_description' => $user->bio ?? __('messages.customise_world_sees_you'),
                'og_type' => 'profile',
                'canonical' => route('profile.info'),
            ]);
    }
}
