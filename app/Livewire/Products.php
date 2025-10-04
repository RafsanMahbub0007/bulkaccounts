<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
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
        $categories = Category::all();

        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when(!empty($this->category), function ($query) { 
                $query->whereIn('category_id', $this->category);
            })
            ->orderBy('price', $this->sortDirection)
            ->paginate(12);

        return view('livewire.products', compact('products', 'categories'));
    }
}
