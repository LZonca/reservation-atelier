<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Intervenant;

class DashboardController
{
    public function index(Request $request)
    {
        // Compter les éléments clés pour le mini-dashboard
        $ateliers = Atelier::count();
        $reservations = Reservation::count();
        $clients = Client::count();
        $intervenants = Intervenant::count();

        return view('dashboard', compact('ateliers', 'reservations', 'clients', 'intervenants'));
    }
}

