@extends('layouts.app')
@section('title', 'Admin — Lietotāji')

@section('content')
<div class="container" style="padding-top:1.5rem">
  @include('admin._sidebar')
  <div class="admin-content-area">
    <div class="form-card">
      <h3>Lietotāju pārvaldība</h3>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Vārds</th>
            <th>E-pasts</th>
            <th>Loma</th>
            <th>Receptes</th>
            <th>Statuss</th>
            <th>Darbības</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td><strong>{{ $user->name }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->isAdmin() ? '⚙️ Admin' : '👤 Lietotājs' }}</td>
            <td>{{ $user->recipes_count }}</td>
            <td>
              <span class="status-badge {{ $user->is_blocked ? 'status-blocked' : 'status-active' }}">
                {{ $user->is_blocked ? 'Bloķēts' : 'Aktīvs' }}
              </span>
            </td>
            <td>
              @if(! $user->isAdmin())
                <form method="POST" action="{{ route('admin.users.toggle-block', $user) }}" style="display:inline">
                  @csrf @method('PATCH')
                  <button class="action-btn {{ $user->is_blocked ? '' : 'danger' }}">
                    {{ $user->is_blocked ? 'Atbloķēt' : 'Bloķēt' }}
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
