<?php

    namespace App\controllers;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use App\models\Database;
    use App\models\Usuarios;

    class ControllerPublic {

        private $twig;
        private $db;

        public function __construct(){
            $loader = new FilesystemLoader(__DIR__ . '/../views');
            $this->twig = new Environment($loader);
            $this->db = new Database();
        }

        public function index(){
            $users = Usuarios::select('nombre')->get();
            echo $this->twig->render('index.html.twig', ['users' => $users]);
            exit;
        }

        // public function indexParametro($param){
        //     echo $this->twig->render('index.html.twig');
        //     exit;
        // }

    }


