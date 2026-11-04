<x-auth-shell
  title="Reset Password"
  eyebrow="Create a new password"
  heading="Sandi baru"
  description="Buat kata sandi baru yang kuat agar akunmu tetap aman."
  quote="A fresh start, with everything important still close."
>
  <form method="POST" action="{{ route('password.update') }}" class="mt-9 space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div>
      <label for="email" class="text-xs font-semibold uppercase tracking-[0.14em]">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', $email) }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <div>
      <label for="password" class="text-xs font-semibold uppercase tracking-[0.14em]">Kata sandi baru</label>
      <input id="password" name="password" type="password" autocomplete="new-password" required class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
    </div>
    <div>
      <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.14em]">Ulangi kata sandi</label>
      <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
    </div>
    @error('password')<p class="text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    <button type="submit" class="btn btn-solid w-full">Simpan kata sandi</button>
  </form>
</x-auth-shell>
