@php
  $profileUrl = fn (string $t) => $isOwn
      ? route('profile', ['tab' => $t])
      : route('users.show', ['user' => $user, 'tab' => $t]);
@endphp

@extends('layouts.app')
@section('title', $isOwn ? __('app.profile_title') : __('app.user_profile_title', ['name' => $user->name]))

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div style="max-width:900px;margin:0 auto">

    <div class="profile-header">
      <div class="profile-avatar">{{ $user->initials() }}</div>
      <div class="profile-info">
        <div class="profile-name">{{ $user->name }}</div>
        @if($isOwn)
          <div class="profile-meta">{{ $user->email }}</div>
        @endif
        <div class="profile-meta" style="margin-top:4px">
          <span class="role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
            {{ $user->isAdmin() ? __('app.administrator') : __('app.registered_user') }}
          </span>
          &nbsp;· {{ __('app.recipes_count_label') }}: {{ $recipesCount }}
        </div>
        <div class="profile-stats">
          <a href="{{ $profileUrl('followers') }}" class="profile-stat {{ $tab === 'followers' ? 'active' : '' }}">
            <strong>{{ $followersCount }}</strong> {{ __('app.followers') }}
          </a>
          <a href="{{ $profileUrl('following') }}" class="profile-stat {{ $tab === 'following' ? 'active' : '' }}">
            <strong>{{ $followingCount }}</strong> {{ __('app.following') }}
          </a>
        </div>
      </div>

      <div class="profile-actions">
        @if($isOwn)
          @if(! auth()->user()->isBlocked())
            <a href="{{ route('recipes.create') }}" class="btn btn-primary">{{ __('app.add_recipe_btn') }}</a>
          @endif
        @elseif(auth()->check())
          @if($isFollowing)
            <form method="POST" action="{{ route('users.unfollow', $user) }}">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-secondary">{{ __('app.unfollow') }}</button>
            </form>
          @else
            <form method="POST" action="{{ route('users.follow', $user) }}">
              @csrf
              <button type="submit" class="btn btn-primary">{{ __('app.follow') }}</button>
            </form>
          @endif
        @endif
      </div>
    </div>

    <div class="profile-tabs">
      <a href="{{ $profileUrl('recipes') }}" class="profile-tab {{ $tab === 'recipes' ? 'active' : '' }}">
        {{ $isOwn ? __('app.my_recipes') : __('app.user_recipes') }}
      </a>
      @if($isOwn)
        <a href="{{ $profileUrl('saved') }}" class="profile-tab {{ $tab === 'saved' ? 'active' : '' }}">
          {{ __('app.saved_recipes') }}
        </a>
      @endif
      <a href="{{ $profileUrl('followers') }}" class="profile-tab {{ $tab === 'followers' ? 'active' : '' }}">
        {{ __('app.followers') }}
      </a>
      <a href="{{ $profileUrl('following') }}" class="profile-tab {{ $tab === 'following' ? 'active' : '' }}">
        {{ __('app.following') }}
      </a>
    </div>

    @if($tab === 'recipes')
      @if($recipes->isEmpty())
        <div class="empty-state">
          <p>{{ $isOwn ? __('app.no_own_recipes') : __('app.no_user_recipes') }}</p>
          @if($isOwn)
            <a href="{{ route('recipes.create') }}" class="btn btn-primary" style="margin-top:1rem">
              {{ __('app.add_first_recipe') }}
            </a>
          @endif
        </div>
      @else
        <div class="recipes-grid">
          @foreach($recipes as $recipe)
            @include('components.recipe-card', ['recipe' => $recipe])
          @endforeach
        </div>
        <div style="margin-top:1.5rem">{{ $recipes->links() }}</div>
      @endif

    @elseif($tab === 'saved')
      @if($savedRecipes->isEmpty())
        <div class="empty-state">
          <p>{{ __('app.no_saved_recipes') }}</p>
        </div>
      @else
        <div class="recipes-grid">
          @foreach($savedRecipes as $recipe)
            @include('components.recipe-card', ['recipe' => $recipe])
          @endforeach
        </div>
        <div style="margin-top:1.5rem">{{ $savedRecipes->links() }}</div>
      @endif

    @elseif($tab === 'followers')
      @if($followers->isEmpty())
        <div class="empty-state"><p>{{ __('app.no_followers') }}</p></div>
      @else
        <div class="user-list">
          @foreach($followers as $follower)
            @include('components.user-card', ['member' => $follower])
          @endforeach
        </div>
        <div style="margin-top:1.5rem">{{ $followers->links() }}</div>
      @endif

    @elseif($tab === 'following')
      @if($following->isEmpty())
        <div class="empty-state"><p>{{ __('app.no_following') }}</p></div>
      @else
        <div class="user-list">
          @foreach($following as $followed)
            @include('components.user-card', ['member' => $followed])
          @endforeach
        </div>
        <div style="margin-top:1.5rem">{{ $following->links() }}</div>
      @endif
    @endif

  </div>
</div>
@endsection
