<?php

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        $this->post = Photo::findOrFail($this->id);
    }
    public function deletePost(){
        if ($this->post->user->id != Auth::id()){
            abort(403);
        }
        $this->post->delete();
        Storage::disk($this->post->file_disk)->delete($this->post->file_path);
        return $this->redirect(route('home'));
    }
    };
?>

<div>
    <p>{{ $post->title }}</p>
    <img src="{{ $post->file_url}}">
    <p>{{ $post->description }}</p>
    @if ($this->post->user->id == Auth::id())
        <span wire:click="deletePost">x</span>
    @endif
</div>