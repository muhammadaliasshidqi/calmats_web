<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') — @yield('title') | CALMATS</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f6f1e9;
            color: #191613;
        }

        * { box-sizing: border-box; }
        body { margin: 0; }

        main {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
            background:
                radial-gradient(circle at top left, rgba(123, 23, 28, .12), transparent 34rem),
                #f6f1e9;
        }

        .card {
            width: min(100%, 42rem);
            padding: clamp(2rem, 6vw, 4rem);
            border: 1px solid rgba(25, 22, 19, .14);
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, .72);
            box-shadow: 0 1.5rem 4rem rgba(25, 22, 19, .08);
            text-align: center;
        }

        .brand {
            margin: 0 0 2rem;
            color: #7b171c;
            font-family: Georgia, "Times New Roman", serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .22em;
        }

        .code {
            margin: 0;
            color: #7b171c;
            font-size: clamp(3.5rem, 12vw, 7rem);
            font-weight: 800;
            line-height: .9;
        }

        h1 {
            margin: 1.5rem 0 .75rem;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(1.8rem, 5vw, 3rem);
            font-weight: 500;
        }

        .message {
            max-width: 34rem;
            margin: 0 auto;
            color: #615a52;
            font-size: 1rem;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .75rem;
            margin-top: 2rem;
        }

        .button {
            display: inline-flex;
            min-height: 2.8rem;
            align-items: center;
            justify-content: center;
            padding: .75rem 1.25rem;
            border: 1px solid #7b171c;
            border-radius: 999px;
            color: #fff;
            background: #7b171c;
            font-size: .86rem;
            font-weight: 700;
            text-decoration: none;
        }

        .button.secondary { color: #7b171c; background: transparent; }
    </style>
</head>
<body>
    <main>
        <section class="card" aria-labelledby="error-title">
            <p class="brand">CALMATS</p>
            <p class="code">@yield('code')</p>
            <h1 id="error-title">@yield('title')</h1>
            <p class="message">@yield('message')</p>
            <div class="actions">
                <a class="button" href="{{ url('/') }}">Kembali ke beranda</a>
                <a class="button secondary" href="javascript:history.back()">Kembali ke halaman sebelumnya</a>
            </div>
        </section>
    </main>
</body>
</html>
