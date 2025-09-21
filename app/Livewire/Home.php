<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $categories = Category::all();
        return view('livewire.home', [
            'categories' => $categories,
        ]);
    }
}
