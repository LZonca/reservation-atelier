<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Atelier;
use App\Models\Commentaire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class ClientsDisplay extends Component
{
    use WithPagination;

    public $ateliers;
    public $search = '';
    public $perPage = 20;

    // Modal client
    public $clientModalOpen = false;
    public $selectedClient = null;
    public $clientCommentaires = []; // Stocker les commentaires séparément pour Livewire

    // Modal création/édition
    public $editModalOpen = false;
    public $editingClientId = null;
    public $editForm = [
        'nom' => '',
        'prenom' => '',
        'email' => '',
        'phone' => '',
        'credit_fidelite' => 0,
    ];

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Optimisation : Ne rien charger au démarrage
        // Les ateliers seront chargés uniquement quand nécessaire (lazy loading)
    }

    public function openClientModal($id)
    {
        $this->selectedClient = Client::find($id);

        // Charger manuellement les commentaires et les stocker dans une propriété Livewire
        $clientOid = new ObjectId((string)$id);
        $commentaires = Commentaire::where('client_id', $clientOid)
            ->orderBy('created_at', 'desc')
            ->get();

        // Stocker dans une propriété Livewire pour persistance
        $this->clientCommentaires = $commentaires->toArray();
        $this->selectedClient->setRelation('commentaires', $commentaires);

        // Convertir les ObjectId du panier en string pour l'UI Livewire
        $panierFromDb = $this->selectedClient->panier ?? ['ateliers' => []];
        $this->panier = $this->convertPanierIdsToString($panierFromDb);

        // Ajuster automatiquement la méthode de paiement selon le contenu du panier
        $hasVip = collect($this->panier['ateliers'] ?? [])->contains(fn($item) => $item['vip'] ?? false);
        $this->methodePaiement = $hasVip ? 'credit_fidelite' : 'carte';

        $this->numCarte = '';
        $this->showPanierSection = false;
        $this->showPaiementForm = false;
        $this->clientModalOpen = true;
    }

    public function closeClientModal()
    {
        $this->selectedClient = null;
        $this->clientCommentaires = [];
        $this->clientModalOpen = false;
        $this->showPanierSection = false;
        $this->showPaiementForm = false;
        $this->addingComment = false;
    }

    // ==========================================
    // GESTION DU MODAL CRÉATION/ÉDITION
    // ==========================================

    public function openCreateModal()
    {
        $this->editModalOpen = true;
        $this->editingClientId = null;
        $this->editForm = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'phone' => '',
            'credit_fidelite' => 0,
        ];
    }

    public function openEditModal($clientId)
    {
        $client = Client::find($clientId);
        if (!$client) {
            session()->flash('error', 'Client introuvable.');
            return;
        }

        $this->editModalOpen = true;
        $this->editingClientId = $clientId;
        $this->editForm = [
            'nom' => $client->nom ?? '',
            'prenom' => $client->prenom ?? '',
            'email' => $client->email ?? '',
            'phone' => $client->phone ?? '',
            'credit_fidelite' => $client->credit_fidelite ?? 0,
        ];
    }

    public function closeEditModal()
    {
        $this->editModalOpen = false;
        $this->editingClientId = null;
        $this->editForm = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'phone' => '',
            'credit_fidelite' => 0,
        ];
    }

    public function saveClient()
    {
        $this->validate([
            'editForm.nom' => 'required|string|max:255',
            'editForm.prenom' => 'required|string|max:255',
            'editForm.email' => 'required|email|max:255',
            'editForm.phone' => 'required|string|max:20',
            'editForm.credit_fidelite' => 'nullable|numeric|min:0',
        ]);

        try {
            if ($this->editingClientId) {
                // Mise à jour
                $client = Client::find($this->editingClientId);
                if (!$client) {
                    session()->flash('error', 'Client introuvable.');
                    return;
                }

                $client->update([
                    'nom' => $this->editForm['nom'],
                    'prenom' => $this->editForm['prenom'],
                    'email' => $this->editForm['email'],
                    'phone' => $this->editForm['phone'],
                    'credit_fidelite' => $this->editForm['credit_fidelite'] ?? 0,
                ]);

                session()->flash('success', 'Client mis à jour avec succès.');
            } else {
                // Création
                Client::create([
                    'nom' => $this->editForm['nom'],
                    'prenom' => $this->editForm['prenom'],
                    'email' => $this->editForm['email'],
                    'phone' => $this->editForm['phone'],
                    'credit_fidelite' => $this->editForm['credit_fidelite'] ?? 0,
                    'panier' => ['ateliers' => []],
                ]);

                session()->flash('success', 'Client créé avec succès.');
            }

            $this->closeEditModal();
            // La liste sera automatiquement rechargée par render() avec la pagination

        } catch (\Exception $e) {
            Log::error('[ClientsDisplay] Erreur sauvegarde client: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la sauvegarde du client.');
        }
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

        // Lazy loading : charger les ateliers uniquement quand on ajoute un commentaire
        if (empty($this->ateliers)) {
            $this->ateliers = Atelier::select('_id', 'nom')->get();
        }
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
        ];

        // Convertir les IDs en ObjectId explicitement
        if ($atelierId) {
            try {
                $data['atelier_id'] = new ObjectId((string) $atelierId);
            } catch (\Exception $e) {
                Log::error('[ClientsDisplay] Erreur conversion atelier_id: ' . $e->getMessage());
                $this->addError('newComment.atelier_id', 'ID atelier invalide.');
                return;
            }
        }

        try {
            $clientIdStr = (string) ($this->selectedClient->_id ?? $this->selectedClient->id);
            $data['client_id'] = new ObjectId($clientIdStr);
        } catch (\Exception $e) {
            Log::error('[ClientsDisplay] Erreur conversion client_id: ' . $e->getMessage());
            $this->addError('newComment', 'ID client invalide.');
            return;
        }

        try {
            // Créer directement le commentaire au lieu d'utiliser la relation
            $created = Commentaire::create($data);

            Log::debug('[ClientsDisplay] Commentaire créé', [
                'id' => $created->_id,
                'client_id' => $created->client_id,
                'atelier_id' => $created->atelier_id ?? 'null',
            ]);

            // Recharger manuellement les commentaires
            $clientId = $this->selectedClient->_id ?? $this->selectedClient->id;
            $clientOid = new ObjectId((string)$clientId);
            $commentaires = Commentaire::where('client_id', $clientOid)
                ->orderBy('created_at', 'desc')
                ->get();

            // Mettre à jour les deux : la relation ET la propriété Livewire
            $this->clientCommentaires = $commentaires->toArray();
            $this->selectedClient->setRelation('commentaires', $commentaires);

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

        // Lazy loading : charger les ateliers uniquement quand on ouvre la section panier
        if ($this->showPanierSection && empty($this->ateliersDisponibles)) {
            $this->ateliersDisponibles = Atelier::select('_id', 'nom', 'prix', 'vip')->get();
        }
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
                'id' => (string) $atelierId, // Garder en string pour l'UI
                'quantity' => 1,
                'nom' => $atelier->nom,
                'prix' => $atelier->prix ?? 0,
                'vip' => $atelier->vip ?? false,
            ];
        }

        // Convertir les IDs en ObjectId avant sauvegarde
        $panierToSave = $this->convertPanierIdsToObjectId($this->panier);
        $panierToSave['expires_at'] = now()->addMinutes(20);
        $this->selectedClient->panier = $panierToSave;
        $this->selectedClient->save();

        // Ajuster la méthode de paiement si nécessaire
        $hasVip = collect($this->panier['ateliers'] ?? [])->contains(fn($item) => $item['vip'] ?? false);
        if ($hasVip) {
            $this->methodePaiement = 'credit_fidelite';
            $this->numCarte = '';
        }

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

        // Convertir les IDs en ObjectId avant sauvegarde
        $panierToSave = $this->convertPanierIdsToObjectId($this->panier);
        $this->selectedClient->panier = $panierToSave;
        $this->selectedClient->save();
    }

    public function removeFromPanier($atelierId)
    {
        $this->panier['ateliers'] = array_values(array_filter($this->panier['ateliers'], function ($item) use ($atelierId) {
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            return $itemId != $atelierId;
        }));

        // Convertir les IDs en ObjectId avant sauvegarde
        $panierToSave = $this->convertPanierIdsToObjectId($this->panier);
        $this->selectedClient->panier = $panierToSave;
        $this->selectedClient->save();

        // Ajuster la méthode de paiement si nécessaire
        $hasVip = collect($this->panier['ateliers'] ?? [])->contains(fn($item) => $item['vip'] ?? false);
        if (!$hasVip && $this->methodePaiement === 'credit_fidelite') {
            $this->methodePaiement = 'carte';
            $this->numCarte = '';
        }

        session()->flash('success', 'Atelier retiré du panier.');
    }

    public function emptyPanier()
    {
        $this->panier = ['ateliers' => []];
        $this->selectedClient->panier = $this->panier;
        $this->selectedClient->save();

        // Réinitialiser la méthode de paiement
        $this->methodePaiement = 'carte';
        $this->numCarte = '';

        session()->flash('success', 'Panier vidé.');
    }

    /**
     * Convertir les IDs du panier en ObjectId pour la sauvegarde
     */
    private function convertPanierIdsToObjectId($panier)
    {
        if (!isset($panier['ateliers']) || empty($panier['ateliers'])) {
            return $panier;
        }

        $converted = $panier;
        foreach ($converted['ateliers'] as &$item) {
            if (isset($item['id']) && !$item['id'] instanceof ObjectId) {
                try {
                    $item['id'] = new ObjectId((string) $item['id']);
                } catch (\Exception $e) {
                    Log::warning('[ClientsDisplay] Impossible de convertir atelier_id en ObjectId', [
                        'id' => $item['id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        unset($item);

        return $converted;
    }

    /**
     * Convertir les ObjectId du panier en string pour l'UI
     */
    private function convertPanierIdsToString($panier)
    {
        if (!isset($panier['ateliers']) || empty($panier['ateliers'])) {
            return $panier;
        }

        $converted = $panier;
        foreach ($converted['ateliers'] as &$item) {
            if (isset($item['id']) && $item['id'] instanceof ObjectId) {
                $item['id'] = (string) $item['id'];
            }
        }
        unset($item);

        return $converted;
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
        // Validation conditionnelle selon la méthode de paiement
        $rules = [
            'methodePaiement' => 'required|string|in:carte,virement,paypal,credit_fidelite',
        ];

        if ($this->methodePaiement === 'carte') {
            $rules['numCarte'] = 'required|string|min:13|max:19'; // Numéro de carte bancaire
        } elseif ($this->methodePaiement === 'virement') {
            $rules['numCarte'] = 'required|string|min:15'; // IBAN
        }
        // Pas de validation pour PayPal et credit_fidelite

        $this->validate($rules);

        if (empty($this->panier['ateliers'])) {
            session()->flash('error', 'Le panier est vide.');
            return;
        }

        try {
            // Vérifier la compatibilité méthode de paiement / ateliers
            // Utiliser les données du panier directement pour éviter les incohérences
            $hasVip = false;
            $hasNonVip = false;

            foreach ($this->panier['ateliers'] as $item) {
                // Utiliser d'abord les données du panier
                $isVip = $item['vip'] ?? false;

                // Vérifier aussi dans la base de données pour s'assurer de la cohérence
                $atelier = Atelier::find($item['id']);
                if (!$atelier) {
                    session()->flash('error', 'Atelier introuvable: ' . ($item['nom'] ?? 'Inconnu'));
                    return;
                }

                // Utiliser la valeur de la base de données comme source de vérité
                if ($atelier->vip) {
                    $hasVip = true;
                } else {
                    $hasNonVip = true;
                }
            }

            // Log pour débogage
            Log::info('Validation paiement', [
                'methodePaiement' => $this->methodePaiement,
                'hasVip' => $hasVip,
                'hasNonVip' => $hasNonVip,
            ]);

            // Validation des règles de paiement VIP
            if ($this->methodePaiement === 'credit_fidelite' && $hasNonVip) {
                session()->flash('error', "Les crédits de fidélité ne peuvent être utilisés que pour les ateliers VIP. Veuillez retirer les ateliers non-VIP de votre panier.");
                return;
            }

            if ($hasVip && $this->methodePaiement !== 'credit_fidelite') {
                session()->flash('error', "Les ateliers VIP ne peuvent être payés qu'avec des crédits de fidélité. Veuillez choisir 'Crédit de fidélité' comme méthode de paiement.");
                return;
            }

            // Vérifier les crédits disponibles si paiement par crédit
            if ($this->methodePaiement == 'credit_fidelite') {
                $totalCreditsNeeded = 0;
                foreach ($this->panier['ateliers'] as $item) {
                    $totalCreditsNeeded += $item['quantity'] * 10; // 10 crédits par personne
                }

                if ($this->selectedClient->credit_fidelite < $totalCreditsNeeded) {
                    session()->flash('error', "Crédits de fidélité insuffisants. Requis: {$totalCreditsNeeded}, Disponible: {$this->selectedClient->credit_fidelite}");
                    return;
                }
            }

            // Vérifier la capacité pour tous les ateliers
            foreach ($this->panier['ateliers'] as $item) {
                $atelier = Atelier::find($item['id']);

                if (!$atelier) {
                    session()->flash('error', 'Atelier introuvable: ' . ($item['nom'] ?? 'Inconnu'));
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
            $totalCreditsUsed = 0;

            foreach ($this->panier['ateliers'] as $item) {
                $atelier = Atelier::find($item['id']);
                $prix = ($atelier->prix ?? 0) * $item['quantity'];

                // Gestion des paiements VIP par crédit
                if ($atelier->vip && $this->methodePaiement == 'credit_fidelite') {
                    $creditsNeeded = $item['quantity'] * 10;
                    $totalCreditsUsed += $creditsNeeded;
                    $prix = 0; // Gratuit quand payé avec crédits
                }

                // Créer les paiements embarqués avec ObjectId
                $paiementId = new ObjectId();

                // Gérer le numéro de carte/identifiant selon la méthode de paiement
                $identifiantPaiement = '';
                switch ($this->methodePaiement) {
                    case 'credit_fidelite':
                        $identifiantPaiement = 'CREDIT_FIDELITE';
                        break;
                    case 'carte':
                        // Masquer le numéro de carte (garder les 4 derniers chiffres)
                        $identifiantPaiement = '****' . substr($this->numCarte, -4);
                        break;
                    case 'virement':
                        // Masquer l'IBAN (garder les 4 derniers caractères)
                        $identifiantPaiement = 'IBAN ****' . substr(str_replace(' ', '', $this->numCarte), -4);
                        break;
                    case 'paypal':
                        // Générer un ID de transaction PayPal simulé
                        $identifiantPaiement = 'PP-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
                        break;
                    default:
                        $identifiantPaiement = $this->numCarte;
                }

                $paiements = [];
                $paiements[] = [
                    '_id' => $paiementId,
                    'numCarte' => $identifiantPaiement,
                    'montant' => $prix,
                    'methode_paiement' => $this->methodePaiement,
                    'statut' => 'validé',
                    'payement_recieved_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Créer la réservation avec paiements embarqués et ObjectId
                $reservationId = new ObjectId();
                $reservationData = [
                    '_id' => $reservationId,
                    'nbPersonne' => $item['quantity'],
                    'prix' => $prix,
                    'client_id' => new ObjectId((string) $this->selectedClient->_id),
                    'paiements' => $paiements,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $atelier->push('reservations', $reservationData);

                $reservations[] = $reservationData;
            }

            // Gérer les crédits de fidélité
            if ($this->methodePaiement == 'credit_fidelite') {
                // Déduire les crédits utilisés
                $this->selectedClient->credit_fidelite -= $totalCreditsUsed;
            } else {
                // Ajouter des crédits pour les achats non-VIP (1 crédit par personne)
                foreach ($this->panier['ateliers'] as $item) {
                    $this->selectedClient->credit_fidelite += $item['quantity'];
                }
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

            $totalReservations = count($reservations);
            $message = "Paiement effectué avec succès! {$totalReservations} réservation(s) créée(s).";

            if ($this->methodePaiement == 'credit_fidelite') {
                $message .= " {$totalCreditsUsed} crédits utilisés. Solde restant: {$this->selectedClient->credit_fidelite}";
            } else {
                $message .= " Crédits de fidélité gagnés: +{$totalReservations}. Nouveau solde: {$this->selectedClient->credit_fidelite}";
            }

            session()->flash('success', $message);

        } catch (\Exception $e) {
            Log::error('[ClientsDisplay] Erreur traitement panier: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            session()->flash('error', 'Erreur lors du traitement du panier: ' . $e->getMessage());
        }
    }

    public function updatedMethodePaiement()
    {
        // Réinitialiser le numéro de carte quand on change de méthode
        $this->numCarte = '';
    }

    public function updatedPanier()
    {
        // Quand le panier change, ajuster automatiquement la méthode de paiement
        $hasVip = collect($this->panier['ateliers'] ?? [])->contains(fn($item) => $item['vip'] ?? false);

        if ($hasVip && $this->methodePaiement !== 'credit_fidelite') {
            // Si le panier contient des VIP et que la méthode n'est pas crédit fidélité, forcer le crédit fidélité
            $this->methodePaiement = 'credit_fidelite';
            $this->numCarte = '';
        } elseif (!$hasVip && $this->methodePaiement === 'credit_fidelite') {
            // Si le panier ne contient plus de VIP mais que la méthode est crédit fidélité, passer à carte
            $this->methodePaiement = 'carte';
            $this->numCarte = '';
        }
    }

    public function render()
    {
        $query = Client::with('commentaires');

        // Filtrer selon la recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $clients = $query->paginate($this->perPage);

        return view('livewire.clients-display', [
            'clients' => $clients
        ])->layout('layouts.app');
    }
}
