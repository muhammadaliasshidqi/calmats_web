@props([
  'title',
  'eyebrow',
  'heading',
  'description',
  'quote' => 'Quiet confidence, built for every movement.',
  'simple' => false,
])

<x-layouts.app :title="$title.' — CALMATS'">
  <section @class([
    'relative mx-auto min-h-[72vh] px-5 py-14 lg:px-10 lg:py-20',
    'grid max-w-7xl lg:grid-cols-2' => ! $simple,
    'flex max-w-4xl items-center justify-center overflow-hidden' => $simple,
  ])>
    @unless ($simple)
      <div class="relative hidden overflow-hidden rounded-[1.75rem] bg-ink shadow-[0_24px_65px_rgba(25,22,19,.18)] lg:block lg:rounded-r-none">
        <x-img class="h-full w-full !rounded-none" src="images/calmats/collection-01/community-stairs.jpg" alt="Editorial komunitas CALMATS" img-class="object-[center_45%]" label="Foto editorial CALMATS belum tersedia" />
      </div>
    @endunless

    <div @class([
      'relative flex items-center justify-center overflow-hidden rounded-[1.75rem] border p-7 shadow-[0_18px_50px_rgba(40,31,25,.07)] sm:p-12',
      'border-mist bg-white/35 lg:rounded-l-none' => ! $simple,
      'z-10 w-full max-w-xl border-accent/15 bg-[#fffaf7]/95 shadow-[0_26px_80px_rgba(78,20,25,.12)]' => $simple,
    ])>
      @if ($simple)
        <div class="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-ink via-accent to-[#d8b9a9]" aria-hidden="true"></div>
      @endif
      <div class="w-full max-w-md">
        <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-3 transition-colors hover:text-accent" aria-label="CALMATS, kembali ke Home">
          <img src="{{ asset('images/Logo.png') }}" alt="" class="h-14 w-14 object-contain" width="56" height="56">
          <span class="font-serif text-2xl tracking-[-0.03em]">CALMATS</span>
        </a>
        <p class="eyebrow">{{ $eyebrow }}</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight">{{ $heading }}</h1>
        <p class="mt-4 text-sm text-stone">{{ $description }}</p>

        {{ $slot }}
      </div>
    </div>
  </section>
</x-layouts.app>
