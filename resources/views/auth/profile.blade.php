<x-layouts.app title="Profile — CALMATS">
  <section class="mx-auto max-w-6xl px-5 py-14 lg:px-10 lg:py-20">
    <header class="grid items-end gap-8 border-b border-ink/15 pb-10 md:grid-cols-12">
      <div class="md:col-span-8">
        <p class="eyebrow">Personal space / Account</p>
        <h1 class="mt-5 font-serif text-6xl italic tracking-[-0.045em] sm:text-7xl">Profilmu.</h1>
        <p class="mt-5 max-w-xl text-sm text-stone">Kelola informasi dasar dan keamanan akun CALMATS dari satu tempat.</p>
      </div>
      <div class="md:col-span-4 md:text-right">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone">Member sejak {{ auth()->user()->created_at->format('M Y') }}</p>
      </div>
    </header>

    @if (session('status'))
      <div class="mt-8 rounded-[1.25rem] border border-green-800/15 bg-green-800/10 px-5 py-4 text-sm text-green-900" role="status">{{ session('status') }}</div>
    @endif

    <div class="mt-10 grid gap-7 lg:grid-cols-2">
      <article class="rounded-[1.75rem] border border-ink/10 bg-white/45 p-6 shadow-[0_18px_50px_rgba(40,31,25,.07)] sm:p-9">
        <p class="eyebrow">Profile information</p>
        <h2 class="mt-4 font-serif text-4xl italic">Identitas akun</h2>
        <p class="mt-3 text-sm text-stone">Nama ini akan digunakan pada pengalaman pemesanan berikutnya.</p>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
          @csrf
          @method('PATCH')
          <div>
            <label for="name" class="text-xs font-semibold uppercase tracking-[0.14em]">Nama lengkap</label>
            <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name', auth()->user()->name) }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
            @error('name')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="email" class="text-xs font-semibold uppercase tracking-[0.14em]">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', auth()->user()->email) }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
            @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
          </div>
          <button type="submit" class="btn btn-accent">Simpan profil</button>
        </form>
      </article>

      <article class="rounded-[1.75rem] bg-ink p-6 text-paper shadow-[0_22px_60px_rgba(25,22,19,.18)] sm:p-9">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-paper/65">Account security</p>
        <h2 class="mt-4 font-serif text-4xl italic">Ubah kata sandi</h2>
        <p class="mt-3 text-sm text-paper/70">Gunakan kata sandi unik dengan minimal delapan karakter.</p>

        <form method="POST" action="{{ route('profile.password') }}" class="mt-8 space-y-5">
          @csrf
          @method('PUT')
          <div>
            <label for="current_password" class="text-xs font-semibold uppercase tracking-[0.14em]">Sandi saat ini</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required class="mt-2 w-full border border-paper/20 bg-paper/10 px-4 py-3.5 text-paper focus:border-paper/60">
            @error('current_password', 'passwordUpdate')<p class="mt-2 text-sm text-red-300" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="new_password" class="text-xs font-semibold uppercase tracking-[0.14em]">Sandi baru</label>
            <input id="new_password" name="password" type="password" autocomplete="new-password" required class="mt-2 w-full border border-paper/20 bg-paper/10 px-4 py-3.5 text-paper focus:border-paper/60">
          </div>
          <div>
            <label for="new_password_confirmation" class="text-xs font-semibold uppercase tracking-[0.14em]">Ulangi sandi baru</label>
            <input id="new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="mt-2 w-full border border-paper/20 bg-paper/10 px-4 py-3.5 text-paper focus:border-paper/60">
          </div>
          @error('password', 'passwordUpdate')<p class="text-sm text-red-300" role="alert">{{ $message }}</p>@enderror
          <button type="submit" class="btn bg-paper text-ink hover:bg-accent hover:text-paper">Perbarui sandi</button>
        </form>
      </article>
    </div>
  </section>
</x-layouts.app>
