<div class="min-h-screen mt-2 p-2">
    <div class="w-full text-white">
        <h1 class="text-3xl font-bold text-center mb-10">{{ $post->title }}</h1>
        <span class="text-sm mb-14">{{ $post->created_at }}</span>
        <div class="flex">
            <img src="{{ asset('storage/photos/' . $post->image) }}" alt="">
        </div>
        <p>{{ $post->body }}</p>
    </div>
</div>
