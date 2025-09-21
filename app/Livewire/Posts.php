<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]

    public $search = ''; // Search query

    protected $queryString = ['search']; // Sync search query with the URL

    public function render()
    {
        $posts = Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.posts', compact('posts'));
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset to the first page when search query changes
    }
}
