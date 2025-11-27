<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Atelier;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class ClientsDisplay extends Component
{
    public $clients;
    public $ateliers;
    public $search = '';

    // Modal client
    public $clientModalOpen = false;
    public $selectedClient = null;

    // Gestion des commentaires
    public $addingComment = false;
    public $newComment = [
        'commentaire' => '',
        'note' => null,
        'atelier_id' => null,
        'reservation_id' => null,
    ];

    // Gestion du panier
    public $panier = ['ateliers' => []];
    public $showPanierSection = false;
    public $ateliersDisponibles = [];

    // Gestion du paiement
    public $showPaiementForm = false;
    public $numCarte = '';
    public $methodePaiement = 'carte';

    public $paymentStatus = [];

    public function mount()
    {
        $this->clients = Client::with('commentaires')->get();
        $this->ateliers = Atelier::all();
        $this->ateliersDisponibles = Atelier::all();

        // Init paymentStatus for existing payments (optional)
        try {
            foreach ($this->ateliers as $atelier) {
                foreach ($atelier->reservations ?? [] as $res) {
                    foreach ($res['paiements'] ?? [] as $pay) {
                        $pid = data_get($pay, '_id') ?? data_get($pay, 'id') ?? ($pay['_id'] ?? ($pay['id'] ?? null));
                        if ($pid) {
                            $this->paymentStatus[(string)$pid] = data_get($pay, 'statut') ?? data_get($pay, 'status') ?? null;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[ClientsDisplay] Impossible d\'initialiser paymentStatus: ' . $e->getMessage());
        }
    }

    public function openClientModal($id)
    {
        $this->selectedClient = Client::with('commentaires')->find($id);
        $this->panier = $this->selectedClient->panier ?? ['ateliers' => []];
        $this->numCarte = '';
        $this->methodePaiement = 'carte';
        $this->showPanierSection = false;
        $this->showPaiementForm = false;
        $this->clientModalOpen = true;
    }

    public function closeClientModal()
    {
        $this->selectedClient = null;
        $this->clientModalOpen = false;
        $this->showPanierSection = false;
        $this->showPaiementForm = false;
        $this->addingComment = false;
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->clients = Client::with('commentaires')->get();
            return;
        }

        $this->clients = Client::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('prenom', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->get();
    }

    // ==========================================
    // GESTION DES COMMENTAIRES
    // ==========================================

    public function addComment()
    {
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
        $this->validate([
            'newComment.commentaire' => 'required|string|max:1000',
            'newComment.note' => 'nullable|integer|min:1|max:5',
        ]);

        $atelierId = $this->newComment['atelier_id'] ?? null;

        if ($atelierId) {
            $atelier = Atelier::find($atelierId);
            if (!$atelier) {
                $this->addError('newComment.atelier_id', 'Atelier sélectionné invalide.');
                return;
            }
        }

        $data = [
            'commentaire' => $this->newComment['commentaire'],
            'note' => $this->newComment['note'] ?? null,
            'atelier_id' => $atelierId,
            'client_id' => $this->selectedClient->_id ?? $this->selectedClient->id,
        ];

        try {
            $created = $this->selectedClient->commentaires()->create($data);

            Log::debug('[ClientsDisplay] Commentaire créé', [
                'id' => $created->_id,
                'client_id' => $created->client_id,
                'atelier_id' => $created->atelier_id,
            ]);

            $this->selectedClient->load('commentaires');

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

    // ==========================================
    // GESTION DU PANIER
    // ==========================================

    public function togglePanierSection()
    {
        $this->showPanierSection = !$this->showPanierSection;
    }

    public function addToPanier($atelierId)
    {
        $atelier = Atelier::find($atelierId);

        if (!$atelier) {
            session()->flash('error', 'Atelier introuvable.');
            return;
        }

        if (!isset($this->panier['ateliers'])) {
            $this->panier['ateliers'] = [];
        }

        // Vérifier si l'atelier existe déjà dans le panier
        $found = false;
        foreach ($this->panier['ateliers'] as &$item) {
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            if ($itemId == $atelierId) {
                $item['quantity'] = ($item['quantity'] ?? 0) + 1;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $this->panier['ateliers'][] = [
                'id' => $atelierId,
                'quantity' => 1,
                'nom' => $atelier->nom,
                'prix' => $atelier->prix ?? 0,
                'vip' => $atelier->vip ?? false,
            ];
        }

        // Sauvegarder dans la base de données
        $this->selectedClient->panier = $this->panier;
        $this->selectedClient->save();

        session()->flash('success', 'Atelier ajouté au panier.');
    }

    public function updateQuantity($atelierId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromPanier($atelierId);
            return;
        }

        foreach ($this->panier['ateliers'] as &$item) {
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            if ($itemId == $atelierId) {
                $item['quantity'] = max(1, (int) $quantity);
                break;
            }
        }
        unset($item);

        $this->selectedClient->panier = $this->panier;
        $this->selectedClient->save();
    }

    public function removeFromPanier($atelierId)
    {
        $this->panier['ateliers'] = array_values(array_filter($this->panier['ateliers'], function ($item) use ($atelierId) {
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            return $itemId != $atelierId;
        }));

        $this->selectedClient->panier = $this->panier;
        $this->selectedClient->save();

        session()->flash('success', 'Atelier retiré du panier.');
    }

    public function emptyPanier()
    {
        $this->panier = ['ateliers' => []];
        $this->selectedClient->panier = $this->panier;
        $this->selectedClient->save();

        session()->flash('success', 'Panier vidé.');
    }

    public function getTotalPanier()
    {
        $total = 0;
        foreach ($this->panier['ateliers'] ?? [] as $item) {
            $total += ($item['prix'] ?? 0) * ($item['quantity'] ?? 1);
        }
        return $total;
    }

    // ==========================================
    // GESTION DU PAIEMENT
    // ==========================================

    public function showPaiement()
    {
        if (empty($this->panier['ateliers'])) {
            session()->flash('error', 'Le panier est vide.');
            return;
        }

        $this->showPaiementForm = true;
    }

    public function processPanier()
    {
        $this->validate([
            'numCarte' => 'required|string|min:4',
            'methodePaiement' => 'required|string|in:carte,virement,paypal,credit_fidelite',
        ]);

        if (empty($this->panier['ateliers'])) {
            session()->flash('error', 'Le panier est vide.');
            return;
        }

        try {
            // Vérifier la capacité pour tous les ateliers
            foreach ($this->panier['ateliers'] as $item) {
                $atelier = Atelier::find($item['id']);

                if (!$atelier) {
                    session()->flash('error', 'Atelier introuvable: ' . $item['nom']);
                    return;
                }

                $remaining = $atelier->remainingCapacity();
                if ($remaining < $item['quantity']) {
                    session()->flash('error', "Capacité insuffisante pour l'atelier {$atelier->nom}. Places restantes: {$remaining}");
                    return;
                }
            }

            // Créer les réservations
            $reservations = [];

            foreach ($this->panier['ateliers'] as $item) {
                $atelier = Atelier::find($item['id']);
                $prix = ($atelier->prix ?? 0) * $item['quantity'];

                // Gestion des ateliers VIP
                if ($atelier->vip && $this->methodePaiement == 'credit_fidelite') {
                    if ($this->selectedClient->credit_fidelite < 10) {
                        session()->flash('error', "Crédits de fidélité insuffisants. Requis: 10, Disponible: {$this->selectedClient->credit_fidelite}");
                        return;
                    }
                    $prix = 0;
                    $this->selectedClient->credit_fidelite -= 10;
                } elseif ($atelier->vip && $this->methodePaiement != 'credit_fidelite') {
                    session()->flash('error', "Les ateliers VIP ne peuvent être payés qu'avec des crédits de fidélité. Atelier: {$atelier->nom}");
                    return;
                } elseif (!$atelier->vip && $this->methodePaiement == 'credit_fidelite') {
                    session()->flash('error', "Les crédits de fidélité ne sont utilisables que pour les ateliers VIP. Votre solde: {$this->selectedClient->credit_fidelite}");
                    return;
                }

                // Créer les paiements
                $paiements = [];
                $paiements[] = [
                    '_id' => new ObjectId(),
                    'numCarte' => $this->numCarte,
                    'montant' => $prix,
                    'methode_paiement' => $this->methodePaiement,
                    'statut' => 'validé',
                    'payement_recieved_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Créer la réservation avec paiements embarqués
                $reservationData = [
                    '_id' => new ObjectId(),
                    'nbPersonne' => $item['quantity'],
                    'prix' => $prix,
                    'client_id' => new ObjectId((string) $this->selectedClient->_id),
                    'atelier_id' => new ObjectId((string) $atelier->_id),
                    'paiements' => $paiements,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $atelier->push('reservations', $reservationData);

                // Ajouter des crédits de fidélité (sauf si payé avec crédits)
                if ($this->methodePaiement != 'credit_fidelite') {
                    $this->selectedClient->credit_fidelite += $item['quantity'];
                }

                $reservations[] = $reservationData;
            }

            // Vider le panier
            $this->panier = ['ateliers' => []];
            $this->selectedClient->panier = $this->panier;
            $this->selectedClient->save();

            // Réinitialiser le formulaire de paiement
            $this->showPaiementForm = false;
            $this->numCarte = '';
            $this->methodePaiement = 'carte';

            // Recharger le client pour afficher les nouvelles données
            $this->selectedClient->refresh();

            session()->flash('success', "Paiement effectué avec succès! " . count($reservations) . " réservation(s) créée(s). Crédits de fidélité: {$this->selectedClient->credit_fidelite}");

        } catch (\Exception $e) {
            Log::error('[ClientsDisplay] Erreur traitement panier: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            session()->flash('error', 'Erreur lors du traitement du panier: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.clients-display')->layout('layouts.app');
    }
}
