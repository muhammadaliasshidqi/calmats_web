<x-layouts.app :title="$title.' — CALMATS'">
  <section class="mx-auto grid min-h-[60vh] max-w-3xl place-items-center px-5 text-center">
    <div>
      <h1 class="font-serif text-5xl tracking-tight">{{ $title }}</h1>
      <p class="mt-4 text-stone">Halaman ini sedang disiapkan.</p>
      <a href="{{ route('home') }}" class="btn btn-line mt-8">Kembali ke Home</a>
    </div>
  </section>
</x-layouts.app>
