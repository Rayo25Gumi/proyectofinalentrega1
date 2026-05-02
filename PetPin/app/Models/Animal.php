<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]

class Animal extends Model
{
    public $timestamps = false;
    public function photos(){
    return $this->belongsToMany(Photo::class);
    }
}
