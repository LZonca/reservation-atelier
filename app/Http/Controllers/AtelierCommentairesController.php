<?php

namespace App\Http\Controllers;

use App\Models\Atelier;
use App\Models\Commentaire;
use MongoDB\BSON\ObjectId;
use Illuminate\Http\Request;

class AtelierCommentairesController extends Controller
{
    public function index($atelierId)
    {
        $atelier = Atelier::findOrFail($atelierId);

        // Charger les commentaires avec les clients
        $atelierOid = new ObjectId((string)$atelierId);
        $commentaires = Commentaire::where('atelier_id', $atelierOid)
            ->orderBy('created_at', 'desc')
            ->get();

        // Charger les clients pour chaque commentaire
        foreach ($commentaires as $commentaire) {
            $commentaire->load('client');
        }

        // Calculer les statistiques
        $noteMoyenne = $atelier->noteMoyenne();
        $nombreCommentaires = $commentaires->count();

        $repartitionNotes = [
            5 => $commentaires->where('note', 5)->count(),
            4 => $commentaires->where('note', 4)->count(),
            3 => $commentaires->where('note', 3)->count(),
            2 => $commentaires->where('note', 2)->count(),
            1 => $commentaires->where('note', 1)->count(),
        ];

        return view('ateliers.commentaires', compact(
            'atelier',
            'commentaires',
            'noteMoyenne',
            'nombreCommentaires',
            'repartitionNotes'
        ));
    }
}

