<x-filament-widgets::widget>
    <section class="relative overflow-hidden rounded-[1.75rem] bg-[#191613] px-7 py-8 text-white shadow-[0_24px_65px_rgba(25,22,19,.18)] sm:px-10 sm:py-10">
        <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-[38%] bg-[url('/images/calmats/preview-shop.png')] bg-cover bg-center opacity-45 lg:block"></div>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-[#191613] via-[#191613]/95 to-[#7b171c]/35"></div>

        <div class="relative z-10 max-w-3xl">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.26em] text-[#d49a9d]">CALMATS / Admin control room</p>
            <h2 class="mt-5 font-serif text-4xl italic tracking-[-0.04em] sm:text-5xl">Selamat datang, {{ filament()->getUserName(filament()->auth()->user()) }}.</h2>
            <p class="mt-5 max-w-xl text-sm font-medium leading-6 text-white/80">Kelola produk, stok, foto, publikasi katalog, dan kiriman Community dari dashboard ini.</p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ \App\Filament\Resources\Products\ProductResource::getUrl('create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#7b171c] px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-[#8f2228]">
                    <span aria-hidden="true">+</span> Tambah produk
                </a>
                <a href="{{ \App\Filament\Resources\Products\ProductResource::getUrl('index') }}" class="inline-flex items-center rounded-full border border-white/25 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-white hover:text-[#191613]">
                    Kelola katalog
                </a>
                <a href="{{ \App\Filament\Resources\CommunityPosts\CommunityPostResource::getUrl('index') }}" class="inline-flex items-center rounded-full border border-white/25 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-white hover:text-[#191613]">
                    Moderasi Community
                </a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white/80 transition hover:text-white">
                    Lihat website &nearr;
                </a>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
