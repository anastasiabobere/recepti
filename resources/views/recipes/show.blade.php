@extends('layouts.app')
@section('title', $recipe->title)

@section('content')
<div class="container" style="padding-top:1.5rem">
  <a href="{{ route('recipes.index') }}" class="back-btn">{{ __('app.back_to_recipes') }}</a>

  <div class="recipe-detail">
    {{-- Header --}}
   <div class="recipe-detail-img">
  @if($recipe->image_path)
    @php
      $imgUrl = str_starts_with($recipe->image_path, 'images/')
          ? asset($recipe->image_path)
          : asset('storage/' . $recipe->image_path);
    @endphp
    <img src="{{ $imgUrl }}"
         alt="{{ $recipe->title }}"
         style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg)">
  @else
    <img src="{{ asset('images/placeholder.jpg') }}"
         alt="{{ __('app.no_image') }}"
         style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg)">
  @endif
</div>

    <div class="recipe-detail-header">
      <div>
        <div class="recipe-cat-label">{{ $recipe->category->localized_name }}</div>
        <h1 class="recipe-detail-title" id="recipeTitle">{{ $recipe->title }}</h1>
        <div class="recipe-detail-meta">
          <span>{{ $recipe->user->name }}</span>
          @if($recipe->cook_time)
            <span>{{ $recipe->cook_time }}</span>
          @endif
          <span>{{ $recipe->created_at->format('d.m.Y') }}</span>
        </div>
        @if($showTranslate)
          <button class="btn btn-secondary btn-sm" id="translateBtn"
                  data-target="{{ $uiLocale }}"
                  data-label-translate="{{ $uiLocale === 'lv' ? __('app.translate_to_lv') : __('app.translate_to_en') }}"
                  data-label-revert="{{ __('app.show_original') }}"
                  data-status-translating="{{ __('app.translating') }}"
                  data-status-failed="{{ __('app.translation_failed') }}"
                  onclick="translateRecipe({{ $recipe->id }})" style="margin-top:.5rem">
            {{ $uiLocale === 'lv' ? __('app.translate_to_lv') : __('app.translate_to_en') }}
          </button>
          <span id="translateStatus" style="font-size:13px;color:var(--text-faint);margin-left:.5rem"></span>
        @endif
        </div>
      </div>
      <div style="display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap">
        @auth
          <form method="POST" action="{{ route('recipes.save', $recipe) }}">
            @csrf
            <button class="btn {{ $isSaved ? 'btn-secondary' : 'btn-primary' }} btn-sm">
              {{ $isSaved ? __('app.unsave_recipe') : __('app.save_recipe_action') }}
            </button>
          </form>
        @endauth

        @can('update', $recipe)
          <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-secondary btn-sm">{{ __('app.edit') }}</a>
          <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                onsubmit="return confirm(@json(__('app.confirm_delete_recipe')))">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
          </form>
        @endcan
      </div>
    </div>

    {{-- Tags --}}
    @if($recipe->tags->count())
      <div class="recipe-tags">
        @foreach($recipe->tags as $tag)
          <a href="{{ route('recipes.index', ['search' => $tag->name]) }}" class="tag-pill">{{ $tag->name }}</a>
        @endforeach
      </div>
    @endif

    {{-- Ingredients --}}
    <div class="detail-section">
      <h3>{{ __('app.ingredients') }}</h3>
      <ul class="ingredients-list" id="ingredientsList">
        @foreach($recipe->ingredients as $ingredient)
          <li><span class="ing-dot"></span><span class="ing-text">{{ $ingredient }}</span></li>
        @endforeach
      </ul>
    </div>

    {{-- Description --}}
    <div class="detail-section">
      <h3>{{ __('app.preparation') }}</h3>
      <div class="recipe-description" id="recipeDescription">{{ $recipe->description }}</div>
    </div>

    <hr class="divider">

    {{-- ── Ratings & Comments ── --}}
    <div class="ratings-section">
      <h2>{{ __('app.ratings_comments') }}</h2>

      {{-- Rating summary --}}
      @php
        $allComments   = $cookedComments->concat($otherComments);
        $cookedAvg     = $cookedComments->avg('rating');
        $allAvg        = $allComments->avg('rating');
        $totalCount    = $allComments->count();
      @endphp

      @if($totalCount)
        <div class="rating-summary">
          <div class="rating-big">
            <div class="rating-num">{{ number_format($allAvg, 1) }}</div>
            <div class="rating-stars">{{ str_repeat('★', round($allAvg)) }}{{ str_repeat('☆', 5 - round($allAvg)) }}</div>
            <div class="rating-count">{{ trans_choice('app.rating_count', $totalCount, ['count' => $totalCount]) }}</div>
            @if($cookedComments->count())
              <div class="cooked-note">{{ trans_choice('app.cooked_count_short', $cookedComments->count(), ['count' => $cookedComments->count()]) }}</div>
            @endif
          </div>
          <div class="rating-bars">
            @foreach([5,4,3,2,1] as $star)
              @php $cnt = $allComments->where('rating', $star)->count(); $pct = $totalCount ? round($cnt / $totalCount * 100) : 0; @endphp
              <div class="rating-bar-row">
                <span>{{ $star }}</span>
                <span class="star-icon">★</span>
                <div class="rating-bar-track"><div class="rating-bar-fill" style="width:{{ $pct }}%"></div></div>
                <span>{{ $cnt }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Info about the split --}}
      <div class="info-box" style="margin-bottom:1.25rem">
        {!! __('app.split_explanation_html') !!}
      </div>

      {{-- Tab buttons --}}
      <div class="rating-tabs" role="tablist">
        <button class="rating-tab active" onclick="switchTab('cooked', this)">
          {{ __('app.tab_cooked_count', ['count' => $cookedComments->count()]) }}
        </button>
        <button class="rating-tab" onclick="switchTab('tasted', this)">
          {{ __('app.tab_tasted_count', ['count' => $otherComments->count()]) }}
        </button>
        <button class="rating-tab" onclick="switchTab('all', this)">
          {{ __('app.tab_all_count', ['count' => $totalCount]) }}
        </button>
      </div>

      {{-- Cooked comments (shown first by default) --}}
      <div id="tab-cooked">
        @forelse($cookedComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">{{ __('app.no_cooked_comments') }}</p>
        @endforelse
      </div>

      <div id="tab-tasted" style="display:none">
        @forelse($otherComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">{{ __('app.no_tasted_comments') }}</p>
        @endforelse
      </div>

      <div id="tab-all" style="display:none">
        @forelse($allComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">{{ __('app.no_comments') }}</p>
        @endforelse
      </div>

      {{-- Add comment form --}}
      @auth
        @if(! $userHasCommented)
          @include('components.comment-form', ['recipe' => $recipe])
        @else
          <div class="info-box">{{ __('app.already_rated') }}</div>
        @endif
      @else
        <div class="add-comment-box" style="text-align:center;padding:2rem">
          <p style="color:var(--text-muted);margin-bottom:1rem">{{ __('app.login_to_comment') }}</p>
          <a href="{{ route('login') }}" class="btn btn-primary">{{ __('app.login') }}</a>
        </div>
      @endauth
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab, btn) {
  ['cooked','tasted','all'].forEach(t => {
    document.getElementById('tab-' + t).style.display = t === tab ? '' : 'none';
  });
  document.querySelectorAll('.rating-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// Star picker
document.querySelectorAll('.star-pick').forEach(star => {
  star.addEventListener('click', () => {
    const val = +star.dataset.v;
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= val);
    });
  });
  star.addEventListener('mouseover', () => {
    const val = +star.dataset.v;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= val);
    });
  });
  star.addEventListener('mouseout', () => {
    const current = +document.getElementById('ratingInput').value;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= current);
    });
  });
});

// Cook answer toggle
function setCookAnswer(val) {
  document.getElementById('hasCookedInput').value = val ? '1' : '0';
  document.getElementById('cookYes').classList.toggle('yes', val);
  document.getElementById('cookNo').classList.toggle('no', !val);
  document.getElementById('cookYes').classList.toggle('no', false);
  document.getElementById('cookNo').classList.toggle('yes', false);
  if (val) {
    document.getElementById('cookYes').className = 'cook-btn yes';
    document.getElementById('cookNo').className = 'cook-btn';
  } else {
    document.getElementById('cookYes').className = 'cook-btn';
    document.getElementById('cookNo').className = 'cook-btn no';
  }
}
let isTranslated = false;
let originalContent = null;

async function translateRecipe(recipeId) {
  const btn = document.getElementById('translateBtn');
  const status = document.getElementById('translateStatus');
  const target = btn.dataset.target;

  if (isTranslated) {
    document.getElementById('recipeTitle').textContent = originalContent.title;
    document.getElementById('recipeDescription').textContent = originalContent.description;
    document.querySelectorAll('#ingredientsList .ing-text').forEach((el, i) => {
      el.textContent = originalContent.ingredients[i];
    });
    btn.textContent = btn.dataset.labelTranslate;
    isTranslated = false;
    status.textContent = '';
    return;
  }

  status.textContent = btn.dataset.statusTranslating;
  btn.disabled = true;

  try {
    const response = await fetch(`/receptes/${recipeId}/translate?target=${target}`);
    if (!response.ok) throw new Error('Translation failed');
    const data = await response.json();

    originalContent = {
      title: document.getElementById('recipeTitle').textContent,
      description: document.getElementById('recipeDescription').textContent,
      ingredients: Array.from(document.querySelectorAll('#ingredientsList .ing-text')).map(el => el.textContent)
    };

    document.getElementById('recipeTitle').textContent = data.title;
    document.getElementById('recipeDescription').textContent = data.description;
    document.querySelectorAll('#ingredientsList .ing-text').forEach((el, i) => {
      if (data.ingredients[i]) el.textContent = data.ingredients[i];
    });

    btn.textContent = btn.dataset.labelRevert;
    isTranslated = true;
    status.textContent = '';
  } catch (err) {
    status.textContent = btn.dataset.statusFailed;
  } finally {
    btn.disabled = false;
  }
}
</script>
@endpush
