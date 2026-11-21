<x-layouts.app title="Shop — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-28 pt-7 lg:px-12 lg:pt-10">
    <div class="shop-luxury-hero px-7 text-paper sm:px-12 lg:px-16">
      <div class="flex min-h-[380px] items-center py-14 sm:min-h-[430px] lg:py-20">
        <div class="max-w-4xl">
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-paper/75">Koleksi CALMATS</p>
          <h1 class="mt-5 max-w-4xl font-serif text-[clamp(3.25rem,7vw,7rem)] leading-[0.92] tracking-[-0.045em]">Pakaian tenang untuk keseharianmu.</h1>
          <p class="mt-7 max-w-2xl text-sm leading-7 text-paper/80 sm:text-base">Siluet nyaman, material berkarakter, dan detail sederhana yang mudah dipakai setiap hari.</p>
          <a href="#collection" class="mt-8 inline-flex rounded-full bg-paper px-6 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-accent transition-colors hover:bg-ink hover:text-paper">Lihat koleksi</a>
        </div>
      </div>
    </div>

    <form method="GET" action="{{ route('shop') }}" x-data="{ timer: null }" class="mt-10 grid gap-3 rounded-[1.75rem] border border-ink/10 bg-white/55 p-4 shadow-[0_18px_55px_rgba(40,31,25,.07)] backdrop-blur-md md:grid-cols-2 md:items-center md:p-5 lg:grid-cols-[minmax(0,1fr)_14rem_14rem_auto]">
      @if ($activeCategory !== '')
        <input type="hidden" name="category" value="{{ $activeCategory }}">
      @endif
      <label class="relative block">
        <span class="sr-only">Cari produk</span>
        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-stone" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama produk" x-on:input="clearTimeout(timer); timer = setTimeout(() => $el.form.requestSubmit(), 350)" class="h-12 w-full rounded-full border border-ink/15 bg-paper pl-11 pr-11 text-sm outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/10">
        @if ($search !== '')
          <a href="{{ route('shop', array_filter(['category' => $activeCategory, 'availability' => $availability])) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-lg text-stone hover:text-accent" aria-label="Hapus pencarian">&times;</a>
        @endif
      </label>

      <x-select-dropdown
        name="sort"
        :value="$sort"
        label="Urutkan produk"
        :disabled="$search !== ''"
        :options="[
          'latest' => 'Produk terbaru',
          'oldest' => 'Produk terlama',
          'price_asc' => 'Harga terendah',
          'price_desc' => 'Harga tertinggi',
        ]"
      />

      <x-select-dropdown
        name="availability"
        :value="$availability"
        label="Semua ketersediaan"
        :options="[
          '' => 'Semua ketersediaan',
          'ready' => 'Ready Stock',
          'preorder' => 'Pre-order',
          'sold_out' => 'Sold Out',
        ]"
      />

      <button type="submit" class="btn btn-accent h-12 justify-center">Terapkan</button>
    </form>

    @php
      $catalogQuery = array_filter([
        'q' => $search,
        'sort' => $sort === 'latest' ? null : $sort,
        'availability' => $availability,
      ], fn ($value) => filled($value));
    @endphp

    <nav class="flex flex-wrap gap-x-8 gap-y-3 border-b border-ink/15 py-8" aria-label="Filter kategori produk">
      <a href="{{ route('shop', $catalogQuery) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === '', 'text-stone' => $activeCategory !== ''])>Semua</a>
      @foreach ($categories as $category)
        <a href="{{ route('shop', [...$catalogQuery, 'category' => $category]) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === $category, 'text-stone' => $activeCategory !== $category])>{{ $category }}</a>
      @endforeach
    </nav>

    @if ($products->isEmpty())
      <div class="py-20 text-center sm:py-24">
        <p class="font-serif text-3xl italic sm:text-4xl">{{ $search !== '' || $activeCategory !== '' ? 'Tidak ada produk yang cocok.' : 'Koleksi akan segera hadir.' }}</p>
        <span class="mx-auto mt-6 block h-px w-20 bg-accent" aria-hidden="true"></span>
        <p class="mx-auto mt-6 max-w-md text-sm leading-6 text-stone">{{ $search !== '' || $activeCategory !== '' ? 'Coba gunakan kata pencarian atau kategori yang berbeda.' : 'Produk akan tampil di sini setelah koleksi resmi ditambahkan melalui dashboard admin.' }}</p>
        @if ($search !== '' || $activeCategory !== '' || $availability !== '' || $sort !== 'latest')
          <a href="{{ route('shop') }}" class="link-line mt-7 inline-block">Reset pencarian</a>
        @endif
      </div>
    @else
      <div id="collection" class="mt-12 flex scroll-mt-28 flex-wrap items-end justify-between gap-5">
        <div>
          <p class="text-[0.62rem] font-semibold uppercase tracking-[0.24em] text-accent">Koleksi</p>
          <h2 class="mt-3 font-serif text-4xl tracking-[-0.035em] sm:text-5xl">Temukan pilihanmu.</h2>
        </div>
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-stone">Menampilkan {{ $products->count() }} dari {{ $totalProducts }} produk</p>
        @if ($search !== '')
          <p>Hasil untuk &ldquo;{{ $search }}&rdquo;</p>
        @endif
      </div>
      <div class="reveal mt-12 grid grid-cols-1 gap-x-7 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($products as $product)
          <x-product-card :p="$product" />
        @endforeach
      </div>

      @if ($search === '' && $products->count() < $totalProducts)
        <div class="mt-16 text-center">
          <a href="{{ route('shop', array_filter(['category' => $activeCategory, 'availability' => $availability, 'sort' => $sort === 'latest' ? null : $sort, 'limit' => $limit + 6], fn ($value) => filled($value))) }}" class="btn border border-ink/20 bg-white/40 hover:border-accent hover:text-accent">
            Muat lebih banyak
            <span class="ml-3 text-accent" aria-hidden="true">+</span>
          </a>
        </div>
      @endif
    @endif
  </section>
</x-layouts.app>
