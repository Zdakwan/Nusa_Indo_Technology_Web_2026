<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Nusa Indo Tech')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .sidebar-bg {
            background-color: #1e3352;
        }

        .nav-item-active {
            background-color: #f3f4f6;
            color: #1e3352;
            font-weight: 600;
        }

        .nav-item-active i {
            color: #1e3352;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
</head>

<body class="flex h-[100dvh] overflow-hidden bg-[#f3f4f6]">

    @php
        $client = Auth::guard('client')->user();
        $jumlahNotifikasiBelumDibaca = 0;

        if ($client) {
            $jumlahNotifikasiBelumDibaca = \App\Models\Notifikasi::where('client_id', $client->id)
                ->where('is_read', false)
                ->count();
        }

        $menuKonsultasiAktif =
            request()->is('konsultasi*')
            || request()->is('proyek*')
            || request()->routeIs('notifikasi.*');
    @endphp

    {{-- BACKDROP MOBILE --}}
    <div
        id="sidebarBackdrop"
        onclick="toggleSidebar(false)"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-[1px] z-30 hidden md:hidden transition-opacity duration-300"
        aria-hidden="true"
    ></div>

    {{-- SIDEBAR (Off-canvas di HP, statis di Desktop) --}}
    <aside
        id="mobileSidebar"
        class="fixed inset-y-0 left-0 w-72 md:w-64 sidebar-bg text-gray-300 flex flex-col transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out z-40 shrink-0 shadow-xl md:shadow-none"
    >
        {{-- LOGO & TOMBOL TUTUP MOBILE --}}
        <div class="h-16 flex items-center justify-between px-5 md:px-6 bg-white border-b border-gray-200 shadow-sm shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img
                    src="{{ asset('images/LogoNusa.png') }}"
                    alt="Logo Nusa Indo Tech"
                    class="h-6 w-auto object-contain"
                >
            </a>

            <button
                type="button"
                onclick="toggleSidebar(false)"
                aria-label="Tutup menu"
                class="md:hidden p-2 -mr-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
            {{-- MENU DASHBOARD --}}
            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'nav-item-active' : 'hover:bg-[#2a4165] text-white' }} flex items-center px-4 py-3 rounded-lg mb-2 transition-colors"
            >
                <i class="fas fa-th-large w-6 text-lg"></i>
                <span class="ml-2 font-medium">Dashboard</span>
            </a>

            {{-- DROPDOWN PUSAT PENGGUNA --}}
            <div class="mb-2">
                <button
                    type="button"
                    onclick="toggleDropdown('menuPusatPengguna', 'iconPusatPengguna')"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors focus:outline-none {{ request()->is('profile*') ? 'nav-item-active' : 'hover:bg-[#2a4165] text-white' }}"
                >
                    <div class="flex items-center font-medium">
                        <i class="fas fa-user-circle w-6 text-lg"></i>
                        <span class="ml-2">Pusat Pengguna</span>
                    </div>

                    <i
                        id="iconPusatPengguna"
                        data-dropdown-icon
                        class="fas fa-chevron-down text-sm transition-transform duration-300 {{ request()->is('profile*') ? '' : '-rotate-90' }}"
                    ></i>
                </button>

                <div
                    id="menuPusatPengguna"
                    data-dropdown-menu
                    class="pl-11 pr-3 py-2 space-y-1 {{ request()->is('profile*') ? '' : 'hidden' }}"
                >
                    <a
                        href="{{ route('profile.index') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('profile.index') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="fas fa-id-card w-5"></i>
                        <span>Data Diri</span>
                    </a>

                    <a
                        href="{{ route('profile.edit') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('profile.edit') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="fas fa-user-edit w-5"></i>
                        <span>Edit Data Diri</span>
                    </a>
                </div>
            </div>

            {{-- DROPDOWN KONSULTASI --}}
            <div class="mb-2 mt-3">
                <button
                    type="button"
                    onclick="toggleDropdown('menuKonsultasi', 'iconKonsultasi')"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors focus:outline-none {{ $menuKonsultasiAktif ? 'nav-item-active' : 'hover:bg-[#2a4165] text-white' }}"
                >
                    <div class="flex items-center font-medium">
                        <i class="fas fa-book w-6 text-lg"></i>
                        <span class="ml-2">Konsultasi</span>
                    </div>

                    <i
                        id="iconKonsultasi"
                        data-dropdown-icon
                        class="fas fa-chevron-down text-sm transition-transform duration-300 {{ $menuKonsultasiAktif ? '' : '-rotate-90' }}"
                    ></i>
                </button>

                <div
                    id="menuKonsultasi"
                    data-dropdown-menu
                    class="pl-11 pr-3 py-2 space-y-1 mt-1 {{ $menuKonsultasiAktif ? '' : 'hidden' }}"
                >
                    <a
                        href="{{ route('konsultasi.booking') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('konsultasi.booking') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="fas fa-headset w-5"></i>
                        <span>Booking</span>
                    </a>

                    <a
                        href="{{ route('konsultasi.index') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('konsultasi.index', 'konsultasi.detail', 'konsultasi.jadwal') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="fas fa-folder w-5"></i>
                        <span>Konsultasi Saya</span>
                    </a>

                    <a
                        href="{{ route('proyek.index') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('proyek.*') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="fas fa-briefcase w-5"></i>
                        <span>Proyek Saya</span>
                    </a>

                    <a
                        href="{{ route('notifikasi.index') }}"
                        class="flex items-center py-2 px-2 rounded-md text-sm {{ request()->routeIs('notifikasi.*') ? 'text-[#f7bd55] font-bold' : 'hover:text-white transition-colors' }}"
                    >
                        <i class="far fa-bell w-5"></i>
                        <span>Notifikasi</span>

                        @if ($jumlahNotifikasiBelumDibaca > 0)
                            <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-[20px] px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold">
                                {{ $jumlahNotifikasiBelumDibaca >= 99 ? '99+' : $jumlahNotifikasiBelumDibaca }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- MENU PENGATURAN --}}
            <a
                href="{{ route('pengaturan.index') }}"
                class="{{ request()->is('pengaturan*') ? 'nav-item-active' : 'hover:bg-[#2a4165] text-white' }} flex items-center px-4 py-3 rounded-lg mt-2 transition-colors"
            >
                <i class="fas fa-cog w-6 text-lg"></i>
                <span class="ml-2">Pengaturan</span>
            </a>

            {{-- MENU KELUAR --}}
            <form action="{{ route('logout') }}" method="POST" class="w-full mt-2">
                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-red-500/20 hover:text-red-400 transition-colors text-white font-medium text-left"
                >
                    <i class="fas fa-sign-out-alt w-6 text-lg"></i>
                    <span class="ml-2">Keluar</span>
                </button>
            </form>
        </nav>
    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 shadow-sm z-10 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <button
                    id="sidebarToggleBtn"
                    type="button"
                    onclick="toggleSidebar()"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                    class="relative p-2 -ml-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 md:hidden focus:outline-none"
                >
                    <i class="fas fa-bars text-xl"></i>
                    @if ($jumlahNotifikasiBelumDibaca > 0)
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </button>

                {{-- Logo ringkas di header khusus mobile --}}
                <img
                    src="{{ asset('images/LogoNusa.png') }}"
                    alt="Nusa Indo Tech"
                    class="h-5 w-auto object-contain md:hidden"
                >
            </div>

            {{-- INFORMASI CLIENT --}}
            <a href="{{ route('profile.index') }}" class="flex items-center space-x-3 shrink-0 group">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-gray-900 leading-none group-hover:text-[#1e3352]">
                        {{ $client->name ?? 'Pengguna' }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        Klien
                    </div>
                </div>

                <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300 shrink-0">
                    @if ($client && $client->avatar)
                        <img
                            src="{{ asset('storage/' . $client->avatar) }}"
                            alt="Avatar {{ $client->name }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <i class="fas fa-user text-gray-400 text-lg md:text-xl mt-1"></i>
                    @endif
                </div>
            </a>
        </header>

        {{-- AREA KONTEN HALAMAN --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f4f7fa] p-4 md:p-6">
            @yield('content')
        </main>
    </div>

    {{-- SCRIPT SIDEBAR MOBILE & DROPDOWN --}}
    <script>
        function toggleSidebar(forceOpen) {
            const sidebar = document.getElementById('mobileSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            const shouldOpen = typeof forceOpen === 'boolean' ? forceOpen : isClosed;

            sidebar.classList.toggle('-translate-x-full', !shouldOpen);
            backdrop.classList.toggle('hidden', !shouldOpen);

            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', String(shouldOpen));
            }
        }

        function toggleDropdown(menuId, iconId) {
            const targetMenu = document.getElementById(menuId);
            const targetIcon = document.getElementById(iconId);

            if (!targetMenu || !targetIcon) return;

            const willOpen = targetMenu.classList.contains('hidden');

            // Reset semua dropdown dan ikon panahnya sebelum membuka target
            document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                menu.classList.add('hidden');
            });
            document.querySelectorAll('[data-dropdown-icon]').forEach(icon => {
                icon.classList.add('-rotate-90');
            });

            if (willOpen) {
                targetMenu.classList.remove('hidden');
                targetIcon.classList.remove('-rotate-90');
            }
        }

        // Tutup drawer mobile dengan tombol Escape
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                toggleSidebar(false);
            }
        });
    </script>

    @stack('scripts')
    <script src="{{ asset('js/mobile-ux.js') }}" defer></script>
</body>
</html>
