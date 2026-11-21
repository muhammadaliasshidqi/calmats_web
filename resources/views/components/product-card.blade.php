@props(['p'])
@php
  $soldOut = method_exists($p, 'isSoldOut') && $p->isSoldOut();
@endphp
<article class="catalog-card group flex h-full flex-col">
  <a href="{{ route('shop.show', $p['slug']) }}" class="block" aria-label="Lihat detail {{ $p['name'] }}">
    <div class="product-media relative rounded-[1.75rem]">
      <x-img class="aspect-[4/5] rounded-[1.75rem] transition-transform duration-700 ease-out group-hover:scale-[1.025]" :src="$p['image'] ?? null" :alt="$p['name']" label="Foto produk belum tersedia" />
      <span class="absolute left-4 top-4 rounded-full border border-white/30 bg-ink/75 px-3 py-1.5 text-[0.58rem] font-semibold uppercase tracking-[0.18em] text-paper backdrop-blur-md">{{ $p['category'] }}</span>
      @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price'])
        <span class="absolute right-4 top-4 rounded-full bg-accent px-3 py-1.5 text-[0.58rem] font-semibold uppercase tracking-[0.16em] text-paper">Promo</span>
      @endif
      <span class="absolute bottom-4 right-4 grid h-10 w-10 translate-y-2 place-items-center rounded-full bg-paper text-ink opacity-0 shadow-lg transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100" aria-hidden="true">↗</span>
    </div>
  </a>
  <div class="mt-6 flex items-start justify-between gap-5 px-1">
    <div>
      <h3 class="font-serif text-2xl leading-tight tracking-[-0.025em]">{{ $p['name'] }}</h3>
    </div>
    @unless ($soldOut)
    <div class="text-right">
      <p class="whitespace-nowrap text-sm font-semibold tracking-[0.04em] @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price']) text-accent @endif">Rp {{ number_format($p['price'], 0, ',', '.') }}</p>
      @if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price'])
        <p class="mt-1 whitespace-nowrap text-[0.65rem] text-stone line-through">Rp {{ number_format($p['compare_at_price'], 0, ',', '.') }}</p>
      @endif
    </div>
    @endunless
  </div>
  <div class="mt-auto px-1 pt-6">
    <a href="{{ route('shop.show', $p['slug']) }}" class="btn btn-line w-full px-4">{{ $soldOut ? 'Lihat detail produk' : 'Pilih warna & ukuran' }}</a>
  </div>
</article>
