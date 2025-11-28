<?php

namespace App\Livewire;

use App\Models\Atelier;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Paiement;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class ReservationEditor extends Component
{
    public $open = false;
    public $atelierId;
    public $reservationId;

    // Données de la réservation
    public $nbPersonne;
    public $prix;

    // Données du paiement
    public $methode_paiement;
    public $statut;
    public $numCarte;
    public $montant;

    // Données du client (lecture seule)
    public $clientNom;
    public $clientPrenom;
    public $clientEmail;

    protected $listeners = ['openReservationEditor'];

    protected $rules = [
        'nbPersonne' => 'required|integer|min:1',
        'prix' => 'required|numeric|min:0',
        'methode_paiement' => 'nullable|string',
        'statut' => 'required|in:pending,completed,failed,refunded,en_attente,validé,remboursé',
        'numCarte' => 'nullable|string',
        'montant' => 'required|numeric|min:0',
    ];

    public function openReservationEditor($atelierId, $reservationId)
    {
        try {
            $this->atelierId = $atelierId;
            $this->reservationId = $reservationId;

            $atelier = Atelier::with(['reservations.client', 'reservations.paiement'])->find($atelierId);

            if (!$atelier) {
                $this->addError('global', 'Atelier introuvable.');
                return;
            }

            // Trouver la réservation via la relation
            $reservation = $this->findEmbeddedReservation($atelier, (string)$reservationId);

            if (!$reservation) {
                $this->addError('global', 'Réservation introuvable.');
                return;
            }

            // Charger les données de la réservation
            $this->nbPersonne = $reservation->nbPersonne ?? 1;
            $this->prix = $reservation->prix ?? 0;

            // Charger les données du paiement via la relation
            $paiement = $reservation->paiement;

            if ($paiement) {
                $this->methode_paiement = $paiement->methode_paiement ?? '';
                $this->statut = $paiement->statut ?? 'pending';
                $this->numCarte = $paiement->numCarte ?? '';
                $this->montant = $paiement->montant ?? 0;
            } else {
                $this->methode_paiement = '';
                $this->statut = 'pending';
                $this->numCarte = '';
                $this->montant = $this->prix;
            }

            // Charger les infos du client via la relation
            $client = $reservation->client;

            if ($client) {
                $this->clientNom = $client->nom;
                $this->clientPrenom = $client->prenom;
                $this->clientEmail = $client->email;
            }

            $this->open = true;

        } catch (\Exception $e) {
            Log::error('[ReservationEditor] Erreur ouverture modal: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('global', 'Erreur lors du chargement de la réservation.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $atelier = Atelier::with('reservations')->find($this->atelierId);

            if (!$atelier) {
                $this->addError('global', 'Atelier introuvable.');
                return;
            }

            // Récupérer la réservation via la relation (recherche robuste)
            $reservation = $this->findEmbeddedReservation($atelier, (string)$this->reservationId);

            if (!$reservation) {
                $this->addError('global', 'Réservation introuvable.');
                return;
            }

            // Mettre à jour la réservation
            $reservation->nbPersonne = (int)$this->nbPersonne;
            $reservation->prix = (float)$this->prix;
            $reservation->updated_at = now();

            // Mettre à jour ou créer le paiement embarqué
            $paiementData = [
                'numCarte' => $this->numCarte,
                'montant' => (float)$this->montant,
                'methode_paiement' => $this->methode_paiement,
                'statut' => $this->statut,
                'updated_at' => now(),
            ];

            // Si le paiement existe, le mettre à jour, sinon le créer via la relation embedsOne
            $paiement = $reservation->paiement;
            if ($paiement) {
                // $paiement est un modèle imbriqué
                foreach ($paiementData as $key => $value) {
                    $paiement->$key = $value;
                }
                $reservation->paiement()->save($paiement);
            } else {
                // Préparer les champs additionnels
                $paiementData['created_at'] = now();
                $paiementData['payement_recieved_at'] = now();
                $reservation->paiement()->create($paiementData);
            }

            // Sauvegarder la réservation imbriquée (va persister dans le parent)
            $reservation->save();

             // émettre un event Livewire/Broadcast local
             if (method_exists($this, 'dispatch')) {
                 $this->dispatch('reservationUpdated');
             } else {
                 $this->emit('reservationUpdated');
             }
             session()->flash('success', 'Réservation mise à jour avec succès.');

             $this->open = false;
             $this->reset();

         } catch (\Exception $e) {
             Log::error('[ReservationEditor] Erreur sauvegarde: ' . $e->getMessage(), [
                 'exception' => $e,
                 'trace' => $e->getTraceAsString(),
                 'atelierId' => $this->atelierId,
                 'reservationId' => $this->reservationId
             ]);
             $this->addError('global', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
         }
     }

     public function cancelReservation()
     {
         try {
             $atelier = Atelier::with('reservations.client')->find($this->atelierId);

             if (!$atelier) {
                 $this->addError('global', 'Atelier introuvable.');
                 return;
             }

             // Récupérer la réservation pour le remboursement (recherche robuste)
             $reservation = $this->findEmbeddedReservation($atelier, $this->reservationId);

             if ($reservation) {
                 $paiement = $reservation->paiement;

                 // Gestion du remboursement si le paiement était validé/completed
                 if ($paiement && in_array($paiement->statut ?? '', ['validé', 'completed'])) {
                     $client = $reservation->client;
                     if ($client && ($reservation->nbPersonne ?? 0) > 0) {
                         // Rembourser les crédits fidélité
                         $client->credit_fidelite = max(0, ($client->credit_fidelite ?? 0) - ($reservation->nbPersonne ?? 0));
                         $client->save();
                     }
                 }

                // Marquer la réservation comme soft-deleted et persister via save()
                $reservation->deleted_at = now();

                // Marquer le paiement comme remboursé si présent
                if ($paiement) {
                    $paiement->statut = 'remboursé';
                    $paiement->rembourse_at = now();
                    $reservation->paiement()->save($paiement);
                }

                $reservation->save();
             }

             if (method_exists($this, 'dispatch')) {
                 $this->dispatch('reservationUpdated');
             } else {
                 $this->emit('reservationUpdated');
             }
             session()->flash('success', 'Réservation annulée avec succès.');

             $this->open = false;
             $this->reset();

         } catch (\Exception $e) {
             Log::error('[ReservationEditor] Erreur annulation: ' . $e->getMessage(), [
                 'exception' => $e,
                 'trace' => $e->getTraceAsString()
             ]);
             $this->addError('global', 'Erreur lors de l\'annulation: ' . $e->getMessage());
         }
     }

    /**
     * Extrait l'identifiant d'une réservation quelle que soit sa forme
     * - array with '_id' => ObjectId or ['\$oid' => '...']
     * - array with 'id'
     * - stdClass/object with _id or id
     */
    private function extractReservationId($res)
    {
        // Si c'est un objet (stdClass ou Model), tenter les propriétés
        if (is_object($res)) {
            if (property_exists($res, '_id')) {
                $val = $res->_id;
                if (is_object($val)) return (string)$val; // ObjectId
                if (is_array($val) && isset($val['$oid'])) return (string)$val['$oid'];
                return (string)$val;
            }
            if (property_exists($res, 'id')) {
                return (string)$res->id;
            }
            return '';
        }

        // Si c'est un tableau
        if (is_array($res)) {
            if (array_key_exists('_id', $res)) {
                $val = $res['_id'];
                if (is_object($val)) return (string)$val;
                if (is_array($val) && isset($val['$oid'])) return (string)$val['$oid'];
                return (string)$val;
            }
            if (array_key_exists('id', $res)) {
                return (string)$res['id'];
            }
            return '';
        }

        // fallback
        return (string)$res;
    }

    /**
     * Trouve une réservation embedée dans un atelier en comparant les id normalisés
     */
    private function findEmbeddedReservation(Atelier $atelier, string $id)
    {
        foreach ($atelier->reservations as $reservation) {
            $resId = $this->extractReservationId($reservation);
            if ($resId === (string)$id) {
                return $reservation;
            }
        }

        return null;
    }

    public function render()
    {
        return view('livewire.reservation-editor');
    }
}
