@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#f1f5f9] px-6 py-5">

    {{-- =====================================================
         TOMBOL KEMBALI
         ===================================================== --}}
    <div class="mb-2">
        <a
            href="{{ route('konsultasi.index') }}"
            class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 transition text-sm"
        >
            <i class="fas fa-sign-in-alt rotate-180"></i>
            Kembali
        </a>
    </div>

    {{-- =====================================================
         HEADER HALAMAN
         ===================================================== --}}
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-black">
            Detail Jadwal Konsultasi
        </h1>
    </div>

    {{-- =====================================================
         CARD UTAMA
         ===================================================== --}}
    <div class="bg-white border border-gray-300 rounded-xl overflow-hidden">

        {{-- HEADER JADWAL --}}
        <div class="px-6 py-4 flex items-center justify-between gap-4">

            <div class="flex items-center gap-5">

                <div class="w-10 h-10 rounded-full bg-[#2874d0] flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-chalkboard-teacher text-white text-lg"></i>
                </div>

                <h2 class="text-lg font-bold text-black">
                    {{ $jadwal['judul'] }}
                </h2>

            </div>

            {{-- Status dari database --}}
            @php
                $statusWarna = match ($jadwal['status_database']) {
                    'approved' => 'bg-[#bdeba9] text-[#55904c]',
                    'completed' => 'bg-gray-200 text-gray-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default => 'bg-yellow-100 text-yellow-700',
                };
            @endphp

            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusWarna }}">
                {{ $jadwal['status'] }}
            </span>

        </div>

        <div class="border-t-2 border-gray-400"></div>

        {{-- =================================================
             INFORMASI JADWAL
             ================================================= --}}
        <div class="px-6 py-4">

            <h2 class="text-sm font-bold text-black mb-7">
                Informasi Jadwal
            </h2>

            <div class="space-y-4">

                {{-- Tanggal --}}
                <div class="flex items-center">
                    <div class="w-10">
                        <i class="far fa-calendar-alt text-[#19375c] text-base"></i>
                    </div>

                    <div class="w-44 text-sm font-semibold text-[#19375c]">
                        Tanggal
                    </div>

                    <div class="w-5 text-sm">:</div>

                    <div class="text-sm text-[#19375c]">
                        {{ $jadwal['tanggal'] }}
                    </div>
                </div>

                {{-- Waktu --}}
                <div class="flex items-center">
                    <div class="w-10">
                        <i class="far fa-clock text-[#19375c] text-base"></i>
                    </div>

                    <div class="w-44 text-sm font-semibold text-[#19375c]">
                        Waktu
                    </div>

                    <div class="w-5 text-sm">:</div>

                    <div class="text-sm text-[#19375c]">
                        {{ $jadwal['waktu'] }}
                    </div>
                </div>

                {{-- Konsultan --}}
                <div class="flex items-center">
                    <div class="w-10">
                        <i class="fas fa-user text-[#19375c] text-base"></i>
                    </div>

                    <div class="w-44 text-sm font-semibold text-[#19375c]">
                        Konsultan
                    </div>

                    <div class="w-5 text-sm">:</div>

                    <div class="text-sm text-[#19375c]">
                        {{ $jadwal['konsultan'] }}
                    </div>
                </div>

                {{-- Metode --}}
                <div class="flex items-center">
                    <div class="w-10">
                        <i class="fas fa-link text-[#19375c] text-base"></i>
                    </div>

                    <div class="w-44 text-sm font-semibold text-[#19375c]">
                        Metode
                    </div>

                    <div class="w-5 text-sm">:</div>

                    <div class="text-sm text-[#19375c]">
                        {{ $jadwal['metode'] }}
                    </div>
                </div>

                {{-- Link Meeting --}}
                <div class="flex items-start">
                    <div class="w-10 pt-1">
                        <i class="fas fa-link text-[#19375c] text-base"></i>
                    </div>

                    <div class="w-44 text-sm font-semibold text-[#19375c]">
                        Link Meeting
                    </div>

                    <div class="w-5 text-sm">:</div>

                    <div class="flex flex-wrap items-center gap-4 min-w-0">

                        @if (
                            in_array($jadwal['status_database'], ['approved', 'completed'])
                            && !empty($jadwal['link_meeting'])
                        )
                            <a
                                href="{{ $jadwal['link_meeting'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-[#4389ef] text-sm hover:underline break-all"
                            >
                                {{ $jadwal['link_meeting'] }}
                            </a>

                            <a
                                href="{{ $jadwal['link_meeting'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="h-9 px-4 bg-[#2874d0] hover:bg-[#2167bd] text-white text-sm font-semibold rounded-xl flex items-center gap-2 transition"
                            >
                                <i class="fas fa-paper-plane text-sm"></i>
                                Gabung Sekarang
                            </a>
                        @else
                            <span class="text-sm text-gray-500">
                                Belum tersedia
                            </span>
                        @endif

                    </div>
                </div>

            </div>

        </div>

        <div class="mx-4 border-t-2 border-gray-400"></div>

        {{-- =================================================
             INFORMASI STATUS
             Catatan dibuat berdasarkan status database,
             bukan catatan admin yang tersimpan.
             ================================================= --}}
        <div class="m-4 bg-[#d8e7fb] rounded-xl px-5 py-4 shadow-[0_5px_8px_rgba(0,0,0,0.15)]">

            <div class="flex items-start gap-4">

                <div class="w-12 h-12 rounded-full bg-[#2874d0] flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation text-white text-base"></i>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-[#2874d0] mb-3">
                        Informasi Jadwal
                    </h2>

                    <p class="text-sm font-semibold text-[#19375c]">
                        {{ $jadwal['catatan_admin'] }}
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection