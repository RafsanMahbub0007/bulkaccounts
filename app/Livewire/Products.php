<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $category = [];
    public $sortDirection = 'asc';

    #[Layout('layouts.app')]
    public function render()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        $system = Setting::first();

        $products = Product::query()
            ->with(['subcategory', 'category'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!empty($this->category), function ($query) {
                $query->whereIn('category_id', $this->category);
            })
            ->where('is_active', true)
            ->orderBy('display_order', $this->sortDirection)
            ->paginate(12);

        return view('livewire.products', compact('products', 'categories', 'system'));
    }
}
