<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'description', 'published_year', 'category_id'];

    protected $casts = ['published_year' => 'integer'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeOfCategory(Builder $query, $categoryId): Builder
    {
        return $query->when($categoryId, fn ($q) => $q->where('category_id', $categoryId));
    }
}
