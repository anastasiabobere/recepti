@extends('layouts.app')
@section('title', __('app.register_title'))

@section('content')
<div class="container" style="padding-top:3rem">
  <div style="max-width:420px;margin:0 auto">
    <h2 style="font-size:28px;margin-bottom:1.5rem;text-align:center">{{ __('app.register_title') }}</h2>

    <div class="form-card">
      @if($errors->any())
        <div class="flash flash-error" style="border-radius:var(--radius);margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="name">{{ __('app.name') }}</label>
          <input type="text" id="name" name="name" class="form-input"
                 value="{{ old('name') }}" required autofocus placeholder="{{ __('app.name_placeholder') }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="email">{{ __('app.email') }}</label>
          <input type="email" id="email" name="email" class="form-input"
                 value="{{ old('email') }}" required placeholder="{{ __('app.email_placeholder') }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="password">{{ __('app.password') }}</label>
          <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="password_confirmation">{{ __('app.password_confirm') }}</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">{{ __('app.register_title') }}</button>
      </form>
    </div>

    <p style="text-align:center;font-size:14px;color:var(--text-muted);margin-top:1rem">
      {{ __('app.have_account') }} <a href="{{ route('login') }}" style="color:var(--accent-mid)">{{ __('app.login') }}</a>
    </p>
  </div>
</div>
@endsection
