<x-layouts.app title="Cart — CALMATS">
  <section class="mx-auto min-h-[65vh] max-w-[1440px] px-5 py-14 lg:px-12 lg:py-20" x-data="{ format(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value) } }">
    <header class="grid items-end gap-8 border-b border-ink/15 pb-10 md:grid-cols-12">
      <div class="md:col-span-8">
        <p class="eyebrow">Your selection / 01</p>
        <h1 class="mt-5 font-serif text-6xl italic tracking-[-0.045em] sm:text-7xl">Keranjang</h1>
        <p class="mt-5 max-w-lg text-sm leading-6 text-stone">Periksa kembali pilihanmu sebelum melanjutkan ke tahap pemesanan.</p>
      </div>
      <div class="md:col-span-4 md:text-right">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-stone"><span x-text="$store.cart.count">0</span> item tersimpan</p>
      </div>
    </header>

    <div x-show="$store.cart.items.length === 0" class="mt-10 overflow-hidden rounded-[1.75rem] bg-[#ebe4db] shadow-[0_20px_55px_rgba(40,31,25,.12)]">
      <div class="grid min-h-[480px] md:grid-cols-12">
        <div class="flex flex-col justify-center px-7 py-14 sm:px-12 md:col-span-7 lg:px-16">
          <p class="text-[0.65rem] font-semibold uppercase tracking-[0.25em] text-accent">Nothing here, yet</p>
          <h2 class="mt-6 max-w-xl font-serif text-5xl italic leading-[0.95] tracking-[-0.04em] sm:text-6xl">Keranjangmu masih kosong.</h2>
          <p class="mt-6 max-w-md leading-7 text-stone">Temukan siluet yang cocok dengan ritmemu dan simpan pilihanmu di sini.</p>
          <div class="mt-9 flex flex-wrap items-center gap-5">
            <a href="{{ route('shop') }}" class="btn btn-accent">Explore collection <span class="ml-4" aria-hidden="true">→</span></a>
            <span class="text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-stone">CALMATS / Collection 01</span>
          </div>
        </div>
        <div class="relative min-h-[360px] md:col-span-5">
          <x-img class="h-full w-full !rounded-none !bg-transparent" src="images/calmats/preview-shop.png" alt="Editorial CALMATS untuk halaman keranjang" label="Foto editorial belum tersedia" />
          <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4a1014]/55 via-transparent to-black/10"></div>
          <p class="pointer-events-none absolute bottom-8 right-8 font-serif text-5xl italic text-paper/80">Quiet choices.</p>
        </div>
      </div>
    </div>

    <div x-show="$store.cart.items.length > 0" x-cloak class="grid gap-8 py-10 lg:grid-cols-[1fr_24rem] lg:gap-12">
      <div>
        <template x-for="item in $store.cart.items" :key="`${item.slug}-${item.size}`">
          <article class="mb-4 grid grid-cols-[5.5rem_1fr] gap-5 rounded-[1.5rem] border border-ink/10 bg-white/45 p-4 shadow-[0_12px_32px_rgba(40,31,25,.08)] sm:grid-cols-[8rem_1fr_auto] sm:p-5">
            <div class="grid aspect-[3/4] place-items-center rounded-[1.25rem] bg-mist px-2 text-center text-[10px] font-semibold uppercase tracking-[0.18em] text-stone">CALMATS</div>
            <div class="self-center">
              <p class="text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-accent">Selected item</p>
              <a :href="`{{ url('/shop') }}/${item.slug}`" class="mt-2 block font-serif text-2xl italic transition-colors hover:text-accent" x-text="item.name"></a>
              <p class="mt-2 text-sm text-stone">Ukuran: <span x-text="item.size"></span></p>
              <p class="mt-3 text-sm font-medium" x-text="format(item.price)"></p>
              <div class="mt-4 flex items-center gap-3">
                <label class="text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-stone" :for="`qty-${item.slug}-${item.size}`">Jumlah</label>
                <input :id="`qty-${item.slug}-${item.size}`" type="number" min="1" max="20" :value="item.quantity" x-on:change="$store.cart.update(item.slug, item.size, $event.target.value)" class="w-16 border border-mist bg-paper px-2 py-1.5 text-center text-sm">
              </div>
            </div>
            <div class="col-start-2 flex items-end justify-between gap-5 sm:col-start-3 sm:row-start-1 sm:flex-col sm:text-right">
              <p class="font-medium" x-text="format(item.price * item.quantity)"></p>
              <button type="button" x-on:click="$store.cart.remove(item.slug, item.size)" class="text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-stone underline underline-offset-4 transition-colors hover:text-accent">Hapus</button>
            </div>
          </article>
        </template>
      </div>

      <aside class="h-fit rounded-[1.75rem] bg-ink p-8 text-paper shadow-[0_20px_55px_rgba(25,22,19,.2)]">
        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-paper/50">Order review</p>
        <h2 class="mt-4 font-serif text-4xl italic">Ringkasan</h2>
        <dl class="mt-8 space-y-5 text-sm">
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Subtotal</dt><dd x-text="format($store.cart.total)"></dd></div>
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Pengiriman</dt><dd>Dihitung nanti</dd></div>
          <div class="flex justify-between gap-5 border-t border-paper/20 pt-5 text-base font-medium"><dt>Total sementara</dt><dd x-text="format($store.cart.total)"></dd></div>
        </dl>
        <button type="button" disabled class="btn mt-8 w-full cursor-not-allowed bg-paper/10 text-paper/50">Checkout segera hadir</button>
        <p class="mt-5 text-xs leading-5 text-paper/50">Checkout akan diaktifkan setelah kanal pemesanan dan katalog resmi dikonfirmasi.</p>
        <button type="button" x-on:click="$store.cart.clear()" class="mt-7 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-paper/50 underline underline-offset-4 transition-colors hover:text-paper">Kosongkan keranjang</button>
      </aside>
    </div>
  </section>
</x-layouts.app>
