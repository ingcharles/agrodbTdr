<?php

namespace Agrodb\Modelos;

use Agrodb\Core\ModeloBase;

class LogSistemaModelo extends ModeloBase
{

    /**
     * Indentificador de la tabla
     * @var type 
     */
    protected $id;

    /**
     * Fecha que ocurrio el evento
     * @var type 
     */
    protected $fecha;

    /**
     * Tipo de evento
     * @var type 
     */
    protected $tipo;

    /**
     * Descripción del evento
     * @var type 
     */
    protected $evento;

    function getId() {
        return $this->id;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getEvento() {
        return $this->evento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

    public function __construct() {
        
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo() {
        return parent::buscarTodo();
    }

}
