@extends('layouts.app')
@section('title', __('app.admin_panel') . ' — ' . __('app.comments'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>{{ __('app.comments_moderation') }}</h3>
      @forelse($comments as $comment)
        <div class="admin-comment-row">
          <div class="admin-comment-meta">
            <strong>{{ $comment->user->name }}</strong>
            {{ __('app.about') }}
            <a href="{{ route('recipes.show', $comment->recipe) }}" style="color:var(--accent-mid)">
              {{ $comment->recipe->title }}
            </a>
            · {{ $comment->created_at->format('d.m.Y') }}
            <span class="comment-cooked-badge {{ $comment->has_cooked ? 'badge-cooked' : 'badge-tasted' }}" style="margin-left:6px">
              {{ $comment->has_cooked ? __('app.badge_cooked') : __('app.badge_tasted') }}
            </span>
            <span style="color:var(--gold);margin-left:6px">
              {{ str_repeat('★', $comment->rating) }}{{ str_repeat('☆', 5 - $comment->rating) }}
            </span>
          </div>
          <div class="admin-comment-body">{{ $comment->content }}</div>
          <form method="POST" action="{{ route('admin.comments.delete', $comment) }}"
                onsubmit="return confirm(@json(__('app.confirm_delete_comment_admin')))">
            @csrf @method('DELETE')
            <button class="action-btn danger">{{ __('app.delete') }}</button>
          </form>
        </div>
      @empty
        <div class="empty-state"><p>{{ __('app.no_admin_comments') }}</p></div>
      @endforelse
      <div style="margin-top:1rem">{{ $comments->links() }}</div>
    </div>
  </div>
</div>
@endsection
