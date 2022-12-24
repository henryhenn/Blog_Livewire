<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Posts extends Component
{
    use WithFileUploads;

    public $showModalForm = false, $title, $body, $image;

    public function showCreatePostModal()
    {
        $this->showModalForm = true;
    }

    public function storePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|file|image|max:1024'
        ]);

        $image_name = $this->image->getClientOriginalName();
        $this->image->storeAs('public/photos/', $image_name);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $this->title;
        $post->slug = Str::slug($this->title);
        $post->body = $this->body;
        $post->active = true;
        $post->image = $image_name;
        $post->save();

        $this->reset();
    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => Post::all(),
        ]);
    }
}
