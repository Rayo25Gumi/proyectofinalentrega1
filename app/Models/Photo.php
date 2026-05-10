<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'description', 'file_path', 'file_disk', 'user_id'])]
#[Hidden(['file_path', 'file_disk'])]

class Photo extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function animals(){
    return $this->belongsToMany(Animal::class);
    }
    public function getFileUrlAttribute(){
        return Storage::url($this->file_path);
    }
}
