<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Online')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex flex-col">
    @include('components.customer.header')

    <main class="flex-1">
        @if (session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('components.customer.footer')
</body>
</html>
