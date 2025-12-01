<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Statistiques principales --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Ateliers --}}
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Ateliers</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($ateliers ?? 0) }}</p>
                        </div>
                        <div class="bg-indigo-400 bg-opacity-30 rounded-full p-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ url('/ateliers') }}" class="inline-flex items-center gap-1 text-indigo-100 hover:text-white text-sm font-medium">
                        Gérer les ateliers
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Salles --}}
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Salles</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($salles ?? 0) }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ url('/salles') }}" class="inline-flex items-center gap-1 text-green-100 hover:text-white text-sm font-medium">
                        Gérer les salles
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Boutiques --}}
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Boutiques</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($boutiques ?? 0) }}</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ url('/boutiques') }}" class="inline-flex items-center gap-1 text-purple-100 hover:text-white text-sm font-medium">
                        Gérer les boutiques
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Clients --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Clients</p>
                            <p class="text-4xl font-bold mt-2">{{ number_format($clients ?? 0) }}</p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-1 text-blue-100 hover:text-white text-sm font-medium">
                        Gérer les clients
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Statistiques secondaires --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-medium">Réservations totales</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($reservations ?? 0) }}</p>
                            <p class="text-gray-400 text-xs mt-1">Toutes les réservations enregistrées</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-teal-500">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm font-medium">Revenu actif</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($reservationsActives ?? 0, 2) }} €</p>
                            <p class="text-gray-400 text-xs mt-1">Réservations non annulées</p>
                        </div>
                        <div class="bg-teal-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Derniers ateliers --}}
                <section class="col-span-2 bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Derniers ateliers créés
                        </h3>
                        <a href="{{ url('/ateliers') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Voir tous →
                        </a>
                    </div>

                    @if(!empty($recentAteliers) && $recentAteliers->count())
                        <div class="space-y-3">
                            @foreach($recentAteliers as $atelier)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-bold text-gray-900">{{ $atelier->nom ?? ($atelier->titre ?? ($atelier->name ?? 'Atelier')) }}</h4>
                                                @if(!empty($atelier->vip))
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded">VIP</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($atelier->description ?? '', 100) }}</p>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ optional($atelier->created_at)->diffForHumans() ?? 'Date inconnue' }}
                                                </span>
                                                @if(!empty($atelier->prix))
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ number_format($atelier->prix, 2) }} €
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ url('/ateliers/' . ($atelier->_id ?? $atelier->id ?? $atelier->getKey())) }}"
                                           class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-200 transition whitespace-nowrap">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Aucun atelier récent</p>
                            <p class="text-gray-400 text-sm mt-1">Créez votre premier atelier pour commencer</p>
                        </div>
                    @endif
                </section>

                {{-- Raccourcis API --}}
                <aside class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        Raccourcis API
                    </h3>
                    <div class="space-y-3">
                        @foreach($apiRoutes as $route)
                            <a href="{{ url($route['uri']) }}"
                               class="block p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md transition group">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900 group-hover:text-green-600">{{ $route['label'] }}</p>
                                        <p class="text-xs text-gray-500 font-mono mt-1">{{ $route['uri'] }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Dernières boutiques
                        </h3>
                        @if(!empty($recentBoutiques) && $recentBoutiques->count())
                            <div class="space-y-2">
                                @foreach($recentBoutiques as $boutique)
                                    <a href="{{ url('/boutiques/' . ($boutique->_id ?? $boutique->id ?? $boutique->getKey())) }}"
                                       class="block p-3 border border-gray-200 rounded-lg hover:border-purple-300 hover:shadow-md transition">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">{{ $boutique->nom ?? 'Boutique' }}</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Aucune boutique récente</p>
                        @endif
                    </div>

                     @include('partials.mini-postman')
                 </aside>
             </div>
         </div>
     </div>
 </x-app-layout>
