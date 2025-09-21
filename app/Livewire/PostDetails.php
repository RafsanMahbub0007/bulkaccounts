<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PostDetails extends Component
{
    public Post $post;

    #[Layout('layouts.app')]
    public function render()
    {
        $relatedPosts = Post::where('id', '!=', $this->post->id)
            ->where('published', true)
            ->take(3)
            ->get();

        $this->post->content = \Illuminate\Support\Str::markdown($this->post->content);

        return view('livewire.post-details', [
            'post' => $this->post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
