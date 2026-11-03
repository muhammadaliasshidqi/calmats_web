<section class="relative overflow-hidden">
  <div class="mx-auto grid max-w-7xl gap-10 px-5 pb-10 pt-10 lg:grid-cols-12 lg:px-10 lg:pt-16">
    <div class="flex flex-col justify-between lg:col-span-5">
      <div>
        <h1 class="font-serif text-5xl leading-[1.02] tracking-tight sm:text-6xl xl:text-7xl">Tenang itu sikap, bukan diam.</h1>
        <p class="mt-6 max-w-sm text-base leading-relaxed text-stone">Pakaian minimalis berjiwa streetwear. Siluet bersih, bahan yang jujur, dan cerita di balik setiap potongan.</p>
        <div class="mt-9 flex flex-wrap gap-3">
          <a href="{{ route('shop') }}" class="btn btn-solid">Shop now</a>
          <a href="{{ route('about') }}" class="btn btn-line">Our story</a>
        </div>
      </div>
    </div>
    <x-img class="aspect-[4/5] lg:col-span-7 lg:aspect-[5/6]" src="images/calmats/hero.jpg" alt="Model mengenakan koleksi CALMATS" label="Foto hero editorial (placeholder) — taruh di public/images/calmats/hero.jpg" />
  </div>
  {{-- Wordmark besar di dasar hero: satu-satunya momen "berani" di halaman --}}
  <div class="pointer-events-none select-none overflow-hidden leading-none" aria-hidden="true">
    <p class="wordmark whitespace-nowrap text-center text-[19.5vw] font-bold tracking-[-0.04em] text-ink">CALMATS</p>
  </div>
</section>
