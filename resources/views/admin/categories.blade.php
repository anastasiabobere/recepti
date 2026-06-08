@extends('layouts.app')
@section('title', 'Admin — Kategorijas')

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>Kategoriju pārvaldība</h3>

      {{-- Professor's requirement: clear warning about what happens to recipes on delete --}}
      <div class="cat-warning">
        <span>⚠️</span>
        <div>
          <strong>Svarīgi par kategoriju dzēšanu:</strong><br>
          Dzēšot kategoriju, kurai ir receptes, tās automātiski tiek pārvietotas uz
          <strong>"Nekategorizēts"</strong>. Tukšas kategorijas var dzēst droši.
          Sistēmas kategoriju <strong>"Nekategorizēts"</strong> nevar dzēst.
        </div>
      </div>

      {{-- Add new category form --}}
      <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex;gap:.5rem;margin-bottom:1.5rem">
        @csrf
        <input type="text" name="name" class="form-input" placeholder="Jaunas kategorijas nosaukums"
               value="{{ old('name') }}" required style="flex:1">
        <input type="text" name="emoji" class="form-input" placeholder="🍽️"
               value="{{ old('emoji') }}" maxlength="5" style="width:64px">
        <button type="submit" class="btn btn-primary">Pievienot</button>
      </form>

      @if($errors->any())
        <div class="flash flash-error" style="margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      {{-- Category list --}}
      <ul class="category-list">
        @foreach($categories as $cat)
          <li class="category-item">
            <span class="category-emoji">{{ $cat->emoji }}</span>
            <span class="category-name">{{ $cat->name }}</span>
            <span class="category-count">{{ $cat->recipes_count }} recepte{{ $cat->recipes_count === 1 ? '' : 's' }}</span>

            @if($cat->isUncategorized())
              <span style="font-size:11px;color:var(--text-faint);margin-left:auto">sistēmas</span>
            @else
              <form method="POST" action="{{ route('admin.categories.delete', $cat) }}"
                    onsubmit="return confirmCategoryDelete({{ $cat->recipes_count }}, '{{ $cat->name }}')"
                    style="margin-left:auto">
                @csrf @method('DELETE')
                <button class="action-btn danger">Dzēst</button>
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
function confirmCategoryDelete(recipeCount, catName) {
  if (recipeCount > 0) {
    return confirm(
      `Kategorijā "${catName}" ir ${recipeCount} recepte(s).\n\n` +
      `Tās tiks pārvietotas uz "Nekategorizēts".\n\nVai turpināt?`
    );
  }
  return confirm(`Vai dzēst kategoriju "${catName}"?`);
}
</script>
@endpush
