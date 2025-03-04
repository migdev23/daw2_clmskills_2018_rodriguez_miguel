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
            
            $mantenerSesion = $_POST['mantenerSesion'];


            $usuario = Usuario::where('email', $email)->first();

            if ($usuario && sha1($password) === $usuario->password) {

                $_SESSION['logeado'] = $usuario->uid;

                if($mantenerSesion == 'on'){
                    $_SESSION['sesionActive'] = true;
                }else{
                    $_SESSION['sesionActive'] = false;
                }

                header('Location: /profile');

            } else {
                echo "Credenciales incorrectas.";
            }

            echo $this->twig->render('public/auth/login.html.twig');
            exit;
        }

        public function registerPage(){
            echo $this->twig->render('public/auth/register.html.twig');
            exit;
        }


        public function registerCreate() {

            if (
                !isset($_POST['email'], $_FILES['perfil'], $_POST['nombre'], $_POST['password']) || 
                empty(trim($_POST['email'])) || 
                empty(trim($_POST['nombre'])) || 
                empty(trim($_POST['password'])) ||
                $_FILES['perfil']['error'] > 0
            ) {
                echo "Error: Faltan campos obligatorios.";
                exit;
            }
        

            $email = trim($_POST['email']);
            $nombre = trim($_POST['nombre']);
            $password = trim($_POST['password']);
            $imagen = $_FILES['perfil'];
        
        
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Error: El email no es válido.";
                exit;
            }
        

            if (Usuario::where('email', $email)->exists()) {
                echo "Error: El correo ya está registrado.";
                exit;
            }


            if (Usuario::where('nombre', $nombre)->exists()) {
                echo "Error: Autor (nombre) ya registreado";
                exit;
            }
        

            $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
            if ($extension !== 'jpg' && $extension !== 'jpeg') {
                echo "Error: La imagen debe ser formato JPG.";
                exit;
            }
        
            $imagenBinaria = file_get_contents($imagen['tmp_name']);
        
    
            $usuario = new Usuario();
            $usuario->email = $email;
            $usuario->nombre = $nombre;
            $usuario->password = sha1($password);
            $usuario->perfil = $imagenBinaria; 
        
            if ($usuario->save()) {
                $_SESSION['logeado'] = $usuario->uid;
                $_SESSION['sesionActive'] = true;
                header('Location: /profile');
                exit;
            } else {
                echo "Error: No se pudo registrar el usuario.";
                exit;
            }
        }
        
        
        

        
        public function logout() {
            
            session_unset();

            session_destroy();

            header('Location: /');

            exit;
        }

        public function logoutClosePage() {
            
            if($_SESSION['sesionActive'] == false){
                session_unset();

                session_destroy();

                header('Location: /');
            }
            
            exit;
        }

}


