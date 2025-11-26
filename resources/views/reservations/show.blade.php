<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Détail réservation</h2>
            <a href="{{ url('/reservations') }}" class="text-sm text-gray-600">Retour</a>
        </div>

        <div class="bg-white rounded shadow p-4">
            <div class="mb-2"><strong>ID:</strong> {{ $reservation->_id ?? $reservation->id }}</div>
            <div class="mb-2"><strong>Atelier:</strong> {{ optional($reservation->atelier)->nom ?? optional($reservation->atelier)->titre ?? '—' }}</div>
            <div class="mb-2"><strong>Client:</strong> {{ optional($reservation->client)->name ?? ($reservation->client_name ?? '—') }}</div>
            <div class="mb-2"><strong>Nb personnes:</strong> {{ $reservation->nbPersonne ?? ($reservation->nb_personne ?? '—') }}</div>
            <div class="mb-2"><strong>Prix:</strong> {{ $reservation->prix ?? '—' }}</div>
            <div class="mt-4">
                <a href="{{ url('/reservations') }}" class="text-indigo-600">Retour</a>
            </div>
        </div>
    </div>
</x-app-layout>

