<x-layouts.app title="Shop — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-24 pt-10 lg:px-12 lg:pt-16">
    <div class="relative grid overflow-hidden border-b border-ink/15 lg:min-h-[540px] lg:grid-cols-12">
      <div class="relative z-10 flex flex-col justify-center py-14 lg:col-span-8 lg:py-20 lg:pr-14">
        <p class="eyebrow">Collection 01</p>
        <h1 class="mt-7 max-w-4xl font-serif text-6xl leading-[0.92] tracking-[-0.045em] sm:text-7xl lg:text-[6.5rem]">Made for the quiet<br class="hidden sm:block"> movement.</h1>
        <p class="mt-8 max-w-xl text-base leading-7 text-stone">Katalog CALMATS—potongan sederhana, material berkarakter, dan ruang untuk menjadi diri sendiri.</p>
        <p class="mt-12 text-[0.62rem] font-semibold uppercase tracking-[0.28em] text-stone">Designed in Indonesia <span class="mx-2 text-accent">/</span> Built for everyday movement</p>
      </div>

      <div class="shop-quiet-panel relative min-h-[300px] overflow-hidden text-paper lg:col-span-4 lg:min-h-[540px]">
        <div class="absolute inset-0">
          <x-img class="h-full w-full !bg-transparent" src="images/calmats/preview-shop.png" alt="Model mengenakan utility overshirt bergaya CALMATS" label="Foto editorial Shop belum tersedia" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#471014]/80 via-[#751b20]/20 to-black/10"></div>
        <p class="shop-quiet-word absolute inset-x-0 top-1/2 -translate-y-1/2 text-center font-serif text-8xl italic tracking-[-0.06em] sm:text-9xl lg:text-[7rem]">QUIET</p>
        <p class="absolute right-7 top-8 text-[0.58rem] font-semibold uppercase tracking-[0.3em] text-paper/70 [writing-mode:vertical-rl]">Collection 01 / Vol. 01</p>
        <span class="absolute bottom-8 right-8 text-4xl font-light text-paper" aria-hidden="true">→</span>
      </div>

      <svg class="pointer-events-none absolute left-[54%] top-[58%] z-20 hidden h-20 w-[18%] xl:block" viewBox="0 0 300 80" fill="none" aria-hidden="true">
        <path d="M0 43C58 9 112 12 166 42C214 69 255 66 300 34" stroke="currentColor" stroke-width="1.25" class="text-accent/70"/>
      </svg>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 border-b border-ink/15 py-5 text-[0.62rem] font-semibold uppercase tracking-[0.26em] sm:gap-x-12">
      <span>Culture</span><span class="text-accent">/</span>
      <span>Craft</span><span class="text-accent">/</span>
      <span>Movement</span><span class="text-accent">/</span>
      <span>CALMATS</span>
    </div>

    <nav class="flex flex-wrap gap-x-7 gap-y-3 border-b border-ink/15 py-7" aria-label="Filter kategori produk">
      <a href="{{ route('shop') }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === '', 'text-stone' => $activeCategory !== ''])>Semua</a>
      @foreach ($categories as $category)
        <a href="{{ route('shop', ['category' => $category]) }}" @class(['text-xs uppercase tracking-[0.16em] transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === $category, 'text-stone' => $activeCategory !== $category])>{{ $category }}</a>
      @endforeach
    </nav>

    @if ($products->isEmpty())
      <div class="py-20 text-center sm:py-24">
        <p class="font-serif text-3xl italic sm:text-4xl">The collection will arrive soon.</p>
        <span class="mx-auto mt-6 block h-px w-20 bg-accent" aria-hidden="true"></span>
        <p class="mx-auto mt-6 max-w-md text-sm leading-6 text-stone">Produk akan tampil di sini setelah koleksi resmi ditambahkan melalui dashboard admin.</p>
        @if ($activeCategory !== '')
          <a href="{{ route('shop') }}" class="link-line mt-7 inline-block">Lihat semua kategori</a>
        @endif
      </div>
    @else
      <div class="reveal mt-12 grid grid-cols-1 gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($products as $product)
          <x-product-card :p="$product" />
        @endforeach
      </div>
    @endif
  </section>
</x-layouts.app>
