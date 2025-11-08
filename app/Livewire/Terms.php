<?php

namespace App\Livewire;

use App\Models\Terms as ModelsTerms;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Terms extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $terms=ModelsTerms::all();
        return view('livewire.terms',compact('terms'));
    }
}
