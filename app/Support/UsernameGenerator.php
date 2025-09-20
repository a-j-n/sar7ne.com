<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class UsernameGenerator
{
    /**
     * Generate a unique username from the provided seed.
     */
    public static function generate(string $seed, ?int $ignoreUserId = null): string
    {
        $base = static::normalize($seed);

        if ($base === '') {
            $base = 'user';
        }

        $username = $base;
        $suffix = 0;

        while (static::exists($username, $ignoreUserId)) {
            $suffix++;
            $trimmedBase = Str::limit($base, max(1, 20 - strlen((string) $suffix)), '');
            $username = $trimmedBase.$suffix;
        }

        return $username;
    }

    /**
     * Normalize a raw username string.
     */
    public static function normalize(string $username): string
    {
        return (string) Str::of($username)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/i', '_')
            ->replaceMatches('/_{2,}/', '_')
            ->trim('_')
            ->substr(0, 20);
    }

    /**
     * Determine if the username exists for another user.
     */
    protected static function exists(string $username, ?int $ignoreUserId): bool
    {
        return User::query()
            ->where('username', $username)
            ->when($ignoreUserId, fn ($query) => $query->where('id', '!=', $ignoreUserId))
            ->exists();
    }
}
