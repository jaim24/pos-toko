<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Stitch POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="flex h-screen overflow-hidden text-on-surface bg-background" x-data="{ sidebarOpen: false }">
    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak
         class="fixed inset-0 bg-on-surface/50 z-40 lg:hidden"
         @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="flex flex-col h-full w-sidebar py-8 fixed left-0 top-0 bg-surface-container-lowest z-50 border-r border-outline-variant/30
                  transform transition-transform duration-200 ease-in-out
                  lg:translate-x-0 lg:static lg:z-auto">
        {{-- Brand --}}
        <div class="px-8 mb-10 flex items-center gap-3">
            <div class="w-8 h-8 bg-secondary rounded-lg flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">storefront</span>
            </div>
            <h1 class="text-xl font-bold text-on-surface tracking-tight">Stitch POS</h1>
        </div>

        <div class="px-8 mb-3 text-[11px] font-bold text-on-surface-variant tracking-widest uppercase">Menu</div>

        {{-- Navigation --}}
        <nav class="flex-grow flex flex-col gap-1 px-4">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-semibold transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? 'text-secondary' : '' }}"
                          style="{{ request()->routeIs('admin.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">dashboard</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('admin.products.*') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="text-sm">Produk</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('admin.categories.*') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">category</span>
                    <span class="text-sm">Kategori</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('admin.users.*') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">group</span>
                    <span class="text-sm">Pengguna</span>
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('admin.reports.*') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">analytics</span>
                    <span class="text-sm">Laporan</span>
                </a>

                {{-- Separator --}}
                <div class="px-4 mt-4 mb-2"><div class="border-t border-outline-variant/30"></div></div>

                <a href="{{ route('admin.landing-settings.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('admin.landing-settings.*') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">web</span>
                    <span class="text-sm">Landing Page</span>
                </a>
            @else
                <a href="{{ route('kasir.dashboard') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-semibold transition-colors
                          {{ request()->routeIs('kasir.dashboard') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('kasir.dashboard') ? 'text-secondary' : '' }}"
                          style="{{ request()->routeIs('kasir.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">dashboard</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a href="{{ route('kasir.transactions.create') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('kasir.transactions.create') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span class="text-sm">Transaksi Baru</span>
                </a>
                <a href="{{ route('kasir.transactions.history') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl font-medium transition-colors
                          {{ request()->routeIs('kasir.transactions.history') ? 'bg-secondary/10 text-secondary' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="text-sm">Riwayat</span>
                </a>
            @endif
        </nav>

        {{-- User footer --}}
        <div class="px-8 mb-3 mt-6 text-[11px] font-bold text-on-surface-variant tracking-widest uppercase">General</div>
        <nav class="flex flex-col gap-1 px-4 mb-6">
            <form method="POST" action="{{ route('logout') }}" class="contents">
                @csrf
                <button type="submit"
                        class="flex items-center gap-4 px-4 py-3 text-error hover:bg-error/10 rounded-2xl font-medium transition-colors w-full text-left">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </nav>

        {{-- Profile --}}
        <div class="px-8 pt-4 border-t border-outline-variant/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-on-surface-variant capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Canvas --}}
    <main class="flex-1 flex flex-col min-h-0 min-w-0 bg-background">
        {{-- Top Header --}}
            <header class="flex justify-between items-center px-4 sm:px-8 py-3 sm:py-4 bg-white border-b border-outline-variant/30 sticky top-0 z-30 shrink-0">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 -ml-2 text-on-surface-variant hover:text-on-surface rounded-lg lg:hidden">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="flex items-center gap-3">
                    <h1 class="text-base sm:text-lg font-semibold text-on-surface truncate">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-on-surface-variant hidden sm:block">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </header>

            {{-- Page content --}}
            <div class="flex-1 flex flex-col min-h-0 p-4 sm:p-6 lg:p-8 overflow-auto">
                @if(session('success'))
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-secondary-container text-on-secondary-container rounded-xl text-sm font-medium shrink-0"
                         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-error-container text-on-error-container rounded-xl text-sm shrink-0">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
