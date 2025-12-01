<?php

use App\Http\Controllers\BoutiqueWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationWebController;
use App\Http\Controllers\SalleWebController;
use App\Livewire\ClientsDisplay;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AtelierWebController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes CRUD web pour les ateliers
    Route::get('/ateliers', [AtelierWebController::class, 'index'])->name('ateliers.index');
    Route::get('/ateliers/create', [AtelierWebController::class, 'create'])->name('ateliers.create');
    Route::post('/ateliers', [AtelierWebController::class, 'store'])->name('ateliers.store');
    Route::get('/ateliers/{atelier}', [AtelierWebController::class, 'show'])->name('ateliers.show');
    Route::get('/ateliers/{atelier}/reservations', [AtelierWebController::class, 'reservations'])->name('ateliers.reservations');
    Route::post('/ateliers/{atelier}/reservations', [AtelierWebController::class, 'storeReservation'])->name('ateliers.reservations.store');
    Route::get('/ateliers/{atelier}/edit', [AtelierWebController::class, 'edit'])->name('ateliers.edit');
    Route::put('/ateliers/{atelier}', [AtelierWebController::class, 'update'])->name('ateliers.update');
    Route::delete('/ateliers/{atelier}', [AtelierWebController::class, 'destroy'])->name('ateliers.destroy');

    // Routes CRUD web pour les salles
    Route::get('/salles', [SalleWebController::class, 'index'])->name('salles.index');
    Route::get('/salles/create', [SalleWebController::class, 'create'])->name('salles.create');
    Route::post('/salles', [SalleWebController::class, 'store'])->name('salles.store');
    Route::get('/salles/{salle}', [SalleWebController::class, 'show'])->name('salles.show');
    Route::get('/salles/{salle}/edit', [SalleWebController::class, 'edit'])->name('salles.edit');
    Route::put('/salles/{salle}', [SalleWebController::class, 'update'])->name('salles.update');
    Route::delete('/salles/{salle}', [SalleWebController::class, 'destroy'])->name('salles.destroy');

    // Routes CRUD web pour les boutiques
    Route::get('/boutiques', [BoutiqueWebController::class, 'index'])->name('boutiques.index');
    Route::get('/boutiques/create', [BoutiqueWebController::class, 'create'])->name('boutiques.create');
    Route::post('/boutiques', [BoutiqueWebController::class, 'store'])->name('boutiques.store');
    Route::get('/boutiques/{boutique}', [BoutiqueWebController::class, 'show'])->name('boutiques.show');
    Route::get('/boutiques/{boutique}/edit', [BoutiqueWebController::class, 'edit'])->name('boutiques.edit');
    Route::put('/boutiques/{boutique}', [BoutiqueWebController::class, 'update'])->name('boutiques.update');
    Route::delete('/boutiques/{boutique}', [BoutiqueWebController::class, 'destroy'])->name('boutiques.destroy');

    // Routes pour affecter/retirer des employés et salles
    Route::post('/boutiques/{boutique}/employes', [BoutiqueWebController::class, 'affectEmploye'])->name('boutiques.affectEmploye');
    Route::delete('/boutiques/{boutique}/employes/{employe}', [BoutiqueWebController::class, 'retirerEmploye'])->name('boutiques.retirerEmploye');
    Route::post('/boutiques/{boutique}/salles', [BoutiqueWebController::class, 'affectSalle'])->name('boutiques.affectSalle');
    Route::delete('/boutiques/{boutique}/salles/{salle}', [BoutiqueWebController::class, 'retirerSalle'])->name('boutiques.retirerSalle');

    // Routes web pour les réservations (liste et détail)
    Route::get('/reservations', [ReservationWebController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationWebController::class, 'show'])->name('reservations.show');

    Route::get('/clients', ClientsDisplay::class)->name('clients.index');

});

require __DIR__.'/auth.php';
