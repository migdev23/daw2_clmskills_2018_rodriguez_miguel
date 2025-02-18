<?php

    namespace App\controllers\user;

    use Twig\Loader\FilesystemLoader;

    use Twig\Environment;

    use App\models\Database;

    use App\models\Usuario;

    class ControllerUser {

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
            exit;
        }

        public function profilePage(){
            $usuario = Usuario::find($_SESSION['logeado']);
            echo $this->twig->render('/user/profile.html.twig', ['user' => $usuario]);
            exit;
        }

    }