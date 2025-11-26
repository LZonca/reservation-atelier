<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-6">
            <h2 class="text-2xl font-semibold">Ateliers</h2>
            <a href="{{ url('/ateliers/create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Créer un
                atelier</a>
        </div>

        @if(session('success'))
            <div
                class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($ateliers as $atelier)
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h3>
                            <p class="text-sm text-gray-500 mt-1 break-words">{{ Str::limit($atelier->description ?? '', 140) }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ optional($atelier->date)->format('Y-m-d H:i') ?? 'Date inconnue' }}</span>
                                @if(!empty($atelier->vip))
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">VIP</span>
                                @endif
                                @if(method_exists($atelier, 'remainingCapacity'))
                                    <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded">Capacité restante : {{ $atelier->remainingCapacity() }}</span>
                                @endif
                                @if(!empty($atelier->salle) || !empty($atelier->salle_id))
                                    <span class="bg-green-50 text-green-700 px-2 py-1 rounded">Salle: {{ $atelier->salle->nom ?? $atelier->salle_id }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-0 flex w-full sm:w-auto flex-col sm:flex-col sm:items-end gap-2">
                            <a href="{{ url('/ateliers/' . $atelier->getKey()) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-1.5 border border-transparent rounded text-sm font-medium text-indigo-600 hover:bg-indigo-50">Voir</a>
                            <a href="{{ url('/ateliers/' . $atelier->getKey() . '/reservations') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-1.5 border rounded text-sm font-medium text-indigo-600 hover:bg-indigo-50">Réservations</a>
                            <a href="{{ url('/ateliers/' . $atelier->getKey() . '/edit') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-1.5 border rounded text-sm text-gray-700 hover:bg-gray-50">Éditer</a>

                            <form action="{{ url('/ateliers/' . $atelier->getKey()) }}" method="POST" class="w-full sm:w-auto inline" onsubmit="return confirm('Supprimer cet atelier ?');">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="w-full sm:w-auto text-red-600 text-sm hover:underline">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Aucun atelier trouvé.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $ateliers->links() }}</div>
    </div>
</x-app-layout>
