<footer class="bg-[#0f172a] text-gray-400 py-10 sm:py-12 mt-auto">
    <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-10 lg:gap-16">

            <!-- KOLOM 1: INFO PERUSAHAAN -->
            <div class="md:col-span-5">
                <img
                    src="{{ asset('images/LogoNusa.png') }}"
                    alt="Nusa Indo Technology"
                    class="h-8 sm:h-10 w-auto object-contain mb-5"
                >
                <p class="text-sm leading-relaxed mb-5">
                    Solusi IT terintegrasi untuk masa depan bisnis Anda. Menyediakan layanan konsultasi, arsitektur sistem, tata kelola, dan pengembangan teknologi profesional.
                </p>
                <div class="space-y-2.5 text-sm">
                    <a href="tel:+6287852461990" class="inline-flex items-center gap-3 text-white hover:text-[#f5b73e] transition-colors">
                        <i class="fa-solid fa-phone text-[#f5b73e] shrink-0"></i>
                        <span>+62 878-5246-1990</span>
                    </a>
                    <br>
                    <a href="mailto:nusaindotech@gmail.com" class="inline-flex items-center gap-3 text-white hover:text-[#f5b73e] transition-colors break-all">
                        <i class="fa-solid fa-envelope text-[#f5b73e] shrink-0"></i>
                        <span>nusaindotech@gmail.com</span>
                    </a>
                </div>
            </div>

            <!-- KOLOM 2 & 3: 2 Kolom Sejajar di Mobile agar Ringkas -->
            <div class="grid grid-cols-2 gap-6 sm:gap-8 md:col-span-7">
                <!-- NAVIGASI PUBLIK -->
                <div>
                    <h4 class="text-white font-bold tracking-wider mb-4 sm:mb-6 text-xs sm:text-sm uppercase">Navigasi</h4>
                    <ul class="space-y-2.5 sm:space-y-3.5 text-sm">
                        <li><a href="{{ route('home') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Beranda</a></li>
                        <li><a href="{{ route('layanan') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Layanan IT</a></li>
                        <li><a href="{{ route('portofolio.index') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Portofolio Proyek</a></li>
                        <li><a href="{{ route('pelatihan.index') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Pelatihan (LMS)</a></li>
                        <li><a href="{{ route('ensiklopedia.index') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Ensiklopedia IT</a></li>
                        <li><a href="{{ route('tentang-kami') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- AKUN (PENGUNJUNG) -->
                <div>
                    <h4 class="text-white font-bold tracking-wider mb-4 sm:mb-6 text-xs sm:text-sm uppercase">Layanan &amp; Akun</h4>
                    <ul class="space-y-2.5 sm:space-y-3.5 text-sm">
                        <li><a href="{{ route('login') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Login Klien</a></li>
                        <li><a href="{{ route('register') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Pendaftaran Akun</a></li>
                        <li><a href="{{ route('login') }}" class="inline-block py-0.5 hover:text-[#f5b73e] transition-colors">Booking Konsultasi</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- FOOTER BAWAH -->
        <div class="mt-10 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-3 text-xs text-center md:text-left">
            <p>&copy; 2026 Nusa Indo Technology. Hak cipta dilindungi.</p>
            <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-2">
                <p>By Politeknik Negeri Jember PSDKU Sidoarjo</p>
                <span class="hidden sm:inline text-gray-700">|</span>
                <a href="{{ route('admin.login') }}" class="text-gray-600 hover:text-gray-300 transition-colors" title="Login Portal Admin">
                    <i class="fa-solid fa-shield-halved"></i> Admin
                </a>
            </div>
        </div>
    </div>
</footer>
