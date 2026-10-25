<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | HeroVault</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 font-sans antialiased">
    <div class="max-w-md w-full text-center bg-slate-800/80 backdrop-blur-md border border-red-500/30 p-8 rounded-2xl shadow-2xl">
        <div class="w-20 h-20 mx-auto mb-6 bg-red-500/10 border-2 border-red-500/40 rounded-full flex items-center justify-center text-4xl shadow-lg shadow-red-500/20">
            🛡️
        </div>
        <span class="inline-block px-3 py-1 bg-red-500/20 text-red-400 text-xs font-semibold rounded-full tracking-wider uppercase mb-3">
            Error 403
        </span>
        <h1 class="text-2xl font-black text-white mb-2">Akses Ditolak</h1>
        <p class="text-slate-300 text-sm mb-6">
            Halaman ini hanya dapat diakses oleh <span class="text-red-400 font-semibold">Penjaga Gudang (Admin)</span>. Anggota tidak memiliki izin untuk melihat halaman ini.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition shadow-lg shadow-indigo-600/30 text-sm">
                        Kembali ke Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('member.dashboard') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl transition shadow-lg shadow-amber-500/30 text-sm">
                        Kembali ke Dashboard Anggota
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl transition text-sm">
                    Menuju Halaman Login
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
