@php
  $cookedComments = $recipe->comments->where('has_cooked', true);
  $allRated       = $recipe->comments->where('rating', '>', 0);
  $cookedAvg      = (float) $cookedComments->avg('rating');
  $allAvg         = (float) $allRated->avg('rating');

  $cookedFull  = (int) round($cookedAvg);
  $cookedStars = str_repeat('★', $cookedFull) . str_repeat('☆', 5 - $cookedFull);

  $allFull  = (int) round($allAvg);
  $allStars = str_repeat('★', $allFull) . str_repeat('☆', 5 - $allFull);
@endphp

<a href="{{ route('recipes.show', $recipe) }}" class="recipe-card">
  <div class="recipe-card-img">{{ $recipe->emoji }}</div>
  <div class="recipe-card-body">
    <div class="recipe-card-cat">{{ $recipe->category->emoji }} {{ $recipe->category->name }}</div>
    <div class="recipe-card-title">{{ $recipe->title }}</div>
    <div class="recipe-card-meta">
      <span>👤 {{ $recipe->user->name }}</span>
      @if($recipe->cook_time)
        <span>⏱ {{ $recipe->cook_time }}</span>
      @endif
    </div>

    @if($cookedComments->count())
      <div class="rating-line">
        <span style="color:var(--gold)">{{ $cookedStars }}</span>
        <span class="rating-cooked">🍳 {{ $cookedComments->count() }} gatavojis</span>
      </div>
    @endif

    @if($allRated->count() > $cookedComments->count())
      <div class="rating-line">
        <span style="color:var(--text-faint)">{{ $allStars }}</span>
        <span class="rating-all">👁 kopā {{ $allRated->count() }}</span>
      </div>
    @endif

    @if($recipe->tags->count())
      <div class="recipe-card-tags">
        @foreach($recipe->tags->take(4) as $tag)
          <span class="tag-pill">{{ $tag->name }}</span>
        @endforeach
      </div>
    @endif
  </div>
</a>