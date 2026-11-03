@php
  $links = ['Shop' => 'shop', 'Community' => 'community', 'About' => 'about'];
@endphp
<header x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="sticky top-0 z-50 border-b border-ink/10 bg-paper/90 backdrop-blur-md">
  <nav class="mx-auto grid h-[72px] max-w-[1440px] grid-cols-2 items-center px-5 md:grid-cols-3 lg:px-12" aria-label="Navigasi utama">
    <a href="{{ route('home') }}" class="font-serif text-2xl tracking-[-0.03em] sm:text-[1.75rem]" aria-label="CALMATS, ke Home">CALMATS</a>

    <ul class="hidden items-center justify-center gap-10 md:flex">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" @class(['nav-link', 'text-accent' => request()->routeIs($name)])>{{ $label }}</a></li>
      @endforeach
    </ul>

    <div class="flex items-center justify-end gap-4 sm:gap-5">
      @auth
        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
          @csrf
          <button type="submit" class="nav-link">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="nav-link hidden md:block">Login</a>
      @endauth
      <a href="{{ route('cart') }}" class="relative p-1" aria-label="Keranjang">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.35" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Zm4 0a3 3 0 0 1 6 0"/></svg>
        <span class="absolute -right-2 -top-1.5 grid h-4 min-w-4 place-items-center rounded-full bg-accent px-1 text-[10px] text-paper" x-text="$store.cart.count">0</span>
      </a>
      <button class="p-1 md:hidden" x-on:click="open = !open" :aria-expanded="open" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path x-show="!open" d="M4 8h16M4 16h16"/><path x-show="open" x-cloak d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
  </nav>

  <div x-show="open" x-cloak x-transition.opacity class="border-t border-ink/10 bg-paper md:hidden">
    <ul class="mx-auto max-w-7xl space-y-1 px-5 py-6">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" class="block py-3 font-serif text-3xl italic">{{ $label }}</a></li>
      @endforeach
      @guest
        <li><a href="{{ route('login') }}" class="block py-3 font-serif text-3xl italic">Login</a></li>
      @else
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block py-3 font-serif text-3xl italic">Logout</button>
          </form>
        </li>
      @endguest
    </ul>
  </div>
</header>
