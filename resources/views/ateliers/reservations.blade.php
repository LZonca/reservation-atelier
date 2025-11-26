<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Réservations — Atelier: {{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h2>
            <a href="{{ url('/ateliers') }}" class="text-sm text-gray-600">Retour</a>
        </div>

        {{-- Formulaire rapide de création d'une réservation --}}
        <div class="bg-white rounded shadow p-4 mb-4">
            <form action="{{ url('/ateliers/' . $atelier->getKey() . '/reservations') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-end">
                @csrf
                <div>
                    <label class="text-xs text-gray-600">Client (nom)</label>
                    <input type="text" name="client_name" class="mt-1 block w-full border rounded px-2 py-1 text-sm" placeholder="Nom du client">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Nb personnes *</label>
                    <input type="number" name="nbPersonne" required min="1" class="mt-1 block w-full border rounded px-2 py-1 text-sm" value="1">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Prix</label>
                    <input type="number" name="prix" step="0.01" class="mt-1 block w-full border rounded px-2 py-1 text-sm" placeholder="ex: 25.00">
                </div>
                <div class="sm:col-span-1">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Réserver</button>
                </div>
            </form>
        </div>

        @if($reservations->count())
            <div class="space-y-3">
                @foreach($reservations as $res)
                    <div class="bg-white rounded shadow p-3 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Réservation #{{ $res->_id ?? $res->id }}</div>
                            <div class="text-sm text-gray-500">Client: {{ optional($res->client)->name ?? ($res->client_name ?? '—') }}</div>
                            <div class="text-sm text-gray-500">Nb personnes: {{ $res->nbPersonne ?? ($res->nb_personne ?? '—') }}</div>
                        </div>
                        <div class="space-x-2 text-sm">
                            <a href="{{ url('/reservations/' . ($res->_id ?? $res->id)) }}" class="text-indigo-600">Voir</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">@if(method_exists($reservations, 'links')) {{ $reservations->links() }} @endif</div>
        @else
            <p class="text-gray-500">Aucune réservation pour cet atelier.</p>
        @endif
    </div>
</x-app-layout>
