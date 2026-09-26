@extends('layouts.error')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-2xl text-center">
            <div
                class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-blue-500/20 via-purple-500/20 to-cyan-500/20 border border-white/10 mb-8">
                <span class="text-4xl font-extrabold tracking-tight">404</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                Page not found
            </h1>

            <p class="text-gray-300 text-base sm:text-lg mb-8">
                The page you are looking for doesn’t exist or may have been moved.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition">
                    Go Home
                </a>
                <button type="button" onclick="history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-semibold hover:bg-white/10 transition">
                    Go Back
                </button>
            </div>
        </div>
    </div>
@endsection
