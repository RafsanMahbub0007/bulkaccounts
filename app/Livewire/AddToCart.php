<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class AddToCart extends Component
{
    public $productId;
    public $isPreOrder = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $product = Product::find($productId);
        if ($product && $product->stock <= 0) {
            $this->isPreOrder = true;
        }
    }

    public function addToCart()
    {
        $this->updateCartSession();
        session()->flash('success', $this->isPreOrder ? 'Pre-order item added to cart.' : 'Product added to cart.');
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

        // Logic: If product is out of stock, treat as pre-order.
        // If it is already in cart, check if it was pre-order?
        // Simpler: Just allow adding if stock <= 0 (Pre-Order) OR stock > 0 (Regular).
        // If stock > 0, enforce limit.
        
        $isPreOrderItem = $product->stock <= 0;

        if (isset($cart[$this->productId])) {
            // Existing item
            $currentQty = $cart[$this->productId]['quantity'];
            
            // If it's NOT a pre-order (meaning we have stock), enforce stock limit
            if (!$isPreOrderItem) {
                if ($currentQty + 1 > $product->stock) {
                    session()->flash('error', 'Not enough stock available.');
                    return;
                }
            }
            
            $cart[$this->productId]['quantity'] += 1;
        } else {
            // New item
            $cart[$this->productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->selling_price,
                'quantity' => $product->min_order_qty > 0 ? $product->min_order_qty : 1,
                'stock'    => $product->stock,
                'image'    => $product->subCategory->image ?? null,
                'is_preorder' => $isPreOrderItem,
            ];
        }

        session()->put('cart', $cart);
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
