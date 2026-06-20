<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Toko') — POS Toko</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-primary-700">POS Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Kasir & Manajemen Toko</p>
        </div>
        <div class="card">
            <div class="card-body p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
