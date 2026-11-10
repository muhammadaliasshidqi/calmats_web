<section class="border-t border-ink/10 bg-[#f0ebe4]">
  <div class="mx-auto max-w-[1440px] px-5 py-20 sm:py-24 lg:px-12 lg:py-28">
    <div class="grid gap-12 lg:grid-cols-12 lg:items-center">
      <div class="lg:col-span-4">
        <p class="eyebrow mb-4">People & culture</p>
        <h2 class="font-serif text-6xl italic tracking-[-0.045em] sm:text-7xl">Community</h2>
        <p class="mt-7 max-w-xs text-sm leading-7 text-stone">Ruang untuk orang-orang yang memakai CALMATS dengan caranya sendiri—berbagi gaya, proses, dan cerita.</p>
        <a href="{{ route('community') }}" class="link-line mt-8 inline-block">Explore community</a>
      </div>
      <div class="reveal grid grid-cols-3 gap-2 sm:gap-4 lg:col-span-8">
        @foreach ([
          ['images/calmats/collection-01/community-work-01.jpg', 'Proses kreatif komunitas CALMATS'],
          ['images/calmats/collection-01/night-ride.jpg', 'CALMATS dalam perjalanan malam kota'],
          ['images/calmats/collection-01/community-stairs.jpg', 'Gaya CALMATS di ruang urban'],
        ] as $i => [$image, $alt])
          <div class="community-media {{ $i === 1 ? 'lg:-translate-y-5' : '' }}">
            <x-img class="aspect-[3/4]" :src="$image" :alt="$alt" label="Foto komunitas {{ $i + 1 }} belum tersedia" />
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="border-t border-ink/10 px-5 py-20 text-center sm:py-24 lg:px-12">
  <p class="mx-auto mb-5 h-px w-10 bg-accent"></p>
  <h2 class="font-serif text-4xl italic tracking-[-0.035em] sm:text-6xl">About CALMATS</h2>
  <p class="mx-auto mt-5 max-w-2xl text-xs font-medium uppercase leading-6 tracking-[0.2em] text-stone">An Indonesian streetwear label rooted in culture, craft, and contemporary expression.</p>
  <a href="{{ route('about') }}" class="link-line mt-8 inline-block">Our story</a>
</section>
