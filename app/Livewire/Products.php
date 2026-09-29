<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Products extends Component
{
    public $search = '';
    public $category = [];
    public $sortDirection = 'asc';

    #[Layout('layouts.app')]
    public function render()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $system = Setting::query()->first();
        $sortDirection = in_array($this->sortDirection, ['asc', 'desc'], true)
            ? $this->sortDirection
            : 'asc';
        $categoryIds = collect($this->category)
            ->filter(fn ($id) => filter_var($id, FILTER_VALIDATE_INT) !== false)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $products = Product::query()
            ->with([
                'category:id,name,slug',
                'subCategory:id,name,slug,image',
                'offers' => fn ($query) => $query
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderByDesc('end_date'),
            ])
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($categoryIds->isNotEmpty(), function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->where('is_active', true)
            ->whereHas('subCategory', fn ($query) => $query->where('is_active', true))
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderBy('selling_price', $sortDirection)
            ->orderBy('display_order')
            ->get();

        Product::warmFeatureCache(
            $products->pluck('feature_ids')
                ->flatten()
                ->filter()
                ->all()
        );

        return view('livewire.products', compact('products', 'categories', 'system'));
    }
}