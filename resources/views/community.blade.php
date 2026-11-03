<x-layouts.app title="Community — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-20 pt-10 lg:px-12 lg:pb-28 lg:pt-16">
    <div class="grid overflow-hidden border-b border-ink/15 lg:min-h-[580px] lg:grid-cols-12">
      <div class="flex flex-col justify-center py-16 lg:col-span-7 lg:py-24 lg:pr-16">
        <p class="eyebrow">CALMATS Community / 01</p>
        <h1 class="mt-7 font-serif text-6xl leading-[0.9] tracking-[-0.045em] sm:text-8xl lg:text-[7rem]">Different pace.<br><em>Same energy.</em></h1>
        <p class="mt-8 max-w-xl text-base leading-7 text-stone">Ruang untuk orang-orang yang memilih bergerak dengan caranya sendiri—berbagi gaya, proses, dan cerita tanpa perlu menjadi paling bising.</p>
        <div class="mt-12 flex items-center gap-4 text-[0.62rem] font-semibold uppercase tracking-[0.25em] text-stone">
          <span class="h-px w-12 bg-accent"></span>
          People, culture & movement
        </div>
      </div>

      <div class="community-forest-panel relative min-h-[340px] overflow-hidden text-paper lg:col-span-5 lg:min-h-[580px]">
        <p class="absolute left-8 top-8 text-[0.6rem] font-semibold uppercase tracking-[0.28em] text-paper/70">CALMATS / Collective</p>
        <p class="absolute inset-x-0 top-1/2 -translate-y-1/2 text-center font-serif text-7xl italic tracking-[-0.05em] text-paper/15 sm:text-9xl">PEOPLE</p>
        <p class="absolute bottom-8 right-8 max-w-[12rem] text-right text-[0.62rem] uppercase leading-5 tracking-[0.2em] text-paper/70">Every look carries a different story.</p>
      </div>
    </div>

    <div class="mt-16 grid grid-cols-2 gap-4 md:mt-20 md:grid-cols-12 md:items-end md:gap-6">
      <article class="community-gallery-card community-tone-clay col-span-2 md:col-span-5">
        <div class="flex items-center justify-between px-4 py-3 text-[0.58rem] font-semibold uppercase tracking-[0.22em]">
          <span>01 / Everyday</span><span>CALMATS</span>
        </div>
        <x-img class="aspect-[4/5] !bg-transparent" src="images/calmats/preview-community.png" alt="Tiga anggota komunitas CALMATS berbincang di ruang terbuka" label="Momen komunitas belum tersedia" />
      </article>
      <article class="community-gallery-card community-tone-sage md:col-span-3 md:translate-y-10">
        <div class="flex items-center justify-between px-4 py-3 text-[0.58rem] font-semibold uppercase tracking-[0.22em]">
          <span>02</span><span>Culture</span>
        </div>
        <x-img class="aspect-square !bg-transparent" label="Momen komunitas belum tersedia" />
      </article>
      <article class="community-gallery-card community-tone-wine md:col-span-4">
        <div class="flex items-center justify-between px-4 py-3 text-[0.58rem] font-semibold uppercase tracking-[0.22em]">
          <span>03 / Motion</span><span>Collective</span>
        </div>
        <x-img class="aspect-[3/4] !bg-transparent" label="Momen komunitas belum tersedia" />
      </article>
    </div>

    <div class="community-invite mt-24 grid gap-10 px-7 py-12 text-paper sm:px-10 lg:grid-cols-2 lg:px-14 lg:py-16">
      <div>
        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.25em] text-paper/60">Your pace, your story</p>
        <h2 class="mt-5 font-serif text-5xl italic tracking-[-0.035em] sm:text-6xl">Wear it your way.</h2>
      </div>
      <div class="lg:self-end">
        <p class="max-w-lg leading-7 text-paper/70">Punya cerita bersama CALMATS? Bagikan interpretasi, keseharian, atau proses kreatifmu. Kanal pengiriman komunitas akan dihubungkan setelah akun Instagram resmi tersedia.</p>
        <a href="{{ route('shop') }}" class="btn mt-8 border border-paper/40 text-paper hover:bg-paper hover:text-ink">Explore the collection</a>
      </div>
    </div>
  </section>
</x-layouts.app>
