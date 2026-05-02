<?php

use App\Models\Animal;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|min:3')]
    public $title = '';
    #[Validate(['animalType'=>'required', 'animalType.*'=>'required|min:1|exists:animals,id'])]
    public $animalType = [];
    #[Validate('image')]
    public $animalimage;
    #[Validate('required|min:10')]
    public $description = '';
    public function getanimals()
    {
        return Animal::all();
    }
    public function save()
    {
        $this->validate();
        $disk = 'public';
        $path = $this->animalimage->store(null, $disk);
        $photo = Photo::create([
            'title'=>$this->title,
            'description'=>$this->description,
            'file_path'=>$path,
            'file_disk'=>$disk,
            'user_id'=>Auth::id()
        ]);
    $photo->animals()->sync($this->animalType);
            return $this->redirect(route('post', ['id'=>$photo->id]));

        }
};
?>

<div>
    <form wire:submit='save'>
        <label for="image">Sube tu animal!</label>
        <input wire:model="animalimage" type="file">
        @if ($animalimage)
        <img src="{{ $animalimage->temporaryUrl() }}">
        @endif
        @error('animalimage')
        {{ $message }}
        @enderror
        <label for="text">Titulo del post</label>
        <input wire:model="title" type="text">
        @error('title')
        {{ $message }}
        @enderror
        <label for="text">Descripcion del post</label>
        <input wire:model="description" type="text">
        @error('description')
        {{ $message }}
        @enderror
        <label for="select">Tipo de animal</label>
        <select wire:model="animalType" multiple>
            <option value="" disabled selected>Selecciona un animal!</option>
            @foreach ($this->getanimals() as $animal )
            <option value="{{ $animal->id }}">{{$animal->name}}</option>
            @endforeach
        </select>
        @error('animalType')
        {{ $message }}
        @enderror
        <button type="submit">Subir</button>
    </form>
</div>