@extends('layouts.app')
@section('title', 'Administrācija')

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')

  <div class="admin-content-area">
    <h2 style="font-size:26px;margin-bottom:1.5rem">Kopsavilkums</h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:2rem">
      @foreach([
        ['🍴', 'Receptes',    $recipesCount,    route('admin.recipes')],
        ['👤', 'Lietotāji',   $usersCount,      route('admin.users')],
        ['💬', 'Komentāri',   $commentsCount,   route('admin.comments')],
        ['🏷️','Kategorijas',  $categoriesCount, route('admin.categories')],
      ] as [$icon, $label, $count, $url])
        <a href="{{ $url }}" class="stat-card">
          <div class="stat-icon">{{ $icon }}</div>
          <div class="stat-count">{{ $count }}</div>
          <div class="stat-label">{{ $label }}</div>
        </a>
      @endforeach
    </div>
  </div>
</div>
@endsection
