<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password — Stitch POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-surface-container-low p-4 sm:p-8">

<div class="flex w-full max-w-7xl mx-auto bg-surface-container-lowest rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/20">

    {{-- Left Illustration Panel --}}
    <div class="hidden lg:block lg:w-1/2 relative bg-surface-container">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/80 to-transparent z-10"></div>
        <div class="absolute bottom-12 left-12 right-12 z-20">
            <h2 class="text-3xl font-bold text-on-primary mb-4">Lupa Kata Sandi?</h2>
            <p class="text-on-primary/80 text-lg">Jangan khawatir. Kami akan membantu Anda mendapatkan kembali akses ke akun Anda dengan aman.</p>
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,_rgba(255,255,255,0.05)_0%,_transparent_60%)]"></div>
    </div>

    {{-- Right Panel --}}
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 md:p-16 relative">
        <a href="{{ route('login') }}" class="absolute top-4 sm:top-6 left-4 sm:left-6 flex items-center gap-2 text-on-surface-variant hover:text-secondary transition-colors text-xs sm:text-sm font-medium">
            <span class="material-symbols-outlined text-base sm:text-lg">arrow_back</span>
            Kembali ke Login
        </a>

        <main class="w-full max-w-md">
            <div class="flex items-center gap-3 mb-8 sm:mb-10 justify-center">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-xl sm:text-2xl">lock_reset</span>
                </div>
                <h1 class="text-on-surface text-xl sm:text-2xl font-bold tracking-tight">Stitch POS</h1>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 bg-secondary-container/20 border border-secondary/30 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-secondary mt-0.5 shrink-0">check_circle</span>
                        <div>
                            <p class="text-sm font-semibold text-on-surface mb-1">Instruksi Terkirim</p>
                            <p class="text-xs sm:text-sm text-on-surface-variant">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <header class="mb-6 sm:mb-8 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mb-2">Lupa Password</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant">Masukkan alamat email Anda. Kami akan mengirimkan instruksi untuk mereset kata sandi.</p>
                </header>
            @endif

            @if($errors->any())
                <div class="mb-5 sm:mb-6 p-3 sm:p-4 bg-error-container text-on-error-container rounded-lg">
                    <p class="text-xs sm:text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5 sm:space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block" for="email">
                        Alamat Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline text-lg group-focus-within:text-secondary transition-colors">mail</span>
                        </div>
                        <input
                            class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline-variant
                                   focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                            id="email" name="email" type="email"
                            placeholder="nama@perusahaan.com"
                            value="{{ old('email') }}"
                            required autofocus>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 sm:py-3.5 bg-secondary text-white text-base sm:text-lg font-bold rounded-lg
                               hover:bg-secondary/90 active:scale-[0.98] transition-all duration-200
                               shadow-md shadow-secondary/20 flex items-center justify-center gap-2 mt-4">
                    Kirim Instruksi Reset
                    <span class="material-symbols-outlined text-lg sm:text-xl">send</span>
                </button>
            </form>

            <footer class="mt-8 sm:mt-10 pt-5 sm:pt-6 border-t border-outline-variant/30 text-center">
                <p class="text-xs sm:text-sm text-on-surface-variant">
                    Masih bermasalah? <a href="{{ route('contact.admin') }}" class="text-secondary font-semibold hover:underline">Hubungi Admin</a>
                </p>
            </footer>

            <div class="mt-6 sm:mt-8 flex items-center justify-center gap-2 text-on-surface-variant opacity-60">
                <span class="material-symbols-outlined text-sm">encrypted</span>
                <span class="text-[10px] font-medium tracking-widest uppercase">Secure AES-256 Encrypted Environment</span>
            </div>
        </main>
    </div>
</div>

</body>
</html>
