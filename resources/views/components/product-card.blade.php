@props(['p'])
<article>
  <a href="{{ route('shop.show', $p['slug']) }}" class="block" aria-label="Lihat detail {{ $p['name'] }}">
    <x-img class="aspect-[3/4]" :src="$p['image'] ?? null" :alt="$p['name']" label="Foto produk (placeholder)" />
  </a>
  <div class="mt-4 flex items-start justify-between gap-4">
    <div>
      <h3 class="text-base font-medium">{{ $p['name'] }}</h3>
      <p class="mt-1 text-sm text-stone">{{ $p['stock'] }}</p>
    </div>
    <p class="whitespace-nowrap text-sm font-medium">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
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
