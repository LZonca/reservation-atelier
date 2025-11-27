<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md">
    <h1 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-heading">
        Liste des clients
    </h1>

    <!-- Barre de recherche -->
    <input type="text"
           wire:model.live.debounce.300ms="search"
           placeholder="Rechercher un client..."
           class="mb-4 p-2 border border-gray-300 rounded w-full md:w-1/2"/>

    <!-- Grille des clients -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)
            <div class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs">
                <a href="#">
                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-heading">
                        {{$client->nom . ' ' . $client->prenom}}
                    </h5>
                </a>
                <p class="mb-3 text-body">{{$client->email . ' - ' . $client->phone}}</p>
                <button wire:click="openClientModal('{{ $client->id }}')"
                        class="inline-flex font-medium items-center text-fg-brand hover:underline">
                    Voir l'utilisateur
                    <svg class="w-4 h-4 ms-2 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>

    <!-- Modal Client -->
    @if($clientModalOpen && $selectedClient)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto">

                <!-- En-tête -->
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h2 class="text-2xl font-bold">Détails du client</h2>
                    <button wire:click="closeClientModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
                </div>

                <!-- Messages Flash -->
                @if(session()->has('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Informations du client -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nom complet</p>
                            <p class="font-semibold">{{$selectedClient->nom}} {{$selectedClient->prenom}}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold">{{$selectedClient->email}}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Téléphone</p>
                            <p class="font-semibold">{{$selectedClient->phone}}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Compte fidélité</p>
                            <p class="font-semibold text-green-600">{{$selectedClient->credit_fidelite}} crédits</p>
                        </div>
                    </div>
                </div>

                <!-- Onglets -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4">
                        <button wire:click="$set('showPanierSection', false)"
                                class="py-2 px-4 border-b-2 {{ !$showPanierSection ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            Commentaires
                        </button>
                        <button wire:click="togglePanierSection"
                                class="py-2 px-4 border-b-2 {{ $showPanierSection ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            Panier & Réservations
                            @if(!empty($panier['ateliers']))
                                <span class="ml-2 bg-red-500 text-white rounded-full px-2 py-1 text-xs">
                                    {{ count($panier['ateliers']) }}
                                </span>
                            @endif
                        </button>
                    </nav>
                </div>

                <!-- Section Commentaires -->
                @if(!$showPanierSection)
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xl font-semibold">Commentaires</h3>
                            @if(!$addingComment)
                                <button wire:click="addComment"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    Ajouter un commentaire
                                </button>
                            @endif
                        </div>

                        <!-- Formulaire d'ajout de commentaire -->
                        @if($addingComment)
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="font-semibold mb-3">Nouveau commentaire</h4>

                                <label for="atelier_id" class="block font-semibold mb-1">Atelier associé :</label>
                                <select id="atelier_id"
                                        wire:model="newComment.atelier_id"
                                        class="w-full p-2 border border-gray-300 rounded mb-2">
                                    <option value="">-- Aucun --</option>
                                    @foreach($ateliers ?? [] as $atelier)
                                        <option value="{{ $atelier->id }}">
                                            {{ $atelier->nom }}@if(!empty($atelier->date)) ({{ $atelier->date }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('newComment.atelier_id')
                                <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                @enderror

                                <label for="commentaire" class="block font-semibold mb-1">Commentaire:</label>
                                <textarea id="commentaire"
                                          wire:model="newComment.commentaire"
                                          class="w-full p-2 border border-gray-300 rounded mb-2"
                                          rows="3"></textarea>
                                @error('newComment.commentaire')
                                <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                @enderror

                                <label class="block font-semibold mb-1">Note :</label>
                                <div class="flex items-center gap-1 mb-3">
                                    @for($i=1; $i<=5; $i++)
                                        <button type="button"
                                                wire:click.prevent="setRating({{ $i }})"
                                                class="text-2xl">
                                            @if(isset($newComment['note']) && $newComment['note'] >= $i)
                                                <span class="text-yellow-400">★</span>
                                            @else
                                                <span class="text-gray-300">☆</span>
                                            @endif
                                        </button>
                                    @endfor
                                </div>
                                @error('newComment.note')
                                <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                @enderror

                                <div class="flex gap-2">
                                    <button wire:click="saveComment"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Enregistrer
                                    </button>
                                    <button wire:click="$set('addingComment', false)"
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Liste des commentaires -->
                        @if(!empty($selectedClient->commentaires) && (is_array($selectedClient->commentaires) ? count($selectedClient->commentaires) : $selectedClient->commentaires->count()))
                            <div class="space-y-3">
                                @foreach($selectedClient->commentaires as $commentaire)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    {{ isset($commentaire->created_at) ? $commentaire->created_at->format('d/m/Y H:i') : ($commentaire['created_at'] ?? 'N/A') }}
                                                </p>
                                                @if(isset($commentaire->note) || isset($commentaire['note']))
                                                    <div class="flex items-center gap-1 mt-1">
                                                        @for($i=1; $i<=5; $i++)
                                                            @if($i <= ($commentaire->note ?? $commentaire['note'] ?? 0))
                                                                <span class="text-yellow-400">★</span>
                                                            @else
                                                                <span class="text-gray-300">☆</span>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-gray-800">{{ $commentaire->commentaire ?? $commentaire['commentaire'] ?? 'N/A' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Aucun commentaire pour ce client.</p>
                        @endif
                    </div>
                @endif

                <!-- Section Panier & Réservations -->
                @if($showPanierSection)
                    <div class="mb-4">
                        <!-- Panier actuel -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-xl font-semibold">Panier actuel</h3>
                                @if(!empty($panier['ateliers']))
                                    <button wire:click="emptyPanier"
                                            class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"
                                            onclick="return confirm('Vider le panier ?')">
                                        Vider le panier
                                    </button>
                                @endif
                            </div>

                            @if(!empty($panier['ateliers']))
                                <div class="space-y-3 mb-4">
                                    @foreach($panier['ateliers'] as $item)
                                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                            <div class="flex-1">
                                                <p class="font-semibold">{{ $item['nom'] ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">
                                                    Prix unitaire: {{ number_format($item['prix'] ?? 0, 2) }}€
                                                    @if($item['vip'] ?? false)
                                                        <span class="ml-2 bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded">VIP</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <input type="number"
                                                       value="{{ $item['quantity'] ?? 1 }}"
                                                       wire:change="updateQuantity('{{ $item['id'] }}', $event.target.value)"
                                                       min="1"
                                                       class="w-16 p-1 border border-gray-300 rounded text-center">
                                                <p class="font-semibold w-20 text-right">
                                                    {{ number_format(($item['prix'] ?? 0) * ($item['quantity'] ?? 1), 2) }}€
                                                </p>
                                                <button wire:click="removeFromPanier('{{ $item['id'] }}')"
                                                        class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex justify-between items-center bg-blue-50 p-4 rounded-lg">
                                    <p class="text-xl font-bold">Total:</p>
                                    <p class="text-xl font-bold text-blue-600">{{ number_format($this->getTotalPanier(), 2) }}€</p>
                                </div>

                                <!-- Bouton pour afficher le formulaire de paiement -->
                                @if(!$showPaiementForm)
                                    <button wire:click="showPaiement"
                                            class="w-full mt-4 px-4 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600">
                                        Procéder au paiement
                                    </button>
                                @endif

                                <!-- Formulaire de paiement -->
                                @if($showPaiementForm)
                                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <h4 class="font-semibold mb-3 text-lg">Finaliser le paiement</h4>

                                        <label class="block font-semibold mb-1">Numéro de carte:</label>
                                        <input type="text"
                                               wire:model="numCarte"
                                               placeholder="1234 5678 9012 3456"
                                               class="w-full p-2 border border-gray-300 rounded mb-3">
                                        @error('numCarte')
                                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                        @enderror

                                        <label class="block font-semibold mb-1">Méthode de paiement:</label>
                                        <select wire:model="methodePaiement"
                                                class="w-full p-2 border border-gray-300 rounded mb-3">
                                            <option value="carte">Carte bancaire</option>
                                            <option value="virement">Virement</option>
                                            <option value="paypal">PayPal</option>
                                            <option value="credit_fidelite">Crédit fidélité ({{ $selectedClient->credit_fidelite }} disponibles)</option>
                                        </select>
                                        @error('methodePaiement')
                                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                        @enderror

                                        <div class="flex gap-2 mt-4">
                                            <button wire:click="processPanier"
                                                    class="flex-1 px-4 py-2 bg-green-500 text-white font-semibold rounded hover:bg-green-600">
                                                Confirmer le paiement
                                            </button>
                                            <button wire:click="$set('showPaiementForm', false)"
                                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                                Annuler
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-gray-500 text-center py-4">Le panier est vide.</p>
                            @endif
                        </div>

                        <!-- Ateliers disponibles -->
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Ateliers disponibles</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($ateliersDisponibles as $atelier)
                                    <div class="border border-gray-200 rounded-lg p-3 bg-white hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-semibold">{{ $atelier->nom }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    Prix: {{ number_format($atelier->prix ?? 0, 2) }}€
                                                    @if($atelier->vip)
                                                        <span class="ml-2 bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded">VIP</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Places: {{ $atelier->remainingCapacity() }}/{{ $atelier->salle->capacite ?? 0 }} disponibles
                                                </p>
                                            </div>
                                            <button wire:click="addToPanier('{{ $atelier->id }}')"
                                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                                Ajouter
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bouton de fermeture en bas -->
                <div class="mt-6 pt-4 border-t">
                    <button wire:click="closeClientModal"
                            class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
