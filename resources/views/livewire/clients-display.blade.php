<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md">
    <!-- En-tête avec bouton d'ajout -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold leading-none tracking-tight text-heading">
            Liste des clients
        </h1>
        <button wire:click="openCreateModal"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau client
        </button>
    </div>

    <!-- Barre de recherche -->
    <input type="text"
           wire:model.live.debounce.300ms="search"
           placeholder="Rechercher un client..."
           class="mb-6 p-3 border border-gray-300 rounded-lg w-full md:w-1/2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>

    <!-- Grille des clients -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow p-5">
                <!-- Avatar avec initiales -->
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-md">
                        {{ strtoupper(substr($client->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($client->nom ?? '', 0, 1)) }}
                    </div>
                    <div class="ml-3 flex-1">
                        <h5 class="text-lg font-semibold text-gray-900">
                            {{$client->prenom}} {{$client->nom}}
                        </h5>
                        <p class="text-sm text-gray-500">{{ $client->credit_fidelite ?? 0 }} crédits</p>
                    </div>
                </div>

                <!-- Informations -->
                <div class="space-y-2 mb-4">
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{$client->email}}
                    </p>
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{$client->phone}}
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-2">
                    <button wire:click="openClientModal('{{ $client->id }}')"
                            class="flex-1 px-3 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Voir
                    </button>
                    <button wire:click="openEditModal('{{ $client->id }}')"
                            class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Création/Édition Client -->
    @if($editModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h2 class="text-2xl font-bold">
                        {{ $editingClientId ? 'Modifier le client' : 'Nouveau client' }}
                    </h2>
                    <button wire:click="closeEditModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
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

                <!-- Formulaire -->
                <form wire:submit.prevent="saveClient" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nom -->
                        <div>
                            <label for="edit_nom" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="edit_nom"
                                   wire:model="editForm.nom"
                                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('editForm.nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="edit_prenom" class="block text-sm font-medium text-gray-700 mb-1">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="edit_prenom"
                                   wire:model="editForm.prenom"
                                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('editForm.prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="edit_email"
                                   wire:model="editForm.email"
                                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('editForm.email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel"
                                   id="edit_phone"
                                   wire:model="editForm.phone"
                                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('editForm.phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Crédit fidélité -->
                        <div>
                            <label for="edit_credit" class="block text-sm font-medium text-gray-700 mb-1">
                                Crédit fidélité
                            </label>
                            <input type="number"
                                   id="edit_credit"
                                   wire:model="editForm.credit_fidelite"
                                   min="0"
                                   step="0.01"
                                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('editForm.credit_fidelite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            {{ $editingClientId ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <button type="button"
                                wire:click="closeEditModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

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
                                    @php
                                        $hasVip = collect($panier['ateliers'] ?? [])->contains(fn($item) => $item['vip'] ?? false);
                                        $hasNonVip = collect($panier['ateliers'] ?? [])->contains(fn($item) => !($item['vip'] ?? false));
                                    @endphp

                                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <h4 class="font-semibold mb-3 text-lg">Finaliser le paiement</h4>

                                        <label class="block font-semibold mb-1">Méthode de paiement:</label>
                                        <select wire:model.live="methodePaiement"
                                                class="w-full p-2 border border-gray-300 rounded mb-3">
                                            @if($hasVip)
                                                {{-- Si le panier contient des ateliers VIP, seul le crédit de fidélité est disponible --}}
                                                <option value="credit_fidelite">Crédit fidélité ({{ $selectedClient->credit_fidelite }} disponibles)</option>
                                            @else
                                                {{-- Pour les ateliers non-VIP, toutes les options sauf crédit de fidélité --}}
                                                <option value="carte">Carte bancaire</option>
                                                <option value="virement">Virement bancaire</option>
                                                <option value="paypal">PayPal</option>
                                            @endif
                                        </select>
                                        @error('methodePaiement')
                                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                        @enderror

                                        {{-- Champ pour carte bancaire --}}
                                        @if($methodePaiement === 'carte')
                                            <label class="block font-semibold mb-1">Numéro de carte:</label>
                                            <input type="text"
                                                   wire:model="numCarte"
                                                   placeholder="1234 5678 9012 3456"
                                                   class="w-full p-2 border border-gray-300 rounded mb-3">
                                            @error('numCarte')
                                            <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                            @enderror
                                        @endif

                                        {{-- Champ pour virement bancaire (IBAN) --}}
                                        @if($methodePaiement === 'virement')
                                            <label class="block font-semibold mb-1">IBAN:</label>
                                            <input type="text"
                                                   wire:model="numCarte"
                                                   placeholder="FR76 XXXX XXXX XXXX XXXX XXXX XXX"
                                                   class="w-full p-2 border border-gray-300 rounded mb-3">
                                            @error('numCarte')
                                            <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                                            @enderror
                                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded mb-3 text-sm">
                                                <p><strong>Instructions:</strong></p>
                                                <p>Montant à virer: <strong>{{ number_format($this->getTotalPanier(), 2) }} €</strong></p>
                                                <p>RIB: FR76 1234 5678 9012 3456 7890 123</p>
                                                <p>Référence: RES-{{ strtoupper(uniqid()) }}</p>
                                            </div>
                                        @endif

                                        {{-- Bouton PayPal --}}
                                        @if($methodePaiement === 'paypal')
                                            <div class="mb-3">
                                                <div class="bg-blue-50 border border-blue-300 rounded-lg p-4 text-center">
                                                    <p class="text-sm text-gray-700 mb-3">
                                                        Montant à payer: <strong class="text-lg">{{ number_format($this->getTotalPanier(), 2) }} €</strong>
                                                    </p>
                                                    <button type="button"
                                                            class="bg-[#0070ba] hover:bg-[#005ea6] text-white font-semibold px-6 py-3 rounded-lg flex items-center justify-center gap-2 w-full transition">
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.76-4.852a.932.932 0 0 1 .923-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.746-4.46z"/>
                                                        </svg>
                                                        Payer avec PayPal
                                                    </button>
                                                    <p class="text-xs text-gray-500 mt-2">Vous serez redirigé vers PayPal pour finaliser le paiement</p>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Informations pour crédit de fidélité --}}
                                        @if($methodePaiement === 'credit_fidelite')
                                            @php
                                                $totalCreditsNeeded = collect($panier['ateliers'] ?? [])->sum(fn($item) => ($item['quantity'] ?? 1) * 10);
                                            @endphp
                                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded mb-3">
                                                <p class="text-sm">
                                                    <strong>Crédits requis:</strong> {{ $totalCreditsNeeded }}
                                                    <br>
                                                    <strong>Crédits disponibles:</strong> {{ $selectedClient->credit_fidelite }}
                                                    @if($selectedClient->credit_fidelite >= $totalCreditsNeeded)
                                                        <span class="ml-2 text-green-600">✓ Suffisant</span>
                                                    @else
                                                        <span class="ml-2 text-red-600">✗ Insuffisant (manque {{ $totalCreditsNeeded - $selectedClient->credit_fidelite }} crédits)</span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex gap-2 mt-4">
                                            @if($methodePaiement === 'paypal')
                                                {{-- Pour PayPal, le bouton de paiement est intégré ci-dessus --}}
                                                <button wire:click="processPanier"
                                                        class="flex-1 px-4 py-2 bg-green-500 text-white font-semibold rounded hover:bg-green-600">
                                                    Confirmer la commande
                                                </button>
                                            @else
                                                <button wire:click="processPanier"
                                                        class="flex-1 px-4 py-2 bg-green-500 text-white font-semibold rounded hover:bg-green-600">
                                                    Confirmer le paiement
                                                </button>
                                            @endif
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
