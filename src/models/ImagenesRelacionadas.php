<?php
// app/Models/Imagen.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenesRelacionadas extends Model {
    protected $table = 'imagenes_relacionadas';
    protected $fillable = ['imagen', 'imagen_relacionada'];
    public $timestamps = false;
}
