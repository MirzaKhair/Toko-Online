<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Online')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col antialiased">
    @include('components.customer.header')

    <main class="flex-1">
        @if (session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                @component('components.alert', ['type' => 'success', 'dismissible' => true])
                    {{ session('success') }}
                @endcomponent
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                @component('components.alert', ['type' => 'error', 'dismissible' => true])
                    {{ session('error') }}
                @endcomponent
            </div>
        @endif

        @yield('content')
    </main>

    @include('components.customer.footer')
</body>
</html>
