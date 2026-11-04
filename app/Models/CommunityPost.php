<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'caption',
        'status',
        'moderation_note',
        'moderated_by',
        'moderated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'moderated_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (CommunityPost $post): void {
            Storage::disk('public')->delete($post->image);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function approve(User $moderator): void
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function reject(User $moderator): void
    {
        $this->update([
            'status' => 'rejected',
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
            'published_at' => null,
        ]);
    }
}
