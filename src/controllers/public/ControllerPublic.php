<?php

    namespace App\controllers\public;

    use Twig\Loader\FilesystemLoader;

    use Twig\Environment;

    use App\models\Database;

    use App\models\Usuario;

    class ControllerPublic {

        private $twig;
        private $db;

        public function __construct(){
            $this->db = new Database();
            
            $loader = new FilesystemLoader(__DIR__ . '/../../views');
            $this->twig = new Environment($loader);
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $this->twig->addGlobal('logeado', isset($_SESSION['logeado']) ? $_SESSION['logeado'] : null);
        }

        public function index(){
            $users = Usuario::select('uid','email')->get();
            echo $this->twig->render('/public/index.html.twig', ['users' => $users]);
            exit;
        }

        public function verImagen($id) {
            
            $user = Usuario::find($id);
        
            if ($user && $user->perfil) {
                header('Content-Type: image/jpeg');
                echo $user->perfil;
                exit;
            }

        }

        public function indexParametro($param){
            echo $this->twig->render('/public/index.html.twig');
            exit;
        }

    }