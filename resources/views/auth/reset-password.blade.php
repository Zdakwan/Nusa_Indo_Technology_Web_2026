@extends('layouts.auth')

@section('title', 'Reset Password - Nusa Indo Tech')

@section('left_title')
    Buat Password<br>Baru
@endsection

@section('left_desc', 'Gunakan kombinasi password yang kuat agar akun Anda tetap aman.')

@section('left_extra')
    <div class="flex items-start">
        <div class="flex-shrink-0 mt-0.5">
            <i class="fas fa-shield-alt text-[#f7bd55] text-xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-sm font-bold text-gray-800">Syarat Password</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&).</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-md w-full mx-auto">
        <h2 class="text-4xl font-extrabold text-white mb-8">Reset Password</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 rounded-lg p-3 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-times-circle text-red-600 mt-1 mr-2"></i>
                    <div>
                        <h3 class="text-red-800 font-bold text-sm">Gagal!</h3>
                        <p class="text-red-700 text-xs mt-1">{{ $errors->first() }}</p>
                        @if($errors->has('email'))
                            <a href="{{ route('password.request') }}" class="text-red-800 text-xs font-bold underline">Minta link baru</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-box rounded-2xl px-5 py-3 mb-5 shadow-sm opacity-80">
                <label class="block text-[11px] font-bold text-gray-400 tracking-wide uppercase mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    readonly
                    class="w-full bg-transparent border-none outline-none text-white text-sm font-medium focus:ring-0 p-0">
            </div>

            <div class="input-box rounded-2xl px-5 py-3 mb-5 shadow-sm relative">
                <label class="block text-[11px] font-bold text-gray-400 tracking-wide uppercase mb-1">Password Baru</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    autofocus
                    autocomplete="new-password"
                    class="w-full bg-transparent border-none outline-none text-white text-sm font-medium focus:ring-0 p-0 pr-8"
                    placeholder="••••••••">
                <div class="absolute right-5 top-1/2 transform -translate-y-1/2 cursor-pointer mt-1" onclick="toggle('password','eye1')">
                    <i class="fas fa-eye-slash text-gray-400 hover:text-white transition-colors" id="eye1"></i>
                </div>
            </div>

            <div class="input-box rounded-2xl px-5 py-3 mb-8 shadow-sm relative">
                <label class="block text-[11px] font-bold text-gray-400 tracking-wide uppercase mb-1">Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full bg-transparent border-none outline-none text-white text-sm font-medium focus:ring-0 p-0 pr-8"
                    placeholder="••••••••">
                <div class="absolute right-5 top-1/2 transform -translate-y-1/2 cursor-pointer mt-1" onclick="toggle('password_confirmation','eye2')">
                    <i class="fas fa-eye-slash text-gray-400 hover:text-white transition-colors" id="eye2"></i>
                </div>
            </div>

            <button type="submit" class="w-full btn-yellow hover:bg-yellow-500 font-extrabold py-4 rounded-full transition duration-300 text-sm shadow-lg">
                Simpan Password Baru
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-300 font-medium">
            <a href="{{ route('login') }}" class="font-bold text-[#f7bd55] hover:underline">Kembali ke Login</a>
        </div>
    </div>

    <script>
        function toggle(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', show);
            icon.classList.toggle('fa-eye-slash', !show);
        }
    </script>
@endsection