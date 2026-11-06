@props(['p'])
@php
  $variantRows = collect($p['variant_stock'] ?? []);
  $quickVariant = $variantRows->first(fn ($variant) => (int) ($variant['stock'] ?? 0) > 0);
  $quickSize = $quickVariant['size'] ?? ($p['sizes'][0] ?? 'One size');
  $quickColor = $quickVariant['color'] ?? data_get($p, 'colors.0.name', 'Default');
  $quickStock = $quickVariant['stock'] ?? ($p['stock_quantity'] ?? null);
  $quickAvailable = $variantRows->isNotEmpty()
    ? filled($quickVariant)
    : (($p['stock_quantity'] ?? null) === null || (int) $p['stock_quantity'] > 0);
@endphp
<article class="group">
  <a href="{{ route('shop.show', $p['slug']) }}" class="block" aria-label="Lihat detail {{ $p['name'] }}">
    <div class="product-media relative">
      <x-img class="aspect-[4/5] !rounded-none transition-transform duration-300 group-hover:scale-[1.01]" :src="$p['image'] ?? null" :alt="$p['name']" label="Foto produk belum tersedia" />
      @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price'])
        <span class="absolute left-4 top-4 rounded-full bg-accent px-3 py-1.5 text-[0.58rem] font-semibold uppercase tracking-[0.14em] text-paper">Promo</span>
      @endif
    </div>
  </a>
  <div class="mt-5 flex items-start justify-between gap-4">
    <div>
      <h3 class="text-xs font-semibold uppercase tracking-[0.12em]">{{ $p['name'] }}</h3>
      <p class="mt-1.5 text-xs text-stone">{{ $p['stock_label'] }}</p>
    </div>
    <div class="text-right">
      <p class="whitespace-nowrap text-xs font-semibold tracking-[0.08em] @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price']) text-accent @endif">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
      @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price'])
        <p class="mt-1 whitespace-nowrap text-[0.65rem] text-stone line-through">Rp {{ number_format($p['compare_at_price'], 0, ',', '.') }}</p>
      @endif
    </div>
  </div>
  <div class="mt-4 flex items-center justify-between gap-3">
    <a href="{{ route('shop.show', $p['slug']) }}" class="link-line inline-block">Lihat detail</a>
    <button
      type="button"
      @disabled(!$quickAvailable)
      class="text-xs font-medium uppercase tracking-[0.14em] text-accent transition-colors hover:text-ink disabled:cursor-not-allowed disabled:text-stone/50"
      x-on:click="$store.cart.add(@js(['slug' => $p['slug'], 'name' => $p['name'], 'price' => $p['price'], 'image' => $p['image'] ?? null, 'stock' => $quickStock]), @js($quickSize), @js($quickColor))"
    >{{ $quickAvailable ? '+ Keranjang' : 'Stok habis' }}</button>
  </div>
</article>
