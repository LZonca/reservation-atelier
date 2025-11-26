<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Atelier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use MongoDB\Laravel\Eloquent\Casts\ObjectId;

class ClientsDisplay extends Component
{

    public $clients = [];
    public $search = '';
    public $clientModalOpen = false;
    public $newComment = [];
    // public $newCommentRating = 0; // replaced by $newComment['note']
    public $addingComment = false;
    public $selectedClient = null;

    // Nouveaux : listes des ateliers et atelier sélectionné
    public $ateliers = [];
    public $selectedAtelierId = null;

    public function mount()
    {
        $this->clients = Client::with('commentaires')->get();
        $this->ateliers = Atelier::all();
        $this->clientModalOpen = false;
        $this->selectedClient = null;
        $this->newComment = [
            'commentaire' => '',
            'note' => null,
            'atelier_id' => null,
            'reservation_id' => null,
        ];
    }

    public function openClientModal($id)
    {
        // Charger le client avec ses commentaires pour les afficher immédiatement
        $this->selectedClient = Client::with('commentaires')->find($id);
        $this->clientModalOpen = true;
    }

    public function closeClientModal()
    {
        $this->selectedClient = null;
        $this->clientModalOpen = false;
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->clients = Client::all();
            return;
        }

        $this->clients = Client::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('prenom', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function addComment(){
        // Affiche le formulaire et initialise le tableau
        $this->addingComment = true;
        $this->newComment = [
            'commentaire' => '',
            'note' => null,
            'atelier_id' => null,
            'reservation_id' => null,
        ];
    }

    public function setRating($n)
    {
        $this->newComment['note'] = (int) $n;
    }

    public function saveComment()
    {
        // Validation
        $this->validate([
            'newComment.commentaire' => 'required|string|max:1000',
            'newComment.note' => 'nullable|integer|min:1|max:5',
        ]);

        // Vérifier l'atelier si spécifié
        $atelierId = $this->newComment['atelier_id'] ?? $this->newComment['reservation_id'] ?? null;

        if ($atelierId) {
            $atelier = Atelier::find($atelierId);
            if (!$atelier) {
                $this->addError('newComment.atelier_id', 'Atelier sélectionné invalide.');
                return;
            }
        }

        // Préparer les données (la conversion en ObjectId se fera automatiquement)
        $data = [
            'commentaire' => $this->newComment['commentaire'],
            'note' => $this->newComment['note'] ?? null,
            'atelier_id' => $atelierId, // Sera converti en ObjectId par le modèle
            'client_id' => $this->selectedClient->_id ?? $this->selectedClient->id, // Idem
        ];

        try {
            // Créer le commentaire (les IDs seront convertis automatiquement)
            $created = $this->selectedClient->commentaires()->create($data);

            Log::debug('[ClientsDisplay] Commentaire créé', [
                'id' => $created->_id,
                'client_id' => $created->client_id,
                'atelier_id' => $created->atelier_id,
            ]);

            // Rafraîchir le client
            $this->selectedClient->load('commentaires');

            // Réinitialiser le formulaire
            $this->addingComment = false;
            $this->newComment = [
                'commentaire' => '',
                'note' => null,
                'atelier_id' => null,
                'reservation_id' => null,
            ];

            session()->flash('success', 'Commentaire ajouté avec succès.');

        } catch (\Exception $e) {
            Log::error('[ClientsDisplay] Erreur création commentaire: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            $this->addError('newComment', 'Impossible d\'enregistrer le commentaire.');
        }
    }

    public function render()
    {
        return view('livewire.clients-display')->layout('layouts.app');
    }
}
