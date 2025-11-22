<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('👥 Création des clients...');

        Client::factory(20)->create();

        $this->command->info("   ✓ {$this->getClientCount()} clients créés");
    }

    private function getClientCount(): int
    {
        return Client::count();
    }
}
