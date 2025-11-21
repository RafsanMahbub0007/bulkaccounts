<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class AddToCart extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart()
    {
        $this->updateCartSession();
        session()->flash('success', 'Product added to cart.');
        $this->dispatch('cartUpdated');
    }

    public function buyNow()
    {
        $this->updateCartSession();

        return redirect()->route('checkout');
    }

    private function updateCartSession()
    {
        $product = Product::findOrFail($this->productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$this->productId])) {
            if ($cart[$this->productId]['quantity'] + 1 > $product->stock) {
                session()->flash('error', 'Not enough stock available.');
                return;
            }
            $cart[$this->productId]['quantity'] += 1;
        } else {
            $cart[$this->productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->selling_price,
                'quantity' => $product->min_order_qty,
                'stock'    => $product->stock,
                'image'    => $product->subCategory->image ?? null,
            ];
        }

        session()->put('cart', $cart);
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
