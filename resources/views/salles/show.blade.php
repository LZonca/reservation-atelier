<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ $salle->nom }}</h1>
                    <div class="mt-2 text-sm text-gray-500">ID: <span class="text-gray-700 font-medium">{{ (string)$salle->getKey() }}</span></div>
                </div>

                <div class="text-right">
                    @if(!empty($salle->categorie))
                        <div class="inline-flex items-center px-3 py-1 rounded bg-gray-100 text-sm font-medium">{{ $salle->categorie }}</div>
                    @endif
                    <div class="mt-3 space-x-2">
                        <a href="{{ url('/salles/' . $salle->getKey() . '/edit') }}" class="bg-indigo-600 text-white px-3 py-2 rounded">Éditer</a>
                        <a href="{{ url('/salles') }}" class="ml-2 text-sm text-gray-600">Retour</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <div class="text-sm text-gray-500">Capacité</div>
                        <div class="text-lg font-medium">{{ $salle->capacite ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Catégorie</div>
                        <div class="text-lg font-medium">{{ $salle->categorie ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Boutique</div>
                        <div class="text-lg font-medium">{{ ($salle->relationLoaded('boutique') && $salle->boutique) ? ($salle->boutique->nom ?? (string)$salle->boutique_id) : (string)($salle->boutique_id ?? '—') }}</div>
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <div>Créé: {{ optional($salle->created_at)->toDayDateTimeString() ?? '—' }}</div>
                    <div>Mis à jour: {{ optional($salle->updated_at)->toDayDateTimeString() ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
