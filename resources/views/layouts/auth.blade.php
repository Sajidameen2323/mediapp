<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MediCare - Healthcare Platform')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <!-- Navigation Header -->
    <header class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-b border-gray-200/20 dark:border-gray-700/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-900 p-2.5 rounded-xl shadow-lg">
                        <i class="fas fa-heartbeat text-white text-2xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">MediCare</h1>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Healthcare Platform</span>
                    </div>
                </div>

                <!-- Back to Home Button -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-all duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">Back to Home</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center shadow-lg">
                    <i class="fas fa-check-circle mr-3 text-green-600"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center shadow-lg">
                    <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>

</html>
