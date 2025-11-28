<?php

use App\Http\Controllers\AtelierController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello, World!';
});

Route::get('/employes', [UserController::class, 'index'])->name('api.employes.index');

Route::get('/clients', [ClientController::class, 'index'])->name('api.clients.index');
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('api.clients.show');
Route::post('/clients/{client}/panier', [ClientController::class, 'addToPanier'])->name('api.clients.ajouterAuPanier');
Route::delete('/clients/{client}/panier', [ClientController::class, 'removeFromPanier'])->name('api.clients.retirerDuPanier');
Route::post('/clients/{client}/panier/empty', [ClientController::class, 'emptyPanier'])->name('api.clients.viderPanier');
Route::post('/clients/{client}/panier/process', [ClientController::class, 'processPanier'])->name('api.clients.processPanier');

Route::get('/ateliers', [AtelierController::class, 'index'])->name('api.ateliers.index');
Route::get('/ateliers/{atelier}', [AtelierController::class, 'show'])->name('api.ateliers.show');
Route::post('/ateliers', [AtelierController::class, 'store'])->name('api.ateliers.store');
Route::put('/ateliers/{atelier}', [AtelierController::class, 'update'])->name('api.ateliers.update');
Route::patch('/ateliers/{atelier}', [AtelierController::class, 'update'])->name('api.ateliers.update.patch');
Route::delete('/ateliers/{atelier}', [AtelierController::class, 'destroy'])->name('api.ateliers.destroy');

Route::get('/boutiques', [BoutiqueController::class, 'index'])->name('api.boutiques.index');

Route::post('/atelier/{atelierId}/reservations', [ReservationController::class, 'store'])->name('api.reservations.store');
Route::get('/atelier/{atelierId}/reservations', [ReservationController::class, 'index'])->name('api.reservations.index');

Route::post('/atelier/{atelier}/commentaires', [AtelierController::class, 'addComment'])->name('api.ateliers.addComment');
Route::get('/atelier/{atelier}/commentaires', [AtelierController::class, 'getComments'])->name('api.ateliers.getComments');
Route::put('/commentaires/{commentaire}', [CommentaireController::class, 'update'])->name('api.commentaire.update');
Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('api.commentaire.destroy');
