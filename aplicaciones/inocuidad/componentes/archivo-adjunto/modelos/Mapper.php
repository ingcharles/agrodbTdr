<?php
/**
 * User: acanaveral
 * Clase Entidad que representa la tabla ic_adjunto_mapper
 */

class Mapper{
    private $elemento;
    private $nombreEsquema;
    private $nombreTabla;

    /**
     * ItemArchivoModelo constructor.
     * @param $elemento
     * @param $nombreEsquema
     * @param $nombreTabla
     */
    public function __construct($elemento, $nombreEsquema, $nombreTabla)
    {
        $this->elemento = $elemento;
        $this->nombreEsquema = $nombreEsquema;
        $this->nombreTabla = $nombreTabla;
    }


    /**
     * @return mixed
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * @param mixed $elemento
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;
    }

    /**
     * @return mixed
     */
    public function getNombreEsquema()
    {
        return $this->nombreEsquema;
    }

    /**
     * @param mixed $nombreEsquema
     */
    public function setNombreEsquema($nombreEsquema)
    {
        $this->nombreEsquema = $nombreEsquema;
    }

    /**
     * @return mixed
     */
    public function getNombreTabla()
    {
        return $this->nombreTabla;
    }

    /**
     * @param mixed $nombreTabla
     */
    public function setNombreTabla($nombreTabla)
    {
        $this->nombreTabla = $nombreTabla;
    }


}

