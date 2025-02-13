<?php

namespace App\models;

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;



class Database{

    private $dotenv;

    public function __construct(){   
        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $this->dotenv->load();
        try {
            
            $capsule = new Capsule;
            
            $capsule->addConnection([
                'driver'   => 'mysql',
                'host'     => '127.0.0.1',
                'database' => 'skills',
                'username' => $_ENV['USERDB'],
                'password' => $_ENV['PWDDB'],
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();
        } catch (\Throwable $th) {
            die("Error Processing Request --DB CONNECTION");
        }
    }

}
