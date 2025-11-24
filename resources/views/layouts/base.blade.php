<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Réservation d\'Ateliers') }}</title>
    <meta name="description" content="Trouvez et réservez des ateliers créatifs près de chez vous.">
    <meta property="og:title" content="{{ config('app.name', 'Réservation d\'Ateliers') }}">
    <meta property="og:description" content="Trouvez et réservez des ateliers créatifs près de chez vous.">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
<div class="min-h-screen flex flex-col">

    @include('partials.nav')

    <!-- Hero -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h1 class="text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Trouvez et réservez des ateliers près de chez vous
                </h1>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Explorez des ateliers créatifs, techniques et professionnels — réservez votre place en quelques
                    clics.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="#ateliers" class="inline-flex items-center px-6 py-3 border border-transparent text-base
                        font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Voir les ateliers
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8">
        <div id="ateliers">
            {{-- Si le layout est utilisé comme layout Livewire, Livewire fournit $slot --}}
            @if (!empty($slot))
                {{ $slot }}
            @endif

            {{-- Si une vue Blade étend ce layout, elle peut définir la section 'content' --}}
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500">
            © {{ date('Y') }} Réservation Ateliers — Tous droits réservés.
        </div>
    </footer>
</div>

{{-- Livewire scripts required for Livewire components --}}
@livewireScripts
</body>

</html>
