<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Messages flash --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-2">{{ $boutique->nom }}</h1>
                    <div class="flex items-center gap-4 text-indigo-100">
                        <span class="text-sm">ID: {{ substr((string)$boutique->getKey(), 0, 12) }}</span>
                        @if(!empty($boutique->adresse))
                            <span class="text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}"
                       class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-semibold hover:bg-indigo-50 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Éditer
                    </a>
                    <a href="{{ url('/boutiques') }}"
                       class="bg-indigo-700 bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>

            {{-- Adresse complète --}}
            @if(!empty($boutique->adresse))
                <div class="mt-4 pt-4 border-t border-indigo-400">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '' }} {{ $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '' }}</p>
                            <p>{{ $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '' }} {{ $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Statistiques rapides --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Total employés --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Employés</p>
                        <p class="text-4xl font-bold mt-2">{{ $employes->count() }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total salles --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Salles</p>
                        <p class="text-4xl font-bold mt-2">{{ $salles->count() }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Capacité totale --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Capacité totale</p>
                        <p class="text-4xl font-bold mt-2">{{ $salles->sum('capacite') }}</p>
                        <p class="text-purple-100 text-xs mt-1">personnes</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des employés --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Employés ({{ $employes->count() }})
                </h2>
                <button onclick="document.getElementById('modalAffectEmploye').classList.remove('hidden')"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Affecter un employé
                </button>
            </div>

            @if($employes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($employes as $employe)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition hover:border-indigo-300">
                            <div class="flex items-start gap-3">
                                {{-- Avatar avec initiales --}}
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($employe->name ?? '', 0, 2)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $employe->name }}</h3>
                                    <p class="text-sm text-gray-600 truncate">{{ $employe->email }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Employé
                                        </span>
                                        <form action="{{ url('/boutiques/' . $boutique->getKey() . '/employes/' . $employe->_id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Retirer cet employé de la boutique ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-lg font-medium">Aucun employé assigné à cette boutique</p>
                </div>
            @endif
        </div>

        {{-- Liste des salles --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Salles ({{ $salles->count() }})
                </h2>
                <button onclick="document.getElementById('modalAffectSalle').classList.remove('hidden')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Affecter une salle
                </button>
            </div>

            @if($salles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($salles as $salle)
                        @php
                            // Gérer à la fois les objets Eloquent et les tableaux embarqués
                            $nom = is_array($salle) ? ($salle['nom'] ?? 'Sans nom') : ($salle->nom ?? 'Sans nom');
                            $categorie = is_array($salle) ? ($salle['categorie'] ?? 'Non catégorisée') : ($salle->categorie ?? 'Non catégorisée');
                            $capacite = is_array($salle) ? ($salle['capacite'] ?? 0) : ($salle->capacite ?? 0);
                            $createdAt = is_array($salle) ? ($salle['created_at'] ?? null) : ($salle->created_at ?? null);
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition hover:border-green-300">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $nom }}</h3>
                                    <p class="text-sm text-gray-600">{{ $categorie }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    @if(is_object($salle) && isset($salle->_id))
                                        <form action="{{ url('/boutiques/' . $boutique->getKey() . '/salles/' . $salle->_id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Retirer cette salle de la boutique ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $capacite }} places
                                </span>
                            </div>

                            @if(!empty($createdAt))
                                <p class="text-xs text-gray-400 mt-3">
                                    Créée le {{ \Carbon\Carbon::parse($createdAt)->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-lg font-medium">Aucune salle dans cette boutique</p>
                </div>
            @endif
        </div>

        {{-- Informations supplémentaires --}}
        <div class="bg-gray-50 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informations</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Date de création</p>
                    <p class="font-medium text-gray-900">{{ optional($boutique->created_at)->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Dernière mise à jour</p>
                    <p class="font-medium text-gray-900">{{ optional($boutique->updated_at)->format('d/m/Y à H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Identifiant</p>
                    <p class="font-medium text-gray-900 font-mono text-xs">{{ substr((string)$boutique->getKey(), 0, 16) }}...</p>
                </div>
                <div>
                    <p class="text-gray-500">Statut</p>
                    <p class="font-medium">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1"></span>
                            Active
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Affecter Employé --}}
    <div id="modalAffectEmploye" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Affecter un employé</h2>
                    <button onclick="document.getElementById('modalAffectEmploye').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
                </div>

                <form action="{{ url('/boutiques/' . $boutique->getKey() . '/employes') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner un employé
                        </label>
                        <select name="employe_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choisir un employé --</option>
                            @php
                                $tousEmployes = \App\Models\User::whereNull('boutique_id')
                                    ->orWhere('boutique_id', new \MongoDB\BSON\ObjectId((string) $boutique->getKey()))
                                    ->get();
                            @endphp
                            @foreach($tousEmployes as $emp)
                                <option value="{{ $emp->_id }}">
                                    {{ $emp->name }} ({{ $emp->email }})
                                    @if($emp->boutique_id && (string)$emp->boutique_id == (string)$boutique->getKey())
                                        - Déjà affecté
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            Affecter
                        </button>
                        <button type="button"
                                onclick="document.getElementById('modalAffectEmploye').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Affecter Salle --}}
    <div id="modalAffectSalle" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Affecter une salle</h2>
                    <button onclick="document.getElementById('modalAffectSalle').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
                </div>

                <form action="{{ url('/boutiques/' . $boutique->getKey() . '/salles') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner une salle
                        </label>
                        <select name="salle_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Choisir une salle --</option>
                            @php
                                $toutesSalles = \App\Models\Salle::whereNull('boutique_id')
                                    ->orWhere('boutique_id', new \MongoDB\BSON\ObjectId((string) $boutique->getKey()))
                                    ->get();
                            @endphp
                            @foreach($toutesSalles as $s)
                                <option value="{{ $s->_id }}">
                                    {{ $s->nom }} - {{ $s->capacite }} places ({{ $s->categorie }})
                                    @if($s->boutique_id && (string)$s->boutique_id == (string)$boutique->getKey())
                                        - Déjà affectée
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            Affecter
                        </button>
                        <button type="button"
                                onclick="document.getElementById('modalAffectSalle').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
