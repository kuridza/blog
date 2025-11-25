<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'price',
        'is_new',
        'image',
        'contact_phone',
        'location',
        'category_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithFilters(Builder $query, array $filters = [])
    {
        $query->when($filters['category_id'] ?? null, function ($q, $categoryId) {
            $category = Category::find($categoryId);
            $q->whereIn('category_id', $category->getAllChildrenIds() + [$categoryId]);
        });

        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->whereLike('content', '%' . $search . '%');
            $q->orWhereLike('title', '%' . $search . '%');
        });

        $query->when($filters['price_from'] ?? null, function ($q, $priceFrom) {
            $q->where('price', '>=', (int) $priceFrom);
        });

        $query->when($filters['price_to'] ?? null, function ($q, $priceTo) {
            $q->where('price', '<=', (int) $priceTo);
        });

        $query->when($filters['location'] ?? null, function ($q, $location) {
            $q->where('location', $location);
        });

        if (! isset($filters['sort'])) {
            $query->orderBy('created_at', 'desc');
        }

        if (isset($filters['sort']) && $filters['sort']) {
            $query->orderBy('price', $filters['sort']);
        }

        return $query;
    }
}
