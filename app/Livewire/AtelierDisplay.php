<?php

namespace App\Livewire;

use Livewire\Component;

class AtelierDisplay extends Component
{
    // Pas besoin de charger les données ici,
    // on va les charger avec fetch côté client

    public function render()
    {
        return view('livewire.atelier-display')->layout('layouts.base');
    }
}
