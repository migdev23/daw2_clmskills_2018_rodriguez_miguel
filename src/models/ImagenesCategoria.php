<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class ImagenesCategoria extends Model{
        protected $table = 'imagenes_categorias';
        public $timestamps = false;
        protected $fillable = ['iid', 'cid'];
    }