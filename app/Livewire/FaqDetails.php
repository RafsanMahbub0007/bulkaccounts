<?php

namespace App\Livewire;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FaqDetails extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $faqdetails = Faq::all();
        return view('livewire.faq-details', [
            'faqdetails' => $faqdetails
        ]);
    }
}
