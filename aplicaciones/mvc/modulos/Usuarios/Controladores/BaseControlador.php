<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       BaseControlador
 * @package usuarios
 * @subpackage Controladores
 */
namespace Agrodb\Usuarios\Controladores;

session_start();

use Agrodb\Core\Comun;

/**
 *
 * @author Edison Ayala
 */
class BaseControlador extends Comun
{

    public $itemsFiltrados = array();

    public $codigoJS = null;
    
    public $historialIngreso = null;
	
	public $correoUsuario = null;
       
    public $tipoUsuario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::usuarioActivo();
        // Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
        $this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
    }

    public function crearTabla()
    {
        $tabla = "//No existen datos para mostrar...";
        if (count($this->itemsFiltrados) > 0) {
            $tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
        }
        
        return $tabla;
    }
}
