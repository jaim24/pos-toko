<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hubungi Admin — Stitch POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-surface-container-low p-4 sm:p-8">

<div class="flex w-full max-w-7xl mx-auto bg-surface-container-lowest rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/20">

    {{-- Left Illustration Panel --}}
    <div class="hidden lg:block lg:w-1/2 relative bg-surface-container">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/80 to-transparent z-10"></div>
        <div class="absolute bottom-12 left-12 right-12 z-20">
            <h2 class="text-3xl font-bold text-on-primary mb-4">Kami Siap Membantu</h2>
            <p class="text-on-primary/80 text-lg">Punya pertanyaan tentang akun atau akses? Tim dukungan kami siap melayani Anda.</p>
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,_rgba(0,108,73,0.1)_0%,_transparent_60%)]"></div>
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
                    <span class="material-symbols-outlined text-secondary text-xl sm:text-2xl">support_agent</span>
                </div>
                <h1 class="text-on-surface text-xl sm:text-2xl font-bold tracking-tight">Stitch POS</h1>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 sm:p-5 bg-secondary-container/20 border border-secondary/30 rounded-xl text-center">
                    <span class="material-symbols-outlined text-secondary text-3xl sm:text-4xl mb-3 block">check_circle</span>
                    <p class="text-sm font-semibold text-on-surface mb-1">Pesan Terkirim!</p>
                    <p class="text-xs sm:text-sm text-on-surface-variant">{{ session('status') }}</p>
                    <a href="{{ route('login') }}" class="inline-block mt-4 px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-semibold hover:bg-secondary/90 transition-colors">
                        Kembali ke Login
                    </a>
                </div>
            @else
                <header class="mb-6 sm:mb-8 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mb-2">Hubungi Admin</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant">Isi formulir di bawah ini untuk menghubungi tim administrator kami.</p>
                </header>

                @if($errors->any())
                    <div class="mb-5 sm:mb-6 p-3 sm:p-4 bg-error-container text-on-error-container rounded-lg">
                        <p class="text-xs sm:text-sm font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block" for="name">
                            Nama Lengkap
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-lg group-focus-within:text-secondary transition-colors">person</span>
                            </div>
                            <input
                                class="block w-full pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline-variant
                                       focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                                id="name" name="name" type="text"
                                placeholder="Nama Anda"
                                value="{{ old('name') }}"
                                required autofocus>
                        </div>
                    </div>

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
                                required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block" for="message">
                            Pesan
                        </label>
                        <textarea
                            class="block w-full px-4 py-3 bg-white border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline-variant
                                   focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200 resize-none"
                            id="message" name="message" rows="4"
                            placeholder="Tulis pesan atau pertanyaan Anda di sini..."
                            required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3 sm:py-3.5 bg-secondary text-white text-base sm:text-lg font-bold rounded-lg
                                   hover:bg-secondary/90 active:scale-[0.98] transition-all duration-200
                                   shadow-md shadow-secondary/20 flex items-center justify-center gap-2 mt-2">
                        Kirim Pesan
                        <span class="material-symbols-outlined text-lg sm:text-xl">send</span>
                    </button>
                </form>

                <footer class="mt-8 sm:mt-10 pt-5 sm:pt-6 border-t border-outline-variant/30 text-center space-y-3">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-left max-w-xs mx-auto">
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm text-secondary">mail</span>
                            <span>admin@stitchpos.com</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm text-secondary">call</span>
                            <span>+62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm text-secondary">schedule</span>
                            <span>Sen-Jum, 08:00-17:00 WIB</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm text-secondary">location_on</span>
                            <span>Jakarta, Indonesia</span>
                        </div>
                    </div>
                    <p class="text-xs text-on-surface-variant pt-2">
                        <a href="{{ route('password.request') }}" class="text-secondary font-semibold hover:underline">Lupa Password?</a>
                    </p>
                </footer>
            @endif

            <div class="mt-6 sm:mt-8 flex items-center justify-center gap-2 text-on-surface-variant opacity-60">
                <span class="material-symbols-outlined text-sm">encrypted</span>
                <span class="text-[10px] font-medium tracking-widest uppercase">Secure AES-256 Encrypted Environment</span>
            </div>
        </main>
    </div>
</div>

</body>
</html>
