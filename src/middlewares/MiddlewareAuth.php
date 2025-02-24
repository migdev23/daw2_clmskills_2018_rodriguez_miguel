<?php

    namespace App\middlewares;
    use App\models\Usuario;
    use App\models\Database;
    class MiddlewareAuth{

        public function loginArea(){
            new Database(); 
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        
            if (!isset($_SESSION['logeado'])) {
                header('Location: /login');
                exit;
            }
        

            $uid = $_SESSION['logeado'];
        

            $usuario = Usuario::find($uid);
        
            if (!$usuario) {

                session_unset();
                session_destroy();
                header('Location: /login');
                exit;
            }
        
    
        }
        

        public function notLoginArea(){
            new Database();
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if(isset($_SESSION['logeado'])){
                header('Location: /profile');
                exit;
            }

        }

    }