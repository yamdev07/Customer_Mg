<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts & icônes -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .auth-wrap {
                min-height: 100vh;
                display: flex; align-items: center; justify-content: center;
                padding: 1.5rem;
                background:
                    radial-gradient(1200px 600px at 80% -10%, rgba(6,182,212,.20), transparent 60%),
                    radial-gradient(900px 500px at -10% 110%, rgba(59,130,246,.20), transparent 55%),
                    var(--ax-bg);
            }
            .auth-card {
                width: 100%; max-width: 27rem;
                background: var(--ax-surface);
                border: 1px solid var(--ax-border);
                border-radius: 22px;
                box-shadow: var(--ax-shadow-lg);
                overflow: hidden;
            }
            .auth-card__head {
                background: var(--ax-gradient);
                padding: 2rem 2rem 1.5rem;
                text-align: center;
            }
            .auth-card__head img { height: 52px; width: auto; filter: brightness(0) invert(1); }
            .auth-card__head p { color: rgba(255,255,255,.85); margin: .65rem 0 0; font-size: .9rem; }
            .auth-card__body { padding: 1.75rem 1.75rem 2rem; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="auth-wrap">
            <div class="auth-card">
                <div class="auth-card__head">
                    <a href="/">
                        <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo AnyxTech">
                    </a>
                    <p>Espace de gestion des clients</p>
                </div>
                <div class="auth-card__body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
