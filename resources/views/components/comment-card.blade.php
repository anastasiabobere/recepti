@php
  $initials = collect(explode(' ', $comment->user->name))
              ->map(fn($w) => strtoupper($w[0]))
              ->take(2)->join('');
@endphp

<div class="comment-card">
  <div class="comment-header">
    <div class="comment-avatar {{ $comment->has_cooked ? 'cooked' : 'tasted' }}">{{ $initials }}</div>
    <div>
      <div class="comment-name">{{ $comment->user->name }}</div>
      <div class="comment-stars">
        {{ str_repeat('★', $comment->rating) }}{{ str_repeat('☆', 5 - $comment->rating) }}
        <span style="color:var(--text-faint);font-size:11px">{{ $comment->rating }}/5</span>
      </div>
    </div>
    <span class="comment-cooked-badge {{ $comment->has_cooked ? 'badge-cooked' : 'badge-tasted' }}">
      {{ $comment->has_cooked ? __('app.badge_cooked') : __('app.badge_tasted') }}
    </span>
    @auth
      @if(auth()->user()->isAdmin() || auth()->id() === $comment->user_id)
        <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="margin-left:auto">
          @csrf @method('DELETE')
          <button class="action-btn danger" onclick="return confirm(@json(__('app.confirm_delete_comment')))">{{ __('app.delete') }}</button>
        </form>
      @endif
    @endauth
  </div>
  <div class="comment-text">{{ $comment->content }}</div>
  <div class="comment-date">{{ $comment->created_at->format('d.m.Y') }}</div>
</div>
