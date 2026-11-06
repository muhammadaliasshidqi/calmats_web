@php
  $links = ['Shop' => 'shop', 'Community' => 'community', 'About' => 'about'];
@endphp
<header x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="relative z-50 border-b border-ink/10 bg-white">
  <nav class="mx-auto grid h-14 max-w-[1440px] grid-cols-2 items-center px-5 lg:h-[86px] lg:grid-cols-[1fr_auto_1fr] lg:px-12" aria-label="Navigasi utama">
    <a href="{{ route('home') }}" class="w-fit font-sans text-xl font-semibold tracking-tight transition-colors hover:text-accent lg:text-2xl" aria-label="CALMATS, ke Home">CALMATS</a>

    <ul class="hidden items-center justify-center gap-7 lg:flex">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" @class(['text-xs font-semibold uppercase tracking-wide transition-colors hover:text-accent', 'text-accent' => request()->routeIs($name)])>{{ $label }}</a></li>
      @endforeach
    </ul>

    <div class="flex items-center justify-end gap-4 sm:gap-5">
      @auth
        <a href="{{ route('profile') }}" class="nav-link hidden lg:block">Profile</a>
        <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
          @csrf
          <button type="submit" class="nav-link">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="nav-link hidden lg:block">Login</a>
      @endauth
      <a href="{{ route('cart') }}" @class(['relative rounded-full p-2 transition-all duration-300 hover:bg-accent/10 hover:text-accent', 'bg-accent/10 text-accent' => request()->routeIs('cart')]) aria-label="Keranjang">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.35" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Zm4 0a3 3 0 0 1 6 0"/></svg>
        <span class="absolute -right-2 -top-1.5 grid h-4 min-w-4 place-items-center rounded-full bg-accent px-1 text-[10px] text-paper" x-text="$store.cart.count">0</span>
      </a>
      <button id="mobile-menu-trigger" class="p-1 lg:hidden" x-on:click="open = !open" :aria-expanded="open" aria-controls="mobile-navigation" :aria-label="open ? 'Tutup menu' : 'Buka menu'">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path x-show="!open" d="M4 8h16M4 16h16"/><path x-show="open" x-cloak d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
  </nav>

  <div id="mobile-navigation" x-show="open" x-cloak x-transition.opacity class="max-h-[calc(100dvh-72px)] overflow-y-auto border-t border-ink/10 bg-paper lg:hidden">
    <ul class="mx-auto max-w-7xl space-y-1 px-5 py-6">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" @class(['block rounded-[1rem] px-4 py-3 font-serif text-3xl italic transition-colors hover:bg-accent/10 hover:text-accent', 'bg-accent/10 text-accent' => request()->routeIs($name)])>{{ $label }}</a></li>
      @endforeach
      @guest
        <li><a href="{{ route('login') }}" class="block rounded-[1rem] px-4 py-3 font-serif text-3xl italic transition-colors hover:bg-accent/10 hover:text-accent">Login</a></li>
      @else
        <li><a href="{{ route('profile') }}" class="block rounded-[1rem] px-4 py-3 font-serif text-3xl italic transition-colors hover:bg-accent/10 hover:text-accent">Profile</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full rounded-[1rem] px-4 py-3 text-left font-serif text-3xl italic transition-colors hover:bg-accent/10 hover:text-accent">Logout</button>
          </form>
        </li>
      @endguest
    </ul>
  </div>
</header>
