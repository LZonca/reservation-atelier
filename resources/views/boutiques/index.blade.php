<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Boutiques</h2>
            <a href="{{ url('/boutiques/create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded">Créer une boutique</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($boutiques as $boutique)
                <div class="bg-white rounded shadow hover:shadow-md transition p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-lg font-semibold">{{ $boutique->nom ?? 'Boutique' }}</div>
                                <div class="text-xs text-gray-500">ID: <span class="text-gray-700">{{ substr((string)$boutique->getKey(), 0, 6) }}</span></div>
                            </div>

                            @php
                                $hasAdresse = !empty($boutique->adresse['rue'] ?? $boutique->adresse->rue ?? null);
                            @endphp

                            <div class="text-xs">
                                @if($hasAdresse)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-800">Adresse</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800">Sans adresse</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            {{ $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '' }} {{ $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '' }}<br>
                            {{ $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '' }} {{ $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '' }}
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm space-x-2">
                            <a href="{{ url('/boutiques/' . $boutique->getKey()) }}" class="text-indigo-600 hover:underline">Voir</a>
                            <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}" class="text-indigo-600 hover:underline">Éditer</a>
                        </div>
                        <div class="text-xs text-gray-400">{{ optional($boutique->updated_at)->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Aucune boutique trouvée.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $boutiques->links() }}</div>
    </div>
</x-app-layout>
