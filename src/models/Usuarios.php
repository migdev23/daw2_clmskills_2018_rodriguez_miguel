<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class Usuarios extends Model{
        protected $table = 'usuarios';
        protected $primaryKey = 'uid';
        public $timestamps = false;
        protected $fillable = ['uid', 'email', 'nombre', 'password', 'perfil'];

        public function imagenes(){
            return $this->belongsToMany(Imagenes::class, 'imagenes_usuarios', 'uid', 'iid');
        }
        
    }