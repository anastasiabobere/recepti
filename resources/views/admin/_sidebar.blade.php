<div class="admin-layout">
  <aside class="admin-sidebar">
    <h3>{{ __('app.admin_panel') }}</h3>
    <a href="{{ route('admin.index') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.index') ? 'active' : '' }}">{{ __('app.nav_dashboard') }}</a>
    <a href="{{ route('admin.recipes') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.recipes') ? 'active' : '' }}">{{ __('app.nav_recipes') }}</a>
    <a href="{{ route('admin.categories') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.categories') ? 'active' : '' }}">{{ __('app.nav_categories') }}</a>
    <a href="{{ route('admin.users') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.users') ? 'active' : '' }}">{{ __('app.nav_users') }}</a>
    <a href="{{ route('admin.comments') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.comments') ? 'active' : '' }}">{{ __('app.nav_comments') }}</a>
    <hr style="border:none;border-top:1px solid var(--border);margin:.75rem 0">
    <a href="{{ route('recipes.index') }}" class="admin-nav-btn">{{ __('app.back_to_site') }}</a>
  </aside>
