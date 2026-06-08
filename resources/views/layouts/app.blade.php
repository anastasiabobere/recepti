<!DOCTYPE html>
<html lang="lv">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Garšas Pasaule') — Receptes</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
</head>
<body>

{{-- ── Navigation ── --}}
<nav class="navbar">
  <div class="nav-inner">
    <a href="{{ route('recipes.index') }}" class="nav-brand">
      Garšas Pasaule <span>receptes</span>
    </a>

    {{-- Search --}}
    <form action="{{ route('recipes.index') }}" method="GET" class="nav-search">
      <span class="nav-search-icon">&#9906;</span>
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Meklēt receptes, sastāvdaļas..." autocomplete="off">
    </form>

    <div class="nav-links">
      <a href="{{ route('recipes.index') }}" class="nav-btn {{ request()->routeIs('recipes.index') ? 'active' : '' }}">Receptes</a>

      @guest
        <a href="{{ route('login') }}" class="nav-btn">Pieslēgties</a>
        <a href="{{ route('register') }}" class="nav-btn primary">Reģistrēties</a>
      @endguest

      @auth
        @if(! auth()->user()->isBlocked())
          <a href="{{ route('recipes.create') }}" class="nav-btn">+ Pievienot</a>
        @endif
        <a href="{{ route('profile') }}" class="nav-btn">
          Profils
          <span class="role-badge {{ auth()->user()->isAdmin() ? 'role-admin' : 'role-user' }}">
            {{ auth()->user()->isAdmin() ? 'Admin' : 'Lietotājs' }}
          </span>
        </a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.index') }}" class="nav-btn">&#9881; Admin</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" class="nav-btn">Iziet</button>
        </form>
      @endauth
    </div>
  </div>
</nav>

{{-- ── Flash messages ── --}}
@if(session('success'))
  <div class="flash flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="flash flash-error">{{ session('error') }}</div>
@endif

{{-- ── Page content ── --}}
<main>
  @yield('content')
</main>

<footer class="site-footer">
  <div class="container">
    <p>Garšas Pasaule &copy; {{ date('Y') }} — Recepšu platforma</p>
  </div>
</footer>

@stack('scripts')
</body>
</html>
