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
  <h3>Pamatinformācija</h3>

  <div class="form-group">
    <label class="form-label" for="title">Receptes nosaukums *</label>
    <input type="text" id="title" name="title" class="form-input"
           value="{{ old('title', $recipe?->title) }}"
           placeholder="piem. Mājas ābolu pīrāgs ar kanēli" required>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="category_id">Kategorija *</label>
      <select id="category_id" name="category_id" class="form-select" required>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}"
            {{ old('category_id', $recipe?->category_id) == $cat->id ? 'selected' : '' }}>
            {{ $cat->emoji }} {{ $cat->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="cook_time">Gatavošanas laiks</label>
      <input type="text" id="cook_time" name="cook_time" class="form-input"
             value="{{ old('cook_time', $recipe?->cook_time) }}"
             placeholder="piem. 45 min">
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="emoji">Attēla emocijzīme</label>
    <input type="text" id="emoji" name="emoji" class="form-input"
           value="{{ old('emoji', $recipe?->emoji ?? '🍽️') }}"
           maxlength="5" style="width:80px" placeholder="🥧">
  </div>
</div>

{{-- Ingredients --}}
<div class="form-card">
  <h3>Sastāvdaļas</h3>
  <div id="ingredientsList">
    @php
      $ingredients = old('ingredients', $recipe?->ingredients ?? ['']);
    @endphp
    @foreach($ingredients as $ing)
      <div class="ingredient-row">
        <input type="text" name="ingredients[]" class="form-input"
               value="{{ $ing }}" placeholder="piem. 200g miltu">
        <button type="button" class="btn btn-secondary btn-sm ing-remove">✕</button>
      </div>
    @endforeach
  </div>
  <button type="button" class="btn btn-secondary btn-sm" id="addIngredient" style="margin-top:.5rem">
    + Pievienot sastāvdaļu
  </button>
</div>

{{-- Description --}}
<div class="form-card">
  <h3>Apraksts un pagatavošana</h3>
  <div class="form-group">
    <label class="form-label" for="description">Receptes apraksts / instrukcija *</label>
    <textarea id="description" name="description" class="form-textarea"
              rows="8" required
              placeholder="Apraksti soļus soli pa solim...">{{ old('description', $recipe?->description) }}</textarea>
  </div>
</div>

{{-- Tags --}}
<div class="form-card">
  <h3>Atslēgas vārdi</h3>
  <p class="form-hint">Ievadi atslēgvārdus atdalītus ar komatu.</p>
  <div class="form-group">
    <div class="tags-input-area" id="tagsArea">
      {{-- Pills rendered by JS from hidden input --}}
      <input type="text" id="tagTyper" class="tag-type" placeholder="piem. vegānisks, ātri...">
    </div>
    {{-- The actual submitted value --}}
    <input type="hidden" id="tagsHidden" name="tags"
           value="{{ old('tags', $recipe ? $recipe->tags->pluck('name')->join(', ') : '') }}">
  </div>
</div>
