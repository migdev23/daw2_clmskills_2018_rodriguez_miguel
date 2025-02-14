<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class Imagenes extends Model{
        protected $table = 'imagenes';
        protected $primaryKey = 'iid';
        public $timestamps = false;
        protected $fillable = ['iid', 'titulo', 'descripcion', 'fichero', 'latitud', 'longitud'];
    }