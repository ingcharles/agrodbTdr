<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 06/02/18
 * Time: 8:55
 */

class ProductoInsumo
{
    private $ic_producto_insumo_id;
    private $ic_producto_id;
    private $ic_insumo_id;
    private $ic_lmr_id;
    private $um;
    private $limite_minimo;
    private $limite_maximo;

    /**
     * ProductoInsumo constructor.
     * @param $ic_producto_insumo_id
     * @param $ic_producto_id
     * @param $ic_insumo_id
     * @param $ic_lmr_id
     * @param $um
     * @param $limite_minimo
     * @param $limite_maximo
     */
    public function __construct($ic_producto_insumo_id, $ic_producto_id, $ic_insumo_id, $ic_lmr_id, $um, $limite_minimo, $limite_maximo)
    {
        $this->ic_producto_insumo_id = $ic_producto_insumo_id;
        $this->ic_producto_id = $ic_producto_id;
        $this->ic_insumo_id = $ic_insumo_id;
        $this->ic_lmr_id = $ic_lmr_id;
        $this->um = $um;
        $this->limite_minimo = $limite_minimo;
        $this->limite_maximo = $limite_maximo;
    }

    /**
     * @return mixed
     */
    public function getIcProductoInsumoId()
    {
        return $this->ic_producto_insumo_id;
    }

    /**
     * @param mixed $ic_producto_insumo_id
     */
    public function setIcProductoInsumoId($ic_producto_insumo_id)
    {
        $this->ic_producto_insumo_id = $ic_producto_insumo_id;
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

    /**
     * @return mixed
     */
    public function getLimiteMinimo()
    {
        return $this->limite_minimo;
    }

    /**
     * @param mixed $limite_minimo
     */
    public function setLimiteMinimo($limite_minimo)
    {
        $this->limite_minimo = $limite_minimo;
    }

    /**
     * @return mixed
     */
    public function getLimiteMaximo()
    {
        return $this->limite_maximo;
    }

    /**
     * @param mixed $limite_maximo
     */
    public function setLimiteMaximo($limite_maximo)
    {
        $this->limite_maximo = $limite_maximo;
    }

}