<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Livewire\Attributes\Layout;

class UserPayments extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'paid_at';

    protected $updatesQueryString = ['search', 'sortBy'];

    #[Layout('layouts.app')]
    public function render()
    {
        $payments = Payment::query()
            ->when($this->search, function ($query) {
                $query->where('transaction_id', 'like', '%' . $this->search . '%')
                    ->orWhere('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('currency', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, 'desc')
            ->get();

        return view('livewire.user-payments', compact('payments'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }
}
