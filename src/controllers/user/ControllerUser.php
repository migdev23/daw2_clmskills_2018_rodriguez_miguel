<?php
namespace App\controllers\user;

use App\models\Database;
use App\models\Imagen;
use App\models\ImagenesCategorias;
use App\models\ImagenesRelacionadas;
use App\models\ImagenesUsuarios;
use App\models\Usuario;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerUser
{

    private $twig;
    private $db;

    public function __construct(){

        $this->db = new Database();

        $loader     = new FilesystemLoader(__DIR__ . '/../../views');
        $this->twig = new Environment($loader);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->twig->addGlobal('logeado', isset($_SESSION['logeado']) ? $_SESSION['logeado'] : null);
    }

    public function profilePage()
    {
        try {
            $imgs = Imagen::with(['usuarios', 'categorias'])
            ->whereHas('usuarios', function ($query) {
                $query->where('usuarios.uid', $_SESSION['logeado']);
            })->get();

            $user = Usuario::find($_SESSION['logeado']);

            echo $this->twig->render('/user/profile.html.twig', ['imgs' => $imgs, 'user' => $user]);
            exit;
        } catch (\Exception $th) {
            header('Location: /');
            exit;
        }
    }

    public function newPhotoPage(){
        try {
            echo $this->twig->render('/user/newPhoto.html.twig');
            exit;
        } catch (\Exception $th) {
            header('Location: /');
            exit;
        }
    }

    public function newPhoto(){
        try {
    
            if (empty($_POST['titulo']) || empty($_POST['descripcion']) || empty($_POST['latitud']) || empty($_POST['longitud'])) {
                throw new \Exception("Faltan campos obligatorios.");
            }
        
            if (empty($_FILES['fichero']) || $_FILES['fichero']['error'] != 0) {
                throw new \Exception("No se ha subido un archivo correctamente.");
            }
        
            $imagen = $_FILES['fichero'];
        

            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);
        
            if (!in_array($ext, $extensionesPermitidas)) {
                throw new \Exception("El archivo no tiene una extensión de imagen válida. Las extensiones permitidas son: .jpg, .jpeg, .png, .gif.");
            }
        
        
            $imageInfo = getimagesize($imagen['tmp_name']);
            if ($imageInfo === false) {
                throw new \Exception("El archivo no es una imagen válida.");
            }
        
            
            $titulo        = $_POST['titulo'];
            $descripcion   = $_POST['descripcion'];
            $latitud       = $_POST['latitud'];
            $longitud      = $_POST['longitud'];
        
        
            $latitud       = is_numeric($latitud) ? (float)$latitud : 0.0;
            $longitud      = is_numeric($longitud) ? (float)$longitud : 0.0;  
        
    
            $imagenBinaria = file_get_contents($imagen['tmp_name']);
        

            $nuevaImagen              = new Imagen();
            $nuevaImagen->titulo      = $titulo;
            $nuevaImagen->descripcion = $descripcion;
            $nuevaImagen->fichero     = $imagenBinaria;
            $nuevaImagen->latitud     = $latitud;
            $nuevaImagen->longitud    = $longitud;
            $nuevaImagen->save();
            $iid = $nuevaImagen->iid;
        

            $imagenUsuario      = new ImagenesUsuarios();
            $imagenUsuario->iid = $iid;
            $imagenUsuario->uid = $_SESSION['logeado'];
            $imagenUsuario->save();
        

            header('Location: /profile');
            exit;
        
        } catch (\Exception $th) {
            header('Location: /profile');
            exit;
        }
        
        
    }

    public function editImagePage($iid=null){
        try {
            if(!$iid){
                header('Location: /');
            }else{
    
                $img = Imagen::where('iid', $iid)->select('iid','titulo', 'descripcion', 'latitud', 'longitud')->get();
    
                $imagenUsuario = ImagenesUsuarios::where('iid', $iid)
                ->where('uid', $_SESSION['logeado'])->get();
    
                if(count($img) > 0 && count($imagenUsuario) > 0){
    
                    echo $this->twig->render('/user/editImg.html.twig', [
                        'img'=>$img[0]
                    ]);
    
                }else{
                    header('Location: /');
                }
                
            }
    
            exit;
        } catch (\Exception $th) {
            header('Location: /');
            exit;
        }
    }

    public function editImage($iid = null) {
        try {
            if (!$iid) {
                header('Location: /');
                exit;
            }
        
            $img = Imagen::where('iid', $iid)->select('iid', 'titulo', 'fichero', 'descripcion', 'latitud', 'longitud')->first();
        
            $imagenUsuario = ImagenesUsuarios::where('iid', $iid)
                ->where('uid', $_SESSION['logeado'])->first();
        
            if ($img && $imagenUsuario) {
                $titulo = $_POST['titulo'] ?? $img->titulo;
                $descripcion = $_POST['descripcion'] ?? $img->descripcion;
                $latitud = $_POST['latitud'] ?? $img->latitud;
                $longitud = $_POST['longitud'] ?? $img->longitud;
        
                if (isset($_FILES['fichero']) && $_FILES['fichero']['error'] == 0) {
                    $imagenBinaria = file_get_contents($_FILES['fichero']['tmp_name']);
                    $img->fichero = $imagenBinaria;
                }
        
        
                $img->titulo = $titulo;
                $img->descripcion = $descripcion;
                $img->latitud = $latitud;
                $img->longitud = $longitud;
        
        
                $img->save();
        
        
                header('Location: /profile');
            } else {
                header('Location: /');
            }
        
            exit;
        } catch (\Exception $th) {
            header('Location: /');
            exit;
        }
    }
    

    public function deletePhoto()
    {
        try {

            $img = Imagen::where('iid', $_POST['iid'])->get();
            
            $imagenUsuario = ImagenesUsuarios::where('iid', $_POST['iid'])
            ->where('uid', $_SESSION['logeado'])->get();

            if(count($img) > 0 && count($imagenUsuario) > 0){
                ImagenesRelacionadas::where('imagen', $_POST['iid'])->delete();
                ImagenesRelacionadas::where('imagen_relacionada', $_POST['iid'])->delete();
    
                ImagenesCategorias::where('iid', $_POST['iid'])->delete();
    
                ImagenesUsuarios::where('iid', $_POST['iid'])
                    ->where('uid', $_SESSION['logeado'])
                    ->delete();
    
                Imagen::where('iid', $_POST['iid'])->delete();
                echo json_encode(['status' => 'success']);
            }else{
                header('Location: /');
            }
        } catch (\Exception $th) {
            error_log($th->getMessage());
            echo json_encode(['status' => 'unSuccess']);
        }

        exit;
    }


}
