<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Intervenant;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Compter les éléments clés pour le mini-dashboard
        $ateliers = Atelier::count();
        $reservations = Reservation::count();
        $clients = Client::count();
        $intervenants = Intervenant::count();

        // Quelques éléments récents à afficher
        $recentAteliers = Atelier::orderBy('created_at', 'desc')->limit(5)->get();
        $recentReservations = Reservation::with(['atelier', 'client'])->orderBy('created_at', 'desc')->limit(5)->get();

        // Liens rapides vers les routes API — produire des URLs absolues fiables
        $apiRoutes = [
            ['label' => 'Employés', 'uri' => route('employes.index')],
            ['label' => 'Clients', 'uri' => route('clients.index')],
            ['label' => 'Ateliers', 'uri' => route('ateliers.index')],
            ['label' => 'Boutiques', 'uri' => route('boutiques.index')],
            // exemples de routes paramétrées: on fournit une URL avec placeholder au format lisible
            ['label' => 'Réservations (atelier)', 'uri' => url('/api/atelier/{atelierId}/reservations')],
            ['label' => 'Commentaires (atelier)', 'uri' => url('/api/atelier/{atelier}/commentaires')],
        ];

        // Si la requête veut du JSON, renvoyer un payload JSON (utile pour un front JS)
        if ($request->wantsJson()) {
            return response()->json(compact('ateliers', 'reservations', 'clients', 'intervenants', 'recentAteliers', 'recentReservations', 'apiRoutes'));
        }

        return view('dashboard', compact('ateliers', 'reservations', 'clients', 'intervenants', 'recentAteliers', 'recentReservations', 'apiRoutes'));
    }
}
