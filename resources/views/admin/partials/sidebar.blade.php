@php
    $segment = request()->segment(2);
    $dashboardAktif = ($segment == 'dashboard');
    $kontenAktif    = in_array($segment, ['layanan', 'portofolio', 'pelatihan', 'ensiklopedia', 'tentang-kami']);
    $penggunaAktif  = in_array($segment, ['pengguna', 'client']);
    $approvalAktif  = ($segment == 'approval');
    $pengaturanAktif = ($segment == 'pengaturan');
    $riwayatAktif   = ($segment == 'riwayat-login');
    $proyekAktif = request()->routeIs('admin.proyek.*');

    $adminRole = Auth::guard('admin')->user()->role;
@endphp

<!-- Overlay Latar Belakang Gelap untuk Mobile (Hanya muncul saat sidebar terbuka) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-20 hidden md:hidden transition-opacity"></div>

<!-- Sidebar Container (Responsif dengan Translasi) -->
<!-- PERBAIKAN: Menambahkan class 'hide-scrollbar' -->
<aside id="sidebar" class="admin-sidebar hide-scrollbar fixed inset-y-0 left-0 z-30 w-64 bg-[#254261] text-white transition-transform duration-300 ease-in-out transform -translate-x-full md:relative md:translate-x-0 overflow-y-auto flex flex-col h-screen">

    <!-- Logo & Tombol Close (Mobile) -->
    <div class="bg-white h-16 flex items-center justify-between px-4 shrink-0 shadow-sm border-b border-gray-100">
        <img src="{{ asset('images/LogoNusa.png') }}" alt="Logo Nusa Indo Tech" class="h-8 w-auto object-contain">

        <!-- Tombol Close Sidebar (Hanya tampil di Mobile saat terbuka) -->
        <button type="button" id="closeSidebarBtn" class="md:hidden text-gray-500 hover:text-gray-800 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Navigasi -->
    <!-- PERBAIKAN: Menambahkan class 'hide-scrollbar' pada div navigasi jika meluap -->
    <nav class="px-3 py-4 font-medium space-y-1 text-[13px] flex-1 overflow-y-auto hide-scrollbar">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ $dashboardAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
            <i class="fas fa-table-columns w-5 shrink-0 text-center {{ $dashboardAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i>
            <span class="truncate">Dashboard</span>
        </a>

        <!-- Kelola Konten -->
        @if(in_array($adminRole, ['super_admin', 'admin_konten', 'admin_layanan']))
            <button type="button" onclick="toggleSubMenu('menuKonten', this)"
                    class="nav-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg focus:outline-none transition-colors {{ $kontenAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
                <span class="flex items-center gap-3 overflow-hidden">
                    <i class="fas fa-newspaper w-5 shrink-0 text-center {{ $kontenAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i>
                    <span class="truncate">Kelola Konten</span>
                </span>
                <i class="chevron fas fa-chevron-down shrink-0 text-[10px] transition-transform duration-300 {{ $kontenAktif ? 'rotate-180 text-[#254261]' : 'text-gray-200' }}"></i>
            </button>
            <div id="menuKonten" class="{{ $kontenAktif ? 'block' : 'hidden' }} pl-6 space-y-1 mt-1">

                @if(in_array($adminRole, ['super_admin', 'admin_layanan']))
                <a href="{{ route('admin.layanan.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'layanan' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-briefcase w-4 shrink-0 text-center"></i> <span class="truncate">Manajemen Layanan</span>
                </a>
                @endif

                @if(in_array($adminRole, ['super_admin', 'admin_konten']))
                <a href="{{ route('admin.portofolio.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'portofolio' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-folder-open w-4 shrink-0 text-center"></i> <span class="truncate">Portofolio Proyek</span>
                </a>
                <a href="{{ route('admin.pelatihan.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'pelatihan' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-graduation-cap w-4 shrink-0 text-center"></i> <span class="truncate">Pelatihan IT (LMS)</span>
                </a>
                <a href="{{ route('admin.ensiklopedia.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'ensiklopedia' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-book w-4 shrink-0 text-center"></i> <span class="truncate">Ensiklopedia IT (CMS)</span>
                </a>
                <a href="{{ route('admin.tentang-kami.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'tentang-kami' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-info-circle w-4 shrink-0 text-center"></i> <span class="truncate">Tentang Kami</span>
                </a>
                @endif
            </div>
        @endif

        <!-- Pengguna & Hak Akses -->
        @if(in_array($adminRole, ['super_admin', 'admin_layanan']))
            <button type="button" onclick="toggleSubMenu('menuPengguna', this)"
                    class="nav-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg focus:outline-none transition-colors {{ $penggunaAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
                <span class="flex items-center gap-3 overflow-hidden">
                    <i class="fas fa-circle-user w-5 shrink-0 text-center {{ $penggunaAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i>
                    <span class="truncate">Pengguna & Hak Akses</span>
                </span>
                <i class="chevron fas fa-chevron-down shrink-0 text-[10px] transition-transform duration-300 {{ $penggunaAktif ? 'rotate-180 text-[#254261]' : 'text-gray-200' }}"></i>
            </button>
            <div id="menuPengguna" class="{{ $penggunaAktif ? 'block' : 'hidden' }} pl-6 space-y-1 mt-1">

                @if($adminRole == 'super_admin')
                <a href="{{ route('admin.pengguna.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'pengguna' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-users-cog w-4 shrink-0 text-center"></i> <span class="truncate">Admin</span>
                </a>
                @endif

                @if(in_array($adminRole, ['super_admin', 'admin_layanan']))
                <a href="{{ route('admin.client.index') }}" class="nav-sub-link flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ $segment == 'client' ? 'text-white font-bold bg-[#334f6c]' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-users w-4 shrink-0 text-center"></i> <span class="truncate">Client</span>
                </a>
                @endif
            </div>

            <a href="{{ route('admin.riwayat-login.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ $riwayatAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
                <i class="fas fa-history w-5 shrink-0 text-center {{ $riwayatAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i> <span class="truncate">Riwayat Log In</span>
            </a>
        @endif

        <!-- Approval -->
        @if(in_array($adminRole, ['super_admin', 'admin_layanan']))
            <a href="{{ route('admin.approval.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ $approvalAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
                <i class="fas fa-file-circle-check w-5 shrink-0 text-center {{ $approvalAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i> <span class="truncate">Approval</span>
            </a>
        @endif

        <!-- Manajemen Proyek -->
        @if(in_array($adminRole, ['super_admin', 'admin_layanan']))
            <a href="{{ route('admin.proyek.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ $proyekAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
                <i class="fas fa-folder-open w-5 shrink-0 text-center {{ $proyekAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i>
                <span class="truncate">Manajemen Proyek</span>
            </a>
        @endif

        <!-- PENGATURAN -->
        <a href="{{ route('admin.pengaturan.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ $pengaturanAktif ? 'bg-white text-[#254261] font-bold shadow-sm' : 'text-gray-200 hover:bg-[#334f6c]' }}">
            <i class="fas fa-gear w-5 shrink-0 text-center {{ $pengaturanAktif ? 'text-[#254261]' : 'text-gray-200' }}"></i> <span class="truncate">Pengaturan</span>
        </a>

        <!-- Keluar -->
        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 border-t border-white/10 pt-2">
            @csrf
            <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-left text-[#ff8a8a] hover:bg-red-500 hover:text-white transition-colors">
                <i class="fas fa-right-from-bracket w-5 shrink-0 text-center"></i> <span class="truncate">Keluar</span>
            </button>
        </form>
    </nav>
</aside>

<script>
    function toggleSubMenu(targetId, buttonElement) {
        const target = document.getElementById(targetId);
        const chevron = buttonElement.querySelector('.chevron');

        if (target.classList.contains('hidden')) {
            target.classList.remove('hidden');
            target.classList.add('block');
            chevron.classList.add('rotate-180');
        } else {
            target.classList.remove('block');
            target.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }
</script>
