<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'display_name',
        'avatar_url',
        'bio',
        'provider_id',
        'provider_type',
        'last_login_at',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Casts for native PHP types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Messages received by this user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id')->latest();
    }

    /**
     * Derive the user's initials for avatar placeholders.
     */
    public function initials(): string
    {
        $source = $this->display_name ?: $this->username;

        return Str::of($source)
            ->replaceMatches('/[^A-Za-z0-9 ]+/', ' ')
            ->squish()
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Retrieve the avatar URL regardless of storage source.
     */
    public function avatarUrl(): string
    {
        if (! $this->avatar_url) {
            return 'https://ui-avatars.com/api/?name='.urlencode($this->initials()).'&background=0D1117&color=ffffff';
        }

        if (Str::startsWith($this->avatar_url, ['http://', 'https://'])) {
            return $this->avatar_url;
        }

        $disk = config('filesystems.default', 'public');

        return Storage::disk($disk)->url($this->avatar_url);
    }

    /**
     * Build the public subdomain for this user if configured.
     */
    public function subdomainUrl(): ?string
    {
        $root = config('app.profile_domain_root');

        if (! $root) {
            return null;
        }

        return 'https://'.$this->username.'.'.$root;
    }
}
