@extends('layouts.app')
@section('title', __('app.admin_panel') . ' — ' . __('app.recipes'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>{{ __('app.recipes_management') }}</h3>
      <table class="admin-table">
        <thead>
          <tr>
            <th>{{ __('app.col_recipe') }}</th>
            <th>{{ __('app.col_author') }}</th>
            <th>{{ __('app.col_category') }}</th>
            <th>{{ __('app.col_status') }}</th>
            <th>{{ __('app.col_comments') }}</th>
            <th>{{ __('app.col_actions') }}</th>
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
            <td>{{ $recipe->category->localized_name }}</td>
            <td>
              <span class="status-badge {{ $recipe->is_published ? 'status-active' : 'status-blocked' }}">
                {{ $recipe->is_published ? __('app.published') : __('app.hidden') }}
              </span>
            </td>
            <td>{{ $recipe->comments_count ?? $recipe->comments->count() }}</td>
            <td>
              <form method="POST" action="{{ route('admin.recipes.toggle-publish', $recipe) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="action-btn">
                  {{ $recipe->is_published ? __('app.hide') : __('app.publish') }}
                </button>
              </form>
              <form method="POST" action="{{ route('admin.recipes.delete', $recipe) }}" style="display:inline"
                    onsubmit="return confirm(@json(__('app.confirm_delete_recipe_admin')))">
                @csrf @method('DELETE')
                <button class="action-btn danger">{{ __('app.delete') }}</button>
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
