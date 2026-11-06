<x-layouts.app title="Shop — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 py-10 lg:px-12 lg:py-14">
    <header class="mb-8 border-b border-ink/15 pb-6">
      <p class="text-xs uppercase tracking-[0.12em] text-stone">CALMATS / Shop</p>
      <h1 class="mt-2 font-sans text-4xl font-medium tracking-tight sm:text-5xl">Products</h1>
    </header>

    <div class="grid gap-10 lg:grid-cols-[220px_minmax(0,1fr)] lg:gap-12">
      <aside class="space-y-7">
        <form method="GET" action="{{ route('shop') }}" class="space-y-5">
          <label class="block text-sm" for="product-search">Search product</label>
          <input id="product-search" type="search" name="q" value="{{ $search }}" placeholder="Search product" class="mt-2 h-11 w-full rounded-none border border-ink/20 bg-transparent px-3 text-sm focus:border-ink">
          <input type="hidden" name="category" value="{{ $activeCategory }}">
          <input type="hidden" name="limit" value="{{ $limit }}">
          <label class="block text-sm" for="product-sort">Sort products</label>
          <select id="product-sort" name="sort" class="h-11 w-full rounded-none border border-ink/20 bg-transparent px-3 text-sm">
            <option value="default" @selected($sort === 'default')>Featured</option>
            <option value="latest" @selected($sort === 'latest')>Latest</option>
            <option value="name" @selected($sort === 'name')>Name A-Z</option>
            <option value="price_asc" @selected($sort === 'price_asc')>Price low to high</option>
            <option value="price_desc" @selected($sort === 'price_desc')>Price high to low</option>
          </select>
          <button type="submit" class="min-h-11 border border-ink px-4 text-sm transition-colors hover:bg-ink hover:text-paper">Apply filters</button>
        </form>

        <nav class="space-y-2 border-t border-ink/15 pt-5" aria-label="Filter kategori produk">
          <h2 class="mb-3 text-sm font-semibold">Product type</h2>
          <a href="{{ route('shop', array_filter(['q' => $search, 'sort' => $sort === 'default' ? null : $sort])) }}" @class(['block text-sm transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === '', 'text-stone' => $activeCategory !== ''])>All products</a>
          @foreach ($categories as $category)
            <a href="{{ route('shop', array_filter(['q' => $search, 'sort' => $sort === 'default' ? null : $sort, 'category' => $category])) }}" @class(['block text-sm transition-colors hover:text-accent', 'font-semibold text-ink' => $activeCategory === $category, 'text-stone' => $activeCategory !== $category])>{{ $category }}</a>
          @endforeach
        </nav>
      </aside>

      <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-ink/15 pb-4 text-sm text-stone">
          <p>Menampilkan {{ $products->count() }} dari {{ $totalProducts }} produk</p>
          @if ($search !== '')<p>Hasil untuk “{{ $search }}”</p>@endif
        </div>

        @if ($products->isEmpty())
          <div class="py-16">
            <p class="font-serif text-3xl">{{ $search !== '' || $activeCategory !== '' ? 'Tidak ada produk yang cocok.' : 'The collection will arrive soon.' }}</p>
            <p class="mt-3 max-w-md text-sm leading-6 text-stone">{{ $search !== '' || $activeCategory !== '' ? 'Coba kata pencarian atau kategori yang berbeda.' : 'Produk akan tampil setelah koleksi resmi ditambahkan.' }}</p>
            @if ($search !== '' || $activeCategory !== '' || $sort !== 'default')
              <a href="{{ route('shop') }}" class="mt-5 inline-block text-sm underline underline-offset-4">Reset filters</a>
            @endif
          </div>
        @else
          <div class="grid grid-cols-2 gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($products as $product)
              <x-product-card :p="$product" />
            @endforeach
          </div>

          @if ($products->count() < $totalProducts)
            <div class="mt-12">
              <a href="{{ route('shop', array_filter(['q' => $search, 'category' => $activeCategory, 'sort' => $sort === 'default' ? null : $sort, 'limit' => min($limit + 6, 24)], fn ($value) => filled($value))) }}" class="inline-flex min-h-11 items-center border border-ink px-5 text-sm hover:bg-ink hover:text-paper">Load more</a>
            </div>
          @endif
        @endif
      </div>
    </div>
  </section>
</x-layouts.app>
