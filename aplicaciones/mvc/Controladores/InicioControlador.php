<?php
namespace Agrodb\Controladores;

class InicioControlador
{

    public function index()
    {
        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            
           // require MVC . '/vistas/index/index.php';
        }
    }
    
    public function configuracion() {
       require MVC . '/vistas/index/configuracion.php'; 
    }
}
