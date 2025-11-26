<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-2xl font-semibold">{{ $boutique->nom }}</h2>

            @if(!empty($boutique->adresse))
                <div class="mt-2 text-sm text-gray-600">
                    {{ $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '' }} {{ $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '' }}<br>
                    {{ $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '' }} {{ $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '' }}
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}" class="text-indigo-600">Éditer</a>
                <a href="{{ url('/boutiques') }}" class="ml-4 text-gray-600">Retour</a>
            </div>
        </div>
    </div>
</x-app-layout>
