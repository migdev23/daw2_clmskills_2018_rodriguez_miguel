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
            
            $limit = 10;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

            
            $offset = ($page - 1) * $limit;
        
            $totalImgs = Imagen::count();
            $totalPages = ceil($totalImgs / $limit);
        
            $imgs = Imagen::with(['usuarios', 'categorias'])
                        ->limit($limit)
                        ->offset($offset)
                        ->get();
        
            $categories = Categoria::all();
            $authors = Usuario::all();
        
            echo $this->twig->render('/public/index.html.twig', [
                'imgs' => $imgs,
                'categories' => $categories,
                'authors' => $authors,
                'totalPages' => $totalPages,
                'currentPage' => $page
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

        public function indexParametro($param){
            echo $this->twig->render('/public/index.html.twig');
            exit;
        }

    }