<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryMenu extends Component
{
    public $categories = [];
    public $search = '';
    public $activeIndex = 0;

    public function mount()
    {
        $this->loadCategories();
    }

    public function updatedSearch()
    {
        $this->activeIndex = 0;
    }

    protected function loadCategories()
    {
        $this->categories = Category::with('subcategories')->orderBy('order','ASC')->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'subcategories' => $cat->subcategories->map(function ($sub) use ($cat) {
                        return [
                            'id' => $sub->id,
                            'name' => $sub->name,
                            'slug' => $sub->slug,
                            'url' => route('subcategory.details', [
                                'category' => $cat->slug,
                                'subcategory' => $sub->slug,
                            ]),
                        ];
                    }),
                ];
            });
    }

    public function selectCategory($index)
    {
        $this->activeIndex = $index;
    }

    public function getFilteredCategoriesProperty()
    {
        if (!$this->search) {
            return $this->categories;
        }

        $query = strtolower($this->search);
        return collect($this->categories)
            ->map(function ($cat) use ($query) {
                $subs = collect($cat['subcategories'])
                    ->filter(fn ($s) => str_contains(strtolower($s['name']), $query))
                    ->values();
                $catMatch = str_contains(strtolower($cat['name']), $query);
                return [
                    ...$cat,
                    'subcategories' => $catMatch ? $cat['subcategories'] : $subs,
                ];
            })
            ->filter(fn ($cat) => $cat['subcategories']->isNotEmpty() || str_contains(strtolower($cat['name']), $query))
            ->values();
    }
    public function render()
    {
        return view('livewire.category-menu');
    }
}
