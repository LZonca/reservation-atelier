<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Boutiques</h1>
                    <p class="text-indigo-100">Gérez vos boutiques et leurs adresses</p>
                </div>
                <a href="{{ url('/boutiques/create') }}"
                   class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer une boutique
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
            $totalBoutiques = $boutiques->total();
            $boutiquesAvecAdresse = $boutiques->filter(function($b) {
                return !empty($b->adresse['rue'] ?? $b->adresse->rue ?? null);
            })->count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Boutiques</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBoutiques }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Avec Adresse</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $boutiquesAvecAdresse }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des boutiques --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($boutiques as $boutique)
                @php
                    $hasAdresse = !empty($boutique->adresse['rue'] ?? $boutique->adresse->rue ?? null);
                    $rue = $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '';
                    $numero = $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '';
                    $ville = $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '';
                    $codePostal = $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '';
                @endphp
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                    {{-- En-tête de la carte --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                        <div class="flex items-start justify-between">
                            <h3 class="font-bold text-lg text-white truncate flex-1">
                                {{ $boutique->nom ?? 'Boutique' }}
                            </h3>
                            @if($hasAdresse)
                                <span class="bg-green-400 text-green-900 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1 ml-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Adresse
                                </span>
                            @else
                                <span class="bg-gray-300 text-gray-700 text-xs font-bold px-2 py-1 rounded-full ml-2">
                                    Sans adresse
                                </span>
                            @endif
                        </div>
                        <p class="text-indigo-100 text-xs mt-1">ID: {{ substr((string)$boutique->getKey(), 0, 8) }}</p>
                    </div>

                    {{-- Corps de la carte --}}
                    <div class="p-4">
                        {{-- Adresse --}}
                        @if($hasAdresse)
                            <div class="mb-4 bg-gray-50 rounded-lg p-3">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div class="text-sm text-gray-700">
                                        <p class="font-medium">{{ $numero }} {{ $rue }}</p>
                                        <p class="text-gray-600">{{ $codePostal }} {{ $ville }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4 bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Aucune adresse renseignée</span>
                                </div>
                            </div>
                        @endif

                        {{-- Date de mise à jour --}}
                        @if($boutique->updated_at)
                            <div class="mb-4 flex items-center gap-2 text-xs text-gray-500 bg-gray-50 rounded-lg p-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Modifié {{ $boutique->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ url('/boutiques/' . $boutique->getKey()) }}"
                               class="flex-1 bg-indigo-600 text-white text-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir
                            </a>
                            <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">Aucune boutique trouvée</p>
                    <p class="text-gray-400 mt-1">Créez votre première boutique pour commencer</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $boutiques->links() }}
        </div>
    </div>
</x-app-layout>
