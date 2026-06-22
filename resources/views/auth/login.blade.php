<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Stitch POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-surface-container-low p-4 sm:p-8">

<div class="flex w-full max-w-7xl mx-auto bg-surface-container-lowest rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/20">

    {{-- Left Illustration Panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-surface-container items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/95 to-primary/80 z-10"></div>
        {{-- Abstract shapes --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/20 rounded-full -translate-y-1/2 translate-x-1/4 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-tertiary/15 rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03] z-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>
        {{-- Content --}}
        <div class="relative z-20 text-center px-16 py-20 max-w-lg">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/10 backdrop-blur rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl border border-white/10">
                <span class="material-symbols-outlined text-white text-3xl sm:text-4xl" style="font-variation-settings: 'FILL' 1;">storefront</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-5 leading-tight">Kelola Bisnis Lebih Cerdas</h2>
            <p class="text-white/70 text-base sm:text-lg leading-relaxed mb-10">Sistem POS lengkap untuk retail dan kuliner. Transaksi cepat, stok akurat, laporan real-time — semua dalam satu platform.</p>
            <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white/10">
                <div class="text-center">
                    <span class="material-symbols-outlined text-secondary text-2xl sm:text-3xl block mb-1">speed</span>
                    <p class="text-white text-xs sm:text-sm font-semibold">Cepat</p>
                </div>
                <div class="text-center">
                    <span class="material-symbols-outlined text-secondary text-2xl sm:text-3xl block mb-1">verified_user</span>
                    <p class="text-white text-xs sm:text-sm font-semibold">Aman</p>
                </div>
                <div class="text-center">
                    <span class="material-symbols-outlined text-secondary text-2xl sm:text-3xl block mb-1">cloud_sync</span>
                    <p class="text-white text-xs sm:text-sm font-semibold">Cloud</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Login Panel --}}
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 md:p-16 relative" x-data="loginForm()">
        {{-- Back to Landing --}}
        <a href="{{ route('landing') }}" class="absolute top-4 sm:top-6 left-4 sm:left-6 flex items-center gap-2 text-on-surface-variant hover:text-secondary transition-colors text-xs sm:text-sm font-medium">
            <span class="material-symbols-outlined text-base sm:text-lg">arrow_back</span>
            Kembali
        </a>

        <main class="w-full max-w-md">
            {{-- Brand Header --}}
            <div class="flex items-center gap-3 mb-8 sm:mb-10 justify-center">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-xl sm:text-2xl">storefront</span>
                </div>
                <h1 class="text-on-surface text-xl sm:text-2xl font-bold tracking-tight">Stitch POS</h1>
            </div>

            {{-- Main Login Form --}}
            <div class="bg-surface-container-lowest">
                <header class="mb-6 sm:mb-8 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mb-2">Selamat Datang Kembali</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant">Silakan masuk ke akun operator Anda.</p>
                </header>

                {{-- Demo Credentials --}}
                <div class="mb-5 sm:mb-6 bg-secondary-container/15 border border-secondary/20 rounded-xl overflow-hidden">
                    <button type="button" @click="demoOpen = !demoOpen"
                            class="w-full p-3 sm:p-4 flex items-center justify-between text-left hover:bg-secondary-container/10 transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-lg">info</span>
                            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kredensial Demo</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant text-lg transition-transform duration-200" :class="demoOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="demoOpen" x-collapse>
                        <div class="px-3 sm:px-4 pb-3 sm:pb-4 space-y-2 sm:space-y-2.5">
                            <div class="flex flex-col sm:grid sm:grid-cols-[auto_1fr] gap-x-3 gap-y-0.5 text-sm">
                                <span class="text-xs font-semibold text-secondary bg-secondary/10 px-2 py-0.5 rounded-md self-start w-fit mb-1 sm:mb-0">ADMIN</span>
                                <div>
                                    <span class="text-on-surface text-xs font-mono select-all cursor-text">admin@stitchpos.com</span>
                                    <span class="text-outline mx-2">/</span>
                                    <span class="text-on-surface text-xs font-mono select-all cursor-text">password</span>
                                </div>
                            </div>
                            <div class="flex flex-col sm:grid sm:grid-cols-[auto_1fr] gap-x-3 gap-y-0.5 text-sm">
                                <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded-md self-start w-fit mb-1 sm:mb-0">KASIR</span>
                                <div>
                                    <span class="text-on-surface text-xs font-mono select-all cursor-text">kasir@stitchpos.com</span>
                                    <span class="text-outline mx-2">/</span>
                                    <span class="text-on-surface text-xs font-mono select-all cursor-text">password</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-5 sm:mb-6 p-3 sm:p-4 bg-error-container text-on-error-container rounded-lg" x-data="{ locked: {{ str_contains($errors->first(), 'terkunci') ? 'true' : 'false' }}, countdown: 0 }" x-init="if (locked) { countdown = 15 * 60; const i = setInterval(() => { if (countdown <= 0) { clearInterval(i); locked = false; } else { countdown-- } }, 1000) }">
                        <p class="text-xs sm:text-sm font-medium">
                            {{ $errors->first() }}
                            <span x-show="locked && countdown > 0" class="font-mono font-bold">
                                (<span x-text="Math.floor(countdown/60)"></span>:<span x-text="String(countdown%60).padStart(2,'0')"></span>)
                            </span>
                        </p>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5 sm:space-y-6" @submit="submitting = true">
                    @csrf

                    {{-- Email Field --}}
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

                    {{-- Password Field --}}
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block" for="password">
                            Kata Sandi
                        </label>
                        <div class="relative group" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-lg group-focus-within:text-secondary transition-colors">lock</span>
                            </div>
                            <input
                                class="block w-full pl-10 pr-12 py-2.5 sm:py-3 bg-white border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline-variant
                                       focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                                id="password" name="password"
                                :type="show ? 'text' : 'password'"
                                placeholder="••••••••" required>
                            <button type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface-variant transition-colors"
                                    @click="show = !show">
                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary cursor-pointer">
                            <span class="ml-2 text-xs sm:text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs sm:text-sm text-secondary hover:text-secondary/80 font-medium transition-colors">Lupa password?</a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" :disabled="submitting"
                            class="w-full py-3 sm:py-3.5 bg-secondary text-white text-base sm:text-lg font-bold rounded-lg
                                   hover:bg-secondary/90 active:scale-[0.98] transition-all duration-200
                                   shadow-md shadow-secondary/20 flex items-center justify-center gap-2 mt-4
                                   disabled:opacity-60 disabled:cursor-wait disabled:hover:bg-secondary disabled:active:scale-100">
                        <template x-if="!submitting">
                            <span class="inline-flex items-center gap-2">Masuk <span class="material-symbols-outlined text-lg sm:text-xl">arrow_forward</span></span>
                        </template>
                        <template x-if="submitting">
                            <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-lg sm:text-xl animate-spin">progress_activity</span> Memproses...</span>
                        </template>
                    </button>
                </form>

                <footer class="mt-8 sm:mt-10 pt-5 sm:pt-6 border-t border-outline-variant/30 text-center">
                    <p class="text-xs sm:text-sm text-on-surface-variant">
                        Belum punya akun? <a class="text-secondary font-semibold hover:underline" href="{{ route('contact.admin') }}">Hubungi Admin</a>
                    </p>
                </footer>
            </div>

            {{-- Security Badge --}}
            <div class="mt-6 sm:mt-8 flex items-center justify-center gap-2 text-on-surface-variant opacity-60">
                <span class="material-symbols-outlined text-sm">encrypted</span>
                <span class="text-[10px] font-medium tracking-widest uppercase">Secure AES-256 Encrypted Environment</span>
            </div>
        </main>
    </div>
</div>

<script>
function loginForm() {
    return {
        submitting: false,
        demoOpen: false,
    };
}
</script>

</body>
</html>
