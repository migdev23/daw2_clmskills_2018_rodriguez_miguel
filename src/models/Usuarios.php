<?php
    namespace App\models;

    use Illuminate\Database\Eloquent\Model;

    class Usuarios extends Model{
        protected $table = 'usuarios';
        protected $primaryKey = 'uid';
        public $timestamps = false;
        protected $fillable = ['uid', 'email', 'nombre', 'password', 'perfil'];
    }