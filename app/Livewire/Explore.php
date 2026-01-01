<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Explore extends Component
{
    #[Url]
    public string $q = '';

    public function render(): ViewContract
    {
        $query = User::query();

        if ($this->q !== '') {
            $q = Str::lower($this->q);
            $query->whereRaw('lower(username) like ?', ['%'.$q.'%'])
                ->orWhereRaw('lower(display_name) like ?', ['%'.$q.'%']);
        }

        $users = $query->latest()->limit(30)->get();
        $topUsers = User::query()->latest()->limit(6)->get();
        $topPosts = Post::query()->with('user')->withCount(['likes', 'comments'])->latest()->limit(6)->get();
        $stats = [
            'users' => User::count(),
            'posts' => Post::count(),
            'messages' => Message::count(),
        ];

        return view('livewire/pages/explore', [
            'users' => $users,
            'topUsers' => $topUsers,
            'topPosts' => $topPosts,
            'stats' => $stats,
        ])->title(__('messages.explore').' · sar7ne')
            ->with([
                'meta_description' => __('messages.homepage_discover_title'),
                'og_type' => 'website',
                'canonical' => route('explore'),
            ]);
    }
}
