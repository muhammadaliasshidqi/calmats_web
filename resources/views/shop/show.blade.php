@php
  $sizes = $product->sizes ?: ['One size'];
  $colors = collect($product->colors ?: [['name' => 'Default', 'hex' => '#191613']])
    ->map(fn ($color) => [
      'name' => $color['name'] ?? 'Default',
      'hex' => $color['hex'] ?? '#191613',
    ])
    ->values()
    ->all();
  $variantStock = collect($product->variant_stock ?: [])
    ->map(fn ($variant) => [
      'color' => $variant['color'] ?? '',
      'size' => $variant['size'] ?? '',
      'stock' => (int) ($variant['stock'] ?? 0),
    ])
    ->values()
    ->all();
  $isPromo = filled($product->compare_at_price) && $product->compare_at_price > $product->price;
  $discount = $isPromo ? (int) round((1 - ($product->price / $product->compare_at_price)) * 100) : 0;
@endphp

<x-layouts.app :title="$product->name.' — CALMATS'">
  <section class="mx-auto max-w-[1440px] px-5 pb-28 pt-8 lg:px-12 lg:pt-12">
    <nav class="flex items-center gap-3 text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-stone" aria-label="Breadcrumb">
      <a href="{{ route('shop') }}" class="transition-colors hover:text-accent">Shop</a>
      <span class="text-stone/40">/</span>
      <span class="text-ink">{{ $product->category }}</span>
    </nav>

    <div class="mt-8 grid items-start gap-10 lg:grid-cols-[minmax(0,1.06fr)_minmax(25rem,.94fr)] lg:gap-12 xl:gap-20">
      <div class="product-detail-media relative rounded-[2.25rem] bg-white/45 p-3 sm:p-5 lg:sticky lg:top-28">
        <x-img class="aspect-[4/5] rounded-[1.75rem]" :src="$product->image" :alt="$product->name" label="Foto produk belum tersedia" />
        <div class="absolute left-7 top-7 rounded-full border border-white/35 bg-ink/75 px-4 py-2 text-[0.58rem] font-semibold uppercase tracking-[0.2em] text-paper backdrop-blur-md">CALMATS / {{ $product->category }}</div>
        <div class="absolute bottom-8 left-8 right-8 flex items-end justify-between text-paper drop-shadow-md">
          <span class="text-[0.58rem] font-semibold uppercase tracking-[0.24em]">Collection 01</span>
          <span class="font-serif text-3xl italic">C/M</span>
        </div>
      </div>

      <div
        class="product-detail-panel rounded-[2.25rem] p-6 sm:p-9 lg:p-10 xl:p-12"
        x-data="{
          color: @js($colors[0]['name']),
          size: @js($sizes[0]),
          colors: @js($colors),
          variants: @js($variantStock),
          fallbackStock: @js($product->stock_quantity),
          added: false,
          stockFor(size = this.size, color = this.color) {
            const variant = this.variants.find((item) => item.color === color && item.size === size);
            if (variant) return Number(variant.stock);
            if (this.variants.length > 0) return 0;
            return this.fallbackStock === null ? null : Number(this.fallbackStock);
          },
          isAvailable(size = this.size, color = this.color) {
            const stock = this.stockFor(size, color);
            return stock === null || stock > 0;
          },
          chooseColor(color) {
            this.color = color;
            const availableSize = @js($sizes).find((size) => this.isAvailable(size, color));
            if (availableSize) this.size = availableSize;
            this.added = false;
          },
          stockText() {
            const stock = this.stockFor();
            if (stock === null) return @js($product->stock_label);
            if (stock <= 0) return 'Stok habis untuk varian ini';
            return `${stock} item tersedia`;
          }
        }"
      >
        <div class="flex items-center justify-between gap-5">
          <p class="text-[0.62rem] font-semibold uppercase tracking-[0.24em] text-accent">{{ $product->category }}</p>
          <span class="rounded-full border border-ink/10 px-3 py-1.5 text-[0.58rem] font-semibold uppercase tracking-[0.16em] text-stone">Ready to wear</span>
        </div>
        <h1 class="mt-7 font-serif text-5xl leading-[0.92] tracking-[-0.045em] sm:text-6xl xl:text-7xl">{{ $product->name }}</h1>

        <div class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-2 border-b border-ink/10 pb-7">
          <p class="text-lg font-semibold tracking-[0.04em] text-accent">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
          @if ($isPromo)
            <p class="text-sm text-stone line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
            <span class="rounded-full bg-accent/10 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.14em] text-accent">Promo {{ $discount }}%</span>
          @endif
        </div>

        <p class="mt-7 max-w-xl text-base leading-8 text-stone">{{ $product->description }}</p>

        <dl class="mt-8 grid gap-4 rounded-[1.25rem] bg-ink/[.035] p-5 text-sm sm:grid-cols-2">
          <div><dt class="text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-stone">Material</dt><dd class="mt-2 font-medium">{{ $product->material }}</dd></div>
          <div><dt class="text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-stone">Ketersediaan</dt><dd class="mt-2 font-medium" x-text="stockText()"></dd></div>
        </dl>

        <fieldset class="mt-9 border-t border-ink/10 pt-8">
          <legend class="mx-auto text-[0.62rem] font-semibold uppercase tracking-[0.18em]">Pilih warna: <span class="text-accent" x-text="color"></span></legend>
          <div class="mt-4 flex flex-wrap justify-center gap-3">
            @foreach ($colors as $color)
              <button type="button" x-on:click="chooseColor(@js($color['name']))" :aria-pressed="color === @js($color['name'])" :class="color === @js($color['name']) ? 'border-ink bg-ink text-paper ring-2 ring-ink/10' : 'border-ink/15 bg-paper hover:border-ink'" class="inline-flex min-w-32 items-center justify-center gap-2 rounded-full border px-5 py-3 text-sm transition">
                <span class="h-4 w-4 rounded-full border border-paper/70 shadow-sm" style="background-color: {{ $color['hex'] }}"></span>
                {{ $color['name'] }}
              </button>
            @endforeach
          </div>
        </fieldset>

        <fieldset class="mt-8">
          <legend class="text-[0.62rem] font-semibold uppercase tracking-[0.18em]">Pilih ukuran</legend>
          <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($sizes as $size)
              <button type="button" x-on:click="size = @js($size); added = false" :disabled="!isAvailable(@js($size), color)" :class="size === @js($size) ? 'border-ink bg-ink text-paper shadow-md' : 'border-ink/15 bg-paper hover:border-ink'" class="min-w-14 rounded-full border px-4 py-3 text-sm transition-all disabled:cursor-not-allowed disabled:border-mist disabled:bg-mist/60 disabled:text-stone/50">{{ $size }}</button>
            @endforeach
          </div>
        </fieldset>

        <button type="button" class="btn btn-accent mt-9 w-full py-4 disabled:cursor-not-allowed disabled:opacity-45" :disabled="!isAvailable()" x-on:click="$store.cart.add({ ...@js(['slug' => $product->slug, 'name' => $product->name, 'price' => $product->price, 'image' => $product->image]), stock: stockFor() }, size, color); added = true" x-text="!isAvailable() ? 'Stok varian habis' : (added ? 'Ditambahkan ke keranjang ✓' : 'Tambah ke keranjang')">Tambah ke keranjang</button>
        <p class="mt-4 text-center text-xs leading-5 text-stone">Pilihan warna dan ukuran akan disimpan bersama produk di keranjang.</p>
      </div>
    </div>
  </section>
</x-layouts.app>
