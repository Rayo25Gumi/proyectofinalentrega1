<?php

use App\Models\Animal;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    #[Url(as:"animal")]
    public $animalid = null;
    public ?Animal $animal = null;

    public function getPopular()
    {
        return Animal::withCount('photos')->with(['photos' => fn($q) => $q->limit(10)])->orderBy('photos_count', 'desc')->limit(10)->get();
    }
    public function setAnimal($id){
        $this->animal = Animal::findOrFail($id)->load('photos');
        $this->animalid = $this->animal->id;
    }
    public function mount(){
        if ($this->animalid){
            $this->setAnimal($this->animalid);
        }
    }
};
?>
<div>
    <div>
        @foreach ($this->getPopular() as $animal)
        <a wire:click="setAnimal({{ $animal->id }})">{{ $animal->name }} {{ $animal->photos_count }}</a>
        @endforeach


    </div>
    @if ($this->animal == null)
    <div>
        @foreach ($this->getPopular() as $animal)
        <p>{{ $animal->name }} {{ $animal->photos_count }} photos</p>
        <a wire:click="setAnimal({{ $animal->id }})">Ver todas</a>
        @foreach ($animal->photos as $photo)
        <img src="{{ $photo->file_url}}">
        @endforeach
        @endforeach
    </div>
    @else
    <div>
            <p>{{ $this->animal->name }} {{ count($this->animal->photos) }} photos</p>
            @foreach ($this->animal->photos as $photo)
            <a href="{{ route('post', ['id'=>$photo->id]) }}"><img src="{{$photo->file_url}}"></a> 
            
            @endforeach
    </div>
    @endif
</div>