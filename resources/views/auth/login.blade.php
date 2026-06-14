@extends('layouts.app')
@section('title', __('app.login_title'))

@section('content')
<div class="container" style="padding-top:3rem">
  <div style="max-width:420px;margin:0 auto">
    <h2 style="font-size:28px;margin-bottom:1.5rem;text-align:center">{{ __('app.login_title') }}</h2>

    <div class="form-card">
      @if($errors->any())
        <div class="flash flash-error" style="border-radius:var(--radius);margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">{{ __('app.email') }}</label>
          <input type="email" id="email" name="email" class="form-input"
                 value="{{ old('email') }}" required autofocus placeholder="{{ __('app.email_placeholder') }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="password">{{ __('app.password') }}</label>
          <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
          <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
            <input type="checkbox" name="remember"> {{ __('app.remember_me') }}
          </label>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">{{ __('app.login_title') }}</button>
      </form>

      <div class="info-box" style="margin-top:1rem;margin-bottom:0">
        <strong>{{ __('app.demo_accounts') }}:</strong><br>
        admin@garsa.lv / password ({{ __('app.demo_admin_role') }})<br>
        anna@garsa.lv / password ({{ __('app.demo_user_role') }})
      </div>
    </div>

    <p style="text-align:center;font-size:14px;color:var(--text-muted);margin-top:1rem">
      {{ __('app.no_account') }} <a href="{{ route('register') }}" style="color:var(--accent-mid)">{{ __('app.register') }}</a>
    </p>
  </div>
</div>
@endsection
