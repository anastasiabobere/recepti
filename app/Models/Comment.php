<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'recipe_id',
        'user_id',
        'rating',
        'content',
        'has_cooked',
    ];

    protected function casts(): array
    {
        return [
            'has_cooked' => 'boolean',
            'rating'     => 'integer',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
