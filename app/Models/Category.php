<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
    ];

    protected $casts = [
        'flagged' => 'boolean',
    ];

    public function adds(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function getAllParentNames()
    {
        $parents = collect([]);
        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent->name);
            $parent = $parent->parent;
        }

        // Reverse the collection to get names from the top level parent down to the immediate parent
        return $parents->reverse()->implode(' > ');
    }

    public function getAllChildrenIds()
    {
        // This loads all descendants for the current model instance
        $this->load('children');

        $ids = [];

        // A recursive function to traverse the tree and collect IDs
        $traverse = function ($categories) use (&$ids, &$traverse) {
            foreach ($categories as $category) {
                $ids[] = $category->id;
                if ($category->children->count()) {
                    $traverse($category->children);
                }
            }
        };

        $traverse($this->children);

        return $ids;
    }
}

