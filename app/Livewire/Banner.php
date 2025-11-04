<?php

namespace App\Livewire;

use App\Models\banner as ModelsBanner;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Banner extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $banners = ModelsBanner::all();
        return view('livewire.banner', compact('banners'));
    }
}
