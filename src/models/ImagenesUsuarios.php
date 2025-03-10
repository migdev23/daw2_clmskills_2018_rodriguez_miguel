<?php
// app/Models/Imagen.php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ImagenesUsuarios extends Model {
    protected $table = 'imagenes_usuarios';
    protected $fillable = ['iid', 'uid'];
    public $timestamps = false;
}
