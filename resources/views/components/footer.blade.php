<footer class="border-t border-ink/15">
  <div class="mx-auto grid max-w-[1440px] items-start gap-10 px-5 py-14 md:grid-cols-[1fr_auto_1fr] md:items-center lg:px-12">
    <div>
      <a href="{{ route('home') }}" class="font-serif text-2xl tracking-[-0.03em]">CALMATS</a>
      <p class="mt-4 max-w-xs text-xs uppercase leading-5 tracking-[0.16em] text-stone">Indonesian streetwear personality.</p>
    </div>

    <nav aria-label="Footer">
      <ul class="flex flex-wrap items-center gap-x-7 gap-y-3 text-xs font-semibold uppercase tracking-[0.14em] md:justify-center lg:gap-x-10">
        @foreach (['Home' => 'home', 'Shop' => 'shop', 'About' => 'about', 'Community' => 'community'] as $label => $name)
          <li><a href="{{ route($name) }}" class="transition-colors hover:text-accent">{{ $label }}</a></li>
        @endforeach
      </ul>
    </nav>

    <ul class="flex flex-wrap gap-x-6 gap-y-3 text-xs uppercase tracking-[0.14em] md:justify-end md:text-right">
      <li>
        @if (config('calmats.whatsapp_url'))
          <a href="{{ config('calmats.whatsapp_url') }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">WhatsApp</a>
        @else
          <span class="text-stone">WhatsApp — segera hadir</span>
        @endif
      </li>
      <li>
        @if (config('calmats.instagram_url'))
          <a href="{{ config('calmats.instagram_url') }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">Instagram</a>
        @else
          <span class="text-stone">Instagram — segera hadir</span>
        @endif
      </li>
    </ul>
  </div>
  <p class="border-t border-ink/10 px-5 py-6 text-center text-[0.65rem] uppercase tracking-[0.16em] text-stone">&copy; {{ date('Y') }} CALMATS. Seluruh hak cipta dilindungi.</p>
</footer>
