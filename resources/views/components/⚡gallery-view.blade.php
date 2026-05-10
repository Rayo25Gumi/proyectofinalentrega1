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
        return Animal::withCount('photos')
            ->with(['photos' => fn($q) => $q->limit(10)])
            ->orderBy('photos_count', 'desc')
            ->limit(10)
            ->get();
    }

    public function setAnimal($id)
    {
        $this->animal = Animal::findOrFail($id)->load('photos');
        $this->animalid = $this->animal->id;
    }

    public function clearAnimal()
    {
        $this->animal = null;
        $this->animalid = null;
    }

    public function mount()
    {
        if ($this->animalid) {
            $this->setAnimal($this->animalid);
        }
    }
};
?>
<div class="galeria">
    <aside class="galeria__barra-lateral">
        <div class="galeria__marca">PetPin</div>
        <div class="galeria__lema">Galería animal</div>

        <div class="galeria__encabezado">Populares</div>
        <div class="galeria__lista-animales">
            @foreach ($this->getPopular() as $animal)
                <button type="button" class="galeria__animal {{ $this->animalid == $animal->id ? 'activo' : '' }}" wire:click="setAnimal({{ $animal->id }})">
                    <span class="galeria__nombre-animal">🐾 {{ $animal->name }}</span>
                    <span class="galeria__contador">{{ $animal->photos_count }}</span>
                </button>
            @endforeach
        </div>
    </aside>

    <main class="galeria__principal">
        @if ($this->animal == null)
            @php $palettes = ['', 'seccion--lila', 'seccion--verde', 'seccion--coral', 'seccion--azul']; @endphp
            @foreach ($this->getPopular() as $i => $animal)
                @if ($animal->photos_count > 0)
                    <section class="seccion {{ $palettes[$i % count($palettes)] }}">
                        <div class="seccion__cabecera">
                            <h2 class="seccion__titulo">
                                <span>🐾 {{ $animal->name }}</span>
                                <span class="seccion__conteo">{{ $animal->photos_count }} fotos</span>
                            </h2>
                            <a class="seccion__enlace" wire:click="setAnimal({{ $animal->id }})">Ver todas</a>
                        </div>
                        <div class="tarjetas">
                            @foreach ($animal->photos->take(5) as $photo)
                                <a class="tarjeta" href="{{ route('post', ['id' => $photo->id]) }}">
                                    <img src="{{ $photo->file_url }}" alt="{{ $photo->title }}">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        @else
            <section class="seccion">
                <div class="seccion__cabecera">
                    <h2 class="seccion__titulo">
                        <span>🐾 {{ $this->animal->name }}</span>
                        <span class="seccion__conteo">{{ count($this->animal->photos) }} fotos</span>
                    </h2>
                    <a class="seccion__enlace" wire:click="clearAnimal">Volver</a>
                </div>
                <div class="tarjetas tarjetas--4">
                    @foreach ($this->animal->photos as $photo)
                        <a class="tarjeta" href="{{ route('post', ['id' => $photo->id]) }}">
                            <img src="{{ $photo->file_url }}" alt="{{ $photo->title }}">
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</div>
