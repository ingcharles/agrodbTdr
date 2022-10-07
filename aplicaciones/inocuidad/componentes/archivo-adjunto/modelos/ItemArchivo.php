<?php
/**
 * User: ccarrera
 * Clase entidad que representa una tabla ic_adunto_item
 */

class ItemArchivo
{
private $id;
private $modelId;
private $nombre;
private $descripcion;
private $fechaCarga;
private $etiqueta;
private $ruta;

    /**
     * ItemArchivo constructor.
     * @param $id
     * @param $modelId
     * @param $nombre
     * @param $descripcion
     * @param $fechaCarga
     * @param $etiqueta
     * @param $ruta
     */
    public function __construct( $modelId, $nombre, $descripcion, $fechaCarga, $etiqueta, $ruta)
    {
        $this->modelId = $modelId;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaCarga = $fechaCarga;
        $this->etiqueta = $etiqueta;
        $this->ruta = $ruta;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param mixed $modelId
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
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
    public function getFechaCarga()
    {
        return $this->fechaCarga;
    }

    /**
     * @param mixed $fechaCarga
     */
    public function setFechaCarga($fechaCarga)
    {
        $this->fechaCarga = $fechaCarga;
    }

    /**
     * @return mixed
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * @param mixed $etiqueta
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;
    }

    /**
     * @return mixed
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * @param mixed $ruta
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }


}