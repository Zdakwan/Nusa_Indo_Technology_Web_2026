<header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <nav class="container mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 lg:h-20 gap-3">
            {{-- Logo: dibatasi lebarnya di layar kecil agar tidak mepet ke tombol kanan --}}
            <a href="{{ route('home') }}" class="flex items-center shrink-0 min-w-0">
                <img
                    src="{{ asset('images/LogoNusa.png') }}"
                    alt="Logo Nusa Indo Tech"
                    class="h-7 sm:h-8 w-auto max-w-[180px] sm:max-w-none object-contain"
                >
            </a>

            {{-- Menu Desktop --}}
            <ul class="hidden lg:flex items-center gap-7 text-sm font-medium text-gray-700">
                <li>
                    <a href="{{ route('home') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('/') ? 'text-nusaYellow font-semibold' : '' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('layanan') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('layanan*') ? 'text-nusaYellow font-semibold' : '' }}">
                        Layanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('portofolio.index') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('portofolio*') ? 'text-nusaYellow font-semibold' : '' }}">
                        Portofolio
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelatihan.index') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('pelatihan*') ? 'text-nusaYellow font-semibold' : '' }}">
                        Pelatihan
                    </a>
                </li>
                <li>
                    <a href="{{ route('ensiklopedia.index') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('ensiklopedia*') ? 'text-nusaYellow font-semibold' : '' }}">
                        Ensiklopedia
                    </a>
                </li>
                <li>
                    <a href="{{ route('tentang-kami') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->is('tentang-kami*') ? 'text-nusaYellow font-semibold' : '' }}">
                        Tentang Kami
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}"
                       class="transition-colors hover:text-nusaYellow {{ request()->routeIs('register') ? 'text-nusaYellow font-semibold' : '' }}">
                        Register
                    </a>
                </li>
            </ul>

            {{-- Tombol Login Desktop --}}
            <div class="hidden lg:block shrink-0">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-nusaNavyDark text-white text-sm font-semibold hover:bg-nusaNavy transition-colors shadow-sm">
                    Login
                </a>
            </div>

            {{-- Aksi Mobile: Login ringkas + Tombol Hamburger --}}
            <div class="flex items-center gap-2 lg:hidden shrink-0">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 rounded-full bg-nusaNavyDark text-white text-xs font-semibold hover:bg-nusaNavy transition-colors">
                    Login
                </a>

                <button
                    id="publicMobileMenuBtn"
                    type="button"
                    aria-label="Buka menu navigasi"
                    aria-controls="publicMobileMenu"
                    aria-expanded="false"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-nusaNavy hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-nusaYellow transition-colors"
                >
                    <i id="publicIconOpen" class="fa-solid fa-bars text-lg"></i>
                    <i id="publicIconClose" class="fa-solid fa-xmark text-xl hidden"></i>
                </button>
            </div>
        </div>

        {{-- Dropdown Menu Mobile --}}
        <div
            id="publicMobileMenu"
            class="hidden lg:hidden border-t border-gray-100 py-3 max-h-[calc(100dvh-4rem)] overflow-y-auto"
        >
            <ul class="flex flex-col space-y-1 text-sm font-medium text-gray-700">
                <li>
                    <a href="{{ route('home') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('/') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('layanan') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('layanan*') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Layanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('portofolio.index') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('portofolio*') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Portofolio
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelatihan.index') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('pelatihan*') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Pelatihan
                    </a>
                </li>
                <li>
                    <a href="{{ route('ensiklopedia.index') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('ensiklopedia*') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Ensiklopedia
                    </a>
                </li>
                <li>
                    <a href="{{ route('tentang-kami') }}"
                       class="block px-3 py-2.5 rounded-lg transition-colors {{ request()->is('tentang-kami*') ? 'bg-nusaYellow/15 text-nusaNavy font-bold' : 'hover:bg-gray-50 hover:text-nusaYellow' }}">
                        Tentang Kami
                    </a>
                </li>
            </ul>

            {{-- Tombol Akses Cepat Akun di Mobile --}}
            <div class="grid grid-cols-2 gap-2.5 mt-3 pt-3 border-t border-gray-100">
                <a href="{{ route('register') }}"
                   class="py-2.5 px-4 rounded-xl border border-nusaNavy text-nusaNavy text-center text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Register
                </a>
                <a href="{{ route('login') }}"
                   class="py-2.5 px-4 rounded-xl bg-nusaNavyDark text-white text-center text-sm font-semibold hover:bg-nusaNavy transition-colors">
                    Login Klien
                </a>
            </div>
        </div>
    </nav>
</header>

<script>
    (() => {
        const btn = document.getElementById('publicMobileMenuBtn');
        const menu = document.getElementById('publicMobileMenu');
        const iconOpen = document.getElementById('publicIconOpen');
        const iconClose = document.getElementById('publicIconClose');

        if (!btn || !menu || !iconOpen || !iconClose) return;

        const setMenuState = (isOpen) => {
            menu.classList.toggle('hidden', !isOpen);
            iconOpen.classList.toggle('hidden', isOpen);
            iconClose.classList.toggle('hidden', !isOpen);
            btn.setAttribute('aria-expanded', String(isOpen));
        };

        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            setMenuState(menu.classList.contains('hidden'));
        });

        // Tutup menu saat klik di luar navbar atau menekan Escape
        document.addEventListener('click', (event) => {
            if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !btn.contains(event.target)) {
                setMenuState(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') setMenuState(false);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) setMenuState(false);
        });
    })();
</script>
