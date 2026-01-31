<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\PreOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class UserOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'ordered_at';
    public $sortDirection = 'desc';
    public $activeTab = 'orders'; // 'orders' or 'pre-orders'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
            
        $preOrders = PreOrder::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        return view('livewire.user-orders', [
            'orders' => $orders,
            'preOrders' => $preOrders
        ]);
    }
}
