<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 dark:bg-gray-900 antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen selection:bg-blue-500 selection:text-white">

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex flex-col items-center justify-center text-center">

                    <div class="h-20 w-20 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">
                        {{ config('app.name') }}
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-lg mb-8">
                        Sistema inteligente de gestão de operações financeiras e esteira de crédito.
                    </p>

                    <a href="{{ route('login') }}" class="btn-import">
                        Acessar Plataforma
                    </a>

                </div>

                <div class="mt-16 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} - Desenvolvido por Sabrina Fontes
                </div>
            </div>
        </div>
    </body>
</html>
