@extends('layouts.app')
@section('title', __('app.admin_panel'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')

  <div class="admin-content-area">
    <h2 style="font-size:26px;margin-bottom:1.5rem">{{ __('app.dashboard') }}</h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:2rem">
      @foreach([
        [__('app.recipes'),    $recipesCount,    route('admin.recipes')],
        [__('app.users'),      $usersCount,      route('admin.users')],
        [__('app.comments'),   $commentsCount,   route('admin.comments')],
        [__('app.categories'), $categoriesCount, route('admin.categories')],
      ] as [$label, $count, $url])
        <a href="{{ $url }}" class="stat-card">
          <div class="stat-count">{{ $count }}</div>
          <div class="stat-label">{{ $label }}</div>
        </a>
      @endforeach
    </div>
  </div>
</div>
@endsection
