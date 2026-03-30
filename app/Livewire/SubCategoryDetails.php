<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
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
        
        $isPreOrderItem = $product->stock <= 0;

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
             // Existing item
            $currentQty = $cart[$productId]['quantity'];
            
            // If it's NOT a pre-order (meaning we have stock), enforce stock limit
            if (!$isPreOrderItem) {
                if ($currentQty + 1 > $product->stock) {
                    $this->dispatch('cartUpdateFailed', 'Not enough stock available.');
                    return;
                }
            }
            $cart[$productId]['quantity'] += 1;

        } else {
             // New item
            $cart[$productId] = [
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
        $this->dispatch('cartUpdated', message: 'Item added to cart successfully!');
    }

    public function buyNow($productId)
    {
        $this->addToCart($productId);
        return redirect()->route('checkout');
    }
    public function render()
    {
        $products = Product::where('subcategory_id', $this->subcategory->id)
            ->where('name', 'like', "%{$this->search}%")
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();

        // Fetch related products (e.g., from the same category but different subcategory, or random active products)
        $relatedProducts = Product::where('category_id', $this->subcategory->category_id)
            ->where('subcategory_id', '!=', $this->subcategory->id) // Exclude current subcategory products
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(5)
            ->get();

        if ($relatedProducts->isEmpty()) {
             // Fallback: Just random products if no related ones found in same category
             $relatedProducts = Product::where('id', '!=', 0) // Dummy where
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(5)
                ->get();
        }

        return view('livewire.sub-category-details', [
            'products' => $products,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
