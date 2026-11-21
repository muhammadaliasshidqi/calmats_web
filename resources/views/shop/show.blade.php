@php
  $sizes = $product->sizes ?: ['One size'];
  $colors = collect($product->colors ?: [['name' => 'Default', 'hex' => '#191613']])
    ->map(fn ($color) => [
      'name' => $color['name'] ?? 'Default',
      'hex' => $color['hex'] ?? '#191613',
      'image' => $color['image'] ?? null,
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
  $isSoldOut = $product->isSoldOut();
@endphp

<x-layouts.app :title="$product->name.' — CALMATS'">
  <section class="mx-auto max-w-[1440px] px-5 pb-28 pt-8 lg:px-12 lg:pt-12">
    <nav class="flex items-center gap-3 text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-stone" aria-label="Breadcrumb">
      <a href="{{ route('shop') }}" class="transition-colors hover:text-accent">Shop</a>
      <span class="text-stone/40">/</span>
      <span class="text-ink">{{ $product->category }}</span>
    </nav>

    <div
      class="mt-8 grid items-start gap-10 lg:grid-cols-[minmax(0,1.06fr)_minmax(25rem,.94fr)] lg:gap-12 xl:gap-20"
      x-data="{
        color: null,
        size: null,
        quantity: 1,
        colors: @js($colors),
        variants: @js($variantStock),
        isPreorder: @js($product->is_preorder),
        fallbackImage: @js($product->image),
        fallbackStock: @js($product->stock_quantity),
        imageOk: true,
        added: false,
        adding: false,
        variantNotice: '',
        selectedImage() {
          if (!this.color) return this.fallbackImage;
          return this.colors.find((item) => item.name === this.color)?.image || this.fallbackImage;
        },
        selectedImageUrl() {
          const value = this.selectedImage();
          if (!value) return '';
          if (value.startsWith('http://') || value.startsWith('https://') || value.startsWith('/')) return value;
          return value.startsWith('images/') ? `/${value}` : `/storage/${value}`;
        },
        stockFor(size = this.size, color = this.color) {
          if (!size || !color) return null;
          const variant = this.variants.find((item) => item.color === color && item.size === size);
          if (variant) return Number(variant.stock);
          if (this.variants.length > 0) return 0;
          return this.fallbackStock === null ? null : Number(this.fallbackStock);
        },
        isAvailable(size = this.size, color = this.color) {
          if (!size) return false;
          if (!color) return this.variants.some((item) => item.size === size && Number(item.stock) > 0);
          const stock = this.stockFor(size, color);
          return stock === null || stock > 0;
        },
        chooseColor(color) {
          this.color = color;
          this.size = null;
          this.quantity = 1;
          this.imageOk = Boolean(this.selectedImage());
          this.added = false;
        },
        chooseSize(size) {
          this.size = size;
          this.quantity = 1;
          this.added = false;
        },
        resetChoices() {
          this.color = null;
          this.size = null;
          this.quantity = 1;
          this.imageOk = Boolean(this.fallbackImage);
          this.added = false;
          this.variantNotice = '';
        },
        canAdd() {
          return Boolean(this.color && this.size && this.isAvailable());
        },
        maxQuantity() {
          const stock = this.stockFor();
          return stock === null ? 20 : Math.min(20, stock);
        },
        stockText() {
          if (!this.color && !this.size) return 'Pilih warna dan ukuran';
          if (!this.color || !this.size) return 'Lengkapi pilihan varian';
          const stock = this.stockFor();
          if (stock === null) return @js($product->stock_label);
          if (stock <= 0 && this.isPreorder) return 'Pre-order — hubungi admin untuk informasi';
          if (stock <= 0) return 'Sold Out untuk varian ini';
          return `${stock} item tersedia`;
        },
        async addToCart() {
          if (!this.canAdd()) return;
          this.adding = true;
          this.variantNotice = '';

          try {
            const response = await fetch(@js(route('shop.variant.validate', $product)), {
              method: 'POST',
              credentials: 'same-origin',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
              },
              body: JSON.stringify({ size: this.size, color: this.color, quantity: this.quantity }),
            });
            const payload = await response.json();

            if (!response.ok) {
              this.variantNotice = payload.message ?? 'Varian tidak dapat ditambahkan.';
              return;
            }

            this.$store.cart.add(payload.item, this.size, this.color, payload.quantity);
            this.quantity = payload.quantity;
            this.added = true;
          } catch {
            this.variantNotice = 'Data varian belum dapat diperiksa. Coba lagi.';
          } finally {
            this.adding = false;
          }
        }
      }"
    >
      <div class="product-detail-media relative rounded-[2.25rem] bg-white/45 p-3 sm:p-5 lg:sticky lg:top-28">
        <div class="relative aspect-[4/5] overflow-hidden rounded-[1.75rem] bg-mist">
          <template x-if="selectedImage()">
            <img
              :src="selectedImageUrl()"
              :alt="`${@js($product->name)} warna ${color}`"
              width="1600"
              height="2000"
              class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300"
              x-show="imageOk"
              x-on:load="imageOk = true"
              x-on:error="imageOk = false"
            >
          </template>
          <div x-show="!selectedImage() || !imageOk" class="absolute inset-0 grid place-items-center px-4 text-center text-xs text-stone">Foto warna belum tersedia</div>
        </div>
        <div class="absolute left-7 top-7 rounded-full border border-white/35 bg-ink/75 px-4 py-2 text-[0.58rem] font-semibold uppercase tracking-[0.2em] text-paper backdrop-blur-md">CALMATS / {{ $product->category }}</div>
        <div class="absolute bottom-8 left-8 right-8 flex items-end justify-between text-paper drop-shadow-md">
          <span class="text-[0.58rem] font-semibold uppercase tracking-[0.24em]">Collection 01</span>
          <span class="font-serif text-3xl italic">C/M</span>
        </div>
      </div>

      <div class="product-detail-panel rounded-[2.25rem] p-6 sm:p-9 lg:p-10 xl:p-12">
        <div class="flex items-center justify-between gap-5">
          <p class="text-[0.62rem] font-semibold uppercase tracking-[0.24em] text-accent">{{ $product->category }}</p>
          <span class="rounded-full border border-ink/10 px-3 py-1.5 text-[0.58rem] font-semibold uppercase tracking-[0.16em] text-stone">{{ $isSoldOut ? 'Sold Out' : ($product->is_preorder ? 'Ready / Pre-order' : 'Ready Stock') }}</span>
        </div>
        <h1 class="mt-7 font-serif text-5xl leading-[0.92] tracking-[-0.045em] sm:text-6xl xl:text-7xl">{{ $product->name }}</h1>

        @unless ($isSoldOut)
        <div class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-2 border-b border-ink/10 pb-7">
          <p class="text-lg font-semibold tracking-[0.04em] text-accent">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
          @if ($isPromo)
            <p class="text-sm text-stone line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
            <span class="rounded-full bg-accent/10 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.14em] text-accent">Promo {{ $discount }}%</span>
          @endif
        </div>
        @endunless

        <p class="mt-7 max-w-xl text-base leading-8 text-stone">{{ $product->description }}</p>

        <dl class="mt-8 grid gap-4 rounded-[1.25rem] bg-ink/[.035] p-5 text-sm sm:grid-cols-2">
          <div><dt class="text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-stone">Material</dt><dd class="mt-2 font-medium">{{ $product->material }}</dd></div>
          <div><dt class="text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-stone">Ketersediaan</dt><dd class="mt-2 font-medium" x-text="stockText()"></dd></div>
        </dl>

        <fieldset class="mt-9 border-t border-ink/10 pt-8">
          <div class="flex items-center justify-between gap-4">
            <legend class="text-[0.62rem] font-semibold uppercase tracking-[0.18em]">Pilih warna: <span class="text-accent" x-text="color ?? 'belum dipilih'"></span></legend>
            <button type="button" x-show="color || size" x-on:click="resetChoices()" class="text-xs font-semibold text-stone underline-offset-4 hover:text-accent hover:underline">Reset</button>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-3 sm:flex sm:flex-wrap sm:justify-center">
            @foreach ($colors as $color)
              <button type="button" x-on:click="chooseColor(@js($color['name']))" :aria-pressed="color === @js($color['name'])" :class="color === @js($color['name']) ? 'border-ink bg-ink text-paper ring-2 ring-ink/10' : 'border-ink/15 bg-paper hover:border-ink'" class="inline-flex min-w-0 items-center justify-center gap-2 rounded-full border px-3 py-3 text-sm transition sm:min-w-32 sm:px-5">
                <span class="h-4 w-4 rounded-full border border-paper/70 shadow-sm" style="background-color: {{ $color['hex'] }}"></span>
                {{ $color['name'] }}
              </button>
            @endforeach
          </div>
        </fieldset>

        <fieldset class="mt-8">
          <legend class="text-[0.62rem] font-semibold uppercase tracking-[0.18em]">Pilih ukuran</legend>
          <div class="mt-4 grid grid-cols-3 gap-2 sm:flex sm:flex-wrap">
            @foreach ($sizes as $size)
              <button type="button" x-on:click="chooseSize(@js($size))" :disabled="!isAvailable(@js($size), color)" :class="size === @js($size) ? 'border-ink bg-ink text-paper shadow-md' : 'border-ink/15 bg-paper hover:border-ink'" class="min-w-14 rounded-full border px-4 py-3 text-sm transition-all disabled:cursor-not-allowed disabled:border-mist disabled:bg-mist/60 disabled:text-stone/50">{{ $size }}</button>
            @endforeach
          </div>
        </fieldset>

        @unless ($isSoldOut)
          <div class="mt-8 flex items-center justify-between gap-4 rounded-[1.25rem] bg-ink/[.035] p-4">
            <span class="text-sm font-semibold">Jumlah</span>
            <div class="inline-grid grid-cols-[2.75rem_3rem_2.75rem] overflow-hidden rounded-full border border-ink/15 bg-paper">
              <button type="button" class="h-11 text-lg disabled:opacity-30" :disabled="quantity <= 1" x-on:click="quantity--">−</button>
              <span class="grid h-11 place-items-center border-x border-ink/10 text-sm font-semibold" x-text="quantity"></span>
              <button type="button" class="h-11 text-lg disabled:opacity-30" :disabled="!canAdd() || quantity >= maxQuantity()" x-on:click="quantity++">+</button>
            </div>
          </div>

          <button type="button" class="btn btn-accent mt-5 w-full py-4 disabled:cursor-not-allowed disabled:opacity-45" :disabled="!canAdd() || adding" x-on:click="addToCart()" x-text="adding ? 'Memeriksa varian...' : (!color || !size ? 'Pilih warna dan ukuran' : (!isAvailable() ? (isPreorder ? 'Varian Pre-order — hubungi admin' : 'Varian Sold Out') : (added ? 'Ditambahkan ke keranjang ✓' : 'Tambah ke keranjang')))">Tambah ke keranjang</button>
          <p x-show="variantNotice" x-cloak x-text="variantNotice" class="mt-3 text-center text-sm font-medium text-accent" role="alert"></p>
        @endunless
        <p class="mt-4 text-center text-xs leading-5 text-stone">Pilihan warna dan ukuran akan disimpan bersama produk di keranjang.</p>
      </div>
    </div>
  </section>
</x-layouts.app>
