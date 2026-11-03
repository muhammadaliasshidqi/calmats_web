<x-layouts.app title="Cart — CALMATS">
  <section class="mx-auto min-h-[65vh] max-w-7xl px-5 py-16 lg:px-10 lg:py-24" x-data="{ format(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value) } }">
    <div class="flex flex-wrap items-end justify-between gap-5 border-b border-mist pb-8">
      <div>
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">Your selection</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight sm:text-6xl">Keranjang</h1>
      </div>
      <p class="text-sm text-stone"><span x-text="$store.cart.count">0</span> item</p>
    </div>

    <div x-show="$store.cart.items.length === 0" class="py-24 text-center">
      <p class="font-serif text-4xl">Keranjangmu masih kosong.</p>
      <p class="mt-4 text-stone">Temukan siluet yang cocok dengan ritmemu.</p>
      <a href="{{ route('shop') }}" class="btn btn-solid mt-8">Mulai belanja</a>
    </div>

    <div x-show="$store.cart.items.length > 0" x-cloak class="grid gap-14 py-10 lg:grid-cols-[1fr_22rem]">
      <div class="divide-y divide-mist border-y border-mist">
        <template x-for="item in $store.cart.items" :key="`${item.slug}-${item.size}`">
          <article class="grid grid-cols-[5rem_1fr] gap-5 py-6 sm:grid-cols-[7rem_1fr_auto]">
            <div class="grid aspect-[3/4] place-items-center bg-mist px-2 text-center text-[10px] uppercase tracking-wider text-stone">CALMATS</div>
            <div>
              <a :href="`{{ url('/shop') }}/${item.slug}`" class="font-medium hover:text-accent" x-text="item.name"></a>
              <p class="mt-1 text-sm text-stone">Ukuran: <span x-text="item.size"></span></p>
              <p class="mt-3 text-sm" x-text="format(item.price)"></p>
              <div class="mt-4 flex items-center gap-3">
                <label class="text-xs uppercase tracking-wider text-stone" :for="`qty-${item.slug}-${item.size}`">Jumlah</label>
                <input :id="`qty-${item.slug}-${item.size}`" type="number" min="1" max="20" :value="item.quantity" x-on:change="$store.cart.update(item.slug, item.size, $event.target.value)" class="w-16 border border-mist bg-transparent px-2 py-1.5 text-center text-sm">
              </div>
            </div>
            <div class="col-start-2 flex items-end justify-between gap-5 sm:col-start-3 sm:row-start-1 sm:flex-col sm:text-right">
              <p class="font-medium" x-text="format(item.price * item.quantity)"></p>
              <button type="button" x-on:click="$store.cart.remove(item.slug, item.size)" class="text-xs uppercase tracking-wider text-stone underline underline-offset-4 hover:text-ink">Hapus</button>
            </div>
          </article>
        </template>
      </div>

      <aside class="h-fit border border-mist p-7">
        <h2 class="font-serif text-3xl">Ringkasan</h2>
        <dl class="mt-7 space-y-4 text-sm">
          <div class="flex justify-between gap-5"><dt class="text-stone">Subtotal</dt><dd x-text="format($store.cart.total)"></dd></div>
          <div class="flex justify-between gap-5"><dt class="text-stone">Pengiriman</dt><dd>Dihitung nanti</dd></div>
          <div class="flex justify-between gap-5 border-t border-mist pt-4 text-base font-medium"><dt>Total sementara</dt><dd x-text="format($store.cart.total)"></dd></div>
        </dl>
        <button type="button" disabled class="btn mt-7 w-full cursor-not-allowed bg-mist text-stone">Checkout segera hadir</button>
        <p class="mt-4 text-xs leading-5 text-stone">Checkout akan diaktifkan setelah kanal pembayaran dan katalog resmi dikonfirmasi.</p>
        <button type="button" x-on:click="$store.cart.clear()" class="mt-6 text-xs uppercase tracking-wider text-stone underline underline-offset-4 hover:text-ink">Kosongkan keranjang</button>
      </aside>
    </div>
  </section>
</x-layouts.app>
