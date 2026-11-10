<x-auth-shell
  title="Login"
  eyebrow="Welcome back"
  heading="Login"
  description="Masuk untuk menyimpan pilihan, menyinkronkan keranjang, dan mengelola profilmu."
  quote="Keep your favorites close and your movement effortless."
  :simple="true"
>
  <form method="POST" action="{{ route('login.store') }}" class="mt-9 space-y-6">
    @csrf
    <div>
      <label for="email" class="text-xs font-semibold uppercase tracking-[0.14em]">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <div>
      <div class="flex items-center justify-between gap-4">
        <label for="password" class="text-xs font-semibold uppercase tracking-[0.14em]">Kata sandi</label>
        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-accent hover:underline">Lupa sandi?</a>
      </div>
      <input id="password" name="password" type="password" autocomplete="current-password" required class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('password')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <label class="flex items-center gap-3 text-sm text-stone">
      <input type="checkbox" name="remember" value="1" class="h-5 w-5 border-mist"> Ingat saya
    </label>
    <button type="submit" class="btn btn-solid w-full">Masuk</button>
  </form>

  <div class="mt-7 space-y-3 border-t border-mist pt-6 text-sm text-stone">
    <p>Belum memiliki akun? <a href="{{ route('register') }}" class="font-semibold text-accent hover:underline">Buat akun</a></p>
    <p>Pengelola CALMATS? <a href="{{ url('/admin/login') }}" class="font-semibold text-accent hover:underline">Masuk ke control room</a></p>
  </div>
</x-auth-shell>
