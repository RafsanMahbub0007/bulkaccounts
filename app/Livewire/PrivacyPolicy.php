<?php

namespace App\Livewire;

use App\Models\privacy;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PrivacyPolicy extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $policies = privacy::all();
        return view('livewire.privacy-policy', compact('policies'));
    }
}
