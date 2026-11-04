<x-auth-shell
  title="Register"
  eyebrow="Join the movement"
  heading="Buat akun"
  description="Simpan pilihanmu dan lanjutkan pengalaman CALMATS dari perangkat mana pun."
  quote="A personal space for quiet choices and considered movement."
>
  <form method="POST" action="{{ route('register.store') }}" class="mt-9 space-y-5">
    @csrf
    <div>
      <label for="name" class="text-xs font-semibold uppercase tracking-[0.14em]">Nama lengkap</label>
      <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('name')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <div>
      <label for="email" class="text-xs font-semibold uppercase tracking-[0.14em]">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="password" class="text-xs font-semibold uppercase tracking-[0.14em]">Kata sandi</label>
        <input id="password" name="password" type="password" autocomplete="new-password" required class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      </div>
      <div>
        <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.14em]">Ulangi sandi</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      </div>
    </div>
    @error('password')<p class="text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    <p class="text-sm text-stone">Gunakan minimal 8 karakter. Jangan memakai kata sandi yang sama dengan akun lain.</p>
    <button type="submit" class="btn btn-solid w-full">Buat akun</button>
  </form>

  <p class="mt-7 border-t border-mist pt-6 text-sm text-stone">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-accent hover:underline">Login</a></p>
</x-auth-shell>
