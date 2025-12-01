<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Salles</h1>
                    <p class="text-indigo-100">Gérez vos salles et leur capacité</p>
                </div>
                <a href="{{ url('/salles/create') }}"
                   class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer une salle
                </a>
            </div>
        </div>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Statistiques rapides --}}
        @php
            $totalSalles = $salles->total();
            $totalCapacite = $salles->sum('capacite');
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Salles</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSalles }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Capacité Totale</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCapacite }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des salles --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($salles as $salle)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                    {{-- En-tête de la carte --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                        <div class="flex items-start justify-between">
                            <h3 class="font-bold text-lg text-white truncate flex-1">
                                {{ $salle->nom ?? 'Salle' }}
                            </h3>
                            @if(!empty($salle->categorie))
                                <span class="bg-indigo-200 text-indigo-900 text-xs font-bold px-2 py-1 rounded-full ml-2">
                                    {{ $salle->categorie }}
                                </span>
                            @endif
                        </div>
                        <p class="text-indigo-100 text-xs mt-1">ID: {{ substr((string) $salle->getKey(), 0, 8) }}</p>
                    </div>

                    {{-- Corps de la carte --}}
                    <div class="p-4">
                        {{-- Informations clés --}}
                        <div class="grid grid-cols-1 gap-3 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-purple-100 rounded p-1.5">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Capacité</p>
                                    <p class="text-sm font-semibold">{{ $salle->capacite ?? '—' }} personnes</p>
                                </div>
                            </div>

                            @if(($salle->relationLoaded('boutique') && $salle->boutique) || $salle->boutique_id)
                                <div class="flex items-center gap-2">
                                    <div class="bg-blue-100 rounded p-1.5">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Boutique</p>
                                        <p class="text-sm font-semibold truncate">{{ ($salle->relationLoaded('boutique') && $salle->boutique) ? ($salle->boutique->nom ?? substr((string)$salle->boutique_id, 0, 8)) : substr((string)($salle->boutique_id ?? '—'), 0, 8) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Date de mise à jour --}}
                        @if($salle->updated_at)
                            <div class="mb-4 flex items-center gap-2 text-xs text-gray-500 bg-gray-50 rounded-lg p-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Modifié {{ $salle->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ url('/salles/' . $salle->getKey()) }}"
                               class="flex-1 bg-indigo-600 text-white text-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir
                            </a>
                            <a href="{{ url('/salles/' . $salle->getKey() . '/edit') }}"
                               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">Aucune salle trouvée</p>
                    <p class="text-gray-400 mt-1">Créez votre première salle pour commencer</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $salles->links() }}
        </div>
    </div>
</x-app-layout>
