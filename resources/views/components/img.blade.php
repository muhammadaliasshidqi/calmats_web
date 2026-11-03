{{-- Gambar dengan fallback. Jika file belum ada, tampil blok abu-abu berlabel placeholder. --}}
@props(['src' => null, 'alt' => '', 'label' => 'Foto placeholder'])
<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-mist']) }} x-data="{ ok: @js((bool) $src) }">
  @if ($src)
    <img src="{{ asset($src) }}" alt="{{ $alt }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover" x-show="ok" x-on:error="ok = false">
  @endif
  <div x-show="!ok" class="absolute inset-0 grid place-items-center px-4 text-center text-xs text-stone">{{ $label }}</div>
</div>
