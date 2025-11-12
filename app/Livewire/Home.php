<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $products = Product::all();
        return view('livewire.home', [
            'products' => $products,
        ]);
    }
}
