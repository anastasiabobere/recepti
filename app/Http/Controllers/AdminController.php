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
        $categories = Category::withCount('recipes')->orderBy('name')->get();
        return view('admin.categories', compact('categories'));
    }

    // ── User management ──────────────────────────────────────────────────────

    public function toggleBlock(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403, 'Nevar bloķēt administratoru.');
        $user->update(['is_blocked' => ! $user->is_blocked]);
        $msg = $user->is_blocked ? "{$user->name} bloķēts." : "{$user->name} atbloķēts.";
        return back()->with('success', $msg);
    }

    // ── Recipe management ────────────────────────────────────────────────────

    public function deleteRecipe(Recipe $recipe): RedirectResponse
    {
        $recipe->forceDelete();
        return back()->with('success', 'Recepte pilnībā izdzēsta.');
    }

    public function togglePublish(Recipe $recipe): RedirectResponse
    {
        $recipe->update(['is_published' => ! $recipe->is_published]);
        return back()->with('success', $recipe->is_published ? 'Recepte publicēta.' : 'Recepte paslēpta.');
    }

    // ── Comment moderation ───────────────────────────────────────────────────

    public function deleteComment(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('success', 'Komentārs izdzēsts.');
    }

    // ── Category management ──────────────────────────────────────────────────

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100|unique:categories,name',
            'emoji' => 'nullable|string|max:5',
        ]);
        Category::create([
            'name'  => $data['name'],
            'emoji' => $data['emoji'] ?? '🍽️',
        ]);
        return back()->with('success', "Kategorija \"{$data['name']}\" pievienota!");
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
            return back()->with('error', 'Sistēmas kategoriju "Nekategorizēts" nevar dzēst.');
        }

        $recipeCount = $category->recipes()->count();

        if ($recipeCount > 0) {
            $fallback = Category::uncategorized();
            // Move all recipes to the fallback category before deletion
            $category->recipes()->update(['category_id' => $fallback->id]);
        }

        $category->delete();

        $msg = $recipeCount > 0
            ? "Kategorija izdzēsta. {$recipeCount} recepte(-s) pārvietotas uz \"Nekategorizēts\"."
            : 'Kategorija izdzēsta.';

        return back()->with('success', $msg);
    }
}
