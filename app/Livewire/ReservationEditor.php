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

    public function mount()
    {
        Log::info('[ReservationEditor] Component mounted');
    }

    public function openReservationEditor($atelierId, $reservationId)
    {
        Log::info('[ReservationEditor] Event received via Livewire listener', [
            'atelierId' => $atelierId,
            'reservationId' => $reservationId,
            'type_atelierId' => gettype($atelierId),
            'type_reservationId' => gettype($reservationId)
        ]);

        try {
            $this->atelierId = $atelierId;
            $this->reservationId = $reservationId;

            Log::info('[ReservationEditor] Searching for atelier', ['atelierId' => $atelierId]);

            $atelier = Atelier::with(['reservations.client', 'reservations.paiement'])->find($atelierId);

            if (!$atelier) {
                Log::error('[ReservationEditor] Atelier not found', ['atelierId' => $atelierId]);
                $this->addError('global', 'Atelier introuvable.');
                return;
            }

            Log::info('[ReservationEditor] Atelier found', [
                'atelier_id' => $atelier->_id,
                'reservations_count' => $atelier->reservations->count()
            ]);

            $reservation = $this->findEmbeddedReservation($atelier, (string)$reservationId);

            if (!$reservation) {
                Log::error('[ReservationEditor] Reservation not found', [
                    'reservationId' => $reservationId,
                    'available_ids' => $atelier->reservations->pluck('_id')->toArray()
                ]);
                $this->addError('global', 'Réservation introuvable.');
                return;
            }

            Log::info('[ReservationEditor] Reservation found', [
                'reservation_id' => $reservation->_id,
                'nbPersonne' => $reservation->nbPersonne,
                'prix' => $reservation->prix
            ]);

            // Charger les données de la réservation
            $this->nbPersonne = $reservation->nbPersonne ?? 1;
            $this->prix = $reservation->prix ?? 0;

            // Accéder via la relation
            $paiement = $reservation->paiement;

            Log::info('[ReservationEditor] Paiement loaded', [
                'paiement_exists' => !is_null($paiement),
                'paiement_data' => $paiement ? $paiement->toArray() : null
            ]);

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

            // Charger les infos du client
            $client = $reservation->client;

            Log::info('[ReservationEditor] Client loaded', [
                'client_exists' => !is_null($client),
                'client_data' => $client ? [
                    'nom' => $client->nom ?? '',
                    'prenom' => $client->prenom ?? '',
                    'email' => $client->email ?? ''
                ] : null
            ]);

            if ($client) {
                $this->clientNom = $client->nom ?? '';
                $this->clientPrenom = $client->prenom ?? '';
                $this->clientEmail = $client->email ?? '';
            }

            Log::info('[ReservationEditor] Opening modal', ['open' => true]);
            $this->open = true;

            Log::info('[ReservationEditor] Modal should be open now');

        } catch (\Exception $e) {
            Log::error('[ReservationEditor] Exception in openReservationEditor', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('global', 'Erreur lors du chargement de la réservation: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $atelier = Atelier::with(['reservations.paiement'])->find($this->atelierId);

            if (!$atelier) {
                $this->addError('global', 'Atelier introuvable.');
                return;
            }

            $reservation = $this->findEmbeddedReservation($atelier, (string)$this->reservationId);

            if (!$reservation) {
                $this->addError('global', 'Réservation introuvable.');
                return;
            }

            // Mettre à jour la réservation
            $reservation->nbPersonne = (int)$this->nbPersonne;
            $reservation->prix = (float)$this->prix;
            $reservation->updated_at = now();

            // Mettre à jour le paiement via la relation
            $paiement = $reservation->paiement;

            if ($paiement) {
                $paiement->numCarte = $this->numCarte;
                $paiement->montant = (float)$this->montant;
                $paiement->methode_paiement = $this->methode_paiement;
                $paiement->statut = $this->statut;
                $paiement->updated_at = now();
                $paiement->save();
            } else {
                $reservation->paiement()->create([
                    'numCarte' => $this->numCarte,
                    'montant' => (float)$this->montant,
                    'methode_paiement' => $this->methode_paiement,
                    'statut' => $this->statut,
                    'payement_recieved_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $reservation->save();

            session()->flash('success', 'Réservation mise à jour avec succès.');

            $this->open = false;
            $this->reset();

            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('[ReservationEditor] Erreur sauvegarde: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('global', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    public function cancelReservation()
    {
        try {
            $atelier = Atelier::with(['reservations.client', 'reservations.paiement'])->find($this->atelierId);

            if (!$atelier) {
                $this->addError('global', 'Atelier introuvable.');
                return;
            }

            $reservation = $this->findEmbeddedReservation($atelier, $this->reservationId);

            if ($reservation) {
                $paiement = $reservation->paiement;

                if ($paiement && in_array($paiement->statut ?? '', ['validé', 'completed'])) {
                    $client = $reservation->client;
                    if ($client && ($reservation->nbPersonne ?? 0) > 0) {
                        $client->credit_fidelite = max(0, ($client->credit_fidelite ?? 0) - ($reservation->nbPersonne ?? 0));
                        $client->save();
                    }
                }

                $reservation->deleted_at = now();

                if ($paiement) {
                    $paiement->statut = 'remboursé';
                    $paiement->rembourse_at = now();
                    $paiement->save();
                }

                $reservation->save();
            }

            session()->flash('success', 'Réservation annulée avec succès.');

            $this->open = false;
            $this->reset();

            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('[ReservationEditor] Erreur annulation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('global', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    private function extractReservationId($res)
    {
        if (is_object($res)) {
            if (property_exists($res, '_id')) {
                $val = $res->_id;
                if (is_object($val)) return (string)$val;
                if (is_array($val) && isset($val['$oid'])) return (string)$val['$oid'];
                return (string)$val;
            }
            if (property_exists($res, 'id')) {
                return (string)$res->id;
            }
            return '';
        }

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

        return (string)$res;
    }

    private function findEmbeddedReservation(Atelier $atelier, string $id)
    {
        Log::info('[ReservationEditor] Searching for reservation', [
            'looking_for' => $id,
            'total_reservations' => count($atelier->reservations)
        ]);

        foreach ($atelier->reservations as $index => $reservation) {
            $resId = $this->extractReservationId($reservation);
            Log::info('[ReservationEditor] Comparing reservation', [
                'index' => $index,
                'resId' => $resId,
                'looking_for' => $id,
                'match' => $resId === (string)$id
            ]);

            if ($resId === (string)$id) {
                return $reservation;
            }
        }

        return null;
    }

    public function render()
    {
        Log::info('[ReservationEditor] Rendering component', ['open' => $this->open]);
        return view('livewire.reservation-editor');
    }
}
