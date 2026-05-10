<?php

namespace App\Console\Commands;

use App\Models\Animal;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('animals:import')]
#[Description('Import animals to database')]
class ImportAnimalsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // esto es una funcion de laravel para que me gestione el obtener los datos de la api 
        $list = Http::get('https://gist.githubusercontent.com/borlaym/585e2e09dd6abd9b0d0a/raw/6e46db8f5c27cb18fd1dfa50c7c921a0fbacbad0/animals.json')->json();
        // funcion que hace que si está en la base de datos actualiza y si no lo inserta, lo de array es para formatearlo ya que necesita una key campo y el nombre
        // primero se hace el map y luego los argumentos son los del upset, que dice primero la lista y luego el siguinete argumento (name) es por lo que cada item es unico
        Animal::upsert(array_map(fn($item)=>['name'=>$item], $list),['name']);
    }
}
