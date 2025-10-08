<?php

namespace App\Livewire;

use App\Models\guideline;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Guidelinedetails extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $giudlines = guideline::all();
        return view('livewire.guidelinedetails',[
            'guidlines' => $giudlines
        ]);
    }
}
