<x-layouts.app title="Login — CALMATS">
  <section class="mx-auto grid min-h-[70vh] max-w-7xl px-5 py-16 lg:grid-cols-2 lg:px-10 lg:py-24">
    <div class="relative hidden overflow-hidden rounded-[1.75rem] bg-ink text-paper lg:flex lg:flex-col lg:justify-between lg:rounded-r-none">
      <div class="absolute inset-0">
        <x-img class="h-full w-full !rounded-none" src="images/calmats/preview-about.png" alt="Proses pembuatan koleksi CALMATS" label="Foto proses CALMATS belum tersedia" />
      </div>
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-black/20"></div>
      <p class="relative z-10 p-12 text-xs uppercase tracking-[0.2em] text-paper/70">CALMATS Account / 01</p>
      <div class="relative z-10 p-12">
        <p class="mb-5 h-px w-12 bg-accent"></p>
        <p class="max-w-md font-serif text-5xl italic leading-[0.95]">Keep your favorites close and your movement effortless.</p>
      </div>
    </div>

    <div class="flex items-center justify-center rounded-[1.75rem] border border-mist p-7 sm:p-12 lg:rounded-l-none">
      <div class="w-full max-w-md">
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-stone">Welcome back</p>
        <h1 class="mt-4 font-serif text-5xl tracking-tight">Login</h1>
        <p class="mt-4 text-sm leading-6 text-stone">Masuk menggunakan akun yang sudah terdaftar di sistem.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-9 space-y-6">
          @csrf
          <div>
            <label for="email" class="text-xs font-medium uppercase tracking-[0.14em]">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="mt-2 w-full border border-mist bg-transparent px-4 py-3.5 focus:border-accent">
            @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="password" class="text-xs font-medium uppercase tracking-[0.14em]">Kata sandi</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required class="mt-2 w-full border border-mist bg-transparent px-4 py-3.5 focus:border-accent">
            @error('password')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
          </div>
          <label class="flex items-center gap-3 text-sm text-stone">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 border-mist"> Ingat saya
          </label>
          <button type="submit" class="btn btn-solid w-full">Masuk</button>
        </form>

        <p class="mt-6 border-t border-mist pt-6 text-xs leading-5 text-stone">Pendaftaran publik belum dibuka. Akun dibuat oleh pengelola CALMATS.</p>
      </div>
    </div>
  </section>
</x-layouts.app>
