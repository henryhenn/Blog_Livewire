<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class PostList extends Component
{
    public function posts()
    {
        return $posts = Post::all();
    }

    public function render()
    {
        return view('components.post-list');
    }
}
