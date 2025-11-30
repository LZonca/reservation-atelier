<?php

namespace App\Livewire;

use App\Models\Atelier;
use App\Models\Reservation; // Assurez-vous que ce modèle existe
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ReservationEditor extends Component
{
    public $open = false;
    public $atelierId;
    public $reservationId;

    // Données formulaire
    public $nbPersonne;
    public $prix;
    public $methode_paiement;
    public $statut;
    public $numCarte;
    public $montant;

    // Lecture seule
    public $clientNom;
    public $clientPrenom;
    public $clientEmail;

    // Livewire 3 listeners
    protected $listeners = ['openReservationEditor'];

    protected $rules = [
        'nbPersonne' => 'required|integer|min:1',
        'prix'       => 'required|numeric|min:0',
        'statut'     => 'required|string',
        'montant'    => 'required|numeric|min:0',
    ];

    public function openReservationEditor($atelierId, $reservationId)
    {
        $this->resetErrorBag();

        // 1. Force le cast en string pour éviter les objets MongoDB
        $this->atelierId = (string) $atelierId;
        $this->reservationId = (string) $reservationId;

        // 2. Recherche de l'atelier
        $atelier = Atelier::find($this->atelierId);

        if (!$atelier) {
            $this->addError('global', 'Atelier introuvable.');
            return;
        }

        // 3. Recherche simplifiée dans la collection (nettoyage du code)
        // On cherche la réservation dont l'ID (converti en string) correspond
        $reservation = $atelier->reservations->first(function ($res) {
            return (string) $res->_id === $this->reservationId;
        });

        if (!$reservation) {
            Log::error("Réservation introuvable: Atelier {$this->atelierId}, Reservation {$this->reservationId}");
            $this->addError('global', 'Réservation introuvable.');
            return;
        }

        // 4. Chargement des données
        $this->nbPersonne = $reservation->nbPersonne ?? 1;
        $this->prix = $reservation->prix ?? 0;

        $paiement = $reservation->paiement;
        $this->methode_paiement = $paiement->methode_paiement ?? '';
        $this->statut = $paiement->statut ?? 'pending';
        $this->numCarte = $paiement->numCarte ?? '';
        $this->montant = $paiement->montant ?? $this->prix;

        $client = $reservation->client;
        $this->clientNom = $client->nom ?? '';
        $this->clientPrenom = $client->prenom ?? '';
        $this->clientEmail = $client->email ?? '';

        $this->open = true;
    }

    public function save()
    {
        $this->validate();

        $atelier = \App\Models\Atelier::find($this->atelierId);

        if (!$atelier) {
            $this->addError('global', 'Atelier introuvable.');
            return;
        }

        // 1. EXTRAIRE : On récupère la collection complète dans une variable locale
        // Le '->toArray()' assure qu'on travaille avec un tableau PHP standard modifiable
        $reservations = $atelier->reservations instanceof \Illuminate\Support\Collection
            ? $atelier->reservations->toArray()
            : $atelier->reservations;

        // Recherche de l'index
        $index = null;
        foreach ($reservations as $i => $res) {
            // Gestion robuste des IDs (MongoDB peut avoir $oid ou être une string)
            $resId = isset($res['_id'])
                ? (is_array($res['_id']) ? $res['_id']['$oid'] : (string)$res['_id'])
                : ($res['id'] ?? '');

            if ($resId === $this->reservationId) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            $this->addError('global', 'Réservation introuvable.');
            return;
        }

        // 2. MODIFIER : On modifie la variable locale
        $reservations[$index]['nbPersonne'] = (int)$this->nbPersonne;
        $reservations[$index]['prix'] = (float)$this->prix;
        $reservations[$index]['updated_at'] = now();

        // Gestion du Paiement (supposé être un sous-tableau ou objet)
        // On s'assure que la structure existe
        if (!isset($reservations[$index]['paiement'])) {
            $reservations[$index]['paiement'] = [];
        }

        // Note: Votre code original utilisait 'paiements' (pluriel) ou 'paiement' (singulier) ?
        // J'utilise ici la clé que vous aviez dans la lecture ('paiement') ou modification ('paiements').
        // Vérifiez le nom exact dans votre BDD. Ici je corrige selon votre code de lecture (mount).
        $currentPaiement = $reservations[$index]['paiement'] ?? [];

        $reservations[$index]['paiement'] = array_merge($currentPaiement, [
            'numCarte' => $this->numCarte,
            'montant' => (float)$this->montant,
            'methode_paiement' => $this->methode_paiement,
            'statut' => $this->statut,
            'updated_at' => now(),
        ]);

        // 3. RÉASSIGNER : On remet le tableau modifié dans le modèle
        $atelier->reservations = $reservations;

        // 4. SAUVEGARDER
        $atelier->save();

        session()->flash('success', 'Réservation mise à jour.');
        $this->open = false;

        return redirect()->to(request()->header('Referer'));
    }

    public function cancelReservation()
    {
        // 1. Récupérer l'atelier
        $atelier = \App\Models\Atelier::find($this->atelierId);

        if (!$atelier) {
            $this->addError('global', 'Atelier introuvable.');
            return;
        }

        // 2. EXTRAIRE les réservations en tableau PHP natif
        // C'est indispensable pour modifier des données imbriquées (Embedded Documents) sans erreur
        $reservations = $atelier->reservations instanceof \Illuminate\Support\Collection
            ? $atelier->reservations->toArray()
            : $atelier->reservations;

        $index = null;

        // 3. Trouver l'index de la réservation à modifier
        foreach ($reservations as $i => $res) {
            // Gestion robuste de l'ID (String ou ObjectId)
            $resId = isset($res['_id'])
                ? (is_array($res['_id']) ? $res['_id']['$oid'] : (string)$res['_id'])
                : ($res['id'] ?? '');

            if ($resId === $this->reservationId) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            $this->addError('global', 'Réservation introuvable.');
            return;
        }

        // 4. MODIFIER les données dans le tableau temporaire

        // Marquer comme supprimé
        $reservations[$index]['deleted_at'] = now();

        // Mettre à jour le statut du paiement
        // D'après votre JSON, la clé est 'paiements' (au pluriel)
        if (isset($reservations[$index]['paiements'])) {
            $reservations[$index]['paiements']['statut'] = 'remboursé';

            // Optionnel : ajouter la date de remboursement
            $reservations[$index]['paiements']['rembourse_at'] = now();
            $reservations[$index]['paiements']['updated_at'] = now();
        }

        // 5. RÉASSIGNER le tableau modifié au modèle
        $atelier->reservations = $reservations;

        // 6. SAUVEGARDER
        $atelier->save();

        session()->flash('success', 'Réservation annulée et paiement marqué comme remboursé.');

        $this->open = false;

        // Rafraîchir la page
        return redirect()->to(request()->header('Referer'));
    }

    public function closeModal()
    {
        if (method_exists($this, 'dispatch')) {
            $this->dispatch('closeEditModal');
        }
        $this->reset();
    }

    public function render()
    {
        return view('livewire.reservation-editor');
    }
}
