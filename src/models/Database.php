<?php

namespace App\models;

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;
class Database{

    private $dotenv;

    public function __construct(){   
        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $this->dotenv->load();

        try {
            
            $capsule = new Capsule;
            
            error_log($_ENV['DB_USER']);
            error_log($_ENV['DB_PASS']);

            $capsule->addConnection([
                'driver'   => 'mysql',
                'host'     => $_ENV['DB_HOST'],
                'database' => $_ENV['BD_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $capsule->setAsGlobal();

            $capsule->bootEloquent();

            $capsule->getConnection()->getPdo();

        } catch (\Exception $th) {
            echo 'No hay conexion a la base de datos';
            exit;
        }

    }

}
