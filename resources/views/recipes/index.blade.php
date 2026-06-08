@extends('layouts.app')
@section('title', 'Receptes')

@section('content')
{{-- Hero --}}
<div class="hero">
  <div class="container">
    <h1>Atklāj <em>garšas</em> pasauli</h1>
    <p>Dalies ar savām receptēm un atklāj citu iedvesmu — no vienkāršām brokastīm līdz izsmalcinātiem šedevriem.</p>
  </div>
</div>

<div class="container" style="padding-top:1.5rem">
  {{-- Category filter chips --}}
  <div class="filter-bar">
    <span class="filter-label">Kategorija:</span>
    <a href="{{ route('recipes.index', array_filter(['search' => $search])) }}"
       class="filter-chip {{ ! $categorySlug ? 'active' : '' }}">Visas</a>
    @foreach($categories as $cat)
      <a href="{{ route('recipes.index', array_filter(['category' => $cat->slug, 'search' => $search])) }}"
         class="filter-chip {{ $categorySlug === $cat->slug ? 'active' : '' }}">
        {{ $cat->emoji }} {{ $cat->name }}
        <span class="chip-count">{{ $cat->recipes_count }}</span>
      </a>
    @endforeach
  </div>

  @if($search)
    <div class="info-box">
      Meklēšanas rezultāti "<strong>{{ $search }}</strong>": atrast{{ $recipes->total() === 1 ? 'a' : 'as' }} {{ $recipes->total() }} recepte{{ $recipes->total() === 1 ? '' : 's' }}.
      <a href="{{ route('recipes.index') }}">Notīrīt</a>
    </div>
  @endif

  {{-- Recipe grid --}}
  @if($recipes->isEmpty())
    <div class="empty-state">
      <div class="empty-icon">🍽️</div>
      <p>Nav atrasta neviena recepte.</p>
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
