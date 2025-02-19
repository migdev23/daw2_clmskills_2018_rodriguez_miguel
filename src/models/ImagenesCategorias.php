<?php
// app/Models/Imagen.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenesCategorias extends Model {
    protected $table = 'imagenes_categorias';
    protected $fillable = ['iid', 'cid'];
    public $timestamps = false;
}
