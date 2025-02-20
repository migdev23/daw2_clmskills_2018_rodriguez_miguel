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
    
        $categoria = isset($_GET['category']) && $_GET['category'] != 'undefined'  ? trim($_GET['category']) : "";
        $autor = isset($_GET['author']) &&  $_GET['author'] != 'undefined' ? trim($_GET['author']) : "";
        
    
        // Obtener el total de imágenes antes de aplicar limit() y offset()
        $totalImgs = Imagen::when($categoria, fn($query) => $query->whereHas('categorias', fn($q) => $q->where('nombre', $categoria)))
            ->when($autor, fn($query) => $query->whereHas('usuarios', fn($q) => $q->where('nombre', $autor)))
            ->count();
    
        // Calcular el total de páginas
        $totalPages = ($totalImgs > 0) ? ceil($totalImgs / $limit) : 1;
    
        // Obtener las imágenes con paginación
        $imgs = Imagen::select('iid', 'titulo', 'descripcion', 'latitud', 'longitud')
            ->with([
                'usuarios:uid,nombre',
                'categorias:cid,nombre'
            ])
            ->when($categoria, fn($query) => $query->whereHas('categorias', fn($q) => $q->where('nombre', $categoria)))
            ->when($autor, fn($query) => $query->whereHas('usuarios', fn($q) => $q->where('nombre', $autor)))
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();
    
        // Devolver la respuesta en JSON
        echo json_encode([
            'imgs' => $imgs,
            'totalImgs' => $totalImgs,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
        exit;
    }
    

}
