<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 19/02/18
 * Time: 22:45
 */

class Laboratorio implements JsonSerializable
{
    private $ic_analisis_muestra_id;
    private $ic_muestra_id;
    private $activo;
    private $ic_tipo_requerimiento_id;
    private $ic_requerimiento_id;
    private $ic_producto_id;
    private $observaciones;

    /**
     * Laboratorio constructor.
     * @param $ic_analisis_muestra_id
     * @param $ic_muestra_id
     * @param $activo
     */
    public function __construct($ic_analisis_muestra_id, $ic_muestra_id, $activo)
    {
        $this->ic_analisis_muestra_id = $ic_analisis_muestra_id;
        $this->ic_muestra_id = $ic_muestra_id;
        $this->activo = $activo;
    }

    public function jsonSerialize() { return get_object_vars($this); }

    /**
     * @return mixed
     */
    public function getIcAnalisisMuestraId()
    {
        return $this->ic_analisis_muestra_id;
    }

    /**
     * @param mixed $ic_analisis_muestra_id
     */
    public function setIcAnalisisMuestraId($ic_analisis_muestra_id)
    {
        $this->ic_analisis_muestra_id = $ic_analisis_muestra_id;
    }

    /**
     * @return mixed
     */
    public function getIcMuestraId()
    {
        return $this->ic_muestra_id;
    }

    /**
     * @param mixed $ic_muestra_id
     */
    public function setIcMuestraId($ic_muestra_id)
    {
        $this->ic_muestra_id = $ic_muestra_id;
    }

    /**
     * @return mixed
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * @param mixed $activo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    /**
     * @return mixed
     */
    public function getIcTipoRequerimientoId()
    {
        return $this->ic_tipo_requerimiento_id;
    }

    /**
     * @param mixed $ic_tipo_requerimiento_id
     */
    public function setIcTipoRequerimientoId($ic_tipo_requerimiento_id)
    {
        $this->ic_tipo_requerimiento_id = $ic_tipo_requerimiento_id;
    }

    /**
     * @return mixed
     */
    public function getIcRequerimientoId()
    {
        return $this->ic_requerimiento_id;
    }

    /**
     * @param mixed $ic_requerimiento_id
     */
    public function setIcRequerimientoId($ic_requerimiento_id)
    {
        $this->ic_requerimiento_id = $ic_requerimiento_id;
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
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param mixed $observacion
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }


}