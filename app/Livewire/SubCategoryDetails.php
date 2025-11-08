<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SubCategoryDetails extends Component
{
         use WithPagination;

    #[Layout('layouts.app')]
    public $search = '';
    public $category;
    public $subcategory;
    public $slug;
    public function mount(Category $category, SubCategory $subcategory)
    {
        $this->category = $category;
        $this->subcategory = $subcategory;
    }
    public function render()
    {
       $products = $this->subcategory->products()
        ->where('name', 'like', "%{$this->search}%")
        ->paginate(10);
        return view('livewire.sub-category-details',[
             'products' => $products
        ]);
    }
}
