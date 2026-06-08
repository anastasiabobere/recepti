<div class="admin-layout">
  <aside class="admin-sidebar">
    <h3>Administrācija</h3>
    <a href="{{ route('admin.index') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.index') ? 'active' : '' }}">📊 Kopsavilkums</a>
    <a href="{{ route('admin.recipes') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.recipes') ? 'active' : '' }}">📄 Receptes</a>
    <a href="{{ route('admin.categories') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.categories') ? 'active' : '' }}">🏷️ Kategorijas</a>
    <a href="{{ route('admin.users') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.users') ? 'active' : '' }}">👤 Lietotāji</a>
    <a href="{{ route('admin.comments') }}"
       class="admin-nav-btn {{ request()->routeIs('admin.comments') ? 'active' : '' }}">💬 Komentāri</a>
    <hr style="border:none;border-top:1px solid var(--border);margin:.75rem 0">
    <a href="{{ route('recipes.index') }}" class="admin-nav-btn">← Uz vietni</a>
  </aside>
