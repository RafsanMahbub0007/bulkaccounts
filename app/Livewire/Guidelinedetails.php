<?php

namespace App\Livewire;

use App\Models\Guideline;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Guidelinedetails extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $giudlines = Guideline::all();
        return view('livewire.guidelinedetails',[
            'guidlines' => $giudlines
        ]);
    }
}
