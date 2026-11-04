@props([
  'title',
  'eyebrow',
  'heading',
  'description',
  'quote' => 'Quiet confidence, built for every movement.',
])

<x-layouts.app :title="$title.' — CALMATS'">
  <section class="mx-auto grid min-h-[72vh] max-w-7xl px-5 py-14 lg:grid-cols-2 lg:px-10 lg:py-20">
    <div class="relative hidden overflow-hidden rounded-[1.75rem] bg-ink text-paper shadow-[0_24px_65px_rgba(25,22,19,.18)] lg:flex lg:flex-col lg:justify-between lg:rounded-r-none">
      <div class="absolute inset-0">
        <x-img class="h-full w-full !rounded-none" src="images/calmats/preview-about.png" alt="Editorial CALMATS" label="Foto editorial CALMATS belum tersedia" />
      </div>
      <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/20"></div>
      <p class="relative z-10 p-12 text-xs font-semibold uppercase tracking-[0.2em] text-paper/80">CALMATS Account / Private space</p>
      <div class="relative z-10 p-12">
        <p class="mb-5 h-px w-12 bg-accent"></p>
        <p class="max-w-md font-serif text-5xl italic leading-[0.95]">{{ $quote }}</p>
      </div>
    </div>

    <div class="flex items-center justify-center rounded-[1.75rem] border border-mist bg-white/35 p-7 shadow-[0_18px_50px_rgba(40,31,25,.07)] sm:p-12 lg:rounded-l-none">
      <div class="w-full max-w-md">
        <p class="eyebrow">{{ $eyebrow }}</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight">{{ $heading }}</h1>
        <p class="mt-4 text-sm text-stone">{{ $description }}</p>

        {{ $slot }}
      </div>
    </div>
  </section>
</x-layouts.app>
