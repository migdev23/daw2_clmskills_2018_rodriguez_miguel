<?php

    namespace App\controllers\public\auth;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use App\models\Database;
    use App\models\Usuario;

    class ControllerAuth {

        private $twig;

        private $db;

        public function __construct(){
            $this->db = new Database();

            $loader = new FilesystemLoader(__DIR__ . '/../../../views');
            $this->twig = new Environment($loader);
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $this->twig->addGlobal('logeado', isset($_SESSION['logeado']) ? $_SESSION['logeado'] : null);
        }

        public function loginPage (){
            echo $this->twig->render('public/auth/login.html.twig');
            exit;
        }

        public function loginAccess (){

            $email = $_POST['email'];

            $password = $_POST['password'];

            $usuario = Usuario::where('email', $email)->first();

            if ($usuario && sha1($password) === $usuario->password) {

                $_SESSION['logeado'] = $usuario->uid;

                header('Location: /profile');

            } else {
                echo "Credenciales incorrectas.";
            }

            echo $this->twig->render('public/auth/login.html.twig');
            exit;
        }

        
        public function logout() {
            
            session_unset();

            session_destroy();

            header('Location: /');
            exit;
        }

}


