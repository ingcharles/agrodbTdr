<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 22:06
 */

class Evaluacion implements JsonSerializable
{
    private $ic_evaluacion_analisis_id;
    private $ic_analisis_muestra_id;
    private $ic_muestra_id;
    private $observacion;
    private $activo;
    private $ic_requerimiento_id;
    private $ic_tipo_requerimiento_id;
    private $ic_resultado_decision_id;

    /**
     * Evaluacion constructor.
     * @param $ic_evaluacion_analisis_id
     * @param $ic_analisis_muestra_id
     * @param $ic_muestra_id
     * @param $observacion
     * @param $activo
     * @param $ic_resultado_decision_id
     */
    public function __construct($ic_evaluacion_analisis_id, $ic_analisis_muestra_id, $ic_muestra_id, $observacion, $activo, $ic_resultado_decision_id)
    {
        $this->ic_evaluacion_analisis_id = $ic_evaluacion_analisis_id;
        $this->ic_analisis_muestra_id = $ic_analisis_muestra_id;
        $this->ic_muestra_id = $ic_muestra_id;
        $this->observacion = $observacion;
        $this->activo = $activo;
        $this->ic_resultado_decision_id = $ic_resultado_decision_id;
    }

    public function jsonSerialize() { return get_object_vars($this); }



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
    public function getIcResultadoDecisionId()
    {
        return $this->ic_resultado_decision_id;
    }

    /**
     * @param mixed $ic_resultado_decision_id
     */
    public function setIcResultadoDecisionId($ic_resultado_decision_id)
    {
        $this->ic_resultado_decision_id = $ic_resultado_decision_id;
    }

}