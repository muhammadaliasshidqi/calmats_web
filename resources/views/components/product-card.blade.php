@props(['p'])
<article class="group">
  <a href="{{ route('shop.show', $p['slug']) }}" class="block" aria-label="Lihat detail {{ $p['name'] }}">
    <div class="product-media">
      <x-img class="aspect-[4/3] transition-transform duration-700 group-hover:scale-[1.015]" :src="$p['image'] ?? null" :alt="$p['name']" label="Foto produk belum tersedia" />
    </div>
  </a>
  <div class="mt-5 flex items-start justify-between gap-4">
    <div>
      <h3 class="text-xs font-semibold uppercase tracking-[0.12em]">{{ $p['name'] }}</h3>
      <p class="mt-1.5 text-xs text-stone">{{ $p['stock_label'] }}</p>
    </div>
    <p class="whitespace-nowrap text-xs font-medium tracking-[0.08em]">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
  </div>
  <div class="mt-4 flex items-center justify-between gap-3">
    <a href="{{ route('shop.show', $p['slug']) }}" class="link-line inline-block">Lihat detail</a>
    <button
      type="button"
      class="text-xs font-medium uppercase tracking-[0.14em] text-accent transition-colors hover:text-ink"
      x-on:click="$store.cart.add(@js(['slug' => $p['slug'], 'name' => $p['name'], 'price' => $p['price'], 'image' => $p['image'] ?? null]), @js($p['sizes'][0] ?? 'One size'))"
    >+ Keranjang</button>
  </div>
</article>
