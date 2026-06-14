<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', __('app.site_name')) — {{ __('app.page_title_recipes') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
</head>
<body>

{{-- ── Navigation ── --}}
<nav class="navbar">
  <div class="nav-inner">
    <a href="{{ route('recipes.index') }}" class="nav-brand">
      {{ __('app.site_name') }} <span>{{ __('app.site_suffix') }}</span>
    </a>

    <form action="{{ route('recipes.index') }}" method="GET" class="nav-search">
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="{{ __('app.search_placeholder') }}" autocomplete="off">
    </form>

    <div class="nav-links">
      <div style="display:flex;gap:4px;margin-left:8px">
        <a href="{{ route('lang.switch', 'lv') }}" class="nav-btn {{ app()->getLocale() === 'lv' ? 'active' : '' }}" style="padding:7px 10px">LV</a>
        <a href="{{ route('lang.switch', 'en') }}" class="nav-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" style="padding:7px 10px">EN</a>
      </div>
      <a href="{{ route('recipes.index') }}" class="nav-btn {{ request()->routeIs('recipes.index') ? 'active' : '' }}">{{ __('app.recipes') }}</a>
      @guest
        <a href="{{ route('login') }}" class="nav-btn">{{ __('app.login') }}</a>
        <a href="{{ route('register') }}" class="nav-btn primary">{{ __('app.register') }}</a>
      @endguest
      @auth
        @if(! auth()->user()->isBlocked())
          <a href="{{ route('recipes.create') }}" class="nav-btn">{{ __('app.add_recipe') }}</a>
        @endif
        <a href="{{ route('profile') }}" class="nav-btn">
          {{ __('app.profile') }}
          <span class="role-badge {{ auth()->user()->isAdmin() ? 'role-admin' : 'role-user' }}">
            {{ auth()->user()->isAdmin() ? __('app.admin') : __('app.registered_user') }}
          </span>
        </a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.index') }}" class="nav-btn">{{ __('app.admin') }}</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" class="nav-btn">{{ __('app.logout') }}</button>
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
    <p>{{ __('app.site_name') }} &copy; {{ date('Y') }} — {{ __('app.footer') }}</p>
  </div>
</footer>

@stack('scripts')
</body>
</html>
