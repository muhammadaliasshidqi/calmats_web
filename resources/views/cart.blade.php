<x-layouts.app title="Cart — CALMATS">
  <section
    class="mx-auto min-h-[70vh] max-w-[1440px] px-5 py-10 sm:py-14 lg:px-12 lg:py-16"
    x-data="cartPage(@js(config('calmats.whatsapp_url')))"
  >
    <header class="flex flex-col gap-7 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-stone transition-colors hover:text-accent">
          <span aria-hidden="true">←</span>
          Lanjut belanja
        </a>
        <h1 class="mt-5 font-serif text-5xl tracking-[-0.045em] sm:text-7xl">Keranjang belanja</h1>
        <p class="mt-4 max-w-xl text-sm leading-6 text-stone">Periksa produk dan lengkapi data pemesan sebelum melanjutkan melalui WhatsApp.</p>
      </div>

      <div class="flex items-center gap-4 text-sm sm:pb-1 sm:text-right">
        <div>
          <p class="font-semibold"><span x-text="$store.cart.count">0</span> item</p>
          @auth
            <p class="mt-1 text-xs text-accent">Tersinkron ke akun</p>
          @else
            <p class="mt-1 text-xs text-stone">Tersimpan di browser</p>
          @endauth
        </div>
        <span class="grid h-11 w-11 place-items-center rounded-full bg-ink text-paper" aria-hidden="true">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Zm4 0a3 3 0 0 1 6 0"/></svg>
        </span>
      </div>
    </header>

    <div x-show="$store.cart.items.length === 0" class="mt-10 overflow-hidden rounded-[1.75rem] bg-[#191513] text-paper">
      <div class="grid min-h-[440px] md:grid-cols-12">
        <div class="flex flex-col justify-center px-7 py-14 sm:px-12 md:col-span-7 lg:px-16">
          <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#d8a2a5]">Keranjang kosong</p>
          <h2 class="mt-5 max-w-xl font-serif text-5xl leading-[0.95] tracking-[-0.04em] sm:text-6xl">Temukan pilihan yang terasa tepat.</h2>
          <p class="mt-6 max-w-md text-sm leading-7 text-paper/65">Jelajahi koleksi CALMATS, pilih warna dan ukuran, lalu produkmu akan tersimpan di sini.</p>
          <a href="{{ route('shop') }}" class="btn btn-accent mt-9 w-fit">Lihat koleksi <span class="ml-3" aria-hidden="true">→</span></a>
        </div>
        <div class="relative min-h-[320px] md:col-span-5">
          <x-img class="h-full w-full !rounded-none !bg-transparent" src="images/calmats/collection-01/oversized-tee-front.jpg" alt="Kaus oversized CALMATS tampak depan" label="Foto editorial belum tersedia" />
        </div>
      </div>
    </div>

    <div x-show="$store.cart.items.length > 0" x-cloak class="mt-10 grid items-start gap-7 lg:grid-cols-[minmax(0,1fr)_26rem] lg:gap-10">
      <section class="overflow-hidden rounded-[1.75rem] border border-ink/10 bg-white/55 shadow-[0_18px_55px_rgba(40,31,25,.07)]" aria-labelledby="cart-items-heading">
        <div class="flex items-center justify-between gap-5 px-5 py-5 sm:px-7">
          <div>
            <p class="eyebrow">Pilihanmu</p>
            <h2 id="cart-items-heading" class="mt-1 font-serif text-3xl">Produk di keranjang</h2>
          </div>
          <button
            type="button"
            x-on:click="if (confirm('Kosongkan seluruh isi keranjang?')) $store.cart.clear()"
            class="text-xs font-semibold uppercase tracking-[0.12em] text-stone transition-colors hover:text-accent"
          >Kosongkan</button>
        </div>

        <div class="divide-y divide-ink/10 border-t border-ink/10">
          <template x-for="item in $store.cart.items" :key="`${item.slug}-${item.color}-${item.size}`">
            <article class="grid grid-cols-[6.25rem_minmax(0,1fr)] gap-4 p-5 sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:gap-6 sm:p-7">
              <a :href="`{{ url('/shop') }}/${item.slug}`" class="relative grid aspect-[3/4] place-items-center overflow-hidden rounded-[1.15rem] bg-mist px-2 text-center text-[10px] font-semibold uppercase tracking-[0.18em] text-stone">
                <template x-if="item.image">
                  <img :src="imageUrl(item.image)" :alt="item.name" class="absolute inset-0 h-full w-full object-cover">
                </template>
                <span x-show="!item.image">CALMATS</span>
              </a>

              <div class="min-w-0 py-1">
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-accent" x-text="`${item.color} / ${item.size}`"></p>
                <a :href="`{{ url('/shop') }}/${item.slug}`" class="mt-2 block break-words font-serif text-2xl leading-tight transition-colors hover:text-accent sm:text-3xl" x-text="item.name"></a>
                <p class="mt-2 text-sm text-stone" x-text="`${format(item.price)} / item`"></p>

                <div class="mt-5 flex flex-wrap items-center gap-4">
                  <div class="inline-grid grid-cols-[2.5rem_3rem_2.5rem] items-center overflow-hidden rounded-full border border-ink/15 bg-paper" role="group" :aria-label="`Jumlah ${item.name}`">
                    <button
                      type="button"
                      class="grid h-10 place-items-center text-lg transition-colors hover:bg-ink hover:text-paper disabled:cursor-not-allowed disabled:opacity-30"
                      :disabled="item.quantity <= 1"
                      :aria-label="`Kurangi jumlah ${item.name}`"
                      x-on:click="$store.cart.update(item.slug, item.size, item.color, item.quantity - 1)"
                    >−</button>
                    <input
                      :id="`qty-${item.slug}-${item.color}-${item.size}`"
                      type="number"
                      min="1"
                      :max="item.stock ?? 20"
                      :value="item.quantity"
                      :aria-label="`Jumlah ${item.name}`"
                      x-on:change="$store.cart.update(item.slug, item.size, item.color, $event.target.value); $event.target.value = item.quantity"
                      class="h-10 w-full border-x border-y-0 border-ink/15 bg-transparent p-0 text-center text-sm font-semibold outline-none"
                    >
                    <button
                      type="button"
                      class="grid h-10 place-items-center text-lg transition-colors hover:bg-ink hover:text-paper disabled:cursor-not-allowed disabled:opacity-30"
                      :disabled="item.quantity >= (item.stock ?? 20)"
                      :aria-label="`Tambah jumlah ${item.name}`"
                      x-on:click="$store.cart.update(item.slug, item.size, item.color, item.quantity + 1)"
                    >+</button>
                  </div>

                  <button
                    type="button"
                    x-on:click="if (confirm(`Hapus ${item.name} dari keranjang?`)) $store.cart.remove(item.slug, item.size, item.color)"
                    class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-stone transition-colors hover:text-accent"
                  >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v5m4-5v5"/></svg>
                    Hapus
                  </button>
                </div>
              </div>

              <div class="col-start-2 flex items-end justify-between gap-4 sm:col-start-3 sm:flex-col sm:items-end sm:py-1 sm:text-right">
                <p class="text-xs uppercase tracking-[0.12em] text-stone sm:hidden">Total</p>
                <p class="whitespace-nowrap font-semibold" x-text="format(item.price * item.quantity)"></p>
                <p x-show="item.stock !== null && item.stock !== undefined" class="hidden text-xs text-stone sm:block"><span x-text="item.stock"></span> stok tersedia</p>
              </div>
            </article>
          </template>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-ink/10 bg-paper/45 px-5 py-5 text-sm sm:px-7">
          <a href="{{ route('shop') }}" class="font-semibold transition-colors hover:text-accent">← Tambah produk lain</a>
          <p class="text-stone">Harga pengiriman dikonfirmasi melalui WhatsApp.</p>
        </div>
      </section>

      <aside class="h-fit rounded-[1.75rem] bg-ink p-6 text-paper shadow-[0_22px_60px_rgba(25,22,19,.2)] sm:p-8 lg:sticky lg:top-24">
        <div class="flex items-start justify-between gap-5">
          <div>
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-paper/60">Ringkasan pesanan</p>
            <h2 class="mt-2 font-serif text-4xl">Checkout</h2>
          </div>
          <span class="text-sm text-paper/60"><span x-text="$store.cart.count"></span> item</span>
        </div>

        <dl class="mt-7 space-y-4 text-sm">
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Subtotal</dt><dd x-text="format($store.cart.total)"></dd></div>
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Pengiriman</dt><dd class="text-right">Dikonfirmasi nanti</dd></div>
          <div class="flex justify-between gap-5 border-t border-paper/20 pt-5 text-base font-semibold"><dt>Total sementara</dt><dd x-text="format($store.cart.total)"></dd></div>
        </dl>

        <form class="mt-7 border-t border-paper/20 pt-7" x-on:submit.prevent="handoff">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-paper/60">Data pemesan</p>
            <p class="mt-2 text-xs leading-5 text-paper/60">Digunakan untuk konfirmasi pesanan dan pengiriman.</p>
          </div>

          <div class="mt-5 space-y-4">
            <label class="block">
              <span class="mb-1.5 block text-sm text-paper/85">Nama lengkap</span>
              <input x-model.trim="customer.name" required type="text" autocomplete="name" placeholder="Nama penerima" class="w-full border border-paper/25 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/70">
            </label>
            <label class="block">
              <span class="mb-1.5 block text-sm text-paper/85">Nomor WhatsApp</span>
              <input x-model.trim="customer.phone" required type="tel" inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" class="w-full border border-paper/25 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/70">
            </label>
            <label class="block">
              <span class="mb-1.5 block text-sm text-paper/85">Alamat pengiriman</span>
              <textarea x-model.trim="customer.address" required rows="3" autocomplete="street-address" placeholder="Alamat lengkap dan kode pos" class="w-full resize-none border border-paper/25 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/70"></textarea>
            </label>
            <label class="block">
              <span class="mb-1.5 block text-sm text-paper/85">Catatan <span class="text-paper/45">(opsional)</span></span>
              <textarea x-model.trim="customer.note" rows="2" placeholder="Permintaan khusus untuk pesanan" class="w-full resize-none border border-paper/25 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/70"></textarea>
            </label>
          </div>

          <button type="submit" class="btn btn-accent mt-6 w-full">
            {{ config('calmats.whatsapp_url') ? 'Pesan via WhatsApp' : 'Salin ringkasan pesanan' }}
            <span class="ml-3" aria-hidden="true">→</span>
          </button>
          <p class="mt-4 text-xs leading-5 text-paper/60">Kamu masih dapat memeriksa isi pesan sebelum mengirimkannya kepada admin CALMATS.</p>
          <p x-show="handoffNotice" x-cloak x-text="handoffNotice" class="mt-4 rounded-xl bg-paper/10 px-4 py-3 text-xs leading-5 text-paper" role="status"></p>
        </form>
      </aside>
    </div>
  </section>
</x-layouts.app>
