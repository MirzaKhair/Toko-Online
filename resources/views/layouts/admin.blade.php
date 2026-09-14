<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Toko Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    @include('components.admin.sidebar')

    <div class="lg:pl-64">
        @include('components.admin.header')

        <main class="p-4 sm:p-6 lg:p-8">
            @if (session('success'))
                @component('components.alert', ['type' => 'success', 'dismissible' => true])
                    {{ session('success') }}
                @endcomponent
            @endif

            @if (session('error'))
                @component('components.alert', ['type' => 'error', 'dismissible' => true])
                    {{ session('error') }}
                @endcomponent
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
