<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold">Boutiques</h2>
            <a href="{{ url('/boutiques/create') }}" class="bg-indigo-600 text-white px-3 py-2 rounded">Créer une boutique</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($boutiques as $boutique)
                <div class="bg-white rounded shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-semibold">{{ $boutique->nom ?? 'Boutique' }}</div>
                        </div>
                        <div class="text-sm space-x-2">
                            <a href="{{ url('/boutiques/' . $boutique->getKey()) }}" class="text-indigo-600">Voir</a>
                            <a href="{{ url('/boutiques/' . $boutique->getKey() . '/edit') }}" class="text-indigo-600">Éditer</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Aucune boutique trouvée.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $boutiques->links() }}</div>
    </div>
</x-app-layout>

