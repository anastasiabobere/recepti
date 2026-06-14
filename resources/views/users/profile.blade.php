@extends('layouts.app')
@section('title', __('app.profile_title'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div style="max-width:900px;margin:0 auto">

    {{-- Profile header --}}
    <div class="profile-header">
      <div class="profile-avatar">
        {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->join('') }}
      </div>
      <div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-meta">{{ $user->email }}</div>
        <div class="profile-meta" style="margin-top:4px">
          <span class="role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
            {{ $user->isAdmin() ? __('app.administrator') : __('app.registered_user') }}
          </span>
          &nbsp;· {{ __('app.recipes_count_label') }}: {{ $recipes->total() }}
        </div>
      </div>
      <a href="{{ route('recipes.create') }}" class="btn btn-primary" style="margin-left:auto">
        {{ __('app.add_recipe_btn') }}
      </a>
    </div>

    <h3 class="section-title">{{ __('app.my_recipes') }}</h3>

    @if($recipes->isEmpty())
      <div class="empty-state">
        <p>{{ __('app.no_own_recipes') }}</p>
        <a href="{{ route('recipes.create') }}" class="btn btn-primary" style="margin-top:1rem">
          {{ __('app.add_first_recipe') }}
        </a>
      </div>
    @else
      <div class="recipes-grid">
        @foreach($recipes as $recipe)
          @include('components.recipe-card', ['recipe' => $recipe])
        @endforeach
      </div>
      <div style="margin-top:1.5rem">{{ $recipes->links() }}</div>
    @endif

  </div>
</div>
@endsection
