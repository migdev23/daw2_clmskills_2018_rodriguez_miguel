<?php
// app/Models/Imagen.php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ImagenesCategorias extends Model {
    protected $table = 'imagenes_categorias';
    protected $fillable = ['iid', 'cid'];
    public $timestamps = false;
}
