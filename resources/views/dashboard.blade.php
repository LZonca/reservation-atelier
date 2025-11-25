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
                    <a href="{{ route('ateliers.index') }}" class="mt-4 inline-block text-sm text-indigo-600">Gérer les ateliers</a>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Réservations</div>
                            <div class="text-3xl font-bold">{{ number_format($reservations ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">📅</div>
                    </div>
                    <a href="{{ url('/atelier') }}" class="mt-4 inline-block text-sm text-indigo-600">Voir les réservations</a>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Clients</div>
                            <div class="text-3xl font-bold">{{ number_format($clients ?? 0) }}</div>
                        </div>
                        <div class="text-indigo-600 text-3xl">👥</div>
                    </div>
                    <a href="{{ route('clients.index') }}" class="mt-4 inline-block text-sm text-indigo-600">Gérer les clients</a>
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
                                    <a href="{{ route('ateliers.show', ['atelier' => $atelier->_id ?? $atelier->id ?? $atelier->getKey()]) }}" class="text-indigo-600 text-sm">Voir</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Aucun atelier récent.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('ateliers.index') }}" class="text-sm text-indigo-600">Voir tous les ateliers</a>
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

                    <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Dernières réservations</h3>
                    @if(!empty($recentReservations) && $recentReservations->count())
                        <ul class="space-y-3 text-sm">
                            @foreach($recentReservations as $res)
                                <li class="border rounded p-2">
                                    <div class="font-semibold">{{ $res->client->name ?? ($res->client_name ?? 'Client') }} — <span class="text-gray-500">{{ $res->atelier->titre ?? ($res->atelier->name ?? 'Atelier') }}</span></div>
                                    <div class="text-xs text-gray-500">{{ optional($res->created_at)->diffForHumans() }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Aucune réservation récente.</p>
                    @endif

                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
