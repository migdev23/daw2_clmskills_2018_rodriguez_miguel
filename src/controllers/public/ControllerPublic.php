<?php

    namespace App\controllers\public;

    use App\Models\Categoria;
    use Twig\Loader\FilesystemLoader;

    use Twig\Environment;

    use App\models\Database;

    use App\models\Usuario;
    use App\models\Imagen;

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
        
            $categories = Categoria::all();
            $authors = Usuario::all();
        
            echo $this->twig->render('/public/index.html.twig', [
                'categories' => $categories,
                'authors' => $authors,
            ]);
            exit;
        }

        
        
        public function viewPhotoProfileUid($id) {
            
            $user = Usuario::find($id);
        
            if ($user && $user->perfil) {
                header('Content-Type: image/jpeg');
                echo $user->perfil;
                exit;
            }else{
                echo 'No se encontro la img';
            }

        }

        public function viewImageIid($id) {
            
            $img = Imagen::find($id);
        
            if ($img && $img->fichero) {
                header('Content-Type: image/jpeg');
                echo $img->fichero;
                exit;
            }else{
                echo 'No se encontro la img';
            }

        }

        public function detailsImage($id){
        
            $img = Imagen::with([
                'usuarios:uid,nombre',
                'categorias:cid,nombre'
            ])->find($id);

        
            $relacionadas = $img->relacionadas();

            echo $this->twig->render('/public/detailsImg.html.twig', [
                'img' => $img,
                'relacionadas' => $relacionadas,
                'author' => $img->usuarios
            ]);
            
            exit;
        }


    }