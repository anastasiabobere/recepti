@php
  $cookedComments = $recipe->comments->where('has_cooked', true);
  $allRated       = $recipe->comments->where('rating', '>', 0);
  $cookedAvg      = (float) $cookedComments->avg('rating');
  $allAvg         = (float) $allRated->avg('rating');
  $cookedFull     = (int) round($cookedAvg);
  $cookedStars    = str_repeat('★', $cookedFull) . str_repeat('☆', 5 - $cookedFull);
  $allFull        = (int) round($allAvg);
  $allStars       = str_repeat('★', $allFull) . str_repeat('☆', 5 - $allFull);
@endphp

<div class="recipe-card">
  <a href="{{ route('recipes.show', $recipe) }}" class="recipe-card-img-link">
    <div class="recipe-card-img">
      @if($recipe->image_path)
        @php
          $imgUrl = str_starts_with($recipe->image_path, 'images/')
              ? asset($recipe->image_path)
              : asset('storage/' . $recipe->image_path);
        @endphp
        <img src="{{ $imgUrl }}"
             alt="{{ $recipe->title }}"
             style="width:100%;height:100%;object-fit:cover">
      @else
        <img src="{{ asset('images/placeholder.jpg') }}"
             alt="{{ __('app.no_image') }}"
             style="width:100%;height:100%;object-fit:cover">
      @endif
    </div>
  </a>
  <div class="recipe-card-body">
    <div class="recipe-card-cat">{{ $recipe->category->localized_name }}</div>
    <a href="{{ route('recipes.show', $recipe) }}" class="recipe-card-title">{{ $recipe->title }}</a>
    <a href="{{ route('users.show', $recipe->user) }}" class="recipe-card-author">{{ $recipe->user->name }}</a>
    <a href="{{ route('recipes.show', $recipe) }}" class="recipe-card-details">
      @if($recipe->cook_time)
        <div class="recipe-card-meta">
          <span>{{ $recipe->cook_time }}</span>
        </div>
      @endif
      @if($cookedComments->count())
        <div class="rating-line">
          <span style="color:var(--gold)">{{ $cookedStars }}</span>
          <span class="rating-cooked"> {{ trans_choice('app.cooked_count_short', $cookedComments->count(), ['count' => $cookedComments->count()]) }}</span>
        </div>
      @endif
      @if($allRated->count() > $cookedComments->count())
        <div class="rating-line">
          <span style="color:var(--text-faint)">{{ $allStars }}</span>
          <span class="rating-all">{{ __('app.ratings_total', ['count' => $allRated->count()]) }}</span>
        </div>
      @endif
      @if($recipe->tags->count())
        <div class="recipe-card-tags">
          @foreach($recipe->tags->take(4) as $tag)
            <span class="tag-pill">{{ $tag->name }}</span>
          @endforeach
        </div>
      @endif
    </a>
  </div>
</div>
