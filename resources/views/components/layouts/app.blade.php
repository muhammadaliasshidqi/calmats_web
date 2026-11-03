<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'CALMATS — Tenang itu sikap' }}</title>
  <meta name="description" content="CALMATS adalah brand fashion minimalis dengan karakter streetwear dan cerita di setiap produk.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Familjen+Grotesk:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;1,6..72,400&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="premium-page bg-paper font-sans text-ink antialiased">
  <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[60] focus:bg-ink focus:px-4 focus:py-2 focus:text-paper">Lewati ke konten</a>
  <x-navbar />
  @if (session('status'))
    <div class="border-b border-mist bg-white px-5 py-3 text-center text-sm" role="status">{{ session('status') }}</div>
  @endif
  <main id="main">{{ $slot }}</main>
  <x-footer />
</body>
</html>
