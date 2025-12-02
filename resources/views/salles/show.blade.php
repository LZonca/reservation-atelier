<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg shadow-lg p-8 text-white mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">{{ $salle->nom }}</h1>
                    <div class="flex items-center gap-4 text-green-100">
                        <span class="text-sm">ID: {{ substr((string)$salle->getKey(), 0, 12) }}</span>
                        @if(!empty($salle->categorie))
                            <span class="bg-green-500 bg-opacity-30 px-3 py-1 rounded-full text-sm font-medium">{{ $salle->categorie }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ url('/salles/' . $salle->getKey() . '/edit') }}"
                       class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Éditer
                    </a>
                    <a href="{{ url('/salles') }}"
                       class="bg-green-700 bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        {{-- Statut en cours --}}
        @if($atelierEnCours)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-red-500 rounded-full p-2 animate-pulse">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-red-900">🔴 Salle occupée en ce moment</h3>
                        <p class="text-red-700 mt-1">
                            <span class="font-semibold">{{ $atelierEnCours->nom }}</span>
                            - Se termine à {{ \Carbon\Carbon::parse($atelierEnCours->date)->addHours((int) $atelierEnCours->duree)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-green-500 rounded-full p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-green-900">🟢 Salle disponible</h3>
                        <p class="text-green-700 text-sm">Aucun atelier en cours</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Informations principales --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Capacité</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $salle->capacite ?? '—' }}</p>
                        <p class="text-gray-400 text-xs mt-1">places</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Catégorie</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $salle->categorie ?? 'Non définie' }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-500 text-sm font-medium">Boutique</p>
                        <p class="text-xl font-bold text-gray-900 mt-2 truncate">
                            {{ ($salle->relationLoaded('boutique') && $salle->boutique) ? $salle->boutique->nom : 'Non assignée' }}
                        </p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calendrier d'occupation sur 7 jours --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Calendrier d'occupation (7 prochains jours)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-7 gap-3">
                @foreach($calendrier as $jour)
                    <div class="border rounded-lg overflow-hidden {{ $jour['date']->isToday() ? 'ring-2 ring-indigo-500' : 'border-gray-200' }}">
                        <div class="bg-gradient-to-r {{ $jour['date']->isToday() ? 'from-indigo-600 to-purple-600' : 'from-gray-600 to-gray-700' }} text-white p-3">
                            <div class="text-xs font-medium uppercase">{{ $jour['date']->isoFormat('ddd') }}</div>
                            <div class="text-2xl font-bold">{{ $jour['date']->format('d') }}</div>
                            <div class="text-xs">{{ $jour['date']->isoFormat('MMM') }}</div>
                        </div>

                        <div class="p-3 bg-white min-h-[120px]">
                            @if($jour['count'] > 0)
                                <div class="space-y-2">
                                    @foreach($jour['ateliers'] as $atelier)
                                        <div class="bg-indigo-50 border border-indigo-200 rounded p-2 text-xs hover:bg-indigo-100 transition">
                                            <div class="font-semibold text-indigo-900 truncate">{{ $atelier->nom }}</div>
                                            <div class="text-indigo-600 flex items-center gap-1 mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($atelier->date)->format('H:i') }}
                                                <span class="text-gray-500">({{ $atelier->duree }}h)</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-gray-400 py-8">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs">Libre</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Prochains ateliers --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Prochains ateliers
            </h2>

            @if($prochainsAteliers->count() > 0)
                <div class="space-y-3">
                    @foreach($prochainsAteliers as $atelier)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition hover:border-green-300">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $atelier->nom }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($atelier->description ?? '', 80) }}</p>
                                    <div class="flex items-center gap-4 mt-3 text-sm">
                                        <span class="flex items-center gap-1 text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($atelier->date)->isoFormat('dddd D MMMM YYYY') }}
                                        </span>
                                        <span class="flex items-center gap-1 text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($atelier->date)->format('H:i') }} - {{ \Carbon\Carbon::parse($atelier->date)->addHours((int) $atelier->duree)->format('H:i') }}
                                        </span>
                                        <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-semibold">
                                            {{ $atelier->duree }}h
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ url('/ateliers/' . $atelier->getKey()) . "/reservations" }}"
                                   class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-200 transition whitespace-nowrap">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Aucun atelier planifié</p>
                    <p class="text-gray-400 text-sm mt-1">Cette salle n'a pas d'ateliers prévus dans les prochains jours</p>
                </div>
            @endif
        </div>

        {{-- Informations supplémentaires --}}
        <div class="bg-gray-50 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informations</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Date de création</p>
                    <p class="font-medium text-gray-900">{{ optional($salle->created_at)->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Dernière mise à jour</p>
                    <p class="font-medium text-gray-900">{{ optional($salle->updated_at)->format('d/m/Y à H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Identifiant</p>
                    <p class="font-medium text-gray-900 font-mono text-xs">{{ substr((string)$salle->getKey(), 0, 16) }}...</p>
                </div>
                <div>
                    <p class="text-gray-500">Statut</p>
                    <p class="font-medium">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $atelierEnCours ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            <span class="w-1.5 h-1.5 {{ $atelierEnCours ? 'bg-red-600' : 'bg-green-600' }} rounded-full mr-1"></span>
                            {{ $atelierEnCours ? 'Occupée' : 'Disponible' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
