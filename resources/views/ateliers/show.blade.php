<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold">{{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h2>
            <div class="mt-2 text-sm text-gray-600">{{ optional($atelier->date)->format('Y-m-d H:i') ?? '' }}</div>
            <p class="mt-4 text-gray-700">{{ $atelier->description ?? '' }}</p>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div><strong>Durée :</strong> {{ $atelier->duree ?? '—' }}h</div>
                <div><strong>Prix :</strong> {{ isset($atelier->prix) ? number_format($atelier->prix,2) . ' €' : '—' }}</div>
                <div>
                    @if(!empty($atelier->vip))
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded">VIP</span>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-700">
                @if(!empty($atelier->intervenant))
                    <div><strong>Intervenant :</strong> {{ $atelier->intervenant['nom'] ?? ($atelier->intervenant->nom ?? '') }} {{ $atelier->intervenant['prenom'] ?? ($atelier->intervenant->prenom ?? '') }}</div>
                    <div class="text-xs text-gray-500">{{ $atelier->intervenant['email'] ?? ($atelier->intervenant->email ?? '') }} — {{ $atelier->intervenant['telephone'] ?? ($atelier->intervenant->telephone ?? '') }}</div>
                @endif
                @if(!empty($atelier->salle))
                    <div class="mt-2"><strong>Salle :</strong> {{ $atelier->salle->nom ?? ($atelier->salle_id ?? '—') }}</div>
                @endif
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <a href="{{ url('/ateliers/' . $atelier->getKey() . '/edit') }}" class="text-indigo-600">Éditer</a>
                <a href="{{ url('/ateliers') }}" class="text-gray-600">Retour</a>
            </div>
        </div>

    {{-- Réservations pour cet atelier --}}
    <div class="max-w-3xl mx-auto mt-6">
        <h3 class="text-lg font-semibold mb-3">Réservations</h3>

        {{-- Formulaire rapide pour ajouter une réservation directement depuis la page de l'atelier --}}
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
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Ajouter</button>
                </div>
            </form>
        </div>

        @if(isset($reservations) && $reservations->count())
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
        @else
            <p class="text-gray-500">Aucune réservation pour cet atelier.</p>
        @endif
    </div>
    </div>
</x-app-layout>
