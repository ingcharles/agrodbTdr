<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 11:20
 */

class MuestraRapidaValor
{
    private $ic_muestra_rapida_id   ;
    private $ic_muestra_id ;
    private $ic_producto_id         ;
    private $ic_insumo_id           ;
    private $valor                  ;
    private $observaciones          ;
    private $um                     ;

    /**
     * MuestraRapidaValor constructor.
     * @param $ic_muestra_rapida_id
     * @param $ic_muestra_id
     * @param $ic_producto_id
     * @param $ic_insumo_id
     * @param $valor
     * @param $observaciones
     * @param $um
     */
    public function __construct($ic_muestra_rapida_id, $ic_muestra_id, $ic_producto_id, $ic_insumo_id, $valor, $observaciones, $um)
    {
        $this->ic_muestra_rapida_id = $ic_muestra_rapida_id;
        $this->ic_muestra_id = $ic_muestra_id;
        $this->ic_producto_id = $ic_producto_id;
        $this->ic_insumo_id = $ic_insumo_id;
        $this->valor = $valor;
        $this->observaciones = $observaciones;
        $this->um = $um;
    }


    /**
     * @return mixed
     */
    public function getIcMuestraRapidaId()
    {
        return $this->ic_muestra_rapida_id;
    }

    /**
     * @param mixed $ic_muestra_rapida_id
     */
    public function setIcMuestraRapidaId($ic_muestra_rapida_id)
    {
        $this->ic_muestra_rapida_id = $ic_muestra_rapida_id;
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
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param mixed $observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    /**
     * @return mixed
     */
    public function getUm()
    {
        return $this->um;
    }

    /**
     * @param mixed $um
     */
    public function setUm($um)
    {
        $this->um = $um;
    }
}