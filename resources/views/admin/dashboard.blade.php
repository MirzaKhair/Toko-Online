@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800">Selamat Datang</h3>
                <p class="text-blue-600 mt-1">{{ Auth::user()->name }}</p>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800">Email</h3>
                <p class="text-green-600 mt-1">{{ Auth::user()->email }}</p>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-purple-800">Role</h3>
                <p class="text-purple-600 mt-1 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>
@endsection
