<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExploreController extends Controller
{
    public function __invoke(Request $request): View
    {
        $term = Str::of($request->query('q', ''))->trim();

        $baseQuery = User::query()
            ->withCount([
                'receivedMessages as unread_messages_count' => fn ($query) => $query->where('status', Message::STATUS_UNREAD),
                'receivedMessages as total_messages_count',
            ])
            ->when(Auth::check(), fn ($query) => $query->whereKeyNot(Auth::id()));

        if ($term->isNotEmpty()) {
            $search = (clone $baseQuery)
                ->where(function ($query) use ($term) {
                    $query->where('username', 'like', '%'.$term.'%')
                        ->orWhere('display_name', 'like', '%'.$term.'%');
                })
                ->orderByDesc('total_messages_count')
                ->limit(24)
                ->get();

            $trending = collect();
            $featured = $search;
        } else {
            $trending = (clone $baseQuery)
                ->orderByDesc('total_messages_count')
                ->limit(6)
                ->get();

            $featured = (clone $baseQuery)
                ->inRandomOrder()
                ->limit(12)
                ->get();
        }

        return view('explore', [
            'searchTerm' => $term->value(),
            'featuredUsers' => $featured,
            'trendingUsers' => $trending,
        ]);
    }
}
