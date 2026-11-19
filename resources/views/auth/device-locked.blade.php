<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Dibatasi - Nusa Indo Technology</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
</head>
<body class="min-h-[100dvh] bg-nusaNavy flex items-center justify-center p-4 sm:p-6 font-sans antialiased">

    <div class="w-full max-w-md bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-white/10">
        <div class="bg-nusaNavyDark px-6 py-7 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-nusaYellow/15 border border-nusaYellow/30 flex items-center justify-center mb-4">
                <i class="fa-solid fa-laptop-file text-nusaYellow text-2xl"></i>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-semibold tracking-wide uppercase mb-2">
                Sesi Aktif di Perangkat Lain
            </span>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white leading-snug">
                Logout Terlebih Dahulu
            </h1>
        </div>

        <div class="p-6 sm:p-8 space-y-5">
            <p class="text-sm sm:text-base text-gray-600 text-center leading-relaxed">
                Akun <strong class="text-nusaNavy">{{ $lockInfo['role'] ?? 'Pengguna' }}</strong> Anda saat ini masih login di perangkat lain.
                Silakan <strong class="text-nusaNavy">logout terlebih dahulu dari perangkat tersebut</strong> sebelum login kembali di perangkat ini.
            </p>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-2.5 text-xs sm:text-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500">Akun</span>
                    <span class="font-semibold text-nusaNavy truncate">{{ $lockInfo['email'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500">Perangkat Aktif</span>
                    <span class="font-semibold text-nusaNavy text-right">{{ $lockInfo['device'] ?? 'Perangkat Lain' }}</span>
                </div>
                @if(!empty($lockInfo['ip']))
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-gray-500">Alamat IP</span>
                        <span class="font-mono text-gray-700">{{ $lockInfo['ip'] }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500">Aktivitas Terakhir</span>
                    <span class="font-medium text-gray-700">{{ $lockInfo['last_active'] ?? 'Baru saja' }}</span>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 text-xs text-amber-900 leading-relaxed flex items-start gap-2.5">
                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0"></i>
                <span>
                    Jika perangkat lama sudah tidak bisa diakses, tunggu hingga sesi otomatis berakhir (<strong>{{ config('session.lifetime', 120) }} menit</strong>) atau hubungi Administrator.
                </span>
            </div>

            <div class="flex flex-col gap-3 pt-1">
                <a href="{{ $lockInfo['login_url'] ?? route('login') }}"
                   class="w-full py-3.5 px-5 rounded-xl bg-nusaYellow hover:bg-nusaYellowHover text-nusaNavyDark font-bold text-sm text-center transition-colors shadow-sm">
                    <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Kembali ke Halaman Login
                </a>
                <a href="{{ route('home') }}"
                   class="w-full py-3 px-5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm text-center transition-colors">
                    Ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>