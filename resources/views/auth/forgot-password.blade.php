<x-auth-shell
  title="Lupa Password"
  eyebrow="Account recovery"
  heading="Lupa sandi?"
  description="Masukkan email akunmu. Kami akan mengirimkan tautan untuk membuat kata sandi baru."
  quote="Take a breath. Your account is still within reach."
  :simple="true"
>
  @if (session('status'))
    <div class="mt-8 rounded-[1.25rem] border border-green-800/15 bg-green-800/10 px-5 py-4 text-sm text-green-900" role="status">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('password.email') }}" class="mt-9 space-y-6">
    @csrf
    <div>
      <label for="email" class="text-xs font-semibold uppercase tracking-[0.14em]">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="mt-2 w-full border border-mist bg-paper/60 px-4 py-3.5 focus:border-accent">
      @error('email')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn btn-solid w-full">Kirim tautan reset</button>
  </form>

  <a href="{{ route('login') }}" class="link-line mt-7 inline-block">Kembali ke login</a>
</x-auth-shell>
