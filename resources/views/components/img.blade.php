{{-- Gambar dengan fallback. Jika file belum ada, tampil blok abu-abu berlabel placeholder. --}}
@props(['src' => null, 'alt' => '', 'label' => 'Foto placeholder'])
@php
  $url = $src && str_starts_with($src, 'products/')
    ? \Illuminate\Support\Facades\Storage::disk('public')->url($src)
    : ($src ? asset($src) : null);
@endphp
<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-mist']) }} x-data="{ ok: @js((bool) $src) }">
  @if ($src)
    <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover" x-show="ok" x-on:error="ok = false">
  @endif
  <div x-show="!ok" class="absolute inset-0 grid place-items-center px-4 text-center text-xs text-stone">{{ $label }}</div>
</div>
