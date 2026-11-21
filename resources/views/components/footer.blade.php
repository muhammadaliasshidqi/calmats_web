@php
  $whatsappUrl = \App\Models\SiteSetting::whatsappUrl();
  $instagramUrl = config('calmats.instagram_url');
  $tiktokUrl = config('calmats.tiktok_url');
  $email = config('calmats.email');
@endphp
<footer class="border-t border-ink/15">
  <div class="mx-auto grid max-w-[1440px] items-start gap-10 px-5 py-14 sm:grid-cols-2 lg:grid-cols-3 lg:px-12">
    <div>
      <a href="{{ route('home') }}" class="inline-flex items-center gap-4 transition-colors hover:text-accent">
        <img src="{{ asset('images/Logo.png') }}" alt="" class="h-16 w-16 object-contain" width="64" height="64" loading="lazy">
        <span class="font-serif text-2xl tracking-[-0.03em]">CALMATS</span>
      </a>
      <p class="mt-4 max-w-xs text-xs uppercase leading-5 tracking-[0.16em] text-stone">Indonesian streetwear personality.</p>
    </div>

    <nav aria-label="Navigasi footer" class="sm:text-right lg:text-left">
      <p class="mb-3 text-xs uppercase tracking-[0.2em] text-stone">Jelajahi</p>
      <ul class="flex flex-wrap gap-x-6 gap-y-3 text-xs uppercase tracking-[0.14em] sm:justify-end lg:justify-start">
        <li><a href="{{ route('shop') }}" class="transition-colors hover:text-accent">Shop</a></li>
        <li><a href="{{ route('about') }}" class="transition-colors hover:text-accent">About</a></li>
        <li><a href="{{ route('community') }}" class="transition-colors hover:text-accent">Community</a></li>
      </ul>
    </nav>

<div class="sm:col-span-2 sm:text-right lg:col-span-1">
  <p class="mb-3 text-xs uppercase tracking-[0.2em] text-stone">Hubungi kami</p>
  <ul class="flex flex-wrap gap-x-6 gap-y-3 text-xs uppercase tracking-[0.14em] sm:justify-end">
    @if ($whatsappUrl)<li><a href="{{ $whatsappUrl }}?text=Halo%20CALMATS%2C%20saya%20ingin%20bertanya." target="_blank" rel="noopener" class="transition-colors hover:text-accent">WhatsApp</a></li>@endif
    @if ($instagramUrl)<li><a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">Instagram</a></li>@endif
    @if ($tiktokUrl)<li><a href="{{ $tiktokUrl }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">TikTok</a></li>@endif
    @if ($email)<li><a href="mailto:{{ $email }}" class="transition-colors hover:text-accent">Email</a></li>@endif
  </ul>
</div>
  </div>
  <p class="border-t border-ink/10 px-5 py-6 text-center text-[0.65rem] uppercase tracking-[0.16em] text-stone">&copy; {{ date('Y') }} CALMATS. Seluruh hak cipta dilindungi.</p>
</footer>
