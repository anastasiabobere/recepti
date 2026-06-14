@extends('layouts.app')
@section('title', __('app.page_title_recipes'))

@section('content')
{{-- Hero --}}
<div class="hero">
  <div class="container">
    <h1>{!! __('app.hero_title', ['word' => '<em>' . __('app.hero_word') . '</em>']) !!}</h1>
    <p>{{ __('app.hero_subtitle') }}</p>
  </div>
</div>

<div class="container" style="padding-top:1.5rem">
  {{-- Category filter chips --}}
  <div class="filter-bar">
    <span class="filter-label">{{ __('app.category') }}:</span>
    <a href="{{ route('recipes.index', array_filter(['search' => $search])) }}"
       class="filter-chip {{ ! $categorySlug ? 'active' : '' }}">{{ __('app.all') }}</a>
    @foreach($categories as $cat)
      <a href="{{ route('recipes.index', array_filter(['category' => $cat->slug, 'search' => $search])) }}"
         class="filter-chip {{ $categorySlug === $cat->slug ? 'active' : '' }}">
        {{ $cat->localized_name }}
        <span class="chip-count">{{ $cat->recipes_count }}</span>
      </a>
    @endforeach
  </div>

  @if($search)
    <div class="info-box">
      {{ trans_choice('app.search_results', $recipes->total(), ['term' => $search, 'count' => $recipes->total()]) }}
      <a href="{{ route('recipes.index') }}">{{ __('app.clear') }}</a>
    </div>
  @endif

  {{-- Recipe grid --}}
  @if($recipes->isEmpty())
    <div class="empty-state">
      <p>{{ __('app.no_recipes') }}</p>
    </div>
  @else
    <div class="recipes-grid">
      @foreach($recipes as $recipe)
        @include('components.recipe-card', ['recipe' => $recipe])
      @endforeach
    </div>
    <div style="margin-top:2rem">
      {{ $recipes->links() }}
    </div>
  @endif
</div>
@endsection
