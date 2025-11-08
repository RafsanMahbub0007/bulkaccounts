<?php

namespace App\Livewire;

use App\Models\Privacy;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PrivacyPolicy extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $policies = Privacy::all();
        return view('livewire.privacy-policy', compact('policies'));
    }
}
