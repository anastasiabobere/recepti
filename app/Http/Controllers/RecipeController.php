<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RecipeController extends Controller
{
    // ── Public ──────────────────────────────────────────────────────────────

    /**
     * GET /  —  recipe listing with optional search & category filter
     */
    public function index(Request $request): View
    {
        $query = Recipe::published()
            ->with(['user', 'category', 'tags', 'comments']);

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($categorySlug = $request->get('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
        }

        $recipes    = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount('recipes')->get();

        return view('recipes.index', compact('recipes', 'categories', 'search', 'categorySlug'));
    }

    /**
     * GET /receptes/{recipe}  —  single recipe detail
     */
    public function show(Recipe $recipe): View
    {
        abort_if(! $recipe->is_published && ! optional(Auth::user())->isAdmin(), 403);

        $recipe->load(['user', 'category', 'tags']);

        // Comments split: cooked first, then the rest
        $cookedComments = $recipe->comments()->where('has_cooked', true)->with('user')->get();
        $otherComments  = $recipe->comments()->where('has_cooked', false)->with('user')->get();

        $userHasCommented = Auth::check() && Auth::user()->hasCommentedOn($recipe);

        return view('recipes.show', compact(
            'recipe',
            'cookedComments',
            'otherComments',
            'userHasCommented'
        ));
    }

    // ── Authenticated ────────────────────────────────────────────────────────

    /**
     * GET /receptes/create
     */
    public function create(): View
    {
        $this->requireActiveUser();
        $categories = Category::orderBy('name')->get();
        return view('recipes.create', compact('categories'));
    }

    /**
     * POST /receptes
     */
    public function store(Request $request): RedirectResponse
    {
        $this->requireActiveUser();

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'ingredients'   => 'required|array|min:1',
            'ingredients.*' => 'required|string|max:255',
            'cook_time'     => 'nullable|string|max:100',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id'   => 'required|exists:categories,id',
            'tags'          => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = Auth::user()->recipes()->create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'ingredients' => $data['ingredients'],
            'cook_time'   => $data['cook_time'] ?? null,
            'image_path'  => $imagePath,
            'category_id' => $data['category_id'],
        ]);

        $this->syncTags($recipe, $data['tags'] ?? '');

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recepte veiksmīgi pievienota!');
    }

    /**
     * PUT /receptes/{recipe}
     */
    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        Gate::authorize('update', $recipe);

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'ingredients'   => 'required|array|min:1',
            'ingredients.*' => 'required|string|max:255',
            'cook_time'     => 'nullable|string|max:100',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id'   => 'required|exists:categories,id',
            'tags'          => 'nullable|string',
        ]);

        $imagePath = $recipe->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if it was user-uploaded (not a seeder path)
            if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'ingredients' => $data['ingredients'],
            'cook_time'   => $data['cook_time'] ?? null,
            'image_path'  => $imagePath,
            'category_id' => $data['category_id'],
        ]);

        $this->syncTags($recipe, $data['tags'] ?? '');

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recepte atjaunināta!');
    }

    /**
     * GET /receptes/{recipe}/edit
     */
    public function edit(Recipe $recipe): View
    {
        Gate::authorize('update', $recipe);
        $categories = Category::orderBy('name')->get();
        $recipe->load('tags');
        return view('recipes.edit', compact('recipe', 'categories'));
    }
    
    /**
     * DELETE /receptes/{recipe}
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        Gate::authorize('delete', $recipe);
        $recipe->delete();   // soft delete
        return redirect()->route('recipes.index')
            ->with('success', 'Recepte izdzēsta.');
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function syncTags(Recipe $recipe, string $rawTags): void
    {
        $names = collect(explode(',', $rawTags))
            ->map(fn($t) => trim($t))
            ->filter()
            ->unique();

        $tagIds = $names->map(function (string $name) {
            return Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            )->id;
        });

        $recipe->tags()->sync($tagIds);
    }

    private function requireActiveUser(): void
    {
        abort_if(! Auth::check(), 401);
        abort_if(Auth::user()->isBlocked(), 403, 'Jūsu konts ir bloķēts.');
    }
}
