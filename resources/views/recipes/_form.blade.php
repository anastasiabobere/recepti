{{-- Shared form partial for create & edit --}}

@if($errors->any())
  <div class="flash flash-error" style="margin-bottom:1rem">
    <ul style="margin:0;padding-left:1.2rem">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

{{-- Basic info --}}
<div class="form-card">
  <h3>{{ __('app.basic_info') }}</h3>

  <div class="form-group">
    <label class="form-label" for="title">{{ __('app.recipe_title') }} *</label>
    <input type="text" id="title" name="title" class="form-input"
           value="{{ old('title', $recipe?->title) }}"
           placeholder="{{ __('app.recipe_title_placeholder') }}" required>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="category_id">{{ __('app.choose_category') }} *</label>
      <select id="category_id" name="category_id" class="form-select" required>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}"
            {{ old('category_id', $recipe?->category_id) == $cat->id ? 'selected' : '' }}>
            {{ $cat->localized_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="cook_time">{{ __('app.cook_time') }}</label>
      <input type="text" id="cook_time" name="cook_time" class="form-input"
             value="{{ old('cook_time', $recipe?->cook_time) }}"
             placeholder="{{ __('app.cook_time_placeholder') }}">
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="image">{{ __('app.recipe_image') }}</label>

    {{-- Show current image if editing --}}
    @if($recipe?->image_path)
      <div style="margin-bottom:.75rem">
        <img src="{{ asset('storage/' . $recipe->image_path) }}"
             alt="{{ __('app.current_image_alt') }}"
             style="width:200px;height:130px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border)">
        <div style="font-size:12px;color:var(--text-faint);margin-top:4px">
          {{ __('app.current_image_note') }}
        </div>
      </div>
    @endif

    <input type="file" id="image" name="image" accept="image/jpg,image/jpeg,image/png,image/webp"
           style="display:none" onchange="previewImage(this)">
    <label for="image" class="btn btn-secondary" style="cursor:pointer;display:inline-block">
      {{ __('app.choose_image') }}
    </label>
    <span id="imageFileName" style="font-size:13px;color:var(--text-muted);margin-left:.5rem"></span>

    {{-- Preview --}}
    <div id="imagePreview" style="margin-top:.75rem;display:none">
      <img id="previewImg"
           style="width:200px;height:130px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border)">
    </div>
    <div style="font-size:12px;color:var(--text-faint);margin-top:4px">
      {{ __('app.image_hint') }}
    </div>
  </div>
</div>

{{-- Ingredients --}}
<div class="form-card">
  <h3>{{ __('app.ingredients') }}</h3>
  <div id="ingredientsList">
    @php
      $ingredients = old('ingredients', $recipe?->ingredients ?? ['']);
    @endphp
    @foreach($ingredients as $ing)
      <div class="ingredient-row">
        <input type="text" name="ingredients[]" class="form-input"
               value="{{ $ing }}" placeholder="{{ __('app.ingredient_placeholder') }}">
        <button type="button" class="btn btn-secondary btn-sm ing-remove">✕</button>
      </div>
    @endforeach
  </div>
  <button type="button" class="btn btn-secondary btn-sm" id="addIngredient" style="margin-top:.5rem">
    {{ __('app.add_ingredient') }}
  </button>
</div>

{{-- Description --}}
<div class="form-card">
  <h3>{{ __('app.description_section') }}</h3>
  <div class="form-group">
    <label class="form-label" for="description">{{ __('app.description_instruction') }} *</label>
    <textarea id="description" name="description" class="form-textarea"
              rows="8" required
              placeholder="{{ __('app.description_placeholder') }}">{{ old('description', $recipe?->description) }}</textarea>
  </div>
</div>

{{-- Tags --}}
<div class="form-card">
  <h3>{{ __('app.tags') }}</h3>
  <p class="form-hint">{{ __('app.tags_hint') }}</p>
  <div class="form-group">
    <div class="tags-input-area" id="tagsArea">
      {{-- Pills rendered by JS from hidden input --}}
      <input type="text" id="tagTyper" class="tag-type" placeholder="{{ __('app.tags_placeholder') }}">
    </div>
    {{-- The actual submitted value --}}
    <input type="hidden" id="tagsHidden" name="tags"
           value="{{ old('tags', $recipe ? $recipe->tags->pluck('name')->join(', ') : '') }}">
  </div>
</div>
