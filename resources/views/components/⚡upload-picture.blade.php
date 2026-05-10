<?php

use App\Models\Animal;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate(['animalType' => 'required', 'animalType.*' => 'required|min:1|exists:animals,id'])]
    public $animalType = [];

    #[Validate('image')]
    public $animalimage;

    #[Validate('required|min:10')]
    public $description = '';

    public function getanimals()
    {
        return Animal::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $disk = 'public';
        $path = $this->animalimage->store(null, $disk);

        $photo = Photo::create([
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $path,
            'file_disk' => $disk,
            'user_id' => Auth::id(),
        ]);
        $photo->animals()->sync($this->animalType);

        $this->reset();

        return redirect()->route('post', ['id' => $photo->id]);
    }
};
?>

<div class="subida">
    <h1 class="subida__titulo">Subir imagen</h1>
    <p class="subida__subtitulo">Comparte tu foto con la comunidad</p>

    <form wire:submit="save" class="subida__formulario">
        <label class="zona-arrastre">
            <input wire:model="animalimage" type="file" accept="image/*">
            @if ($animalimage)
                <img src="{{ $animalimage->temporaryUrl() }}" class="zona-arrastre__vista-previa" alt="Vista previa">
            @else
                <div class="zona-arrastre__icono">📷</div>
                <div class="zona-arrastre__texto">Arrastra la imagen aquí</div>
                <div class="zona-arrastre__pista">o haz click para seleccionar</div>
            @endif
        </label>
        @error('animalimage')
            <span class="campo__error">{{ $message }}</span>
        @enderror

        <div class="campo">
            <label class="campo__etiqueta" for="title">Título del post</label>
            <input id="title" class="campo__entrada" wire:model="title" type="text" placeholder="Ponle un título a tu foto">
            @error('title')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="description">Descripción</label>
            <textarea id="description" class="campo__area-texto" wire:model="description" rows="3"
                      placeholder="Cuenta algo sobre la foto"></textarea>
            @error('description')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="animalType">Animal</label>
            <select id="animalType" class="campo__seleccion" wire:model="animalType" multiple>
                @foreach ($this->getanimals() as $animal)
                    <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                @endforeach
            </select>
            @error('animalType')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="boton-primario">Subir imagen</button>
    </form>
</div>
