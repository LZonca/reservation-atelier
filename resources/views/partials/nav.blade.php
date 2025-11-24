<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-4">
                <a href="/" class="text-2xl font-extrabold text-indigo-600">Réservation Ateliers</a>
                <form action="{{ route('home') }}" method="GET" class="hidden sm:flex">
                    <input type="text" name="q" placeholder="Rechercher un atelier..."
                           class="ml-4 px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </form>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        {!! csrf_field() !!}
                        <button type="submit" class="text-gray-700 hover:text-gray-900">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Connexion</a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">Inscription</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
