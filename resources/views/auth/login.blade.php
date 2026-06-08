@extends('layouts.app')
@section('title', 'Pieslēgties')

@section('content')
<div class="container" style="padding-top:3rem">
  <div style="max-width:420px;margin:0 auto">
    <h2 style="font-size:28px;margin-bottom:1.5rem;text-align:center">Pieslēgties</h2>

    <div class="form-card">
      @if($errors->any())
        <div class="flash flash-error" style="border-radius:var(--radius);margin-bottom:1rem">
          @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">E-pasts</label>
          <input type="email" id="email" name="email" class="form-input"
                 value="{{ old('email') }}" required autofocus placeholder="epasts@piemers.lv">
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Parole</label>
          <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
          <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
            <input type="checkbox" name="remember"> Atcerēties mani
          </label>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Pieslēgties</button>
      </form>

      <div class="info-box" style="margin-top:1rem;margin-bottom:0">
        <strong>Demo konti:</strong><br>
        admin@garsa.lv / password (administrators)<br>
        anna@garsa.lv / password (lietotājs)
      </div>
    </div>

    <p style="text-align:center;font-size:14px;color:var(--text-muted);margin-top:1rem">
      Nav konta? <a href="{{ route('register') }}" style="color:var(--accent-mid)">Reģistrēties</a>
    </p>
  </div>
</div>
@endsection
