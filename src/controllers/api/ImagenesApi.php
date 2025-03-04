<?php

namespace App\controllers\api;
use App\models\Database;
use App\models\Imagen;
use App\Models\Usuario;

class ImagenesApi {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function imgs(){
        try {
            header('Content-Type: application/json');
    
            $limit = isset($_GET['pageLimit']) && is_numeric($_GET['pageLimit']) && (int) $_GET['pageLimit'] > 0 ? (int) $_GET['pageLimit'] : 6;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;
        
            $categoria = isset($_GET['category']) && $_GET['category'] != 'undefined'  ? trim($_GET['category']) : "";
            $autor = isset($_GET['author']) &&  $_GET['author'] != 'undefined' ? trim($_GET['author']) : "";
            $titulo = isset($_GET['title']) && $_GET['title'] != 'undefined' ? trim($_GET['title']) : "";
    
            $totalImgs = Imagen::
                when($categoria, fn($query) => $query->whereHas('categorias', fn($q) => $q->where('nombre', $categoria)))
                ->when($autor, fn($query) => $query->whereHas('usuarios', fn($q) => $q->where('nombre', $autor)))
                ->when($titulo, fn($query) => $query->where('titulo', 'LIKE', "%$titulo%"))
                ->count();
            
            $totalPages = ($totalImgs > 0) ? ceil($totalImgs / $limit) : 1;
            
            $imgs = Imagen::select('iid', 'titulo', 'descripcion', 'latitud', 'longitud')
                ->with([
                    'usuarios:uid,nombre',
                    'categorias:cid,nombre'
                ])
                ->when($categoria, fn($query) => $query->whereHas('categorias', fn($q) => $q->where('nombre', $categoria)))
                ->when($autor, fn($query) => $query->whereHas('usuarios', fn($q) => $q->where('nombre', $autor)))
                ->when($titulo, fn($query) => $query->where('titulo', 'LIKE', "%$titulo%"))
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
        
    
            echo json_encode([
                'imgs' => $imgs,
                'totalImgs' => $totalImgs,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);
            exit;
        } catch (\Exception $th) {
            
            echo json_encode([
                'imgs' => [],
                'totalImgs' => 0,
                'totalPages' => 0,
                'currentPage' => 0,
            ]);

            exit;

        }
    }


    public function imgsProfile(){
        try {
            header('Content-Type: application/json');
    
            $limit = 6;
            
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;
    
            $totalImgs = Usuario::find($_SESSION['logeado'])->imagenes()->count();
            
            $totalPages = ($totalImgs > 0) ? ceil($totalImgs / $limit) : 1;
            
            $imgs = Usuario::find($_SESSION['logeado'])->imagenes()
                ->select('imagenes.iid','imagenes.titulo', 'imagenes.descripcion', 'imagenes.latitud', 'imagenes.longitud')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
        
    
            echo json_encode([
                'imgs' => $imgs,
                'totalImgs' => $totalImgs,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);
            exit;
        } catch (\Exception $th) {
            
            echo json_encode([
                'imgs' => [],
                'totalImgs' => 0,
                'totalPages' => 0,
                'currentPage' => 0,
            ]);
            exit;
        }
    }

    
    

}
