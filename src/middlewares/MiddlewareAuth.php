<?php

    namespace App\middlewares;

    class MiddlewareAuth{

        public function loginArea(){
            error_log('Login Area');
        }

        public function notLoginArea(){
            error_log('not login Area');
        }

    }