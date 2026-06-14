@php
  $isSelf = auth()->id() === $member->id;
  $isFollowingMember = auth()->check() && ! $isSelf && auth()->user()->isFollowing($member);
@endphp

<div class="user-card">
  <a href="{{ route('users.show', $member) }}" class="user-card-avatar">{{ $member->initials() }}</a>
  <div class="user-card-body">
    <a href="{{ route('users.show', $member) }}" class="user-card-name">{{ $member->name }}</a>
    <div class="user-card-meta">
      {{ trans_choice('app.recipes_in_category', $member->recipes_count, ['count' => $member->recipes_count]) }}
    </div>
  </div>
  @auth
    @if(! $isSelf)
      @if($isFollowingMember)
        <form method="POST" action="{{ route('users.unfollow', $member) }}">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.unfollow') }}</button>
        </form>
      @else
        <form method="POST" action="{{ route('users.follow', $member) }}">
          @csrf
          <button type="submit" class="btn btn-primary btn-sm">{{ __('app.follow') }}</button>
        </form>
      @endif
    @endif
  @endauth
</div>
