<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 23/02/18
 * Time: 8:20
 */

class Comite implements JsonSerializable
{
    private $ic_evaluacion_comite_id    ;
    private $ic_evaluacion_analisis_id  ;
    private $observacion                ;
    private $estado                     ;
    private $ic_requerimiento_id        ;
    private $ic_tipo_requerimiento_id   ;
    private $ic_analisis_muestra_id     ;

    public function jsonSerialize() { return get_object_vars($this); }

    /**
     * Comite constructor.
     * @param $ic_evaluacion_comite_id
     * @param $ic_evaluacion_analisis_id
     * @param $observacion
     * @param $estado
     */
    public function __construct($ic_evaluacion_comite_id, $ic_evaluacion_analisis_id, $observacion, $estado)
    {
        $this->ic_evaluacion_comite_id = $ic_evaluacion_comite_id;
        $this->ic_evaluacion_analisis_id = $ic_evaluacion_analisis_id;
        $this->observacion = $observacion;
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getIcEvaluacionComiteId()
    {
        return $this->ic_evaluacion_comite_id;
    }

    /**
     * @param mixed $ic_evaluacion_comite_id
     */
    public function setIcEvaluacionComiteId($ic_evaluacion_comite_id)
    {
        $this->ic_evaluacion_comite_id = $ic_evaluacion_comite_id;
    }

    /**
     * @return mixed
     */
    public function getIcEvaluacionAnalisisId()
    {
        return $this->ic_evaluacion_analisis_id;
    }

    /**
     * @param mixed $ic_evaluacion_analisis_id
     */
    public function setIcEvaluacionAnalisisId($ic_evaluacion_analisis_id)
    {
        $this->ic_evaluacion_analisis_id = $ic_evaluacion_analisis_id;
    }

    /**
     * @return mixed
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * @param mixed $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
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

}