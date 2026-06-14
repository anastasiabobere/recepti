<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SavedRecipeController extends Controller
{
    public function toggle(Recipe $recipe): RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasSaved($recipe)) {
            $user->savedRecipes()->detach($recipe->id);
            $msg = __('app.recipe_unsaved');
        } else {
            $user->savedRecipes()->attach($recipe->id);
            $msg = __('app.recipe_saved');
        }

        return back()->with('success', $msg);
    }
}