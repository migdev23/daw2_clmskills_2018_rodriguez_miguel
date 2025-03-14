<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {
    // Especifica el nombre de la tabla
    protected $table = 'categorias';
    
    // Define la clave primaria personalizada
    protected $primaryKey = 'cid';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre'];

    public $timestamps = false;

    /**
     * Relación Muchos a Muchos con Imagenes.
     * Una categoría puede tener muchas imágenes y una imagen puede pertenecer a muchas categorías.
     * Se usa la tabla pivot `imagenes_categorias`.
     */
    public function imagenes() {
        return $this->belongsToMany(
            Imagen::class,               // Modelo relacionado
            'imagenes_categorias',        // Nombre de la tabla pivot
            'cid',                       // Clave foránea en pivot hacia Categorias
            'iid'                        // Clave foránea en pivot hacia Imagenes
        );
    }
}
