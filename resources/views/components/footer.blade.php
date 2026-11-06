<footer class="border-t border-ink/15">
  <div class="mx-auto flex max-w-[1440px] flex-wrap items-center justify-between gap-5 px-5 py-8 lg:px-12">
    <a href="{{ route('home') }}" class="font-sans text-lg font-semibold tracking-tight">CALMATS</a>
    <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-stone">
      <li>
        @if (config('calmats.whatsapp_url'))
          <a href="{{ config('calmats.whatsapp_url') }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">WhatsApp</a>
        @else
          <span class="text-stone">WhatsApp segera hadir</span>
        @endif
      </li>
      <li>
        @if (config('calmats.instagram_url'))
          <a href="{{ config('calmats.instagram_url') }}" target="_blank" rel="noopener" class="transition-colors hover:text-accent">Instagram</a>
        @else
          <span class="text-stone">Instagram segera hadir</span>
        @endif
      </li>
    </ul>
  </div>
  <p class="border-t border-ink/10 px-5 py-4 text-center text-xs text-stone">&copy; {{ date('Y') }} CALMATS</p>
</footer>
