<?php

// ============================================
// App/Http/Controllers/AtelierController.php
// ============================================

namespace App\Http\Controllers;

use App\Http\Requests\AtelierRequest;
use App\Http\Resources\AtelierResource;
use App\Models\Atelier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commentaire;
use App\Http\Resources\CommentaireResource;
use App\Http\Requests\CommentaireRequest;
use MongoDB\BSON\ObjectId;

class AtelierController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $ateliers = Atelier::with(['salle', 'employe'])->get();
        return AtelierResource::collection($ateliers);
    }

    public function store(AtelierRequest $request)
    {
        $data = $request->validated();

        // Vérifier la disponibilité de la salle si salle_id, date et durée sont fournis
        if (!empty($data['salle_id']) && !empty($data['date']) && !empty($data['duree'])) {
            $salle = \App\Models\Salle::find($data['salle_id']);

            if ($salle && !$salle->isDisponible($data['date'], $data['duree'])) {
                return response()->json([
                    'message' => 'La salle n\'est pas disponible pour le créneau sélectionné.',
                    'errors' => [
                        'salle_id' => ['Cette salle n\'est pas disponible pour le créneau sélectionné.']
                    ]
                ], 422);
            }
        }

        $atelier = Atelier::create($data);
        return new AtelierResource($atelier);
    }

    public function addComment(Atelier $atelier, CommentaireRequest $request)
    {
        // Créer le commentaire avec ObjectId
        $commentaire = $atelier->commentaires()->create([
            'commentaire' => $request->input('commentaire'),
            'note' => $request->input('note'),
            'atelier_id' => new ObjectId((string) $atelier->_id),
            'client_id' => new ObjectId($request->input('client_id')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return new CommentaireResource($commentaire);
    }

    public function getComments(Atelier $atelier)
    {
        $commentaires = $atelier->commentaires()->with('client')->get();
        return CommentaireResource::collection($commentaires);
    }

    public function show($id)
    {
        $atelier = Atelier::with(['reservations.client', 'salle', 'employe'])->findOrFail($id);
        return new AtelierResource($atelier);
    }

    public function update(AtelierRequest $request, $id)
    {
        \Log::info('Update atelier', [
            'id' => $id,
            'data' => $request->validated()
        ]);

        $atelier = Atelier::findOrFail($id);
        $data = $request->validated();

        // Vérifier la disponibilité de la salle si salle_id, date et durée sont fournis
        if (!empty($data['salle_id']) && !empty($data['date']) && !empty($data['duree'])) {
            $salle = \App\Models\Salle::find($data['salle_id']);

            // Exclure l'atelier en cours de modification
            if ($salle && !$salle->isDisponible($data['date'], $data['duree'], $id)) {
                return response()->json([
                    'message' => 'La salle n\'est pas disponible pour le créneau sélectionné.',
                    'errors' => [
                        'salle_id' => ['Cette salle n\'est pas disponible pour le créneau sélectionné.']
                    ]
                ], 422);
            }
        }

        $atelier->update($data);

        \Log::info('Atelier updated', ['atelier' => $atelier->toArray()]);

        return new AtelierResource($atelier);
    }

    public function destroy(Atelier $atelier)
    {
        $atelier->delete();
        return response()->json(['message' => 'Atelier supprimé avec succès.']);
    }

    public function statistics($id)
    {
        $atelier = Atelier::with(['salle', 'employe'])->findOrFail($id);

        // Récupérer les réservations embarquées
        $reservations = collect($atelier->reservations ?? []);

        // Filtrer les réservations actives et annulées
        $activeReservations = $reservations->filter(fn($r) => empty($r['deleted_at']));
        $deletedReservations = $reservations->filter(fn($r) => !empty($r['deleted_at']));

        // Compteurs de base
        $stats = [
            'active_count' => $activeReservations->count(),
            'deleted_count' => $deletedReservations->count(),
            'total_count' => $reservations->count(),
        ];

        // Total de personnes (uniquement réservations actives)
        $stats['total_personnes'] = $activeReservations->sum(function($reservation) {
            return $reservation['nbPersonne'] ?? $reservation['nb_personne'] ?? 0;
        });

        // Revenu total (paiements validés uniquement)
        $validatedPayments = $activeReservations->filter(function($reservation) {
            $paiements = $reservation['paiements'] ?? [];
            if (empty($paiements)) return false;

            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;
            return $firstPayment && in_array($firstPayment['statut'] ?? '', ['validé', 'completed']);
        });

        $stats['total_revenue'] = $validatedPayments->sum(function($reservation) {
            $paiements = $reservation['paiements'] ?? [];
            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;
            return (float)($firstPayment['montant'] ?? 0);
        });

        // Paiements en attente
        $pendingPayments = $activeReservations->filter(function($reservation) {
            $paiements = $reservation['paiements'] ?? [];
            if (empty($paiements)) return false;

            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;
            return $firstPayment && in_array($firstPayment['statut'] ?? '', ['en_attente', 'pending']);
        });

        $stats['pending_count'] = $pendingPayments->count();
        $stats['pending_revenue'] = $pendingPayments->sum(function($reservation) {
            $paiements = $reservation['paiements'] ?? [];
            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;
            return (float)($firstPayment['montant'] ?? 0);
        });

        // Prix moyen par personne
        if ($stats['total_personnes'] > 0) {
            $stats['avg_price_per_person'] = $stats['total_revenue'] / $stats['total_personnes'];
        } else {
            $stats['avg_price_per_person'] = 0;
        }

        // Taux d'occupation (par rapport à la capacité de la salle si disponible)
        $salleCapacity = $atelier->salle->capacite ?? null;
        if ($salleCapacity && $salleCapacity > 0) {
            $stats['occupation_rate'] = min(100, ($stats['total_personnes'] / $salleCapacity) * 100);
        } else {
            // Sinon, on met 0 ou 100 selon s'il y a des réservations
            $stats['occupation_rate'] = $stats['active_count'] > 0 ? 100 : 0;
        }

        // Taux de paiement validé
        $totalExpectedRevenue = $activeReservations->sum(function($reservation) {
            $paiements = $reservation['paiements'] ?? [];
            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;
            return $firstPayment ? (float)($firstPayment['montant'] ?? 0) : 0;
        });

        if ($totalExpectedRevenue > 0) {
            $stats['payment_rate'] = ($stats['total_revenue'] / $totalExpectedRevenue) * 100;
        } else {
            $stats['payment_rate'] = 0;
        }

        // Taille moyenne des groupes
        $stats['avg_group_size'] = $stats['active_count'] > 0
            ? $stats['total_personnes'] / $stats['active_count']
            : 0;

        // Répartition par méthode de paiement
        $revenueByMethod = [];
        foreach ($activeReservations as $reservation) {
            $paiements = $reservation['paiements'] ?? [];
            $firstPayment = is_array($paiements) ? ($paiements[0] ?? null) : $paiements;

            if ($firstPayment && in_array($firstPayment['statut'] ?? '', ['validé', 'completed'])) {
                $method = $firstPayment['methode_paiement'] ?? 'non_specifié';
                $montant = (float)($firstPayment['montant'] ?? 0);

                if (!isset($revenueByMethod[$method])) {
                    $revenueByMethod[$method] = [
                        'total' => 0,
                        'count' => 0
                    ];
                }
                $revenueByMethod[$method]['total'] += $montant;
                $revenueByMethod[$method]['count']++;
            }
        }

        $stats['revenue_by_method'] = $revenueByMethod;

        // Moyenne des notes des commentaires
        // Rechercher les commentaires par atelier_id (essayer avec string et ObjectId)
        $atelierIdString = (string) $atelier->_id;
        $atelierIdObject = new ObjectId($atelierIdString);

        $commentaires = Commentaire::where(function($query) use ($atelierIdString, $atelierIdObject) {
            $query->where('atelier_id', $atelierIdString)
                  ->orWhere('atelier_id', $atelierIdObject);
        })->get();

        $stats['comments_count'] = $commentaires->count();

        if ($stats['comments_count'] > 0) {
            $stats['average_rating'] = round($commentaires->avg('note'), 2);
        } else {
            $stats['average_rating'] = 0;
        }

        // Informations de l'atelier
        $stats['atelier'] = [
            'id' => (string) $atelier->_id,
            'nom' => $atelier->nom,
            'date' => $atelier->date,
            'prix' => $atelier->prix,
            'vip' => $atelier->vip ?? false,
            'salle' => $atelier->salle ? [
                'nom' => $atelier->salle->nom,
                'capacite' => $atelier->salle->capacite,
            ] : null,
        ];

        return response()->json([
            'statistics' => $stats
        ]);
    }
}
