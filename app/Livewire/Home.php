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
        $products = Product::where('is_active', true)
        ->whereHas('subCategory', function ($query) {
            $query->where('is_active', true);
        })

        // Category must be active
        ->whereHas('subCategory.category', function ($query) {
            $query->where('is_active', true);
        })

        ->with([
            'subCategory',
            'offers' => fn ($query) => $query
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now()),
        ])
        ->orderBy('display_order', 'asc')
        ->get();
        Product::warmFeatureCache(
            $products
                ->pluck('feature_ids')
                ->flatten()
                ->filter()
                ->all()
        );
        return view('livewire.home', [
            'products' => $products,
            'system' => $system,
        ]);
    }
}
