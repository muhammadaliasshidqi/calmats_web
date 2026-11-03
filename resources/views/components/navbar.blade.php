@php
  $links = ['Home' => 'home', 'Shop' => 'shop', 'About' => 'about', 'Community' => 'community'];
@endphp
<header x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="sticky top-0 z-50 border-b border-mist bg-paper/90 backdrop-blur">
  <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 lg:px-10" aria-label="Navigasi utama">
    <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="CALMATS, ke Home">
 <img src="{{ asset('images/Logo.png') }}" alt="" class="h-10 w-10 object-contain mix-blend-multiply">
  <span class="text-lg font-bold tracking-[0.28em]">CALMATS</span>
</a>

    <ul class="hidden items-center gap-10 md:flex">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" @class(['text-sm transition-colors hover:text-accent', 'font-semibold' => request()->routeIs($name)])>{{ $label }}</a></li>
      @endforeach
    </ul>

    <div class="flex items-center gap-5">
      @auth
        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
          @csrf
          <button type="submit" class="text-sm hover:text-accent">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="hidden text-sm hover:text-accent md:block">Login</a>
      @endauth
      <a href="{{ route('cart') }}" class="relative p-1" aria-label="Keranjang">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Zm4 0a3 3 0 0 1 6 0"/></svg>
        <span class="absolute -right-2 -top-1.5 grid h-4 min-w-4 place-items-center rounded-full bg-ink px-1 text-[10px] text-paper" x-text="$store.cart.count">0</span>
      </a>
      <button class="p-1 md:hidden" x-on:click="open = !open" :aria-expanded="open" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path x-show="!open" d="M4 8h16M4 16h16"/><path x-show="open" x-cloak d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
  </nav>

  <div x-show="open" x-cloak x-transition.opacity class="border-t border-mist bg-paper md:hidden">
    <ul class="mx-auto max-w-7xl space-y-1 px-5 py-6">
      @foreach ($links as $label => $name)
        <li><a href="{{ route($name) }}" class="block py-3 text-2xl font-medium">{{ $label }}</a></li>
      @endforeach
      @guest
        <li><a href="{{ route('login') }}" class="block py-3 text-2xl font-medium">Login</a></li>
      @else
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block py-3 text-2xl font-medium">Logout</button>
          </form>
        </li>
      @endguest
    </ul>
  </div>
</header>
