<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(Request $request): View
    {
        return $this->renderProfile(Auth::user(), $request, true);
    }

    public function show(User $user, Request $request): View
    {
        $viewer = Auth::user();

        abort_if(
            $user->isBlocked() && $viewer?->id !== $user->id && ! $viewer?->isAdmin(),
            404
        );

        return $this->renderProfile($user, $request, $viewer?->id === $user->id);
    }

    public function follow(User $user): RedirectResponse
    {
        $authUser = Auth::user();

        abort_if($authUser->id === $user->id, 403, __('app.cannot_follow_self'));
        abort_if($user->isBlocked(), 403);

        if (! $authUser->isFollowing($user)) {
            $authUser->following()->attach($user->id);
        }

        return back()->with('success', __('app.followed_user', ['name' => $user->name]));
    }

    public function unfollow(User $user): RedirectResponse
    {
        Auth::user()->following()->detach($user->id);

        return back()->with('success', __('app.unfollowed_user', ['name' => $user->name]));
    }

    public function toggleSave(Recipe $recipe): RedirectResponse
    {
        abort_if(! $recipe->is_published && ! Auth::user()->isAdmin(), 403);

        $user = Auth::user();

        if ($user->hasSaved($recipe)) {
            $user->savedRecipes()->detach($recipe->id);

            return back()->with('success', __('app.recipe_unsaved'));
        }

        $user->savedRecipes()->attach($recipe->id);

        return back()->with('success', __('app.recipe_saved'));
    }

    private function renderProfile(User $user, Request $request, bool $isOwn): View
    {
        $tab = $request->get('tab', 'recipes');

        if (! $isOwn && $tab === 'saved') {
            $tab = 'recipes';
        }

        if (! in_array($tab, ['recipes', 'saved', 'followers', 'following'], true)) {
            $tab = 'recipes';
        }

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        $recipesCountQuery = $user->recipes();
        if (! $isOwn && ! optional(Auth::user())->isAdmin()) {
            $recipesCountQuery->published();
        }
        $recipesCount = $recipesCountQuery->count();

        $recipes = null;
        $savedRecipes = null;
        $followers = null;
        $following = null;

        if ($tab === 'recipes') {
            $recipesQuery = $user->recipes()->with(['category', 'tags', 'comments'])->latest();

            if (! $isOwn && ! optional(Auth::user())->isAdmin()) {
                $recipesQuery->published();
            }

            $recipes = $recipesQuery->paginate(9)->withQueryString();
        } elseif ($tab === 'saved' && $isOwn) {
            $savedRecipes = $user->savedRecipes()
                ->with(['category', 'tags', 'comments', 'user'])
                ->published()
                ->latest('saved_recipes.created_at')
                ->paginate(9)
                ->withQueryString();
        } elseif ($tab === 'followers') {
            $followers = $user->followers()
                ->withCount('recipes')
                ->latest('follows.created_at')
                ->paginate(20)
                ->withQueryString();
        } elseif ($tab === 'following') {
            $following = $user->following()
                ->withCount('recipes')
                ->latest('follows.created_at')
                ->paginate(20)
                ->withQueryString();
        }

        $isFollowing = Auth::check()
            && Auth::id() !== $user->id
            && Auth::user()->isFollowing($user);

        return view('users.profile', compact(
            'user',
            'isOwn',
            'tab',
            'recipes',
            'savedRecipes',
            'followers',
            'following',
            'followersCount',
            'followingCount',
            'recipesCount',
            'isFollowing',
        ));
    }
}
