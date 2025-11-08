<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryMenu extends Component
{
    public $categories = [];

    public function mount()
    {
        $this->categories = Category::with('subcategories')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }
    public function render()
    {
        return view('livewire.category-menu');
    }
}
