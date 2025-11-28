<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ $boutique->nom }}</h1>
                    <div class="mt-2 text-sm text-gray-500">ID: <span class="text-gray-700 font-medium">{{ substr((string)$boutique->getKey(), 0, 8) }}</span></div>
                </div>

                <div class="text-right">
                    <div class="mt-3 space-x-2">
                        <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}" class="bg-indigo-600 text-white px-3 py-2 rounded">Éditer</a>
                        <a href="{{ url('/boutiques') }}" class="ml-2 text-sm text-gray-600">Retour</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4">
                <div class="text-gray-700">
                    @if(!empty($boutique->adresse))
                        <div>{{ $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '' }} {{ $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '' }}</div>
                        <div>{{ $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '' }} {{ $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '' }}</div>
                    @else
                        <div>Aucune adresse renseignée.</div>
                    @endif
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <div>Créé: {{ optional($boutique->created_at)->toDayDateTimeString() ?? '—' }}</div>
                    <div>Mis à jour: {{ optional($boutique->updated_at)->toDayDateTimeString() ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
