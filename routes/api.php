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


// TODO: Sécuriser les routes avec un middleware d'authentification

Route::get('/employes', [UserController::class, 'index'])->name('employes.index');

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
Route::post('/clients/{client}/panier', [ClientController::class, 'addToPanier'])->name('clients.ajouterAuPanier');
Route::delete('/clients/{client}/panier', [ClientController::class, 'removeFromPanier'])->name('clients.retirerDuPanier');
Route::post('/clients/{client}/panier/empty', [ClientController::class, 'emptyPanier'])->name('clients.viderPanier');
Route::post('/clients/{client}/panier/process', [ClientController::class, 'processPanier'])->name('clients.processPanier');

Route::get('/ateliers', [AtelierController::class, 'index'])->name('ateliers.index');
Route::get('/ateliers/{atelier}', [AtelierController::class, 'show'])->name('ateliers.show');
Route::post('/ateliers', [AtelierController::class, 'store'])->name('ateliers.store');
// NOTE: Ensure AtelierController@update handles partial updates correctly (PATCH expects only changed fields)
Route::patch('/ateliers/{atelier}', [AtelierController::class, 'update'])->name('ateliers.update');
Route::delete('/ateliers/{atelier}', [AtelierController::class, 'destroy'])->name('ateliers.destroy');

Route::get('/boutiques', [BoutiqueController::class, 'index'])->name('boutiques.index');

Route::post('/atelier/{atelierId}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/atelier/{atelierId}/reservations', [ReservationController::class, 'index'])->name('reservations.index');



Route::post('/atelier/{atelier}/commentaires', [AtelierController::class, 'addComment'])->name('ateliers.addComment');
Route::get('/atelier/{atelier}/commentaires', [AtelierController::class, 'getComments'])->name('ateliers.getComments');
Route::put('/commentaires/{commentaire}', [CommentaireController::class, 'update'])->name('commentaire.update');
Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaire.destroy');
