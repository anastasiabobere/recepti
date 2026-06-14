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
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="{{ route('recipes.index') }}" class="nav-brand">
      {{ __('app.site_name') }} <span>{{ __('app.site_suffix') }}</span>
    </a>

    <button type="button" class="nav-toggle" id="navToggle"
            aria-expanded="false" aria-controls="navMenu" aria-label="{{ __('app.menu') }}">
      <span class="nav-toggle-eq" aria-hidden="true">=</span>
      <span class="nav-toggle-x" aria-hidden="true">×</span>
    </button>

    <div class="nav-menu" id="navMenu">
      <form action="{{ route('recipes.index') }}" method="GET" class="nav-search">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('app.search_placeholder') }}" autocomplete="off">
      </form>

      <div class="nav-links">
        <div class="nav-lang">
          <a href="{{ route('lang.switch', 'lv') }}" class="nav-btn {{ app()->getLocale() === 'lv' ? 'active' : '' }}">LV</a>
          <a href="{{ route('lang.switch', 'en') }}" class="nav-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
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
          <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
            @csrf
            <button type="submit" class="nav-btn">{{ __('app.logout') }}</button>
          </form>
        @endauth
      </div>
    </div>
  </div>
  <div class="nav-backdrop" id="navBackdrop" hidden></div>
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
<script>
(function () {
  const navbar   = document.getElementById('navbar');
  const toggle   = document.getElementById('navToggle');
  const backdrop = document.getElementById('navBackdrop');
  if (!navbar || !toggle) return;

  const openLabel  = @json(__('app.menu'));
  const closeLabel = @json(__('app.close_menu'));

  function setOpen(open) {
    navbar.classList.toggle('nav-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? closeLabel : openLabel);
    document.body.classList.toggle('nav-menu-open', open);
    if (backdrop) backdrop.hidden = !open;
  }

  toggle.addEventListener('click', () => setOpen(!navbar.classList.contains('nav-open')));

  if (backdrop) {
    backdrop.addEventListener('click', () => setOpen(false));
  }

  navbar.querySelectorAll('.nav-menu a, .nav-menu button').forEach(el => {
    el.addEventListener('click', () => setOpen(false));
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) setOpen(false);
  });
})();
</script>
</body>
</html>
