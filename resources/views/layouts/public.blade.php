<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nusa Indo Technology - IT Management Consultant')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nusaNavy: '#23364f',
                        nusaNavyDark: '#0f172a',
                        nusaYellow: '#f5b73e',
                        nusaYellowHover: '#e0a430',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- CSS Utama Kustom -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Menghilangkan kotak biru bawaan saat tap di browser HP */
        a, button { -webkit-tap-highlight-color: transparent; }
    </style>
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-[100dvh] overflow-x-hidden font-sans">

    <!-- NAVBAR PUBLIK -->
    @include('partials.navbar')

    <!-- KONTEN UTAMA -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER PUBLIK -->
    @include('partials.footer')

    @stack('scripts')
    <script src="{{ asset('js/mobile-ux.js') }}" defer></script>
</body>
</html>
