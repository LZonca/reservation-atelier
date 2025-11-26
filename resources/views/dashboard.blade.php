<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Ateliers</div>
                            <div class="text-3xl font-bold">{{ number_format($ateliers ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">🎨</div>
                    </div>
                    <a href="{{ url('/ateliers') }}" class="mt-4 inline-block text-sm text-indigo-600">Gérer les ateliers</a>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Salles</div>
                            <div class="text-3xl font-bold">{{ number_format($salles ?? 0) }}</div>
                        </div>
                        <div class="text-green-600 text-3xl">🏢</div>
                    </div>
                    <a href="{{ url('/salles') }}" class="mt-4 inline-block text-sm text-green-600">Gérer les salles</a>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Boutiques</div>
                            <div class="text-3xl font-bold">{{ number_format($boutiques ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">🏬</div>
                    </div>
                    <a href="{{ url('/boutiques') }}" class="mt-4 inline-block text-sm text-indigo-600">Gérer les boutiques</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Réservations</div>
                            <div class="text-3xl font-bold">{{ number_format($reservations ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">📅</div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Clients</div>
                            <div class="text-3xl font-bold">{{ number_format($clients ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">👥</div>
                    </div>
                    <a href="{{ route('api.clients.index') }}" class="mt-4 inline-block text-sm text-indigo-600">Gérer les clients</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <section class="col-span-2 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Derniers ateliers créés</h3>
                    @if(!empty($recentAteliers) && $recentAteliers->count())
                        <ul class="space-y-4">
                            @foreach($recentAteliers as $atelier)
                                <li class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold">{{ $atelier->titre ?? ($atelier->name ?? 'Atelier') }}</div>
                                        <div class="text-sm text-gray-500">{{ optional($atelier->created_at)->diffForHumans() ?? '' }} — {{ Str::limit($atelier->description ?? '', 80) }}</div>
                                    </div>
                                    <a href="{{ url('/ateliers/' . ($atelier->_id ?? $atelier->id ?? $atelier->getKey())) }}" class="text-indigo-600 text-sm">Voir</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Aucun atelier récent.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ url('/ateliers') }}" class="text-sm text-indigo-600">Voir tous les ateliers</a>
                    </div>
                </section>

                <aside class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Raccourcis API</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($apiRoutes as $route)
                            <li>
                                <div class="flex items-center justify-between">
                                    <div class="text-gray-700">{{ $route['label'] }}</div>
                                    <a href="{{ url($route['uri']) }}" class="text-indigo-600 truncate ml-4">{{ $route['uri'] }}</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <h3 class="text-lg font-medium text-gray-900 mt-6 mb-2">Dernières boutiques</h3>
                    @if(!empty($recentBoutiques) && $recentBoutiques->count())
                        <ul class="space-y-2 text-sm">
                            @foreach($recentBoutiques as $boutique)
                                <li class="flex items-center justify-between">
                                    <div class="text-gray-700">{{ $boutique->nom ?? 'Boutique' }}</div>
                                    <a href="{{ url('/boutiques/' . ($boutique->_id ?? $boutique->id ?? $boutique->getKey())) }}" class="text-indigo-600 text-sm">Voir</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Aucune boutique récente.</p>
                    @endif

                     @include('partials.mini-postman')
                 </aside>
             </div>
         </div>
     </div>
 </x-app-layout>
