<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Salles</h2>
            <a href="{{ url('/salles/create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded">Créer une salle</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($salles as $salle)
                <div class="bg-white rounded shadow hover:shadow-md transition p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-lg font-semibold">{{ $salle->nom ?? 'Salle' }}</div>
                                <div class="text-xs text-gray-500">ID: <span class="text-gray-700">{{ substr((string) $salle->getKey(), 0, 6) }}</span></div>
                            </div>

                            @if(!empty($salle->categorie))
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $salle->categorie }}</span>
                            @endif
                        </div>

                        <div class="mt-2 text-sm text-gray-600">Capacité: <span class="font-medium text-gray-800">{{ $salle->capacite ?? '—' }}</span></div>

                        <div class="mt-2 text-sm text-gray-600">Boutique: <span class="font-medium text-gray-800">{{ ($salle->relationLoaded('boutique') && $salle->boutique) ? ($salle->boutique->nom ?? (string)$salle->boutique_id) : (string)($salle->boutique_id ?? '—') }}</span></div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm space-x-2">
                            <a href="{{ url('/salles/' . $salle->getKey()) }}" class="text-indigo-600 hover:underline">Voir</a>
                            <a href="{{ url('/salles/' . $salle->getKey() . '/edit') }}" class="text-indigo-600 hover:underline">Éditer</a>
                        </div>
                        <div class="text-xs text-gray-400">{{ optional($salle->updated_at)->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Aucune salle trouvée.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $salles->links() }}</div>
    </div>
</x-app-layout>
