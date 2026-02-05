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
        $popularCategories = cache()->remember('popular_categories', 3600, fn() => 
            Category::where('is_active', true)
                ->orderby('order', 'asc')
                ->take(10)
                ->get()
        );

        $subcategories = $this->category->subcategories()
            ->where('name', 'like', "%{$this->search}%")
            ->where('is_active', true)
            ->orderby('order', 'asc')
            ->get();

        return view('livewire.categorydetails', [
            'category' => $this->category,
            'subcategories' => $subcategories,
            'popularCategories' => $popularCategories,
        ]);
    }
}
