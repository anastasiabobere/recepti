<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ── Dashboard panels ────────────────────────────────────────────────────

    public function index(): View
    {
        return view('admin.index', [
            'recipesCount'    => Recipe::count(),
            'usersCount'      => User::count(),
            'commentsCount'   => Comment::count(),
            'categoriesCount' => Category::count(),
        ]);
    }

    public function recipes(): View
    {
        $recipes = Recipe::with(['user', 'category'])->latest()->paginate(20);
        return view('admin.recipes', compact('recipes'));
    }

    public function users(): View
    {
        $users = User::withCount('recipes')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function comments(): View
    {
        $comments = Comment::with(['user', 'recipe'])->latest()->paginate(20);
        return view('admin.comments', compact('comments'));
    }

    public function categories(): View
    {
        $categories = Category::withCount('recipes')->get()
            ->sortBy(fn ($cat) => $cat->localized_name)
            ->values();
        return view('admin.categories', compact('categories'));
    }

    // ── User management ──────────────────────────────────────────────────────

    public function toggleBlock(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403, __('app.cannot_block_admin'));
        $user->update(['is_blocked' => ! $user->is_blocked]);
        $msg = $user->is_blocked
            ? __('app.user_blocked', ['name' => $user->name])
            : __('app.user_unblocked', ['name' => $user->name]);
        return back()->with('success', $msg);
    }

    // ── Recipe management ────────────────────────────────────────────────────

    public function deleteRecipe(Recipe $recipe): RedirectResponse
    {
        $recipe->forceDelete();
        return back()->with('success', __('app.recipe_deleted_admin'));
    }

    public function togglePublish(Recipe $recipe): RedirectResponse
    {
        $recipe->update(['is_published' => ! $recipe->is_published]);
        return back()->with('success', $recipe->is_published ? __('app.recipe_published') : __('app.recipe_hidden'));
    }

    // ── Comment moderation ───────────────────────────────────────────────────

    public function deleteComment(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('success', __('app.comment_deleted'));
    }

    // ── Category management ──────────────────────────────────────────────────

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100|unique:categories,name',
        ]);
        Category::create([
            'name'  => $data['name'],
        ]);
        return back()->with('success', __('app.category_added', ['name' => $data['name']]));
    }

    /**
     * Delete a category.
     *
     * Business rule (professor's feedback):
     *   — If the category has recipes, they are moved to "Nekategorizēts".
     *   — The "Nekategorizēts" system category itself cannot be deleted.
     *   — An empty category can be deleted freely.
     */
    public function deleteCategory(Category $category): RedirectResponse
    {
        if ($category->isUncategorized()) {
            return back()->with('error', __('app.cannot_delete_uncategorized'));
        }

        $recipeCount = $category->recipes()->count();

        if ($recipeCount > 0) {
            $fallback = Category::uncategorized();
            // Move all recipes to the fallback category before deletion
            $category->recipes()->update(['category_id' => $fallback->id]);
        }

        $category->delete();

        $msg = $recipeCount > 0
            ? __('app.category_deleted_with_recipes', ['count' => $recipeCount])
            : __('app.category_deleted');

        return back()->with('success', $msg);
    }
}
