<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 8:48
 */

class Insumo
{
    private $ic_insumo_id;
    private $nombre;
    private $descripcion;
    private $programa_id;

    /**
     * Insumo constructor.
     * @param $ic_insumo_id
     * @param $nombre
     * @param $descripcion
     * @param $programa_id
     */
    public function __construct($ic_insumo_id, $nombre, $descripcion, $programa_id)
    {
        $this->ic_insumo_id = $ic_insumo_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->programa_id = $programa_id;
    }

    /**
     * @return mixed
     */
    public function getIcInsumoId()
    {
        return $this->ic_insumo_id;
    }

    /**
     * @param mixed $ic_insumo_id
     */
    public function setIcInsumoId($ic_insumo_id)
    {
        $this->ic_insumo_id = $ic_insumo_id;
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

    /**
     * @return mixed
     */
    public function getProgramaId()
    {
        return $this->programa_id;
    }

    /**
     * @param mixed $programa_id
     */
    public function setProgramaId($programa_id)
    {
        $this->programa_id = $programa_id;
    }

    public function __toString()
    {
        return $this->ic_insumo_id . ' '. $this->nombre;
    }

}