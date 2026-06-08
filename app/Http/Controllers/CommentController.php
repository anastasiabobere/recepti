<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * POST /receptes/{recipe}/comments
     * Store a new comment + rating for a recipe.
     * Enforces: one comment per user per recipe.
     */
    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        abort_if(! Auth::check(), 401);
        abort_if(Auth::user()->isBlocked(), 403, 'Jūsu konts ir bloķēts.');

        // Prevent double-commenting
        if (Auth::user()->hasCommentedOn($recipe)) {
            return back()->with('error', 'Jūs jau esat atstājis vērtējumu šai receptei.');
        }

        $data = $request->validate([
            'rating'     => 'required|integer|min:1|max:5',
            'content'    => 'required|string|max:2000',
            'has_cooked' => 'required|boolean',
        ]);

        $recipe->comments()->create([
            'user_id'    => Auth::id(),
            'rating'     => $data['rating'],
            'content'    => $data['content'],
            'has_cooked' => $data['has_cooked'],
        ]);

        return back()->with('success', 'Vērtējums pievienots! Paldies!');
    }

    /**
     * DELETE /comments/{comment}
     * Only admin or the comment owner can delete.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $user = Auth::user();
        abort_if(
            ! $user || (! $user->isAdmin() && $comment->user_id !== $user->id),
            403
        );

        $recipeId = $comment->recipe_id;
        $comment->delete();

        return back()->with('success', 'Komentārs izdzēsts.');
    }
}
