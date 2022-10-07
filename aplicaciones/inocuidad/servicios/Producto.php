<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 06/02/18
 * Time: 8:43
 */

class Producto
{
    private $ic_producto_id;
    private $producto_id;
    private $programa_id;
    private $nombre;
    private $id_subtipo_producto;
    private $id_tipo_producto;
    private $id_area;
    private $muestra_rapida;

    /**
     * Producto constructor.
     * @param $ic_producto_id
     * @param $producto_id
     * @param $programa_id
     * @param $nombre
     */
    public function __construct($ic_producto_id, $producto_id, $programa_id, $nombre, $muestra_rapida)
    {
        $this->ic_producto_id = $ic_producto_id;
        $this->producto_id = $producto_id;
        $this->programa_id = $programa_id;
        $this->nombre = $nombre;
        $this->muestra_rapida = $muestra_rapida;
    }

    /**
     * @return mixed
     */
    public function getIcProductoId()
    {
        return $this->ic_producto_id;
    }

    /**
     * @param mixed $ic_producto_id
     */
    public function setIcProductoId($ic_producto_id)
    {
        $this->ic_producto_id = $ic_producto_id;
    }

    /**
     * @return mixed
     */
    public function getProductoId()
    {
        return $this->producto_id;
    }

    /**
     * @param mixed $producto_id
     */
    public function setProductoId($producto_id)
    {
        $this->producto_id = $producto_id;
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
    public function getIdSubtipoProducto()
    {
        return $this->id_subtipo_producto;
    }

    /**
     * @param mixed $id_subtipo_producto
     */
    public function setIdSubtipoProducto($id_subtipo_producto)
    {
        $this->id_subtipo_producto = $id_subtipo_producto;
    }

    /**
     * @return mixed
     */
    public function getIdTipoProducto()
    {
        return $this->id_tipo_producto;
    }

    /**
     * @param mixed $id_tipo_producto
     */
    public function setIdTipoProducto($id_tipo_producto)
    {
        $this->id_tipo_producto = $id_tipo_producto;
    }

    /**
     * @return mixed
     */
    public function getIdArea()
    {
        return $this->id_area;
    }

    /**
     * @param mixed $id_area
     */
    public function setIdArea($id_area)
    {
        $this->id_area = $id_area;
    }

    /**
     * @return mixed
     */
    public function getMuestraRapida()
    {
        return $this->muestra_rapida;
    }

    /**
     * @param mixed $muestra_rapida
     */
    public function setMuestraRapida($muestra_rapida)
    {
        $this->muestra_rapida = $muestra_rapida;
    }


}