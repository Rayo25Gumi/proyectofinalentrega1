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
        return Animal::orderBy('name')->get();
    }

    public function addnewtype()
    {
        $this->validate();
        $animal = Animal::where('name', '=', $this->newtype)->first();
        if ($animal) {
            if (!in_array($animal->id, $this->types)) {
                $this->types[] = $animal->id;
            }
            $this->newtype = '';
            return;
        }
        $animals = Animal::where('name', 'like', $this->newtype . '%')->get();
        if (count($animals) == 1) {
            if (!in_array($animals[0]->id, $this->types)) {
                $this->types[] = $animals[0]->id;
            }
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
        $this->types = array_values(array_filter($this->types, fn($e) => $e != $id));
    }

    public function clearTags()
    {
        $this->types = [];
    }

    public function search()
    {
        if (empty($this->types)) {
            return collect();
        }
        return Photo::with('animals')
            ->whereHas('animals', fn(Builder $q) => $q->whereIn('id', $this->types))
            ->latest()
            ->get();
    }
};
?>

<div class="busqueda">
    <h1 class="busqueda__titulo">Buscar animales</h1>
    <p class="busqueda__subtitulo">Escribe el nombre del animal y pulsa Enter para añadir filtros</p>

    <form wire:submit="addnewtype" class="busqueda__barra">
        <input type="text" list="animals" wire:model="newtype" class="busqueda__entrada" placeholder="Buscar animales...">
        <datalist id="animals">
            @foreach ($this->getAnimals() as $animal)
                <option value="{{ $animal->name }}"></option>
            @endforeach
        </datalist>
    </form>
    @error('newtype')
        <div class="campo__error" style="margin-top:8px">{{ $message }}</div>
    @enderror

    <div class="etiquetas">
        @foreach ($this->getselectedanimals() as $selected)
            <span class="etiqueta">
                {{ $selected->name }}
                <span class="etiqueta__cerrar" wire:click="deletetag({{ $selected->id }})">x</span>
            </span>
        @endforeach
        @if (count($this->types) > 0)
            <button type="button" class="etiquetas__limpiar" wire:click="clearTags">Limpiar todo</button>
        @endif
    </div>

    @php $results = $this->search(); @endphp

    @if (count($this->types) > 0)
        <div class="resultados__meta">{{ count($results) }} {{ count($results) === 1 ? 'resultado' : 'resultados' }}</div>
    @endif

    @if (count($this->types) === 0)
        <div class="vacio">Añade uno o más animales para empezar a buscar.</div>
    @elseif (count($results) === 0)
        <div class="vacio">No se encontraron fotos para los filtros seleccionados.</div>
    @else
        <div class="tarjetas tarjetas--4">
            @foreach ($results as $photo)
                <a class="tarjeta" href="{{ route('post', ['id' => $photo->id]) }}">
                    <img src="{{ $photo->file_url }}" alt="{{ $photo->title }}">
                    @if ($photo->animals->isNotEmpty())
                        <span class="tarjeta__etiqueta">{{ $photo->animals->first()->name }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
