<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            ClientSeeder::class,
            BoutiqueSeeder::class,
            SalleSeeder::class,
            UserSeeder::class,
            AtelierSeeder::class,
            ReservationSeeder::class,
            CommentaireSeeder::class,
            PanierSeeder::class,
        ]);
    }
}
