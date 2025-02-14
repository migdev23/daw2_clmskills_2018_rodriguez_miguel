<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class ImagenesUsuarios extends Model{
        protected $table = 'imagenes_usuarios';
        public $timestamps = false;
        protected $fillable = ['iid', 'uid'];
    }