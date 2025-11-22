<?php

namespace Database\Seeders;
use App\Models\Boutique;
use App\Models\Adresse;
use Illuminate\Database\Seeder;

class BoutiqueSeeder extends Seeder
{
    public function run(){
        Boutique::factory(3)->create();
    }


}
