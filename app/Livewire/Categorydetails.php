<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Categorydetails extends Component
{
     use WithPagination;

    #[Layout('layouts.app')]

    public $search = ''; 
    public $category;
    public $slug;

    protected $queryString = ['search']; 

    public function mount(Category $category)
{
    $this->slug = $category->slug;
    $this->category = $category;
}

    public function render()
    {
        $subcategories = $this->category->subcategories()
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.categorydetails', [
            'category' => $this->category,
            'subcategories' => $subcategories,
        ]);
    }
}
