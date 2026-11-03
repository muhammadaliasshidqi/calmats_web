<x-layouts.app title="About — CALMATS">
  <section class="mx-auto max-w-7xl px-5 py-20 lg:px-10 lg:py-28">
    <div class="grid gap-12 lg:grid-cols-12 lg:items-end">
      <div class="lg:col-span-8">
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">Tentang CALMATS</p>
        <h1 class="mt-6 font-serif text-6xl leading-[0.95] tracking-tight sm:text-8xl">Tenang bukan berarti berhenti.</h1>
      </div>
      <p class="max-w-md text-base leading-7 text-stone lg:col-span-4">CALMATS lahir dari gagasan bahwa pakaian dapat menjadi ruang tenang di tengah gerak kota—jujur dalam bentuk, nyaman dipakai, dan tidak berisik.</p>
    </div>
  </section>

  <section class="mx-3 overflow-hidden rounded-[1.75rem] bg-ink text-paper sm:mx-5 lg:mx-8">
    <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-2 lg:px-10 lg:py-28">
      <x-img class="aspect-[4/5]" src="images/calmats/preview-about.png" alt="Proses penandaan dan pemotongan bahan CALMATS" label="Foto proses CALMATS belum tersedia" />
      <div class="self-center lg:px-10">
        <p class="text-xs uppercase tracking-[0.2em] text-paper/60">Our point of view</p>
        <h2 class="mt-5 font-serif text-5xl tracking-tight">Dibuat untuk menemani, bukan mendominasi.</h2>
        <div class="mt-8 space-y-5 leading-7 text-paper/70">
          <p>Kami menyukai siluet yang memberi ruang, detail yang punya alasan, dan palet yang mudah hidup bersama pemakainya.</p>
          <p>Setiap rancangan dimulai dari satu pertanyaan sederhana: apakah benda ini tetap terasa tepat ketika tren sudah bergerak pergi?</p>
        </div>
      </div>
    </div>
  </section>

  <section class="mx-auto max-w-7xl px-5 py-24 lg:px-10">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">Prinsip kami</p>
    <div class="mt-10 grid border-y border-mist md:grid-cols-3">
      @foreach ([['01', 'Clarity', 'Bentuk yang bersih dan fungsi yang mudah dipahami.'], ['02', 'Character', 'Detail secukupnya untuk memberi identitas tanpa kehilangan ketenangan.'], ['03', 'Longevity', 'Pakaian yang dirancang untuk kembali dipakai, berkali-kali.']] as [$number, $title, $copy])
        <article class="border-b border-mist py-10 md:border-b-0 md:border-r md:px-8 md:first:pl-0 md:last:border-r-0">
          <p class="text-xs text-stone">{{ $number }}</p>
          <h2 class="mt-5 font-serif text-3xl">{{ $title }}</h2>
          <p class="mt-4 max-w-xs text-sm leading-6 text-stone">{{ $copy }}</p>
        </article>
      @endforeach
    </div>
  </section>
</x-layouts.app>
