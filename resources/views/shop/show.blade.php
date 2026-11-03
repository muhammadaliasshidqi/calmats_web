<x-layouts.app :title="$product['name'].' — CALMATS'">
  <section class="mx-auto max-w-7xl px-5 py-12 lg:px-10 lg:py-20">
    <a href="{{ route('shop') }}" class="link-line inline-block">Kembali ke Shop</a>

    <div class="mt-10 grid gap-12 lg:grid-cols-2 lg:gap-20">
      <x-img class="aspect-[3/4]" :src="$product['image']" :alt="$product['name']" label="Foto produk belum tersedia" />

      <div class="lg:pt-10" x-data="{ size: @js($product['sizes'][0]), added: false }">
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">{{ $product['category'] }}</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight sm:text-6xl">{{ $product['name'] }}</h1>
        <p class="mt-5 text-lg">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
        <p class="mt-8 max-w-lg leading-7 text-stone">{{ $product['description'] }}</p>

        <dl class="mt-9 grid max-w-lg grid-cols-2 gap-5 border-y border-mist py-6 text-sm">
          <div><dt class="text-stone">Material</dt><dd class="mt-1">{{ $product['material'] }}</dd></div>
          <div><dt class="text-stone">Ketersediaan</dt><dd class="mt-1">{{ $product['stock_label'] }}</dd></div>
        </dl>

        <fieldset class="mt-9">
          <legend class="text-xs font-medium uppercase tracking-[0.16em]">Pilih ukuran</legend>
          <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($product['sizes'] as $size)
              <button type="button" x-on:click="size = '{{ $size }}'; added = false" :class="size === '{{ $size }}' ? 'bg-ink text-paper' : 'border border-mist hover:border-ink'" class="min-w-12 px-4 py-3 text-sm transition-colors">{{ $size }}</button>
            @endforeach
          </div>
        </fieldset>

        <button type="button" class="btn btn-solid mt-9 w-full max-w-lg" x-on:click="$store.cart.add(@js(['slug' => $product['slug'], 'name' => $product['name'], 'price' => $product['price'], 'image' => $product['image']]), size); added = true" x-text="added ? 'Ditambahkan ke keranjang' : 'Tambah ke keranjang'">Tambah ke keranjang</button>
        <p class="mt-4 max-w-lg text-xs leading-5 text-stone">Produk ini merupakan data konsep untuk demonstrasi website, bukan penawaran penjualan resmi.</p>
      </div>
    </div>
  </section>
</x-layouts.app>
