<?php

namespace App\controllers\api;
use App\models\Database;
use App\models\Imagen;

class ImagenesApi {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function imgs(){
        header('Content-Type: application/json');
    
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
    
        $totalImgs = Imagen::count();
        $totalPages = ceil($totalImgs / $limit);
    
        $imgs = Imagen::select('iid', 'titulo', 'descripcion', 'latitud', 'longitud') 
            ->with([
                'usuarios:uid,nombre',
                'categorias:cid,nombre'
            ])
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();

        echo json_encode([
            'imgs' => $imgs,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
        exit;
    }

}
