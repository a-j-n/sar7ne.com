<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function show(Post $post)
    {
        // Count unique view per session
        $viewed = session()->get('viewed_posts', []);
        if (! in_array($post->id, $viewed, true)) {
            $post->increment('views');
            $viewed[] = $post->id;
            session()->put('viewed_posts', $viewed);
        }

        return view('posts.show', compact('post'));
    }

    public function trackShare(Request $request, Post $post)
    {
        $post->increment('shares');

        return response()->noContent();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:500'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'anonymous' => ['sometimes', 'boolean'],
        ]);

        if (empty($validated['content']) && empty($validated['images'])) {
            return back()->withErrors(['content' => __('At least text or one image is required.')]);
        }

        $paths = [];
        if (! empty($validated['images'])) {
            foreach ($validated['images'] as $file) {
                $paths[] = $file->store('posts', ['disk' => config('filesystems.default', 'public')]);
            }
        }

        $user = Auth::user();
        $isAnonymous = (bool) ($validated['anonymous'] ?? false);

        $deleteTokenPlain = null;
        $deleteTokenHash = null;
        $anonKeyHash = null;

        if (! $user) {
            $deleteTokenPlain = Str::random(32);
            $deleteTokenHash = Hash::make($deleteTokenPlain);
            $anonKeyHash = Hash::make(Str::uuid()->toString());
        }

        $post = Post::create([
            'user_id' => $user?->id,
            'is_anonymous' => $user ? $isAnonymous : true,
            'content' => $validated['content'] ?? null,
            'images' => $paths,
            'delete_token_hash' => $deleteTokenHash,
            'anon_key_hash' => $anonKeyHash,
        ]);

        // Return token to anonymous creator
        if (! $user && $request->wantsJson()) {
            return response()->json([
                'id' => $post->id,
                'delete_token' => $deleteTokenPlain,
            ], 201);
        }

        // If anon via form, surface token in session flash so user can save it
        if (! $user && $deleteTokenPlain) {
            return redirect()->route('posts')->with('status', __('Post created. Save this delete token: :token', ['token' => $deleteTokenPlain]));
        }

        return redirect()->route('posts')->with('status', __('Post created.'));
    }

    public function destroy(Request $request, Post $post)
    {
        $user = Auth::user();
        $token = (string) $request->input('delete_token', '');

        $authorized = false;
        if ($user && $post->user_id === $user->id) {
            $authorized = true;
        } elseif (! empty($token) && $post->delete_token_hash && Hash::check($token, $post->delete_token_hash)) {
            $authorized = true;
        }

        if (! $authorized) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();

        return $request->wantsJson()
            ? response()->json(['status' => 'deleted'])
            : back()->with('status', __('Post deleted.'));
    }

    public function toggleLike(Request $request, Post $post)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['requires_auth' => true, 'message' => __('messages.login_to_like')], 401);
        }

        $liked = $post->likes()->where('user_id', $user->id)->exists();
        if ($liked) {
            $post->likes()->detach($user->id);
            $state = false;
        } else {
            $post->likes()->attach($user->id);
            $state = true;
        }

        $count = $post->likes()->count();

        return response()->json([
            'liked' => $state,
            'count' => $count,
        ]);
    }
}
