<?php
namespace App\controllers\user;

use App\models\Database;
use App\Models\Imagen;
use App\Models\ImagenesCategorias;
use App\Models\ImagenesRelacionadas;
use App\Models\ImagenesUsuarios;
use App\models\Usuario;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerUser
{

    private $twig;
    private $db;

    public function __construct()
    {

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

        $imgs = Imagen::with(['usuarios', 'categorias'])
            ->whereHas('usuarios', function ($query) {
                $query->where('usuarios.uid', $_SESSION['logeado']);
            })->get();

        $user = Usuario::find($_SESSION['logeado']);

        echo $this->twig->render('/user/profile.html.twig', ['imgs' => $imgs, 'user' => $user]);
        exit;
    }

    public function newPhotoPage()
    {
        echo $this->twig->render('/user/newPhoto.html.twig');
        exit;
    }

    public function newPhoto()
    {

        $titulo        = $_POST['titulo'];
        $descripcion   = $_POST['descripcion'];
        $imagen        = $_FILES['fichero'];
        $imagenBinaria = file_get_contents($imagen['tmp_name']);
        $latitud       = $_POST['latitud'];
        $longitud      = $_POST['longitud'];

        $nuevaImagen              = new Imagen();
        $nuevaImagen->titulo      = $titulo;
        $nuevaImagen->descripcion = $descripcion;
        $nuevaImagen->fichero     = $imagenBinaria;
        $nuevaImagen->latitud     = $latitud;
        $nuevaImagen->latitud     = $longitud;
        $nuevaImagen->save();
        $iid = $nuevaImagen->iid;

        $imagenUsuario      = new ImagenesUsuarios();
        $imagenUsuario->iid = $iid;
        $imagenUsuario->uid = $_SESSION['logeado'];
        $imagenUsuario->save();

        header('Location: /profile');
        exit;
    }

    public function deletePhoto()
    {

        try {

            ImagenesRelacionadas::where('imagen', $_POST['iid'])->delete();
            ImagenesRelacionadas::where('imagen_relacionada', $_POST['iid'])->delete();

            ImagenesCategorias::where('iid', $_POST['iid'])->delete();

            ImagenesUsuarios::where('iid', $_POST['iid'])
                ->where('uid', $_SESSION['logeado'])
                ->delete();

            Imagen::where('iid', $_POST['iid'])->delete();

            echo json_encode(['status' => 'success']);
        } catch (\Exception $th) {
            error_log($th->getMessage());
            echo json_encode(['status' => 'unSuccess']);
        }

        exit;
    }

}
