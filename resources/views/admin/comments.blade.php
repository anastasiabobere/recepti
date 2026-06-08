@extends('layouts.app')
@section('title', 'Admin — Komentāri')

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>Komentāru moderācija</h3>
      @forelse($comments as $comment)
        <div class="admin-comment-row">
          <div class="admin-comment-meta">
            <strong>{{ $comment->user->name }}</strong>
            par
            <a href="{{ route('recipes.show', $comment->recipe) }}" style="color:var(--accent-mid)">
              {{ $comment->recipe->title }}
            </a>
            · {{ $comment->created_at->format('d.m.Y') }}
            <span class="comment-cooked-badge {{ $comment->has_cooked ? 'badge-cooked' : 'badge-tasted' }}" style="margin-left:6px">
              {{ $comment->has_cooked ? '🍳 Gatavojis' : '👁 Apskatījis' }}
            </span>
            <span style="color:var(--gold);margin-left:6px">
              {{ str_repeat('★', $comment->rating) }}{{ str_repeat('☆', 5 - $comment->rating) }}
            </span>
          </div>
          <div class="admin-comment-body">{{ $comment->content }}</div>
          <form method="POST" action="{{ route('admin.comments.delete', $comment) }}"
                onsubmit="return confirm('Dzēst šo komentāru?')">
            @csrf @method('DELETE')
            <button class="action-btn danger">Dzēst</button>
          </form>
        </div>
      @empty
        <div class="empty-state"><div class="empty-icon">💬</div><p>Nav komentāru.</p></div>
      @endforelse
      <div style="margin-top:1rem">{{ $comments->links() }}</div>
    </div>
  </div>
</div>
@endsection
