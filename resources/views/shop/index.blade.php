<x-layouts.app title="Shop — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-24 pt-10 lg:px-12 lg:pt-16">
    <div class="relative overflow-hidden rounded-[2rem] border border-ink/10 bg-gradient-to-br from-[#fffaf7] via-[#f3e8e2] to-[#e4cbc1] px-7 shadow-[0_24px_70px_rgba(64,35,28,.09)] sm:px-12 lg:px-16">
      <div class="relative z-10 flex min-h-[460px] max-w-5xl flex-col justify-center py-14 lg:py-20">
        <p class="eyebrow">Collection 01</p>
        <h1 class="mt-7 max-w-4xl font-serif text-6xl leading-[0.92] tracking-[-0.045em] sm:text-7xl lg:text-[6.5rem]">Made for the quiet<br class="hidden sm:block"> movement.</h1>
        <p class="mt-8 max-w-xl text-base leading-7 text-stone">Katalog CALMATS—potongan sederhana, material berkarakter, dan ruang untuk menjadi diri sendiri.</p>
        <p class="mt-12 text-[0.62rem] font-semibold uppercase tracking-[0.28em] text-stone">Designed in Indonesia <span class="mx-2 text-accent">/</span> Built for everyday movement</p>
      </div>

    </div>

    <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 border-b border-ink/15 py-5 text-[0.62rem] font-semibold uppercase tracking-[0.26em] sm:gap-x-12">
      <span>Culture</span><span class="text-accent">/</span>
      <span>Craft</span><span class="text-accent">/</span>
      <span>Movement</span><span class="text-accent">/</span>
      <span>CALMATS</span>
    </div>

    <form method="GET" action="{{ route('shop') }}" class="mt-8 grid gap-3 rounded-[1.5rem] border border-ink/10 bg-white/45 p-4 shadow-[0_14px_38px_rgba(40,31,25,.06)] md:grid-cols-[minmax(0,1fr)_14rem] md:items-center md:p-5 lg:grid-cols-[minmax(0,1fr)_15rem_auto]">
      @if ($activeCategory !== '')
        <input type="hidden" name="category" value="{{ $activeCategory }}">
      @endif

      <label class="relative block">
        <span class="sr-only">Cari produk</span>
        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-stone" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, kategori, atau material" class="h-12 w-full rounded-full border border-ink/15 bg-paper pl-11 pr-4 text-sm outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/10">
      </label>

      <x-select-dropdown
        name="sort"
        :value="$sort"
        label="Urutkan produk"
        :options="[
          'default' => 'Urutan pilihan',
          'latest' => 'Produk terbaru',
          'name' => 'Nama A-Z',
          'price_asc' => 'Harga terendah',
          'price_desc' => 'Harga tertinggi',
        ]"
      />

      <button type="submit" class="btn btn-accent h-12 justify-center md:col-span-2 lg:col-span-1">Terapkan</button>
    </form>

    @php
      $catalogQuery = array_filter([
        'q' => $search,
        'sort' => $sort === 'default' ? null : $sort,
      ], fn ($value) => filled($value));
    @endphp

    <nav class="flex flex-wrap gap-x-7 gap-y-3 border-b border-ink/15 py-7" aria-label="Filter kategori produk">
      <a href="{{ route('shop', $catalogQuery) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === '', 'text-stone' => $activeCategory !== ''])>Semua</a>
      @foreach ($categories as $category)
        <a href="{{ route('shop', [...$catalogQuery, 'category' => $category]) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === $category, 'text-stone' => $activeCategory !== $category])>{{ $category }}</a>
      @endforeach
    </nav>

    @if ($products->isEmpty())
      <div class="py-20 text-center sm:py-24">
        <p class="font-serif text-3xl italic sm:text-4xl">{{ $search !== '' || $activeCategory !== '' ? 'Tidak ada produk yang cocok.' : 'The collection will arrive soon.' }}</p>
        <span class="mx-auto mt-6 block h-px w-20 bg-accent" aria-hidden="true"></span>
        <p class="mx-auto mt-6 max-w-md text-sm leading-6 text-stone">{{ $search !== '' || $activeCategory !== '' ? 'Coba gunakan kata pencarian atau kategori yang berbeda.' : 'Produk akan tampil di sini setelah koleksi resmi ditambahkan melalui dashboard admin.' }}</p>
        @if ($search !== '' || $activeCategory !== '' || $sort !== 'default')
          <a href="{{ route('shop') }}" class="link-line mt-7 inline-block">Reset pencarian</a>
        @endif
      </div>
    @else
      <div class="mt-9 flex flex-wrap items-center justify-between gap-3 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-stone">
        <p>Menampilkan {{ $products->count() }} dari {{ $totalProducts }} produk</p>
        @if ($search !== '')
          <p>Hasil untuk &ldquo;{{ $search }}&rdquo;</p>
        @endif
      </div>
      <div class="reveal mt-12 grid grid-cols-1 gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($products as $product)
          <x-product-card :p="$product" />
        @endforeach
      </div>

      @if ($products->count() < $totalProducts)
        <div class="mt-16 text-center">
          <a href="{{ route('shop', array_filter(['q' => $search, 'category' => $activeCategory, 'sort' => $sort === 'default' ? null : $sort, 'limit' => min($limit + 6, 24)], fn ($value) => filled($value))) }}" class="btn border border-ink/20 bg-white/40 hover:border-accent hover:text-accent">
            Muat lebih banyak
            <span class="ml-3 text-accent" aria-hidden="true">+</span>
          </a>
        </div>
      @endif
    @endif
  </section>
</x-layouts.app>
