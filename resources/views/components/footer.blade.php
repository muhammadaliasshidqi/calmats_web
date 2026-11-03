<footer class="border-t border-mist">
  <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 md:grid-cols-12 lg:px-10">
    <div class="md:col-span-5">
      <a href="{{ route('home') }}" class="text-lg font-bold tracking-[0.28em]">CALMATS</a>
      <p class="mt-4 max-w-xs text-sm text-stone">Tenang itu sikap, bukan diam.</p>
    </div>
    <nav class="md:col-span-3" aria-label="Footer">
      <ul class="space-y-3 text-sm">
        @foreach (['Home' => 'home', 'Shop' => 'shop', 'About' => 'about', 'Community' => 'community'] as $l => $n)
          <li><a href="{{ route($n) }}" class="hover:text-accent">{{ $l }}</a></li>
        @endforeach
      </ul>
    </nav>
    <ul class="space-y-3 text-sm md:col-span-4">
      <li>
        @if (config('calmats.whatsapp_url'))
          <a href="{{ config('calmats.whatsapp_url') }}" target="_blank" rel="noopener" class="hover:text-accent">WhatsApp</a>
        @else
          <span class="text-stone">WhatsApp — segera hadir</span>
        @endif
      </li>
      <li>
        @if (config('calmats.instagram_url'))
          <a href="{{ config('calmats.instagram_url') }}" target="_blank" rel="noopener" class="hover:text-accent">Instagram</a>
        @else
          <span class="text-stone">Instagram — segera hadir</span>
        @endif
      </li>
    </ul>
  </div>
  <p class="border-t border-mist px-5 py-6 text-center text-xs text-stone">&copy; {{ date('Y') }} CALMATS. Seluruh hak cipta dilindungi.</p>
</footer>
