<?php

use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello, World!';
});

Route::get('/users', function () {
    return UserResource::collection(User::with('reservations')->get());
});

Route::get('/clients', function () {
    return ClientResource::collection(Client::with('reservations')->get());
});

Route::get('/clients/{id}', function ($id) {
    $client = Client::with('reservations')->findOrFail($id);
    return new ClientResource($client);
});
