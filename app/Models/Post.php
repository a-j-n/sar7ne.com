<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'is_anonymous', 'content', 'images', 'delete_token_hash', 'anon_key_hash',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'images' => 'array',
            'views' => 'integer',
            'shares' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
