<?php

    namespace App\middlewares;

    class MiddlewareAuth{

        public function loginArea(){
            
            session_start();

            if(!isset($_SESSION['logeado'])){
                header('Location: /login');
                exit;
            }

        }

        public function notLoginArea(){
            session_start();

            if(isset($_SESSION['logeado'])){
                header('Location: /profile');
                exit;
            }

        }

    }