<?php

    namespace App\controllers\public;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use App\models\Database;
    use App\models\Usuarios;

    class ControllerPublic {

        private $twig;
        private $db;

        public function __construct(){
            $loader = new FilesystemLoader(__DIR__ . '/../../views');
            $this->twig = new Environment($loader);
            $this->db = new Database();
        }

        public function index(){
            $users = Usuarios::select('uid','nombre')->get();
            echo $this->twig->render('/public/index.html.twig', ['users' => $users]);
            exit;
        }

        public function verImagen($id) {
            
            $user = Usuarios::find($id);
        
            if ($user && $user->perfil) {
                header('Content-Type: image/jpeg');
                
                echo $user->perfil;

                // foreach ($user->imagenes as $img => $binImg) {
                //     echo $binImg;
                // }
            
                exit;
            }

        }

        public function indexParametro($param){
            echo $this->twig->render('/public/index.html.twig');
            exit;
        }

    }


