<?php

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    public $id;

    #[Locked]
    public ?Photo $post = null;

    public function mount()
    {
        $this->post = Photo::with(['animals', 'user'])->findOrFail($this->id);
    }

    public function deletePost()
    {
        if ($this->post->user->id != Auth::id()) {
            abort(403);
        }
        $disk = $this->post->file_disk;
        $path = $this->post->file_path;
        $this->post->delete();
        Storage::disk($disk)->delete($path);
        return redirect()->route('home');
    }
};
?>

<div class="detalle">
    <h1 class="detalle__titulo">{{ $post->title }}</h1>

    @if ($post->animals->isNotEmpty())
        <div class="etiquetas" style="margin-bottom: 16px; margin-top: -8px;">
            @foreach ($post->animals as $animal)
                <span class="etiqueta" style="cursor: default;">{{ $animal->name }}</span>
            @endforeach
        </div>
    @endif

    <img src="{{ $post->file_url }}" class="detalle__imagen" alt="{{ $post->title }}">

    <p class="detalle__descripcion">{{ $post->description }}</p>

    @auth
        @if ($this->post->user->id == Auth::id())
            <div class="detalle__acciones">
                <button type="button" class="detalle__borrar"
                        wire:click="deletePost"
                        wire:confirm="¿Seguro que quieres borrar este post?">
                    Borrar post
                </button>
            </div>
        @endif
    @endauth
</div>
