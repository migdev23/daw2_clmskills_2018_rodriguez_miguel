<?php
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {

    protected $table = 'usuarios';

    protected $primaryKey = 'uid';

    protected $fillable = ['email', 'nombre', 'password', 'perfil'];

    public $timestamps = false;

    public function imagenes() {
        return $this->belongsToMany(
            Imagen::class,          
            'imagenes_usuarios',         
            'uid',                    
            'iid'                      
        );
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = sha1($value);
    }

}
