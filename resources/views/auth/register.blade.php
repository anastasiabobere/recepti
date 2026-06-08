@extends('layouts.app')
@section('title', 'Reģistrēties')

@section('content')
<div class="container" style="padding-top:3rem">
  <div style="max-width:420px;margin:0 auto">
    <h2 style="font-size:28px;margin-bottom:1.5rem;text-align:center">Reģistrēties</h2>

    <div class="form-card">
      @if($errors->any())
        <div class="flash flash-error" style="border-radius:var(--radius);margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="name">Vārds</label>
          <input type="text" id="name" name="name" class="form-input"
                 value="{{ old('name') }}" required autofocus placeholder="Tavs vārds">
        </div>
        <div class="form-group">
          <label class="form-label" for="email">E-pasts</label>
          <input type="email" id="email" name="email" class="form-input"
                 value="{{ old('email') }}" required placeholder="epasts@piemers.lv">
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Parole</label>
          <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="password_confirmation">Parole vēlreiz</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Reģistrēties</button>
      </form>
    </div>

    <p style="text-align:center;font-size:14px;color:var(--text-muted);margin-top:1rem">
      Jau ir konts? <a href="{{ route('login') }}" style="color:var(--accent-mid)">Pieslēgties</a>
    </p>
  </div>
</div>
@endsection
