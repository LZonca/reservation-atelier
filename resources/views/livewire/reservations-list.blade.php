<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Réservations — Atelier: {{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}
        </h2>
        <a href="{{ route('ateliers.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
            ← Retour
        </a>
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistiques --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-lg shadow flex flex-wrap gap-6">
        <div>
            <span class="font-bold text-lg text-indigo-700">{{ $total }}</span>
            <span class="text-gray-600">réservations</span>
        </div>
        <div>
            <span class="font-bold text-lg text-green-700">{{ $active }}</span>
            <span class="text-gray-600">actives</span>
        </div>
        <div>
            <span class="font-bold text-lg text-red-700">{{ $cancelled }}</span>
            <span class="text-gray-600">annulées</span>
        </div>
        @if(isset($stats))
            <div>
                <span class="font-bold text-lg text-blue-700">{{ $stats['total_personnes'] ?? 0 }}</span>
                <span class="text-gray-600">personnes</span>
            </div>
            <div>
                <span class="font-bold text-lg text-yellow-700">{{ number_format($stats['total_revenue'] ?? 0, 2) }} €</span>
                <span class="text-gray-600">revenu validé</span>
            </div>
            <div>
                <span class="font-bold text-lg text-purple-700">{{ number_format($stats['occupation_rate'] ?? 0, 1) }}%</span>
                <span class="text-gray-600">occupation</span>
            </div>
            <div>
                <span class="font-bold text-lg text-pink-700">{{ number_format($stats['avg_price_per_person'] ?? 0, 2) }} €</span>
                <span class="text-gray-600">prix moyen/personne</span>
            </div>
            <div>
                <span class="font-bold text-lg text-orange-700">{{ $stats['pending_count'] ?? 0 }}</span>
                <span class="text-gray-600">paiements en attente</span>
            </div>
        @endif
    </div>

    {{-- Statistiques --}}
    @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total réservations actives --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Réservations actives</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['active_count'] }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total personnes --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total personnes</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_personnes'] }}</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Revenu total --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Revenu total</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_revenue'], 2) }} €</p>
                        <p class="text-green-100 text-xs mt-1">Paiements validés</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Paiements en attente --}}
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">En attente</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['pending_revenue'], 2) }} €</p>
                        <p class="text-orange-100 text-xs mt-1">{{ $stats['pending_count'] }} paiement(s)</p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques détaillées --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Détails</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Réservations annulées</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['deleted_count'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Taux d'occupation</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['occupation_rate'], 1) }}%</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Prix moyen/personne</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['avg_price_per_person'], 2) }} €</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Taux de paiement</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['payment_rate'], 1) }}%</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulaire rapide de création d'une réservation --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Nouvelle réservation</h3>
        <form method="POST" action="{{ route('reservations.store') }}" class="flex gap-4 flex-wrap">
            @csrf
            <input type="hidden" name="atelier_id" value="{{ $atelierId }}">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Client (nom)</label>
                <input type="text" name="client_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nb personnes *</label>
                <input type="number" name="nb_personne" required min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                <input type="number" name="prix" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition">
                    Réserver
                </button>
            </div>
        </form>
    </div>

    {{-- Composant modal Livewire --}}
    @if($editReservationId)
        @livewire('reservation-editor', ['atelierId' => $atelier->_id, 'reservationId' => $editReservationId], key($editReservationId))
    @endif

    @if(isset($reservations) && $reservations->count())
        {{-- Liste des réservations --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Liste des réservations</h3>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($reservations as $reservation)
                    @php
                        $isDeleted = !empty($reservation->deleted_at);
                    @endphp
                    <div class="p-6 {{ $isDeleted ? 'bg-gray-50 opacity-60' : 'hover:bg-gray-50' }} transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-2">
                                    Réservation #{{ substr($reservation->_id, -8) }}
                                    @if($isDeleted)
                                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                            Annulée le {{ $reservation->deleted_at->format('d/m/Y à H:i') }}
                                        </span>
                                    @endif
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">
                                            <strong>Client:</strong>
                                            {{ optional($reservation->client)->prenom ? optional($reservation->client)->prenom . ' ' . optional($reservation->client)->nom : ($reservation->client_name ?? '—') }}
                                        </p>
                                        <p class="text-gray-600">
                                            <strong>Nb personnes:</strong>
                                            {{ $reservation->nbPersonne ?? ($reservation->nb_personne ?? '—') }}
                                        </p>
                                    </div>

                                    @php
                                        $payment = $reservation->paiement;
                                        $paymentId = $payment ? (data_get($payment, '_id') ?? data_get($payment, 'id')) : null;
                                    @endphp
                                    <div>
                                        <p class="text-gray-600">
                                            <strong>Prix payé:</strong>
                                            {{ $payment ? number_format((float)($payment->montant ?? 0), 2) . ' €' : '—' }}
                                        </p>
                                        <p class="text-gray-600">
                                            <strong>Moyen de paiement:</strong>
                                            {{ $payment->methode_paiement ?? '—' }}
                                        </p>
                                        <p class="text-gray-600">
                                            <strong>Statut paiement:</strong>
                                            @if($payment)
                                                <span class="px-2 py-1 rounded text-xs {{ $payment->statut === 'validé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($payment->statut ?? 'pending') }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-4">
                                @if(!$isDeleted)
                                    <button wire:click="openEditModal('{{ $reservation->_id }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition">
                                        Éditer
                                    </button>
                                @else
                                    <span class="bg-gray-400 text-white px-4 py-2 rounded-md text-sm cursor-not-allowed">
                                        Annulée
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(method_exists($reservations, 'links'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="mt-4 text-gray-500 text-lg">Aucune réservation pour cet atelier.</p>
        </div>
    @endif
</div>
