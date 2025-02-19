<?php
// app/Models/Imagen.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model {
    // Especifica el nombre de la tabla
    protected $table = 'imagenes';

    // Define la clave primaria personalizada
    protected $primaryKey = 'iid';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['titulo', 'descripcion', 'fichero', 'latitud', 'longitud'];


    public function categorias() {
        // Relación N:M con Categorias a través de `imagenes_categorias`
        return $this->belongsToMany(
            Categoria::class,             // Modelo relacionado
            'imagenes_categorias',         // Nombre de la tabla pivot
            'iid',                        // Clave foránea en pivot hacia Imagenes
            'cid'                         // Clave foránea en pivot hacia Categorias
        );
    }


    public function usuarios() {
        // Relación N:M con Usuarios usando `imagenes_usuarios`
        return $this->belongsToMany(
            Usuario::class,               // Modelo relacionado
            'imagenes_usuarios',           // Nombre de la tabla pivot
            'iid',                        // Clave foránea en pivot hacia Imagenes
            'uid'                         // Clave foránea en pivot hacia Usuarios
        );
    }
    
    public function relacionadas() {
        // Relación recursiva N:M en la misma tabla a través de `imagenes_relacionadas`
        return $this->belongsToMany(
            Imagen::class,                 // Modelo relacionado (auto-relación)
            'imagenes_relacionadas',        // Tabla pivot
            'imagen',                      // Clave foránea en pivot hacia Imagen principal
            'imagen_relacionada'            // Clave foránea en pivot hacia Imagen relacionada
        );
    }
}
