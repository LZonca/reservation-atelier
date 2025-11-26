<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Réservations</h2>
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600">Retour</a>
        </div>

        @if($reservations->count())
            <div class="space-y-3">
                @foreach($reservations as $res)
                    <div class="bg-white rounded shadow p-3 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Réservation #{{ $res->_id ?? $res->id }}</div>
                            <div class="text-sm text-gray-500">Atelier: {{ optional($res->atelier)->nom ?? optional($res->atelier)->titre ?? '—' }}</div>
                            <div class="text-sm text-gray-500">Client: {{ optional($res->client)->name ?? ($res->client_name ?? '—') }}</div>
                        </div>
                        <div class="space-x-2 text-sm">
                            <a href="{{ url('/reservations/' . ($res->_id ?? $res->id)) }}" class="text-indigo-600">Voir</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $reservations->links() }}</div>
        @else
            <p class="text-gray-500">Aucune réservation trouvée.</p>
        @endif
    </div>
</x-app-layout>
