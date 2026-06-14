<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_blocked'        => 'boolean',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function savedRecipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'saved_recipes')
            ->withTimestamps();
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function hasCommentedOn(Recipe $recipe): bool
    {
        return $this->comments()->where('recipe_id', $recipe->id)->exists();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function hasSaved(Recipe $recipe): bool
    {
        return $this->savedRecipes()->where('recipe_id', $recipe->id)->exists();
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn ($w) => strtoupper($w[0] ?? ''))
            ->take(2)
            ->join('');
    }
}
