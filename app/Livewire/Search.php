<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]

    public $query = '';
    public $category = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'newest';
    public $inStock = false;
    public $showFilters = false; // For mobile

    protected $queryString = [
        'query' => ['except' => ''],
        'category' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'inStock' => ['except' => false],
    ];

    protected $listeners = ['refreshSearch' => '$refresh'];

    public function mount()
    {
        $this->fill(request()->only(['query', 'category', 'minPrice', 'maxPrice']));
    }

    public function resetFilters()
    {
        $this->reset(['category', 'minPrice', 'maxPrice', 'sortBy', 'inStock']);
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->showFilters = false; // Close mobile filters
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

 public function render()
    {
        $products = Product::query()
            ->with(['category', 'subcategory'])
            ->when($this->query, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->query . '%')
                          ->orWhereHas('subcategory', function ($catQuery) {
                              $catQuery->where('name', 'like', '%' . $this->query . '%');
                          })
                          ->orWhereHas('category', function ($catQuery) {
                              $catQuery->where('name', 'like', '%' . $this->query . '%');
                          });
                });
            })
            ->when($this->category, function ($q) {
                $q->where('category_id', $this->category);
            })
            ->when($this->minPrice, function ($q) {
                $q->where('selling_price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($q) {
                $q->where('selling_price', '<=', $this->maxPrice);
            })
            ->when($this->inStock, function ($q) {
                $q->where('stock', '>', 0);
            })
            ->when($this->sortBy, function ($q) {
                switch ($this->sortBy) {
                    case 'price_low':
                        $q->orderBy('selling_price', 'asc');
                        break;
                    case 'price_high':
                        $q->orderBy('selling_price', 'desc');
                        break;
                    case 'name':
                        $q->orderBy('name', 'asc');
                        break;
                   case 'popular':
                        // Sort by total quantity sold from order_items
                        $q->withCount(['orderItems as total_sold' => function ($query) {
                            $query->selectRaw('COALESCE(SUM(quantity), 0)');
                        }])->orderBy('total_sold', 'desc');
                        break;
                    case 'newest':
                    default:
                        $q->latest();
                        break;
                }
            });

        return view('livewire.search', [
            'products' => $products->where('is_active', true)->paginate(12),
            'categories' => Category::where('is_active', true)->orderBy('order', 'asc')->get(),
            'totalResults' => $products->count(),
        ]);
    }
}
