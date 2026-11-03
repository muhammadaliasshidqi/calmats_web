<section class="border-t border-mist">
  <div class="mx-auto max-w-7xl px-5 py-24 lg:px-10">
    <div class="grid gap-8 lg:grid-cols-12">
      <h2 class="font-serif text-4xl tracking-tight sm:text-5xl lg:col-span-5">Community</h2>
      <div class="lg:col-span-5 lg:col-start-8">
        <p class="leading-relaxed text-stone">Ruang untuk orang-orang yang memakai CALMATS dengan caranya sendiri. Lihat gaya mereka, bagikan cerita, dan jadi bagian dari gerakan yang tenang.</p>
        <a href="{{ route('community') }}" class="btn btn-solid mt-8">Explore community</a>
      </div>
    </div>
    <div class="reveal mt-14 grid grid-cols-2 gap-3 md:grid-cols-4 lg:gap-4">
      @foreach (['aspect-[3/4]', 'aspect-square', 'aspect-[3/4]', 'aspect-square', 'aspect-square', 'aspect-[3/4]', 'aspect-square', 'aspect-[3/4]'] as $i => $ratio)
        <x-img class="{{ $ratio }}" label="Foto komunitas {{ $i + 1 }} (placeholder)" />
      @endforeach
    </div>
  </div>
</section>
