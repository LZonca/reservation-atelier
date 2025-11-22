<?php

use App\Http\Controllers\AtelierController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello, World!';
});


// TODO: Sécuriser les routes avec un middleware d'authentification

Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
Route::post('/clients/{client}/panier', [ClientController::class, 'addToPanier'])->name('clients.addToPanier');

Route::get('/ateliers', [AtelierController::class, 'index'])->name('ateliers.index');

Route::get('/ateliers/{id}', [AtelierController::class, 'show'])->name('ateliers.show');

Route::post('/ateliers', [AtelierController::class, 'store'])->name('ateliers.store');

Route::put('/ateliers/{id}', [AtelierController::class, 'update'])->name('ateliers.update');

Route::delete('/ateliers/{id}', [AtelierController::class, 'destroy'])->name('ateliers.destroy');

Route::get('/boutiques', [BoutiqueController::class, 'index'])->name('boutiques.index');


Route::post('/atelier/{atelierId}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/atelier/{atelierId}/reservations', [ReservationController::class, 'index'])->name('reservations.index');
