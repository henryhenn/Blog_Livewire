<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithFileUploads, WithPagination;

    public $showModalForm = false, $title, $body, $image, $postId = null, $newImage;

    public function showCreatePostModal()
    {
        $this->showModalForm = true;
    }

    public function updatedShowModalForm()
    {
        $this->reset();
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

    public function showEditPostModal($id)
    {
        $this->reset();
        $this->showModalForm = true;
        $this->postId = $id;
        $this->loadEditForm();
    }

    private function loadEditForm()
    {
        $post = Post::findOrFail($this->postId);
        $this->title = $post->title;
        $this->body = $post->body;
        $this->newImage = $post->image;
    }

    public function updatePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|max:1024'
        ]);

        if ($this->image) {
            Storage::delete('public/storage/photos' . $this->newImage);
            $this->newImage = $this->image->getClientOriginalName();
        $this->image->storeAs('public/photos/', $this->newImage);
        }

        Post::find($this->postId)->update([
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->newImage,
        ]);

        $this->reset();

        session()->flash('flash.banner', 'Post has been updated!');
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        Storage::delete('public/storage/photos/' . $post->image);
        $post->delete();

        session()->flash('flash.banner', 'Post has been deleted!');
    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => Post::orderBy('created_at', 'DESC')->paginate(5),
        ]);
    }
}
