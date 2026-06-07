<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'emoji', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (Category $cat) {
            $cat->slug = $cat->slug ?? Str::slug($cat->name);
        });
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Return (or create) the system "uncategorized" fallback category.
     * Used when a category is deleted so recipes are never left orphaned.
     */
    public static function uncategorized(): self
    {
        return static::firstOrCreate(
            ['slug' => 'nekategorizets'],
            ['name' => 'Nekategorizēts', 'emoji' => '📋']
        );
    }

    public function isUncategorized(): bool
    {
        return $this->slug === 'nekategorizets';
    }
}
