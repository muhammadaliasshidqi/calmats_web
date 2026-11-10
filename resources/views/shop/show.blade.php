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
  <section class="mx-auto max-w-7xl px-5 py-12 lg:px-10 lg:py-20">
    <a href="{{ route('shop') }}" class="link-line inline-block">Kembali ke Shop</a>

    <div class="mt-10 grid gap-12 lg:grid-cols-2 lg:gap-20">
      <div class="relative">
        <x-img class="aspect-[3/4]" :src="$product->image" :alt="$product->name" label="Foto produk belum tersedia" />
      </div>

      <div
        class="lg:pt-6"
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
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">{{ $product->category }}</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight sm:text-6xl">{{ $product->name }}</h1>

        <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2">
          <p class="text-xl font-semibold text-accent">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
          @if ($isPromo)
            <p class="text-sm text-stone line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
            <span class="rounded-full bg-accent/10 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.14em] text-accent">Promo {{ $discount }}%</span>
          @endif
        </div>

        <p class="mt-8 max-w-lg leading-7 text-stone">{{ $product->description }}</p>

        <dl class="mt-9 grid max-w-lg gap-5 border-y border-mist py-6 text-sm sm:grid-cols-2">
          <div><dt class="text-stone">Material</dt><dd class="mt-1">{{ $product->material }}</dd></div>
          <div><dt class="text-stone">Ketersediaan</dt><dd class="mt-1" x-text="stockText()"></dd></div>
        </dl>

        <fieldset class="mt-9">
          <legend class="text-xs font-medium uppercase tracking-[0.16em]">Pilih warna: <span class="text-accent" x-text="color"></span></legend>
          <div class="mt-4 flex flex-wrap gap-3">
            @foreach ($colors as $color)
              <button type="button" x-on:click="chooseColor(@js($color['name']))" :aria-pressed="color === @js($color['name'])" :class="color === @js($color['name']) ? 'border-ink ring-2 ring-ink/15' : 'border-mist hover:border-ink'" class="inline-flex items-center gap-2 rounded-full border bg-paper px-4 py-2.5 text-sm transition">
                <span class="h-4 w-4 rounded-full border border-black/10" style="background-color: {{ $color['hex'] }}"></span>
                {{ $color['name'] }}
              </button>
            @endforeach
          </div>
        </fieldset>

        <fieldset class="mt-8">
          <legend class="text-xs font-medium uppercase tracking-[0.16em]">Pilih ukuran</legend>
          <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($sizes as $size)
              <button type="button" x-on:click="size = @js($size); added = false" :disabled="!isAvailable(@js($size), color)" :class="size === @js($size) ? 'bg-ink text-paper' : 'border border-mist hover:border-ink'" class="min-w-12 rounded-full px-4 py-3 text-sm transition-colors disabled:cursor-not-allowed disabled:border-mist disabled:bg-mist/60 disabled:text-stone/50">{{ $size }}</button>
            @endforeach
          </div>
        </fieldset>

        <button type="button" class="btn btn-solid mt-9 w-full max-w-lg disabled:cursor-not-allowed disabled:opacity-45" :disabled="!isAvailable()" x-on:click="$store.cart.add({ ...@js(['slug' => $product->slug, 'name' => $product->name, 'price' => $product->price, 'image' => $product->image]), stock: stockFor() }, size, color); added = true" x-text="!isAvailable() ? 'Stok varian habis' : (added ? 'Ditambahkan ke keranjang' : 'Tambah ke keranjang')">Tambah ke keranjang</button>
        <p class="mt-4 max-w-lg text-xs leading-5 text-stone">Pilihan warna dan ukuran akan disimpan bersama produk di keranjang.</p>
      </div>
    </div>
  </section>
</x-layouts.app>
