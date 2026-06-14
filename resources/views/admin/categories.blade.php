@extends('layouts.app')
@section('title', __('app.admin_panel') . ' — ' . __('app.categories'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>{{ __('app.category_management') }}</h3>

      <div class="cat-warning">
        <div>
          <strong>{{ __('app.category_warning_title') }}</strong><br>
          {{ __('app.category_warning') }}
        </div>
      </div>

      <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex;gap:.5rem;margin-bottom:1.5rem">
        @csrf
        <input type="text" name="name" class="form-input" placeholder="{{ __('app.new_category_name') }}"
               value="{{ old('name') }}" required style="flex:1">
        <button type="submit" class="btn btn-primary">{{ __('app.add_category') }}</button>
      </form>

      @if($errors->any())
        <div class="flash flash-error" style="margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <ul class="category-list">
        @foreach($categories as $cat)
          <li class="category-item">
            <span class="category-name">{{ $cat->localized_name }}</span>
            <span class="category-count">{{ trans_choice('app.recipes_in_category', $cat->recipes_count, ['count' => $cat->recipes_count]) }}</span>

            @if($cat->isUncategorized())
              <span style="font-size:11px;color:var(--text-faint);margin-left:auto">{{ __('app.system_category') }}</span>
            @else
              <form method="POST" action="{{ route('admin.categories.delete', $cat) }}"
                    onsubmit="return confirmCategoryDelete({{ $cat->recipes_count }}, @json($cat->localized_name))"
                    style="margin-left:auto">
                @csrf @method('DELETE')
                <button class="action-btn danger">{{ __('app.delete') }}</button>
              </form>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const confirmCategoryDeleteWithRecipes = @json(__('app.confirm_category_delete_with_recipes'));
const confirmCategoryDeleteEmpty = @json(__('app.confirm_category_delete'));

function confirmCategoryDelete(recipeCount, catName) {
  if (recipeCount > 0) {
    return confirm(
      confirmCategoryDeleteWithRecipes
        .replace(':name', catName)
        .replace(':count', recipeCount)
    );
  }
  return confirm(confirmCategoryDeleteEmpty.replace(':name', catName));
}
</script>
@endpush
