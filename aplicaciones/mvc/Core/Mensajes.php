<?php

/**
 * Maneja los mensajes que se muestran al usuario
 */

namespace Agrodb\Core;

class Mensajes
{

    /**
     * Imprime un mensaje cuando una terea tiene errores y no se ejecuta
     * @param type String 
     */
    public static function fallo($mensaje)
    {
        $datos = array('estado' => 'error', 'mensaje' => $mensaje);
        echo \Zend\Json\Json::encode($datos);
        //exit();
    }

  

    /**
     * Imprime un mensaje cuando una terea se ejecuto de forma correcta
     * @param type String
     */
    public static function exito($mensaje)
    {
       $datos = array('estado' => 'exito', 'mensaje' => $mensaje);
        echo \Zend\Json\Json::encode($datos);
        //exit();
    }

  

    /**
     * Limpia el div antes de validar un campo
     */
    public static function limpiar()
    {

        return '$("#estado").empty();';
    }

}
