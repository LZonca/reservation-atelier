<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Intervenant;
use App\Models\Salle;
use App\Models\Boutique;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Compter les éléments clés pour le mini-dashboard
        $ateliers = Atelier::count();
        $salles = Salle::count();
        $boutiques = Boutique::count();
        // Compter les réservations stockées dans la collection `reservations`
        $externalReservations = Reservation::count();
        // Compter aussi les réservations embarquées dans les ateliers (embedsMany)
        $embeddedReservations = Atelier::all()->reduce(function ($carry, $atelier) {
            $carry += is_array($atelier->reservations) ? count($atelier->reservations) : ($atelier->reservations ? $atelier->reservations->count() : 0);
            return $carry;
        }, 0);

        $reservations = $externalReservations + $embeddedReservations;
        $clients = Client::count();
        $intervenants = Intervenant::count();

        // Quelques éléments récents à afficher
        $recentAteliers = Atelier::with('salle')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentReservations = Reservation::with(['atelier', 'client'])->orderBy('created_at', 'desc')->limit(5)->get();
        $recentBoutiques = Boutique::orderBy('created_at', 'desc')->limit(5)->get();

        // Liens rapides vers les routes API — produire des URLs absolues fiables
        $apiRoutes = [
            ['label' => 'Employés', 'uri' => route('api.employes.index')],
            ['label' => 'Clients', 'uri' => route('api.clients.index')],
            ['label' => 'Ateliers', 'uri' => route('api.ateliers.index')],
            ['label' => 'Boutiques', 'uri' => route('api.boutiques.index')],
            // exemples de routes paramétrées: on fournit une URL avec placeholder au format lisible
            ['label' => 'Réservations (atelier)', 'uri' => url('/api/atelier/{atelierId}/reservations')],
            ['label' => 'Commentaires (atelier)', 'uri' => url('/api/atelier/{atelier}/commentaires')],
        ];

        // Si la requête veut du JSON, renvoyer un payload JSON (utile pour un front JS)
        if ($request->wantsJson()) {
            return response()->json(compact('ateliers', 'salles', 'boutiques', 'reservations', 'clients', 'intervenants', 'recentAteliers', 'recentReservations', 'recentBoutiques', 'apiRoutes'));
        }

        return view('dashboard', compact('ateliers', 'salles', 'boutiques', 'reservations', 'clients', 'intervenants', 'recentAteliers', 'recentReservations', 'recentBoutiques', 'apiRoutes'));
    }
}
