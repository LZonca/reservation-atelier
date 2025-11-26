<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationWebController extends Controller
{
    // Liste web des réservations
    public function index(Request $request)
    {
        $reservations = Reservation::with(['atelier', 'client'])->orderBy('created_at', 'desc')->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    // Détail d'une réservation
    public function show($id)
    {
        $reservation = Reservation::with(['atelier', 'client'])->findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }
}

