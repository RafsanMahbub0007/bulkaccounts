<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Categories extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $categories = Category::all();
        return view('livewire.categories', [
            'categories' => $categories,
        ]);
    }
}
