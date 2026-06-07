<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'cook_time',
        'emoji',
        'image_path',
        'user_id',
        'category_id',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'ingredients'  => 'array',   // stored as JSON, cast to PHP array automatically
            'is_published' => 'boolean',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /** Only published recipes visible to guests */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** Full-text search across title, description and ingredients */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhereJsonContains('ingredients', $term)  // MySQL JSON search
              ->orWhereHas('tags', fn($t) => $t->where('name', 'like', "%{$term}%"))
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$term}%"));
        });
    }

    // ── Computed helpers ────────────────────────────────────────────────────

    /** Average rating from users who actually cooked this recipe */
    public function cookedRatingAvg(): float
    {
        return round(
            $this->comments()->where('has_cooked', true)->avg('rating') ?? 0,
            1
        );
    }

    /** Average rating from all reviewers */
    public function allRatingAvg(): float
    {
        return round($this->comments()->avg('rating') ?? 0, 1);
    }
}
