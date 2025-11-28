<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Réservations — Atelier: {{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h2>
            <a href="{{ url('/ateliers') }}" class="text-sm text-gray-600">Retour</a>
        </div>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

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

        {{-- Composant modal Livewire --}}
        @livewire('reservation-editor')

        @if(isset($reservations) && $reservations->count())
            {{-- Compteur de réservations --}}
            @php
                $activeCount = $reservations->filter(fn($r) => empty($r->deleted_at))->count();
                $deletedCount = $reservations->filter(fn($r) => !empty($r->deleted_at))->count();
            @endphp
            <div class="mb-4 flex items-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-gray-700 font-medium">{{ $activeCount }} active{{ $activeCount > 1 ? 's' : '' }}</span>
                </div>
                @if($deletedCount > 0)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-gray-700 font-medium">{{ $deletedCount }} annulée{{ $deletedCount > 1 ? 's' : '' }}</span>
                    </div>
                @endif
                <div class="text-gray-500">
                    Total : {{ $reservations->count() }}
                </div>
            </div>

            <div class="space-y-3">
                @foreach($reservations as $reservation)
                    @php
                        $isDeleted = !empty($reservation->deleted_at);
                    @endphp
                    <div class="bg-white rounded shadow p-4 {{ $isDeleted ? 'border-l-4 border-red-500 bg-red-50' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-semibold text-lg {{ $isDeleted ? 'text-gray-600' : '' }}">
                                        Réservation #{{ $reservation->_id }}
                                    </div>
                                    @if($isDeleted)
                                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full uppercase">
                                            Annulée
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            le {{ $reservation->deleted_at->format('d/m/Y à H:i') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm {{ $isDeleted ? 'text-gray-500' : '' }}">
                                    {{-- Informations client --}}
                                    <div>
                                        <span class="text-gray-600">Client:</span>
                                        <span class="font-medium ml-1">
                                            {{ optional($reservation->client)->prenom ? optional($reservation->client)->prenom . ' ' . optional($reservation->client)->nom : ($reservation->client_name ?? '—') }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-gray-600">Nb personnes:</span>
                                        <span class="font-medium ml-1">{{ $reservation->nbPersonne ?? ($reservation->nb_personne ?? '—') }}</span>
                                    </div>

                                    {{-- Informations paiement --}}
                                    @php
                                        $payment = $reservation->paiement;
                                        $paymentId = $payment ? (data_get($payment, '_id') ?? data_get($payment, 'id')) : null;
                                    @endphp

                                    <div>
                                        <span class="text-gray-600">Prix payé:</span>
                                        <span class="font-medium ml-1">
                                            {{ $payment ? number_format((float)($payment->montant ?? 0), 2) . ' €' : '—' }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-gray-600">Moyen de paiement:</span>
                                        <span class="font-medium ml-1">{{ $payment->methode_paiement ?? '—' }}</span>
                                    </div>

                                    <div class="md:col-span-2">
                                        <span class="text-gray-600">Statut paiement:</span>
                                        @if($payment)
                                            <span class="ml-2 px-3 py-1 rounded text-xs font-semibold
                                                @if(in_array($payment->statut ?? '', ['completed', 'validé'])) bg-green-100 text-green-800
                                                @elseif(in_array($payment->statut ?? '', ['refunded', 'remboursé'])) bg-red-100 text-red-800
                                                @elseif(in_array($payment->statut ?? '', ['failed', 'échoué'])) bg-orange-100 text-orange-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($payment->statut ?? 'pending') }}
                                            </span>
                                        @else
                                            <span class="ml-2 text-gray-400">—</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Bouton d'édition (masqué si annulée) --}}
                            @if(!$isDeleted)
                                <div class="ml-4">
                                    <button
                                        onclick="Livewire.dispatch('openReservationEditor', { atelierId: '{{ $atelier->_id }}', reservationId: '{{ $reservation->_id }}' })"
                                        class="px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-600 transition-colors">
                                        Éditer
                                    </button>
                                </div>
                            @else
                                <div class="ml-4">
                                    <div class="px-4 py-2 bg-gray-200 text-gray-500 text-sm font-semibold rounded cursor-not-allowed">
                                        Annulée
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                @if(method_exists($reservations, 'links'))
                    {{ $reservations->links() }}
                @endif
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Aucune réservation pour cet atelier.</p>
        @endif
    </div>
</x-app-layout>
