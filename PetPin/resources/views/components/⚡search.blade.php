<?php

use App\Models\Animal;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Validate('required')]
    public $newtype = '';
    public $types = [];
    public function getAnimals()
    {
        return Animal::all();
    }
    public function addnewtype()
    {
        $this->validate();
        $animal = Animal::where('name', '=', $this->newtype)->first();
        if ($animal) {
            $this->types[] = $animal->id;
            $this->newtype = '';
            return;
        }
        $animals = Animal::where('name', 'like', $this->newtype . '%')->get();
        if (count($animals) == 1) {
            $this->types[] = $animals[0]->id;
            $this->newtype = '';
            return;
        }
        $this->addError('newtype', 'Animal no encontrado :(');
    }
    public function getselectedanimals()
    {
        return Animal::whereIn('id', $this->types)->get();
    }
    public function deletetag($id)
    {
        $this->types = array_filter($this->types, fn($e) => $e != $id);
    }

    public function search(){
        return Photo::whereHas('animals', fn(Builder $q)=>$q->whereIn('id', $this->types))->get();
    }
};

?>

<div>
    <form wire:submit="addnewtype">
        <input type="text" list="animals" wire:model="newtype">
        @error('newtype')
        {{ $message }}
        @enderror
        <datalist id="animals">
            @foreach ($this->getAnimals() as $animal)
            <option value="{{$animal->name}}"></option>
            @endforeach
        </datalist>
    </form>
    @foreach ($this->getselectedanimals() as $selected)
    <a>{{ $selected->name }} <span wire:click="deletetag({{ $selected->id }})">x</span></a>
    @endforeach
    <div>
        @foreach ($this->search() as $photo)
        <a href="{{ route('post', ['id'=>$photo->id]) }}"><img src="{{$photo->file_url}}"></a> 
        
        
        @endforeach
    </div>
</div>