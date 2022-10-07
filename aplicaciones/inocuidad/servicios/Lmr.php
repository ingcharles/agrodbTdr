<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 22:34
 */

class Lmr
{
    private $ic_lmr_id;
    private $nombre;
    private $descripcion;

    /**
     * Lmr constructor.
     * @param $ic_lmr_id
     * @param $nombre
     * @param $descripcion
     */
    public function __construct($ic_lmr_id, $nombre, $descripcion)
    {
        $this->ic_lmr_id = $ic_lmr_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getIcLmrId()
    {
        return $this->ic_lmr_id;
    }

    /**
     * @param mixed $ic_lmr_id
     */
    public function setIcLmrId($ic_lmr_id)
    {
        $this->ic_lmr_id = $ic_lmr_id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

}