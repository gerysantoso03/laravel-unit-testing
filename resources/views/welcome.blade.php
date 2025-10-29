@extends('layouts.app')

@section('content')
<div class="text-center py-12">
    <h1 class="text-4xl font-bold text-blue-700 mb-4">Welcome {{ $user->name }}</h1>
    <p class="text-gray-600 mb-6">This is a sample Laravel 12 app using Vite + Tailwindcss</p>
    <a href="#" id="getStartedBtn" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
        Get Started
    </a>
</div>
@endsection
