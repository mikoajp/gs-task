<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Manager | GS-Task</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

<nav class="glass-nav fixed w-full z-10 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-indigo-600 font-bold text-xl tracking-tight hover:text-indigo-500 transition">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <span>ServiceMgr</span>
                    </a>
                </div>
                <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                    <a href="{{ route('dashboard') }}" 
                       class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                        Dashboard
                    </a>
                    <a href="{{ route('inspections') }}" 
                       class="{{ request()->routeIs('inspections') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                        Przeglądy
                    </a>
                    <a href="{{ route('failures') }}" 
                       class="{{ request()->routeIs('failures') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                        Awarii
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                    v1.0.0
                </span>
            </div>
        </div>
    </div>
</nav>

<main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start animate-fade-in-down" role="alert">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start animate-fade-in-down" role="alert">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex justify-center md:justify-start space-x-6">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">GitHub</span>
                    <i class="fa-brands fa-github text-xl"></i>
                </a>
            </div>
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} Service Manager. Built with Laravel 11.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out;
    }
</style>

</body>
</html>