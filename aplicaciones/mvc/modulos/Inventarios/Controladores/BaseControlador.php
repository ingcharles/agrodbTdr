<?php
/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @author AGROCALIDAD
 * @uses     ControladorBase
 * @package Inventarios
 * @subpackage Controladores
 */
namespace Agrodb\Inventarios\Controladores;

session_start();
use Agrodb\Programas\Modelos\AccionesLogicaNegocio;

use Agrodb\Core\Log;
use Agrodb\Core\Comun;

class BaseControlador extends Comun
{

    public $itemsFiltrados = array();

    public $identificador = null;


    /**
     * Crea un combo con las opciones SI/NO
     *
     * @return string - Vista el cÃ³digo html para desplegar los botones
     */
    public function crearComboSINO($respuesta)
    {
        $combo = "";
        if ($respuesta == "1") {
            $combo .= '<option value="1" selected>SI</option>';
            $combo .= '<option value="0" >NO</option>';
        } else {
            $combo .= '<option value="1" >SI</option>';
            $combo .= '<option value="0" selected>NO</option>';
        }
        return $combo;
    }


    /**
     * Crea las opciones de campos estado cuando tiene elementos
     * ACTIVO
     * INACTIVO
     * SUSPENDIDO
     *
     * @param
     *            String HTML de un campo Radio Button
     */
    public function crearRadioEstadoAIS($estado)
    {
        $activo = "";
        $inactivo = "";
        $supendido = "";
        
        if ($estado == "ACTIVO") {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO") {
            $inactivo = 'checked="checked"';
        } elseif ($estado == "SUSPENDIDO") {
            $supendido = 'checked="checked"';
        }
        $radioButon = '<label for="activo">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estado" id="activo" value="INACTIVO" ' . $activo . '>
            <label for="suspendido">Suspendido</label>
            <input type="radio" name="estado" id="suspendido" value="SUSPENDIDO" ' . $inactivo . '>
            <label for="desactivado">Inactivo</label>
            <input type="radio" name="estado" id="desactivado" value="INACTIVO" ' . $supendido . '>
            ';
        
        return $radioButon;
    }

    /**
     * Crea las opciones de campos estado cuando tiene elementos
     * ACTIVO
     * INACTIVO
     * SUSPENDIDO
     *
     * @param
     *            String HTML de un campo Radio Button
     */
    public function crearRadioEstadoAI($estado)
    {
        $activo = "";
        $inactivo = "";
        
        if ($estado == "ACTIVO") {
            $activo = 'checked="checked"';
        } elseif ($estado == "INACTIVO") {
            $inactivo = 'checked="checked"';
        }
        $radioButon = '  <label for="activo" class="lblEstado">Estado: </label>
            <label for="activo">Activo</label>
            <input type="radio" name="estado" required id="activo" value="ACTIVO" ' . $activo . '>
            <label for="desactivado" >Inactivo</label>
            <input type="radio" name="estado" required id="desactivado" value="INACTIVO" ' . $inactivo . '>
            ';
        return $radioButon;
    }

    public function crearTabla()
    {
        $tabla = "//No existen datos para mostrar...";
        if (count($this->itemsFiltrados) > 0) {
            $tabla = '$(document).ready(function () {
            construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			 $("#listadoItems").removeClass("comunes");
			  });
             ';
        }
        
        return $tabla;
    }

    /**
     * Transforma una cadena con el formato adecuado para ponerlo en un formulario.
     *
     * @param String $cadena
     * @return String
     */
    public function quitarHtml($cadena)
    {
        $mayusculas = 'Ã�Ã‰Ã�Ã“ÃšÃ‘:';
        $minusculas = 'Ã¡Ã©Ã­Ã³ÃºÃ± ';
        $cadena = strtr($cadena, $mayusculas, $minusculas);
        $cadena = str_replace("&#10003;", " ", $cadena); // Este cÃ³digo fue puesto para poner un check en los reportes
        $cadena = strtolower(strip_tags($cadena));
        return ucfirst($cadena);
    }

    /**
     * Maneja las exepciones y las guarda en una base de datos
     *
     * @param type $excepciÃ³n
     */
    function manejadorExcepciones($excepcion)
    {
        new Log($excepcion);
    }
}
