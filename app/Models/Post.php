<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'tags',
        'risk_score',
        'risk_level',
        'archived_at',
        'role',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function scopeWithFilters(Builder $query, array $filters = [])
    {
        $query->when($filters['tag'] ?? null, function ($q, $tag) {
            $q->whereLike('tags', '%' . $tag . '%');
        });

        $query->when($filters['user_id'] ?? null, function ($q, $tag) {
            $q->where('user_id', $tag);
        });

        $direction = $filters['sort'] ?? 'desc';
        $query->orderBy('created_at', $direction);

        return $query;
    }
}
