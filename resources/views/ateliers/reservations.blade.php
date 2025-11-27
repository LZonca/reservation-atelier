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

        @if(isset($reservations) && $reservations->count())
            <div class="space-y-3">
                @foreach($reservations as $reservation)
                    <div class="bg-white rounded shadow p-3 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Réservation #{{ $reservation->_id }}</div>
                            <div class="text-sm text-gray-500">Client: {{ optional($reservation->client)->prenom ? optional($reservation->client)->prenom . ' ' . optional($reservation->client)->nom : ($reservation->client_name ?? '—') }}</div>
                            <div class="text-sm text-gray-500">Nb personnes: {{ $reservation->nbPersonne ?? ($reservation->nb_personne ?? '—') }}</div>

                            @php
                                $payment = $reservation->paiement ?? null;
                                $paymentId = data_get($payment, '_id') ?? data_get($payment, 'id') ?? ($payment['_id'] ?? ($payment['id'] ?? ''));
                            @endphp

                            <div class="text-sm text-gray-500">
                                Prix payé : {{ $payment ? number_format((float)($payment->montant ?? $payment['montant'] ?? 0), 2).' €' : '—' }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Moyen de paiement : {{ optional($payment)->methode_paiement ?? ($payment['methode_paiement'] ?? '—') }}
                            </div>

                            <div class="text-sm text-gray-500 mt-2">
                                Statut paiement :
                                @if($payment)
                                    <select
                                        wire:model.live="paymentStatus.{{ $paymentId }}"
                                        wire:change="updatePaymentStatus('{{ $paymentId }}', $event.target.value)"
                                        class="mt-1 block w-full border rounded px-2 py-1 text-sm payment-status-select"
                                        >
                                        <option value="pending" {{ (optional($payment)->statut ?? ($payment['statut'] ?? null)) === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="completed" {{ (optional($payment)->statut ?? ($payment['statut'] ?? null)) === 'completed' ? 'selected' : '' }}>Terminé</option>
                                        <option value="failed" {{ (optional($payment)->statut ?? ($payment['statut'] ?? null)) === 'failed' ? 'selected' : '' }}>Échoué</option>
                                        <option value="refunded" {{ (optional($payment)->statut ?? ($payment['statut'] ?? null)) === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                                    </select>
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <div class="space-x-2 text-sm">
                            {{-- actions (voir, annuler, etc.) --}}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">@if(method_exists($reservations, 'links')) {{ $reservations->links() }} @endif</div>
        @else
            <p class="text-gray-500">Aucune réservation pour cet atelier.</p>
        @endif
    </div>

    {{-- Le script fetch a été retiré : la mise à jour du statut doit être gérée par le composant Livewire (méthode updatePaymentStatus). --}}

    <script>
        (function(){
            // Fallback AJAX si Livewire n'est pas présent
            const selects = document.querySelectorAll('.payment-status-select');
            if (!selects || selects.length === 0) return;

            const csrf = '{{ csrf_token() }}';

            selects.forEach(select => {
                // si Livewire est présent et que le select a wire:change, on laisse Livewire gérer
                const hasWire = select.hasAttribute('wire:change') && typeof window.Livewire !== 'undefined';
                if (hasWire) return;

                select.addEventListener('change', function () {
                    const paymentId = this.dataset.paymentId;
                    const statut = this.value;
                    if (!paymentId) return;

                    fetch(`/paiements/${paymentId}/statut`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ statut })
                    }).then(resp => {
                        if (!resp.ok) throw resp;
                        return resp.json();
                    }).then(json => {
                        // feedback minimal
                        select.classList.add('opacity-75');
                        setTimeout(() => select.classList.remove('opacity-75'), 300);
                    }).catch(err => {
                        console.error('Erreur update statut paiement', err);
                        alert('Impossible de mettre à jour le statut du paiement.');
                    });
                });
            });
        })();
    </script>
</x-app-layout>
