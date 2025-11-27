<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-2xl font-semibold">{{ $salle->nom }}</h2>
            <div class="text-sm text-gray-500">Capacité: {{ $salle->capacite ?? '—' }} @if(!empty($salle->boutique)) — <span class="text-gray-700">Boutique: {{ $salle->boutique->nom ?? $salle->boutique }}</span>@elseif(!empty($salle->boutique)) — <span class="text-gray-700">Boutique: {{ $salle->boutique }}</span>@endif</div>
            <p class="mt-3 text-gray-700">{{ $salle->adresse ?? '' }}</p>

            <div class="mt-4">
                <a href="{{ url('/salles/' . $salle->getKey() . '/edit') }}" class="text-indigo-600">Éditer</a>
                <a href="{{ url('/salles') }}" class="ml-4 text-gray-600">Retour</a>
            </div>
        </div>
    </div>
</x-app-layout>
