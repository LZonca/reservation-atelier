<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md">
    <h1 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-heading">
    Liste des clients
    </h1>
    <!-- Utiliser un debounce côté Livewire -->
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un client..." class="mb-4 p-2 border border-gray-300 rounded"/>
    <div  class="w-full grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
        @foreach($clients as $client)

            <div class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs">
                {{--<x-heroicon-o-user />--}}
                <a href="#">
                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-heading">{{$client->nom . ' ' . $client->prenom  }}</h5>
                </a>
                <p class="mb-3 text-body">{{$client->email . ' - ' . $client->phone }}</p>
                <!-- Appel Livewire correctement : on passe l'id (string) et on n'exécute pas la méthode côté vue -->
                <button wire:click="openClientModal('{{ $client->id }}')" class="inline-flex font-medium items-center text-fg-brand hover:underline">
                    Voir l'utilisateur
                    <svg class="w-4 h-4 ms-2 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/></svg>
                </button>
            </div>

        @endforeach
    </div>

    @if($clientModalOpen && $selectedClient)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h2 class="text-2xl font-bold mb-4">Détails du client</h2>
                <p><strong>Nom:</strong> {{$selectedClient->nom}}</p>
                <p><strong>Prénom:</strong> {{$selectedClient->prenom}}</p>
                <p><strong>Email:</strong> {{$selectedClient->email}}</p>
                <p><strong>Téléphone:</strong> {{$selectedClient->phone}}</p>
                <p><strong>Compte fidélité:</strong> {{$selectedClient->credit_fidelite}}</p>
                <!-- Debug temporaire : afficher les réservations en JSON -->
                <div class="mt-4">
                @if(!empty($selectedClient->commentaires) && (is_array($selectedClient->commentaires) ? count($selectedClient->commentaires) : $selectedClient->commentaires->count()))
                    @foreach($selectedClient->commentaires as $commentaire)
                        <div class="border-t mt-2 pt-2">
                            <p>Atelier: </p>
                            <p>Date: {{ isset($commentaire->created_at) ? $commentaire->created_at : ($commentaire['created_at'] ?? 'N/A') }}</p>
                            <p>Commentaire: {{ $commentaire->commentaire ?? $commentaire['commentaire'] ?? 'N/A' }}</p>
                            <p>Note: {{ $commentaire->note ?? $commentaire['note'] ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                @else
                    <p>Aucun commentaire pour ce client.</p>
                @endif

                <!-- Bouton toujours disponible pour ajouter d'autres commentaires -->
                <div class="mt-2">
                    <button wire:click="addComment" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Ajouter un commentaire</button>
                </div>

                @if($this->addingComment)
                    <div class="mt-4">
                        <label for="atelier_id" class="block font-semibold">Atelier associé :</label>
                        <select id="atelier_id" wire:model="newComment.atelier_id" class="w-full p-2 border border-gray-300 rounded mb-1">
                            <option value="">-- Aucun --</option>
                            @foreach($ateliers ?? [] as $atelier)
                                <option value="{{ $atelier->id }}">{{ $atelier->nom }}@if(!empty($atelier->date)) ({{ $atelier->date }})@endif</option>
                            @endforeach
                        </select>
                        @error('newComment.atelier_id')
                            <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                        @enderror
                        <label for="commentaire" class="block font-semibold">Commentaire:</label>
                        <textarea id="commentaire" wire:model="newComment.commentaire" class="w-full p-2 border border-gray-300 rounded mb-1"></textarea>
                        @error('newComment.commentaire')
                            <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                        @enderror
                        <label class="block font-semibold">Note :</label>
                        <div class="flex items-center gap-1 mb-2">
                            @for($i=1;$i<=5;$i++)
                                <button type="button" wire:click.prevent="setRating({{ $i }})" class="text-xl">
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
                        <button wire:click="saveComment" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Enregistrer le commentaire</button>
                    </div>
                @endif
                </div>

                <button wire:click="closeClientModal" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Fermer</button>
            </div>
        </div>
    @endif
</div>
