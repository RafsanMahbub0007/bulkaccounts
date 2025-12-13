<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $system = Setting::find(1);
        $products = Product::with('category')
            ->orderBy(
                Category::select('order')
                    ->whereColumn('categories.id', 'products.category_id')
            )
            ->get();
        return view('livewire.home', [
            'products' => $products,
            'system' => $system,
        ]);
    }
}
