<x-layouts.app title="Community — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-20 pt-8 lg:px-12 lg:pb-24">
    <section id="feed">
      <div class="flex flex-wrap items-end justify-between gap-5 border-b border-ink/15 pb-5">
        <h1 class="font-serif text-3xl sm:text-4xl">Community</h1>
        @if ($communityPosts->isNotEmpty())
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone">{{ $communityPosts->count() }} cerita terpilih</p>
        @endif
      </div>

      @if ($communityPosts->isNotEmpty())
        @php
          $tones = ['community-tone-clay', 'community-tone-sage', 'community-tone-wine'];
        @endphp
        <div class="mt-5 grid gap-2 sm:grid-cols-3 sm:gap-3">
          @foreach ($communityPosts as $post)
            <article>
              <div class="aspect-[4/5] overflow-hidden bg-mist">
                <img src="{{ asset('storage/'.$post->image) }}" alt="Kiriman komunitas oleh {{ $post->user->name }}" width="800" height="1000" class="h-full w-full object-cover" loading="lazy">
              </div>
              <p class="mt-3 text-sm leading-6">{{ $post->caption }}</p>
              <p class="mt-1 text-xs text-stone">{{ $post->user->name }} · {{ $post->published_at?->translatedFormat('d M Y') }}</p>
            </article>
          @endforeach
        </div>
      @else
        <div class="mt-5 grid grid-cols-3 gap-2 sm:gap-3">
          @foreach ([1, 2, 3] as $number)
            <div class="aspect-[4/5] overflow-hidden bg-mist">
              <x-img class="h-full w-full !rounded-none !bg-transparent" src="{{ $number === 1 ? 'images/calmats/preview-community.png' : null }}" alt="Foto komunitas CALMATS" label="Foto komunitas belum tersedia" />
            </div>
          @endforeach
        </div>
        <p class="mt-4 text-center text-sm text-stone">Share your Calmats moment</p>
      @endif
    </section>

    <section id="share" class="mt-8 border-t border-ink/15 pt-8">
      <div>
        <h2 class="font-serif text-2xl">Share your Calmats moment</h2>
        <p class="mt-2 max-w-lg text-sm leading-6 text-stone">Bagikan interpretasi, keseharian, atau proses kreatifmu bersama CALMATS. Setiap kiriman akan diperiksa admin sebelum tampil di feed.</p>
      </div>
      <div class="mt-5 max-w-2xl">
        @auth
          <form method="POST" action="{{ route('community.store') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ preview: null, fileName: '' }">
            @csrf
            <div>
              <label for="community-image" class="text-sm font-medium">Foto</label>
              <label for="community-image" class="mt-2 flex min-h-44 cursor-pointer items-center justify-center overflow-hidden border border-ink/20 text-center transition hover:border-ink">
                <img x-show="preview" x-cloak :src="preview" alt="Preview foto komunitas" class="h-64 w-full object-cover">
                <span x-show="!preview" class="px-6 py-10">
                  <span class="block text-lg font-medium">Pilih foto</span>
                  <span class="mt-2 block text-sm text-stone">JPG, PNG, atau WebP · maksimal 5 MB</span>
                </span>
              </label>
              <input id="community-image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required class="sr-only" x-on:change="const file = $event.target.files[0]; if (preview) URL.revokeObjectURL(preview); preview = file ? URL.createObjectURL(file) : null; fileName = file?.name ?? ''">
              <p x-show="fileName" x-cloak x-text="fileName" class="mt-2 break-all text-sm text-stone"></p>
              @error('image')<p class="mt-2 text-sm text-red-200" role="alert">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ caption: @js(old('caption', '')) }">
              <div class="flex items-center justify-between gap-4">
                <label for="community-caption" class="text-sm font-medium">Caption</label>
                <span class="text-xs text-stone"><span x-text="caption.length"></span>/500</span>
              </div>
              <textarea id="community-caption" name="caption" x-model="caption" maxlength="500" rows="5" required placeholder="Ceritakan momen, gaya, atau proses kreatifmu..." class="mt-2 w-full border border-ink/20 bg-transparent px-4 py-3 text-ink placeholder:text-stone focus:border-ink">{{ old('caption') }}</textarea>
              @error('caption')<p class="mt-2 text-sm text-red-200" role="alert">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="min-h-11 bg-ink px-6 py-3 text-sm font-semibold text-paper hover:bg-accent">Upload your photo wearing Calmats</button>
          </form>
        @else
          <div>
            <p class="text-sm text-stone">Masuk untuk mengirim foto. Kiriman akan ditinjau sebelum tampil di feed.</p>
            <a href="{{ route('login') }}" class="mt-4 inline-flex min-h-11 items-center bg-ink px-6 py-3 text-sm font-semibold text-paper hover:bg-accent">Login</a>
          </div>
        @endauth
      </div>
    </section>

    @auth
      @if ($mySubmissions->isNotEmpty())
        <section class="mt-16">
          <div class="border-b border-ink/15 pb-6">
            <p class="eyebrow">Kiriman saya</p>
            <h2 class="mt-3 font-serif text-4xl italic">Status moderasi.</h2>
          </div>
          <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($mySubmissions as $submission)
              <article class="overflow-hidden rounded-[1.5rem] border border-ink/10 bg-white/45 shadow-[0_14px_38px_rgba(40,31,25,.07)]">
                <img src="{{ asset('storage/'.$submission->image) }}" alt="Kiriman komunitas milikmu" class="aspect-[4/3] w-full object-cover" loading="lazy">
                <div class="p-5">
                  <span @class([
                    'inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em]',
                    'bg-amber-100 text-amber-900' => $submission->status === 'pending',
                    'bg-green-100 text-green-900' => $submission->status === 'approved',
                    'bg-red-100 text-red-900' => $submission->status === 'rejected',
                  ])>
                    {{ match ($submission->status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu moderasi' } }}
                  </span>
                  <p class="mt-4 line-clamp-3 leading-7 text-stone">{{ $submission->caption }}</p>
                  @if ($submission->status === 'rejected' && $submission->moderation_note)
                    <p class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-900"><strong>Catatan admin:</strong> {{ $submission->moderation_note }}</p>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        </section>
      @endif
    @endauth
  </section>
</x-layouts.app>
