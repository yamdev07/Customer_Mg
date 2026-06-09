<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tableau de bord') · AnyxTech</title>

    <!-- Fonts & icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap (grille + composants) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Design system AnyxTech (chargé après Bootstrap) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="app-shell">

        {{-- Sidebar --}}
        @include('layouts.navigation')

        {{-- Overlay (mobile) --}}
        <div class="app-overlay" x-show="sidebarOpen" x-transition.opacity
             @click="sidebarOpen = false" x-cloak></div>

        {{-- Notifications globales --}}
        @include('partials.flash')

        {{-- Contenu --}}
        <div class="app-content">

            {{-- Barre supérieure --}}
            <header class="app-topbar">
                <button class="app-burger" @click="sidebarOpen = true" aria-label="Ouvrir le menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="me-auto">
                    <div class="app-topbar__title">@yield('title', 'Tableau de bord')</div>
                    <div class="app-topbar__sub">
                        <i class="far fa-calendar me-1"></i>{{ ucfirst(now()->translatedFormat('l d F Y')) }}
                    </div>
                </div>
                @isset($header)
                    <div class="d-none d-md-block">{{ $header }}</div>
                @endisset
            </header>

            {{-- Page --}}
            <main class="app-main">
                @yield('content')
            </main>

            <x-footer />
        </div>
    </div>

    <!-- Bootstrap JS (modales, dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
