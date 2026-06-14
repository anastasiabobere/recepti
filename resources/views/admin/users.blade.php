@extends('layouts.app')
@section('title', __('app.admin_panel') . ' — ' . __('app.users'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>{{ __('app.users_management') }}</h3>
      <table class="admin-table">
        <thead>
          <tr>
            <th>{{ __('app.col_name') }}</th>
            <th>{{ __('app.col_email') }}</th>
            <th>{{ __('app.col_role') }}</th>
            <th>{{ __('app.col_recipes') }}</th>
            <th>{{ __('app.col_status') }}</th>
            <th>{{ __('app.col_actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td><strong>{{ $user->name }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->isAdmin() ? __('app.role_admin_short') : __('app.role_user_short') }}</td>
            <td>{{ $user->recipes_count }}</td>
            <td>
              <span class="status-badge {{ $user->is_blocked ? 'status-blocked' : 'status-active' }}">
                {{ $user->is_blocked ? __('app.blocked') : __('app.active') }}
              </span>
            </td>
            <td>
              @if(! $user->isAdmin())
                <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}" style="display:inline">
                  @csrf @method('PATCH')
                  <button class="action-btn {{ $user->is_blocked ? '' : 'danger' }}">
                    {{ $user->is_blocked ? __('app.unblock') : __('app.block') }}
                  </button>
                </form>
              @else
                <span style="font-size:12px;color:var(--text-faint)">—</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="margin-top:1rem">{{ $users->links() }}</div>
    </div>
  </div>
</div>
@endsection
