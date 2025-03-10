<?php
// app/Models/Imagen.php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model {
    // Especifica el nombre de la tabla
    protected $table = 'imagenes';

    // Define la clave primaria personalizada
    protected $primaryKey = 'iid';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['titulo', 'descripcion', 'fichero', 'latitud', 'longitud'];

    public $timestamps = false;

    public function categorias() {
        // Relación N:M con Categorias a través de `imagenes_categorias`
        return $this->belongsToMany(
            Categoria::class,         
            'imagenes_categorias',      
            'iid',                      
            'cid'                         
        );
    }

    public function usuarios() {
        return $this->belongsToMany(
            Usuario::class,              
            'imagenes_usuarios',        
            'iid',                     
            'uid'                        
        );
    }
    
    public function relacionadas(){
        $imagenId = $this->iid;

        return Imagen::whereIn('iid', function ($query) use ($imagenId) {
            $query->select('imagen_relacionada')
                ->from('imagenes_relacionadas')
                ->where('imagen', $imagenId);
        })
        ->orWhereIn('iid', function ($query) use ($imagenId) {
            $query->select('imagen')
                ->from('imagenes_relacionadas')
                ->where('imagen_relacionada', $imagenId);
        })
        ->get();
    }
}
