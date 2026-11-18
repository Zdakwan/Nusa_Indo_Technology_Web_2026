@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#f1f5f9] px-4 md:px-10 py-8">

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Notifikasi
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Informasi terbaru seputar akun dan konsultasi Anda
            </p>
        </div>

        {{-- TANDAI SEMUA SUDAH DIBACA --}}
        <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
            @csrf
            <button
                type="submit"
                @disabled($notifikasi->isEmpty() || $notifikasi->every(fn ($item) => !$item['baru']))
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#d9e7fb] hover:bg-[#cbdcf4] disabled:opacity-50 disabled:cursor-not-allowed text-[#19375c] text-sm font-semibold rounded-lg shadow-sm transition"
            >
                <i class="far fa-check-circle"></i>
                Tandai semua sudah dibaca
            </button>
        </form>
    </div>

    {{-- PESAN SUKSES --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- DAFTAR NOTIFIKASI --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

        @if ($notifikasi->isEmpty())

            {{-- KONDISI KOSONG --}}
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-[#d9e7fb] flex items-center justify-center">
                    <i class="far fa-bell-slash text-[#2874d0] text-2xl"></i>
                </div>
                <h2 class="text-base font-bold text-[#19375c]">Belum Ada Notifikasi</h2>
                <p class="mt-1 text-gray-500 text-sm">
                    Informasi terbaru seputar akun dan konsultasi Anda akan muncul di sini.
                </p>
            </div>

        @else

            @foreach ($notifikasi as $item)

                @php
                    $warnaIcon = match ($item['warna']) {
                        'green'  => 'bg-[#00d639]',
                        'blue'   => 'bg-[#2874d0]',
                        'yellow' => 'bg-[#ffc04d]',
                        default  => 'bg-[#2874d0]',
                    };
                @endphp

                {{-- KARTU NOTIFIKASI --}}
                <a
                    href="{{ route('notifikasi.detail', $item['id']) }}"
                    class="px-4 md:px-6 py-4 border-b border-gray-200 last:border-b-0 flex items-center gap-4 transition
                    {{ $item['baru'] ? 'bg-blue-50/50 hover:bg-blue-50' : 'bg-white hover:bg-gray-50' }}"
                >
                    {{-- IKON --}}
                    <div class="w-10 h-10 rounded-full {{ $warnaIcon }} flex items-center justify-center flex-shrink-0">
                        <i class="{{ $item['icon'] }} text-white text-sm"></i>
                    </div>

                    {{-- ISI NOTIFIKASI --}}
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-bold text-gray-900">
                            {{ $item['judul'] }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">
                            {{ $item['pesan'] }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $item['waktu'] }}
                        </p>
                    </div>

                    {{-- STATUS BACA --}}
                    <div class="flex-shrink-0">
                        @if ($item['baru'])
                            <span class="px-2.5 py-1 bg-[#2874d0] text-white text-xs font-semibold rounded-full">
                                Baru
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">
                                Sudah dibaca
                            </span>
                        @endif
                    </div>
                </a>

            @endforeach

        @endif

    </div>
</div>

@endsection
