@php
  $products = \App\Models\Product::query()
    ->where('is_active', true)
    ->where('is_featured', true)
    ->orderBy('sort_order')
    ->take(6)
    ->get();
@endphp
<section class="mx-auto max-w-[1440px] px-5 py-20 sm:py-24 lg:px-12 lg:py-32">
  <div class="grid items-end gap-8 border-b border-ink/15 pb-10 md:grid-cols-12 md:gap-6">
    <div class="md:col-span-4">
      <p class="eyebrow mb-4">Latest collection</p>
      <h2 class="font-serif text-6xl italic leading-[0.88] tracking-[-0.045em] sm:text-7xl">New<br>Arrivals</h2>
    </div>
    <p class="max-w-xs text-xs font-medium uppercase leading-6 tracking-[0.18em] text-stone md:col-span-4">Potongan terbaru CALMATS untuk bergerak dengan karakter yang tenang dan tegas.</p>
    <div class="md:col-span-4 md:text-right">
      <a href="{{ route('shop') }}" class="btn btn-accent">View collection <span aria-hidden="true" class="ml-4">→</span></a>
    </div>
  </div>

  @if ($products->isNotEmpty())
    <div class="reveal mt-12 grid grid-cols-1 gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($products as $p) <x-product-card :p="$p" /> @endforeach
    </div>
  @else
    <div class="mt-12 rounded-[1.5rem] border border-dashed border-ink/20 px-6 py-20 text-center">
      <p class="font-serif text-3xl italic">The next collection is taking shape.</p>
      <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-stone">Produk terbaru akan tampil di sini setelah koleksi resmi ditambahkan melalui dashboard admin.</p>
    </div>
  @endif
</section>
