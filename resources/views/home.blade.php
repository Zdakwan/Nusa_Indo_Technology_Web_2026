@extends('layouts.public')

@section('title', 'Beranda - Nusa Indo Technology')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative bg-nusaNavy text-white py-12 sm:py-16 lg:py-28 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#f5b73e_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="container mx-auto px-4 sm:px-6 relative z-10 max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="space-y-4 sm:space-y-6 text-center lg:text-left">
                    <span class="inline-block px-3.5 py-1.5 rounded-full bg-nusaYellow/20 text-nusaYellow font-semibold text-xs sm:text-sm tracking-wide">
                        IT MANAGEMENT CONSULTANT
                    </span>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                        Solusi IT Terintegrasi
                        <span class="block text-nusaYellow mt-1">Untuk Masa Depan Bisnis Anda</span>
                    </h1>

                    <p class="text-gray-300 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Kami menyediakan layanan konsultasi, arsitektur sistem, tata kelola, dan pengembangan teknologi profesional untuk mempercepat transformasi digital perusahaan Anda.
                    </p>

                    {{-- Full-width & seragam di mobile, sejajar di desktop --}}
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-3 sm:gap-4 pt-3 max-w-xs sm:max-w-none mx-auto lg:mx-0">
                        <a href="{{ route('layanan') }}"
                           class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-nusaYellow text-nusaNavyDark font-bold text-sm sm:text-base text-center hover:bg-nusaYellowHover active:scale-[0.99] transition-all shadow-lg hover:-translate-y-0.5">
                            Jelajahi Layanan
                        </a>
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm sm:text-base text-center transition-all border border-white/20 active:scale-[0.99]">
                            Masuk Klien
                        </a>
                    </div>
                </div>

                <!-- Card Showcase -->
                <div class="relative flex justify-center mt-2 lg:mt-0">
                    <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-2xl relative">
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-nusaYellow/30 rounded-full blur-2xl pointer-events-none"></div>
                        <h3 class="text-lg sm:text-xl font-bold text-nusaYellow mb-3 sm:mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i> Performa Digital
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed mb-5 sm:mb-6">
                            Membantu bisnis scaling up melalui arsitektur software yang aman, terukur, dan efisien.
                        </p>
                        <div class="space-y-3">
                            <div class="bg-white/10 p-3.5 rounded-xl flex items-center justify-between gap-3">
                                <span class="text-sm font-medium">Layanan IT Profesional</span>
                                <i class="fas fa-check-circle text-nusaYellow shrink-0"></i>
                            </div>
                            <div class="bg-white/10 p-3.5 rounded-xl flex items-center justify-between gap-3">
                                <span class="text-sm font-medium">Konsultasi Terjadwal</span>
                                <i class="fas fa-check-circle text-nusaYellow shrink-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION LAYANAN UNGGULAN -->
    <section class="py-14 sm:py-16 lg:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="text-nusaYellow font-bold tracking-wider uppercase text-xs sm:text-sm">Ekspertis Kami</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-nusaNavy mt-2">Layanan Unggulan</h2>
                <div class="w-16 h-1.5 bg-nusaYellow mx-auto mt-3 sm:mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
                @forelse($layanan ?? [] as $item)
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-nusaNavy text-nusaYellow flex items-center justify-center text-xl sm:text-2xl mb-5 group-hover:bg-nusaYellow group-hover:text-nusaNavy transition-colors">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-nusaNavy mb-2.5">
                                {{ $item->nama_layanan ?? 'Layanan IT' }}
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed mb-5">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '', 120) }}
                            </p>
                        </div>
                        <a href="{{ route('layanan') }}"
                           class="inline-flex items-center gap-2 py-1 text-nusaNavy font-bold text-sm group-hover:text-nusaYellow transition-colors">
                            Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 px-4 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500 text-sm sm:text-base">Belum ada data layanan yang diinput oleh Super Admin.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-10 sm:mt-12">
                <a href="{{ route('layanan') }}"
                   class="inline-block w-full sm:w-auto px-8 py-3.5 rounded-xl border-2 border-nusaNavy text-nusaNavy font-bold text-sm sm:text-base hover:bg-nusaNavy hover:text-white transition-all">
                    Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION PORTOFOLIO PROYEK -->
    <section class="py-14 sm:py-16 lg:py-20 bg-gray-50 border-t border-gray-200">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="text-nusaYellow font-bold tracking-wider uppercase text-xs sm:text-sm">Portofolio</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-nusaNavy mt-2">Proyek Terbaru</h2>
                <div class="w-16 h-1.5 bg-nusaYellow mx-auto mt-3 sm:mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
                @forelse($portofolio ?? [] as $porto)
                    @php
                        $imagePath = $porto->gambar ?? $porto->foto ?? null;
                        $judulProyek = $porto->judul ?? $porto->nama_proyek ?? 'Proyek IT';
                    @endphp

                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col group">
                        <div class="aspect-video sm:aspect-auto sm:h-52 bg-nusaNavy relative flex items-center justify-center overflow-hidden">
                            @if(!empty($imagePath))
                                <img
                                    src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                    alt="{{ $judulProyek }}"
                                    loading="lazy"
                                    onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                >
                                {{-- Fallback otomatis jika file gambar gagal dimuat --}}
                                <i class="fas fa-folder-open text-nusaYellow text-4xl sm:text-5xl hidden"></i>
                            @else
                                <i class="fas fa-folder-open text-nusaYellow text-4xl sm:text-5xl"></i>
                            @endif
                        </div>

                        <div class="p-5 sm:p-6 flex flex-col flex-grow justify-between relative bg-white z-10">
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-nusaNavy mb-2 leading-snug">
                                    {{ $judulProyek }}
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ \Illuminate\Support\Str::limit($porto->deskripsi ?? '', 90) }}
                                </p>
                            </div>
                            <a href="{{ route('portofolio.index') }}"
                               class="text-nusaYellow font-bold text-sm inline-flex items-center gap-1.5 py-1 hover:underline">
                                Detail Proyek <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 px-4 bg-white rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500 text-sm sm:text-base">Belum ada portofolio proyek yang diinput oleh Super Admin.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- BANNER CTA KUNING -->
    <section class="py-12 sm:py-16 bg-nusaYellow">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl flex flex-col md:flex-row items-center justify-between gap-6 md:gap-8 text-center md:text-left">
            <div>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-nusaNavyDark mb-2 leading-snug">
                    Punya Proyek IT yang Menantang?
                </h3>
                <p class="text-nusaNavyDark/90 font-medium text-sm sm:text-lg">
                    Mari Konsultasikan Gratis dengan Tim Ahli Kami.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row w-full sm:w-auto max-w-xs sm:max-w-none gap-3 sm:gap-4 justify-center shrink-0">
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-nusaYellowHover text-nusaNavyDark font-extrabold text-sm sm:text-base text-center shadow-md hover:bg-black hover:text-white transition-all">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-nusaNavy text-white font-extrabold text-sm sm:text-base text-center shadow-md hover:bg-nusaNavyDark transition-all">
                    Login Klien
                </a>
            </div>
        </div>
    </section>

@endsection
