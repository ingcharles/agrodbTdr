<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 19/02/18
 * Time: 11:12
 */

class Dashboard
{
    private $nombre_programa;
    private $nombre_tipo_requerimiento;
    private $fecha_solicitud;
    private $estado;
    private $usuario;
    private $ic_requerimiento_id;
    private $ic_muestra_id;
    private $ic_analisis_muestra_id;
    private $ic_evaluacion_analisis_id;
    private $ic_evaluacion_comite_id;
    private $cancelado;
    private $motivo_cancelacion;
    private $provincia;

    /**
     * Dashboard constructor.
     * @param $nombre_programa
     * @param $nombre_tipo_requerimiento
     * @param $fecha_solicitud
     * @param $estado
     * @param $usuario
     * @param $ic_requerimiento_id
     * @param $ic_muestra_id
     * @param $ic_analisis_muestra_id
     * @param $ic_evaluacion_analisis_id
     * @param $ic_evaluacion_comite_id
     * @param $cancelado
     * @param $motivo_cancelacion
     * @param $provincia
     */
    public function __construct($nombre_programa, $nombre_tipo_requerimiento, $fecha_solicitud, $estado, $usuario, $ic_requerimiento_id, $ic_muestra_id, $ic_analisis_muestra_id, $ic_evaluacion_analisis_id, $ic_evaluacion_comite_id, $cancelado, $motivo_cancelacion, $provincia)
    {
        $this->nombre_programa = $nombre_programa;
        $this->nombre_tipo_requerimiento = $nombre_tipo_requerimiento;
        $this->fecha_solicitud = $fecha_solicitud;
        $this->estado = $estado;
        $this->usuario = $usuario;
        $this->ic_requerimiento_id = $ic_requerimiento_id;
        $this->ic_muestra_id = $ic_muestra_id;
        $this->ic_analisis_muestra_id = $ic_analisis_muestra_id;
        $this->ic_evaluacion_analisis_id = $ic_evaluacion_analisis_id;
        $this->ic_evaluacion_comite_id = $ic_evaluacion_comite_id;
        $this->cancelado = $cancelado;
        $this->motivo_cancelacion = $motivo_cancelacion;
        $this->provincia = $provincia;
    }

    /**
     * @return mixed
     */
    public function getNombrePrograma()
    {
        return $this->nombre_programa;
    }

    /**
     * @param mixed $nombre_programa
     */
    public function setNombrePrograma($nombre_programa)
    {
        $this->nombre_programa = $nombre_programa;
    }

    /**
     * @return mixed
     */
    public function getNombreTipoRequerimiento()
    {
        return $this->nombre_tipo_requerimiento;
    }

    /**
     * @param mixed $nombre_tipo_requerimiento
     */
    public function setNombreTipoRequerimiento($nombre_tipo_requerimiento)
    {
        $this->nombre_tipo_requerimiento = $nombre_tipo_requerimiento;
    }

    /**
     * @return mixed
     */
    public function getFechaSolicitud()
    {
        return $this->fecha_solicitud;
    }

    /**
     * @param mixed $fecha_solicitud
     */
    public function setFechaSolicitud($fecha_solicitud)
    {
        $this->fecha_solicitud = $fecha_solicitud;
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
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * @param mixed $cancelado
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
    }

    /**
     * @return mixed
     */
    public function getMotivoCancelacion()
    {
        return $this->motivo_cancelacion;
    }

    /**
     * @param mixed $motivo_cancelacion
     */
    public function setMotivoCancelacion($motivo_cancelacion)
    {
        $this->motivo_cancelacion = $motivo_cancelacion;
    }

    /**
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * @param mixed $provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
    }

}