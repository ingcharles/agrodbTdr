<?php

/**
 * Excepción que representa un error en la lógica del programa al Guardar un registro. Este tipo de
 * excepción debe conducir directamente a una corrección del problema.
 *
 *
 * @author DATASTAR
 * @uses     GuardarExcepcion
 * @package Clases
 * @subpackage Excepciones
 */
namespace Agrodb\Core\Excepciones;


class GuardarExcepcionConDatos extends \RuntimeException
{

    protected $message;

    protected $code;

    protected $file;

    protected $line;

    public $errorInfo;

    public function __construct($ex)
    {
        $array = $ex->getTrace();
        $this->message = "Existe un error al guardar el registro, Mensaje  previo: " . json_encode($ex->getMessage()). " Revise los siguientes datos :" . json_encode($array[1]) ;
    }
}
