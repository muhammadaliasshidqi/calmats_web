<x-layouts.app title="Shop — CALMATS">
  <section class="mx-auto max-w-7xl px-5 pb-24 pt-16 lg:px-10 lg:pt-24">
    <div class="max-w-3xl">
      <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">Collection 01</p>
      <h1 class="mt-5 font-serif text-6xl tracking-tight sm:text-7xl">Made for the quiet movement.</h1>
      <p class="mt-6 max-w-xl text-base leading-7 text-stone">Katalog konsep CALMATS—potongan sederhana, material berkarakter, dan ruang untuk menjadi diri sendiri.</p>
    </div>

    <nav class="mt-14 flex flex-wrap gap-x-7 gap-y-3 border-y border-mist py-5" aria-label="Filter kategori produk">
      <a href="{{ route('shop') }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === '', 'text-stone' => $activeCategory !== ''])>Semua</a>
      @foreach ($categories as $category)
        <a href="{{ route('shop', ['category' => $category]) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === $category, 'text-stone' => $activeCategory !== $category])>{{ $category }}</a>
      @endforeach
    </nav>

    @if ($products->isEmpty())
      <div class="py-24 text-center">
        <p class="font-serif text-3xl">Belum ada produk di kategori ini.</p>
        <a href="{{ route('shop') }}" class="link-line mt-7 inline-block">Lihat semua produk</a>
      </div>
    @else
      <div class="reveal mt-12 grid grid-cols-2 gap-x-5 gap-y-14 lg:grid-cols-3">
        @foreach ($products as $product)
          <x-product-card :p="$product" />
        @endforeach
      </div>
    @endif

    <p class="mt-16 max-w-xl border-l border-accent pl-5 text-sm leading-6 text-stone">Nama, harga, dan spesifikasi di halaman ini merupakan data konsep untuk demonstrasi website dan dapat diganti saat katalog resmi tersedia.</p>
  </section>
</x-layouts.app>
