<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_UNREAD = 'unread';

    public const STATUS_READ = 'read';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sender_ip',
        'receiver_id',
        'message_text',
        'image_path',
        'status',
        'is_public',
    ];

    /**
     * Message receiver relation.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Mark this message as read.
     */
    public function markRead(): void
    {
        $this->forceFill(['status' => self::STATUS_READ])->save();
    }

    /**
     * Mark this message as unread.
     */
    public function markUnread(): void
    {
        $this->forceFill(['status' => self::STATUS_UNREAD])->save();
    }

    /**
     * Get the full URL for the message image.
     */
    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'is_public' => 'boolean',
        ];
    }
}
