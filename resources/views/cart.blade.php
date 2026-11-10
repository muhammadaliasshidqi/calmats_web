<x-layouts.app title="Cart — CALMATS">
  <section class="mx-auto min-h-[65vh] max-w-[1440px] px-5 py-14 lg:px-12 lg:py-20" x-data="cartPage(@js(config('calmats.whatsapp_url')))">
    <header class="grid items-end gap-8 border-b border-ink/15 pb-10 md:grid-cols-12">
      <div class="md:col-span-8">
        <p class="eyebrow">Your selection / 01</p>
        <h1 class="mt-5 font-serif text-6xl italic tracking-[-0.045em] sm:text-7xl">Keranjang</h1>
        <p class="mt-5 max-w-lg text-sm leading-6 text-stone">Periksa kembali pilihanmu sebelum melanjutkan ke tahap pemesanan.</p>
      </div>
      <div class="md:col-span-4 md:text-right">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-stone"><span x-text="$store.cart.count">0</span> item tersimpan</p>
        @auth
          <p class="mt-2 text-[0.58rem] font-semibold uppercase tracking-[0.18em] text-accent">Tersinkron ke akun</p>
        @else
          <p class="mt-2 text-[0.58rem] font-semibold uppercase tracking-[0.18em] text-stone">Tersimpan di browser</p>
        @endauth
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
          <x-img class="h-full w-full !rounded-none !bg-transparent" src="images/calmats/collection-01/oversized-tee-front.jpg" alt="Kaus oversized CALMATS tampak depan" label="Foto editorial belum tersedia" />
        </div>
      </div>
    </div>

    <div x-show="$store.cart.items.length > 0" x-cloak class="grid gap-8 py-10 lg:grid-cols-[1fr_24rem] lg:gap-12">
      <div>
        <template x-for="item in $store.cart.items" :key="`${item.slug}-${item.color}-${item.size}`">
          <article class="mb-4 grid grid-cols-[5.5rem_1fr] gap-5 rounded-[1.5rem] border border-ink/10 bg-white/45 p-4 shadow-[0_12px_32px_rgba(40,31,25,.08)] sm:grid-cols-[8rem_1fr_auto] sm:p-5">
            <div class="relative grid aspect-[3/4] place-items-center overflow-hidden rounded-[1.25rem] bg-mist px-2 text-center text-[10px] font-semibold uppercase tracking-[0.18em] text-stone">
              <template x-if="item.image">
                <img :src="imageUrl(item.image)" :alt="item.name" class="absolute inset-0 h-full w-full object-cover">
              </template>
              <span x-show="!item.image">CALMATS</span>
            </div>
            <div class="min-w-0 self-center">
              <p class="text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-accent">Selected item</p>
              <a :href="`{{ url('/shop') }}/${item.slug}`" class="mt-2 block break-words font-serif text-2xl italic transition-colors hover:text-accent" x-text="item.name"></a>
              <p class="mt-2 text-sm text-stone">Warna: <span x-text="item.color"></span> <span class="mx-1">/</span> Ukuran: <span x-text="item.size"></span></p>
              <p class="mt-3 text-sm font-medium" x-text="format(item.price)"></p>
              <div class="mt-4 flex items-center gap-3">
                <label class="text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-stone" :for="`qty-${item.slug}-${item.color}-${item.size}`">Jumlah</label>
                <input :id="`qty-${item.slug}-${item.color}-${item.size}`" type="number" min="1" :max="item.stock ?? 20" :value="item.quantity" x-on:change="$store.cart.update(item.slug, item.size, item.color, $event.target.value); $event.target.value = item.quantity" class="w-16 border border-mist bg-paper px-2 py-1.5 text-center text-sm">
              </div>
            </div>
            <div class="col-start-2 flex items-end justify-between gap-5 sm:col-start-3 sm:row-start-1 sm:flex-col sm:text-right">
              <p class="font-medium" x-text="format(item.price * item.quantity)"></p>
              <button type="button" x-on:click="if (confirm(`Hapus ${item.name} dari keranjang?`)) $store.cart.remove(item.slug, item.size, item.color)" class="text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-stone underline underline-offset-4 transition-colors hover:text-accent">Hapus</button>
            </div>
          </article>
        </template>
      </div>

      <aside class="h-fit rounded-[1.75rem] bg-ink p-6 text-paper shadow-[0_20px_55px_rgba(25,22,19,.2)] sm:p-8">
        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-paper/50">Order review</p>
        <h2 class="mt-4 font-serif text-4xl italic">Ringkasan</h2>
        <dl class="mt-8 space-y-5 text-sm">
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Subtotal</dt><dd x-text="format($store.cart.total)"></dd></div>
          <div class="flex justify-between gap-5"><dt class="text-paper/60">Pengiriman</dt><dd>Dihitung nanti</dd></div>
          <div class="flex justify-between gap-5 border-t border-paper/20 pt-5 text-base font-medium"><dt>Total sementara</dt><dd x-text="format($store.cart.total)"></dd></div>
        </dl>
        <form class="mt-8 border-t border-paper/20 pt-7" x-on:submit.prevent="handoff">
          <p class="text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-paper/60">Data pemesan</p>
          <div class="mt-5 space-y-3">
            <label class="block">
              <span class="sr-only">Nama lengkap</span>
              <input x-model.trim="customer.name" required type="text" autocomplete="name" placeholder="Nama lengkap" class="w-full border border-paper/20 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/60">
            </label>
            <label class="block">
              <span class="sr-only">Nomor WhatsApp</span>
              <input x-model.trim="customer.phone" required type="tel" autocomplete="tel" placeholder="Nomor WhatsApp" class="w-full border border-paper/20 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/60">
            </label>
            <label class="block">
              <span class="sr-only">Alamat pengiriman</span>
              <textarea x-model.trim="customer.address" required rows="3" autocomplete="street-address" placeholder="Alamat lengkap pengiriman" class="w-full border border-paper/20 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/60"></textarea>
            </label>
            <label class="block">
              <span class="sr-only">Catatan pesanan</span>
              <textarea x-model.trim="customer.note" rows="2" placeholder="Catatan tambahan (opsional)" class="w-full border border-paper/20 bg-paper/10 px-4 py-3 text-sm text-paper placeholder:text-paper/45 focus:border-paper/60"></textarea>
            </label>
          </div>

          <button type="submit" class="btn btn-accent mt-5 w-full">
            {{ config('calmats.whatsapp_url') ? 'Pesan via WhatsApp' : 'Salin ringkasan pesanan' }}
          </button>
          <p class="mt-4 text-xs leading-5 text-paper/55">Pesanan belum dikirim otomatis. Kamu tetap dapat memeriksa isi pesan sebelum mengirimkannya.</p>
          <p x-show="handoffNotice" x-cloak x-text="handoffNotice" class="mt-4 rounded-xl bg-paper/10 px-4 py-3 text-xs leading-5 text-paper" role="status"></p>
        </form>
        <button type="button" x-on:click="if (confirm('Kosongkan seluruh isi keranjang?')) $store.cart.clear()" class="mt-7 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-paper/50 underline underline-offset-4 transition-colors hover:text-paper">Kosongkan keranjang</button>
      </aside>
    </div>
  </section>
</x-layouts.app>
