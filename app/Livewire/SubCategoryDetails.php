<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
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
    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        if ($this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            $this->dispatch('cartUpdateFailed');
            return;
        }

        $cart = session()->get('cart', []);
        $cart[$this->product->id] = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'image' => $this->product->product_image,
            'price' => $this->product->selling_price,
            'quantity' => $this->quantity,
            'stock' => $this->product->stock,
        ];

        session()->put('cart', $cart);
        session()->flash('success', 'Product added to cart!');
        $this->dispatch('cartUpdated');
    }

    public function buyNow($productId)
    {
        $product = Product::findOrFail($productId);

        if ($this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        $cart = session()->get('cart', []);
        $cart[$this->product->id] = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'image' => $this->product->product_image,
            'price' => $this->product->selling_price,
            'quantity' => $this->quantity,
            'stock' => $this->product->stock,
        ];

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
        return redirect()->route('checkout');
    }
    public function render()
    {
        $products = $this->subcategory->products()
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);
        return view('livewire.sub-category-details', [
            'products' => $products
        ]);
    }
}
