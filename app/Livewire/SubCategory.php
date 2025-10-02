<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]

    public $search = ''; 
    public $slug;

    protected $queryString = ['search']; 

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $category = Category::where('slug', $this->slug)->firstOrFail();

        $subcategories = $category->subcategories()
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.subcategories', [
            'subcategories' => $subcategories,
        ]);
    }
}
