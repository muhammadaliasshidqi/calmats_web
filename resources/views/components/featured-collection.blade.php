@php
  $products = \App\Models\Product::query()
    ->where('is_active', true)
    ->where('is_featured', true)
    ->orderBy('sort_order')
    ->take(4)
    ->get();
@endphp
<section class="mx-auto max-w-7xl px-5 py-24 lg:px-10">
  <div class="flex items-end justify-between gap-6">
    <div>
      <h2 class="font-serif text-4xl tracking-tight sm:text-5xl">Koleksi terbaru</h2>
      <p class="mt-3 text-sm text-stone">Siluet esensial untuk bergerak dengan ritme sendiri.</p>
    </div>
    <a href="{{ route('shop') }}" class="link-line hidden sm:inline-block">Lihat semua</a>
  </div>
  <div class="reveal mt-12 grid grid-cols-2 gap-x-5 gap-y-12 lg:grid-cols-4">
    @foreach ($products as $p) <x-product-card :p="$p" /> @endforeach
  </div>
  <a href="{{ route('shop') }}" class="link-line mt-12 inline-block sm:hidden">Lihat semua</a>
</section>
