<div>
@if($reservationId)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-xl font-bold">Éditer la réservation</h3>
            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</button>
        </div>

        @if($errors->has('global'))
            <div class="mb-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $errors->first('global') }}
            </div>
        @endif

        <!-- Informations du client (lecture seule) -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-2 text-gray-700">Client</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div>
                    <span class="text-gray-600">Nom:</span>
                    <span class="font-medium ml-2">{{ $clientNom ?? 'N/A' }} {{ $clientPrenom ?? '' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium ml-2">{{ $clientEmail ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Section Réservation -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold mb-3 text-gray-700">Détails de la réservation</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de personnes <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="nbPersonne"
                               min="1"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('nbPersonne')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prix total (€) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="prix"
                               step="0.01"
                               min="0"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('prix')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section Paiement -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold mb-3 text-gray-700">Informations de paiement</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Montant (€) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="montant"
                               step="0.01"
                               min="0"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('montant')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Méthode de paiement
                        </label>
                        <select wire:model="methode_paiement"
                                class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Sélectionner --</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                            <option value="paypal">PayPal</option>
                            <option value="credit_fidelite">Crédit fidélité</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="statut"
                                class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Sélectionner --</option>
                            <option value="en_attente">En attente</option>
                            <option value="validé">Validé</option>
                            <option value="remboursé">Remboursé</option>
                        </select>
                        @error('statut')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro de carte
                        </label>
                        <input type="text"
                               wire:model="numCarte"
                               placeholder="**** **** **** 1234"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        @error('numCarte')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t">
                <button wire:click.prevent="save"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition-colors">
                    Enregistrer les modifications
                </button>
                <button wire:click.prevent="cancelReservation"
                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible.')"
                        class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition-colors">
                    Annuler la réservation
                </button>
                <button wire:click.prevent="$set('open', false)"
                        class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded hover:bg-gray-400 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>
