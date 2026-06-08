@extends('layouts.app')
@section('title', 'Admin — Receptes')

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>Visu recepšu pārvaldība</h3>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Recepte</th>
            <th>Autors</th>
            <th>Kategorija</th>
            <th>Status</th>
            <th>Komentāri</th>
            <th>Darbības</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recipes as $recipe)
          <tr>
            <td>
              <a href="{{ route('recipes.show', $recipe) }}" style="color:var(--accent-mid)">
                {{ $recipe->title }}
              </a>
            </td>
            <td>{{ $recipe->user->name }}</td>
            <td>{{ $recipe->category->emoji }} {{ $recipe->category->name }}</td>
            <td>
              <span class="status-badge {{ $recipe->is_published ? 'status-active' : 'status-blocked' }}">
                {{ $recipe->is_published ? 'Publicēta' : 'Paslēpta' }}
              </span>
            </td>
            <td>{{ $recipe->comments_count ?? $recipe->comments->count() }}</td>
            <td>
              <form method="POST" action="{{ route('admin.recipes.toggle-publish', $recipe) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="action-btn">
                  {{ $recipe->is_published ? 'Paslēpt' : 'Publicēt' }}
                </button>
              </form>
              <form method="POST" action="{{ route('admin.recipes.delete', $recipe) }}" style="display:inline"
                    onsubmit="return confirm('Pilnībā dzēst šo recepti?')">
                @csrf @method('DELETE')
                <button class="action-btn danger">Dzēst</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="margin-top:1rem">{{ $recipes->links() }}</div>
    </div>
  </div>
</div>
@endsection
