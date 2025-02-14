<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class ImagenesRelacionadas extends Model{
        protected $table = 'imagenes_relacionadas';
        public $timestamps = false;
        protected $fillable = ['imagen', 'imagen_relacionada'];
    }