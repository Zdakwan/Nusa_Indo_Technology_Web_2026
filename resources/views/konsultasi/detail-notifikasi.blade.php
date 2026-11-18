@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#f1f5f9] px-4 md:px-10 py-8">

    {{-- HEADER --}}
    <div class="mb-5">
        <a
            href="{{ route('notifikasi.index') }}"
            class="inline-flex items-center gap-2 text-[#19375c] hover:text-blue-700 text-sm font-semibold mb-4"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali ke semua notifikasi
        </a>

        <h1 class="text-2xl font-bold text-gray-900">
            Detail Notifikasi
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Informasi lengkap mengenai notifikasi Anda
        </p>
    </div>

    {{-- KARTU DETAIL --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

        {{-- JUDUL KARTU --}}
        <div class="px-5 py-4 border-b border-gray-200 flex items-center gap-3">

            @php
                $warnaIcon = match ($detail['warna']) {
                    'green'  => 'bg-[#00d639]',
                    'blue'   => 'bg-[#2874d0]',
                    'yellow' => 'bg-[#ffc04d]',
                    default  => 'bg-[#2874d0]',
                };
            @endphp

            <div class="w-10 h-10 rounded-full {{ $warnaIcon }} flex items-center justify-center flex-shrink-0">
                <i class="{{ $detail['icon'] }} text-white text-sm"></i>
            </div>

            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900">
                    {{ $detail['judul'] }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $detail['waktu'] }}
                </p>
            </div>

            {{-- STATUS --}}
            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full flex-shrink-0">
                Sudah dibaca
            </span>
        </div>

        {{-- ISI DETAIL --}}
        <div class="px-5 py-5">
            <h3 class="text-sm font-bold text-[#19375c] mb-2">
                Isi Notifikasi
            </h3>

            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $detail['pesan'] }}
            </p>
        </div>

    </div>

</div>

@endsection
