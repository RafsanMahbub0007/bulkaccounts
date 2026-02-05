<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Layout;

class ProductDetails extends Component
{
    public $product;
    public $relatedProducts;
    public $quantity;
    
    public function mount(Product $product)
    {
        $this->product = $product;
        // If stock > 0, default to min_order_qty, else 1
        $this->quantity = $product->stock > 0 ? $product->min_order_qty : 1;
        $this->relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(5)
            ->get();
    }

    public function addToCart()
    {
        $this->updateCartSession();
        $this->dispatch('cartUpdated');
    }

    public function buyNow()
    {
        $this->updateCartSession();
        $this->dispatch('cartUpdated');
        return redirect()->route('checkout');
    }

    private function updateCartSession()
    {
        $isPreOrder = $this->product->stock <= 0;

        if (!$isPreOrder && $this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            $this->dispatch('cartUpdateFailed');
            return;
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$this->product->id])) {
             if (!$isPreOrder) {
                if ($cart[$this->product->id]['quantity'] + $this->quantity > $this->product->stock) {
                     session()->flash('error', 'Not enough stock available.');
                     $this->dispatch('cartUpdateFailed');
                     return;
                }
             }
             $cart[$this->product->id]['quantity'] += $this->quantity;
        } else {
             $cart[$this->product->id] = [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'image' => $this->product->product_image,
                'price' => $this->product->selling_price,
                'quantity' => $this->quantity,
                'stock' => $this->product->stock,
                'is_preorder' => $isPreOrder,
            ];
        }

        session()->put('cart', $cart);
        session()->flash('success', $isPreOrder ? 'Pre-order item added!' : 'Product added to cart!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $this->product->content = \Illuminate\Support\Str::markdown($this->product->content);
        $system = cache()->remember('system_settings', 3600, fn() => Setting::find(1));
        return view('livewire.product-details', [
            'product' => $this->product,
            'relatedProducts' => $this->relatedProducts,
            'system' => $system,
        ]);
    }
}
