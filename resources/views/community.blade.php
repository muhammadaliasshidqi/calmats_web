<x-layouts.app title="Community — CALMATS">
  <section class="mx-auto max-w-[1440px] px-5 pb-20 pt-10 lg:px-12 lg:pb-28 lg:pt-16">
    <div class="relative overflow-hidden rounded-[2rem] border border-ink/10 bg-[#191513] px-7 text-paper shadow-[0_24px_70px_rgba(35,25,22,.16)] sm:px-12 lg:px-16">
      <div class="relative z-10 flex min-h-[500px] max-w-5xl flex-col justify-center py-16 lg:py-24">
        <p class="eyebrow">CALMATS Community / 01</p>
        <h1 class="mt-7 font-serif text-6xl leading-[0.9] tracking-[-0.045em] sm:text-8xl lg:text-[7rem]">Different pace.<br><em>Same energy.</em></h1>
        <p class="mt-8 max-w-xl text-base leading-7 text-paper/65">Ruang untuk orang-orang yang memilih bergerak dengan caranya sendiri—berbagi gaya, proses, dan cerita tanpa perlu menjadi paling bising.</p>
        <div class="mt-12 text-[0.62rem] font-semibold uppercase tracking-[0.25em] text-paper/60">
          People, culture & movement
        </div>
      </div>

    </div>

    <section id="feed" class="mt-16 md:mt-20">
      <div class="flex flex-wrap items-end justify-between gap-5 border-b border-ink/15 pb-7">
        <div>
          <p class="eyebrow">Community feed</p>
          <h2 class="mt-3 font-serif text-4xl italic sm:text-5xl">Stories from the collective.</h2>
        </div>
        @if ($communityPosts->isNotEmpty())
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone">{{ $communityPosts->count() }} cerita terpilih</p>
        @endif
      </div>

      @if ($communityPosts->isNotEmpty())
        @php
          $tones = ['community-tone-clay', 'community-tone-sage', 'community-tone-wine'];
        @endphp
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($communityPosts as $post)
            <article class="community-gallery-card {{ $tones[$loop->index % count($tones)] }}">
              <div class="flex items-center justify-between gap-4 px-5 py-4 text-xs font-semibold uppercase tracking-[0.16em]">
                <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / Community</span>
                <span>{{ $post->user->name }}</span>
              </div>
              <img src="{{ asset('storage/'.$post->image) }}" alt="Kiriman komunitas oleh {{ $post->user->name }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
              <div class="px-5 py-5">
                <p class="leading-7">{{ $post->caption }}</p>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] opacity-65">{{ $post->published_at?->translatedFormat('d M Y') }}</p>
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-12 md:items-end md:gap-6">
          <article class="community-gallery-card community-tone-clay col-span-2 md:col-span-5">
            <div class="flex items-center justify-between px-4 py-3 text-xs font-semibold uppercase tracking-[0.16em]">
              <span>01 / Everyday</span><span>CALMATS</span>
            </div>
            <x-img class="aspect-[4/5] !bg-transparent" src="images/calmats/collection-01/community-work-02.jpg" alt="Proses kreatif bersama kaus CALMATS" label="Momen komunitas belum tersedia" />
          </article>
          <article class="community-gallery-card community-tone-sage md:col-span-3 md:translate-y-10">
            <div class="flex items-center justify-between px-4 py-3 text-xs font-semibold uppercase tracking-[0.16em]">
              <span>02</span><span>Culture</span>
            </div>
            <x-img class="aspect-square !bg-transparent" src="images/calmats/collection-01/flatlay-01.jpg" alt="Kaus CALMATS ditata bersama materi koleksi" label="Momen komunitas belum tersedia" />
          </article>
          <article class="community-gallery-card community-tone-wine md:col-span-4">
            <div class="flex items-center justify-between px-4 py-3 text-xs font-semibold uppercase tracking-[0.16em]">
              <span>03 / Motion</span><span>Collective</span>
            </div>
            <x-img class="aspect-[3/4] !bg-transparent" src="images/calmats/collection-01/night-ride.jpg" alt="CALMATS dalam perjalanan malam kota" img-class="object-[center_58%]" label="Momen komunitas belum tersedia" />
          </article>
        </div>
      @endif
    </section>

    <section id="share" class="community-invite mt-20 grid gap-10 px-6 py-10 text-paper sm:mt-24 sm:px-10 sm:py-12 lg:grid-cols-2 lg:px-14 lg:py-16">
      <div>
        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.25em] text-paper/60">Your pace, your story</p>
        <h2 class="mt-5 font-serif text-5xl italic tracking-[-0.035em] sm:text-6xl">Wear it your way.</h2>
        <p class="mt-6 max-w-lg leading-7 text-paper/70">Bagikan interpretasi, keseharian, atau proses kreatifmu bersama CALMATS. Setiap kiriman akan diperiksa admin sebelum tampil di feed.</p>
      </div>
      <div class="lg:self-end">
        @auth
          <form method="POST" action="{{ route('community.store') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ preview: null, fileName: '' }">
            @csrf
            <div>
              <label for="community-image" class="text-xs font-semibold uppercase tracking-[0.16em] text-paper/75">Foto</label>
              <label for="community-image" class="mt-2 flex min-h-44 cursor-pointer items-center justify-center overflow-hidden rounded-[1.35rem] border border-dashed border-paper/35 bg-paper/10 text-center transition hover:border-paper/70 hover:bg-paper/15">
                <img x-show="preview" x-cloak :src="preview" alt="Preview foto komunitas" class="h-64 w-full object-cover">
                <span x-show="!preview" class="px-6 py-10">
                  <span class="block font-serif text-2xl italic">Pilih foto</span>
                  <span class="mt-2 block text-sm text-paper/60">JPG, PNG, atau WebP · maksimal 5 MB</span>
                </span>
              </label>
              <input id="community-image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required class="sr-only" x-on:change="const file = $event.target.files[0]; if (preview) URL.revokeObjectURL(preview); preview = file ? URL.createObjectURL(file) : null; fileName = file?.name ?? ''">
              <p x-show="fileName" x-cloak x-text="fileName" class="mt-2 break-all text-sm text-paper/60"></p>
              @error('image')<p class="mt-2 text-sm text-red-200" role="alert">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ caption: @js(old('caption', '')) }">
              <div class="flex items-center justify-between gap-4">
                <label for="community-caption" class="text-xs font-semibold uppercase tracking-[0.16em] text-paper/75">Caption</label>
                <span class="text-xs text-paper/45"><span x-text="caption.length"></span>/500</span>
              </div>
              <textarea id="community-caption" name="caption" x-model="caption" maxlength="500" rows="5" required placeholder="Ceritakan momen, gaya, atau proses kreatifmu..." class="mt-2 w-full border border-paper/25 bg-paper/10 px-4 py-3 text-paper placeholder:text-paper/40 focus:border-paper/70">{{ old('caption') }}</textarea>
              @error('caption')<p class="mt-2 text-sm text-red-200" role="alert">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn w-full bg-paper text-ink hover:bg-accent hover:text-paper">Kirim untuk ditinjau</button>
          </form>
        @else
          <div class="rounded-[1.35rem] border border-paper/20 bg-paper/10 p-6">
            <p class="font-serif text-3xl italic">Masuk untuk berbagi.</p>
            <p class="mt-3 leading-7 text-paper/65">Login terlebih dahulu agar kiriman tersimpan di akunmu dan status moderasinya dapat dilihat.</p>
            <a href="{{ route('login') }}" class="btn mt-7 bg-paper text-ink hover:bg-accent hover:text-paper">Login</a>
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
