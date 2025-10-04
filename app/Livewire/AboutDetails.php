<?php

namespace App\Livewire;

use App\Models\about;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AboutDetails extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $about_details = about::all();
        return view('livewire.about-details', [
            'about_details' => $about_details
        ]);
    }
}
