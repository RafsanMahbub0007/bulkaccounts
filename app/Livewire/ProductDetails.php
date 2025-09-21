<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;

class ProductDetails extends Component
{
    public $product;
    public $relatedProducts;
    public $quantity = 1;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(5)
            ->get();
    }

    public function addToCart()
    {
        if ($this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            $this->dispatch('cartUpdateFailed');
            return;
        }

        $cart = session()->get('cart', []);
        $cart[$this->product->id] = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'quantity' => $this->quantity,
            'stock' => $this->product->stock,
            'image' => $this->product->image,
        ];

        session()->put('cart', $cart);
        session()->flash('success', 'Product added to cart!');
        $this->dispatch('cartUpdated');
    }

    public function buyNow()
    {
        if ($this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        $cart = session()->get('cart', []);
        $cart[$this->product->id] = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'quantity' => $this->quantity,
            'stock' => $this->product->stock,
            'image' => $this->product->image,
        ];

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
        return redirect()->route('checkout');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $this->product->content = \Illuminate\Support\Str::markdown($this->product->content);

        return view('livewire.product-details', [
            'product' => $this->product,
            'relatedProducts' => $this->relatedProducts,
        ]);
    }
}
