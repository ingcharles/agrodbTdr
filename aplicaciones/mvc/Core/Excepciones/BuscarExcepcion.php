<?php

/**
 * Excepción que representa un error en la lógica del programa al Buscar un registro. Este tipo de
 * excepción debe conducir directamente a una corrección del problema.
 *
 *
 * @author DATASTAR
 * @uses     BuscarExcepcion
 * @package Clases
 * @subpackage Excepciones
 */
namespace Agrodb\Core\Excepciones;

/**
 * echo "<br>getCode"; print_r($ex->getCode());
 * echo "<br>getFile"; print_r($ex->getFile());
 * echo "<br>getLine"; print_r($ex->getLine());
 * echo "<br>getMessage"; print_r($ex->getMessage());
 * echo "<br>getPrevious"; print_r($ex->getPrevious());
 * echo "<br>getTrace"; print_r($ex->getTrace());
 * echo "<br>getTraceAsString"; print_r($ex->getTraceAsString());
 */
class BuscarExcepcion extends \RuntimeException
{

    protected $message;

    protected $code;

    protected $file;

    protected $line;

    public $errorInfo;

    public function __construct($ex, $datos = null, $code = null, $previous = null)
    {
        $this->message = "Existe un error al listar el(los) registro(s), revise los siguientes datos :" . json_encode($datos) . " Mensaje  previo: " . $ex->getMessage();
    }
}
