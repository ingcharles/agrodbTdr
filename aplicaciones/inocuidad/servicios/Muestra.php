<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 17/02/18
 * Time: 23:42
 */

class Muestra implements JsonSerializable
{
    private $ic_requerimiento_id;
    private $ic_muestra_id;
    private $fecha_muestreo;
    private $codigo_muestras;
    private $canton_id;
    private $parroquia_id;
    private $tipo_empresa;
    private $finca_id;
    private $utm_x;
    private $utm_y;
    private $registro_importador;
    private $permiso_fitosanitario;
    private $tecnico_id;
    private $ic_resultado_decision_id;
    private $activo;
    private $estado;
    private $provincia_id;
    private $origen_muestra_id;
    private $nombre_rep_legal;
    private $pais_procedencia_id;
    private $tipo_muestra_id;

    private $ic_tipo_requerimiento_id;

    //Laboratorio


    private $fecha_envio_lab;
    private $cantidad_muestras_lab;
    private $cantidad_contra_muestra;
    private $ultimo_insumo_aplicado_id;
    private $produccion_estimada;
    private $fecha_ultima_aplicacion;
    private $tecnica_muestreo;
    private $medio_refrigeracion;
    private $observaciones;


    public function jsonSerialize() { return get_object_vars($this); }


    /**
     * Muestra constructor.
     * @param $ic_requerimiento_id
     * @param $ic_muestra_id
     * @param $fecha_muestreo
     * @param $codigo_muestras
     * @param $canton_id
     * @param $parroquia_id
     * @param $tipo_empresa
     * @param $finca_id
     * @param $utm_x
     * @param $utm_y
     * @param $registro_importador
     * @param $permiso_fitosanitario
     * @param $tecnico_id
     * @param $ic_resultado_decision_id
     * @param $activo
     * @param $estado
     * @param $provincia_id
     * @param $origen_muestra_id
     * @param $nombre_rep_legal
     * @param $pais_procedencia_id
     * @param $tipo_muestra_id
     * @param $fecha_envio_lab
     * @param $cantidad_muestras_lab
     * @param $cantidad_contra_muestra
     * @param $ultimo_insumo_aplicado_id
     * @param $produccion_estimada
     * @param $fecha_ultima_aplicacion
     * @param $tecnica_muestreo
     * @param $medio_refrigeracion
     * @param $observaciones
     */
    public function __construct($ic_requerimiento_id, $ic_muestra_id, $fecha_muestreo, $codigo_muestras, $canton_id, $parroquia_id, $tipo_empresa, $finca_id, $utm_x, $utm_y, $registro_importador, $permiso_fitosanitario, $tecnico_id, $ic_resultado_decision_id, $activo, $estado, $provincia_id, $origen_muestra_id, $nombre_rep_legal, $pais_procedencia_id, $tipo_muestra_id, $fecha_envio_lab, $cantidad_muestras_lab, $cantidad_contra_muestra, $ultimo_insumo_aplicado_id, $produccion_estimada, $fecha_ultima_aplicacion, $tecnica_muestreo, $medio_refrigeracion, $observaciones)
    {
        $this->ic_requerimiento_id = $ic_requerimiento_id;
        $this->ic_muestra_id = $ic_muestra_id;
        $this->fecha_muestreo = $fecha_muestreo;
        $this->codigo_muestras = $codigo_muestras;
        $this->canton_id = $canton_id;
        $this->parroquia_id = $parroquia_id;
        $this->tipo_empresa = $tipo_empresa;
        $this->finca_id = $finca_id;
        $this->utm_x = $utm_x;
        $this->utm_y = $utm_y;
        $this->registro_importador = $registro_importador;
        $this->permiso_fitosanitario = $permiso_fitosanitario;
        $this->tecnico_id = $tecnico_id;
        $this->ic_resultado_decision_id = $ic_resultado_decision_id;
        $this->activo = $activo;
        $this->estado = $estado;
        $this->provincia_id = $provincia_id;
        $this->origen_muestra_id = $origen_muestra_id;
        $this->nombre_rep_legal = $nombre_rep_legal;
        $this->pais_procedencia_id = $pais_procedencia_id;
        $this->tipo_muestra_id = $tipo_muestra_id;
        $this->fecha_envio_lab = $fecha_envio_lab;
        $this->cantidad_muestras_lab = $cantidad_muestras_lab;
        $this->cantidad_contra_muestra = $cantidad_contra_muestra;
        $this->ultimo_insumo_aplicado_id = $ultimo_insumo_aplicado_id;
        $this->produccion_estimada = $produccion_estimada;
        $this->fecha_ultima_aplicacion = $fecha_ultima_aplicacion;
        $this->tecnica_muestreo = $tecnica_muestreo;
        $this->medio_refrigeracion = $medio_refrigeracion;
        $this->observaciones = $observaciones;
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
    public function getFechaMuestreo()
    {
        return $this->fecha_muestreo;
    }

    /**
     * @param mixed $fecha_muestreo
     */
    public function setFechaMuestreo($fecha_muestreo)
    {
        $this->fecha_muestreo = $fecha_muestreo;
    }

    /**
     * @return mixed
     */
    public function getCodigoMuestras()
    {
        return $this->codigo_muestras;
    }

    /**
     * @param mixed $codigo_muestras
     */
    public function setCodigoMuestras($codigo_muestras)
    {
        $this->codigo_muestras = $codigo_muestras;
    }

    /**
     * @return mixed
     */
    public function getCantonId()
    {
        return $this->canton_id;
    }

    /**
     * @param mixed $canton_id
     */
    public function setCantonId($canton_id)
    {
        $this->canton_id = $canton_id;
    }

    /**
     * @return mixed
     */
    public function getParroquiaId()
    {
        return $this->parroquia_id;
    }

    /**
     * @param mixed $parroquia_id
     */
    public function setParroquiaId($parroquia_id)
    {
        $this->parroquia_id = $parroquia_id;
    }

    /**
     * @return mixed
     */
    public function getTipoEmpresa()
    {
        return $this->tipo_empresa;
    }

    /**
     * @param mixed $tipo_empresa
     */
    public function setTipoEmpresa($tipo_empresa)
    {
        $this->tipo_empresa = $tipo_empresa;
    }

    /**
     * @return mixed
     */
    public function getFincaId()
    {
        return $this->finca_id;
    }

    /**
     * @param mixed $finca_id
     */
    public function setFincaId($finca_id)
    {
        $this->finca_id = $finca_id;
    }

    /**
     * @return mixed
     */
    public function getUtmX()
    {
        return $this->utm_x;
    }

    /**
     * @param mixed $utm_x
     */
    public function setUtmX($utm_x)
    {
        $this->utm_x = $utm_x;
    }

    /**
     * @return mixed
     */
    public function getUtmY()
    {
        return $this->utm_y;
    }

    /**
     * @param mixed $utm_y
     */
    public function setUtmY($utm_y)
    {
        $this->utm_y = $utm_y;
    }

    /**
     * @return mixed
     */
    public function getRegistroImportador()
    {
        return $this->registro_importador;
    }

    /**
     * @param mixed $registro_importador
     */
    public function setRegistroImportador($registro_importador)
    {
        $this->registro_importador = $registro_importador;
    }

    /**
     * @return mixed
     */
    public function getPermisoFitosanitario()
    {
        return $this->permiso_fitosanitario;
    }

    /**
     * @param mixed $permiso_fitosanitario
     */
    public function setPermisoFitosanitario($permiso_fitosanitario)
    {
        $this->permiso_fitosanitario = $permiso_fitosanitario;
    }

    /**
     * @return mixed
     */
    public function getTecnicoId()
    {
        return $this->tecnico_id;
    }

    /**
     * @param mixed $tecnico_id
     */
    public function setTecnicoId($tecnico_id)
    {
        $this->tecnico_id = $tecnico_id;
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
    public function getProvinciaId()
    {
        return $this->provincia_id;
    }

    /**
     * @param mixed $provincia_id
     */
    public function setProvinciaId($provincia_id)
    {
        $this->provincia_id = $provincia_id;
    }

    /**
     * @return mixed
     */
    public function getOrigenMuestraId()
    {
        return $this->origen_muestra_id;
    }

    /**
     * @param mixed $origen_muestra_id
     */
    public function setOrigenMuestraId($origen_muestra_id)
    {
        $this->origen_muestra_id = $origen_muestra_id;
    }

    /**
     * @return mixed
     */
    public function getNombreRepLegal()
    {
        return $this->nombre_rep_legal;
    }

    /**
     * @param mixed $nombre_rep_legal
     */
    public function setNombreRepLegal($nombre_rep_legal)
    {
        $this->nombre_rep_legal = $nombre_rep_legal;
    }

    /**
     * @return mixed
     */
    public function getPaisProcedenciaId()
    {
        return $this->pais_procedencia_id;
    }

    /**
     * @param mixed $pais_procedencia_id
     */
    public function setPaisProcedenciaId($pais_procedencia_id)
    {
        $this->pais_procedencia_id = $pais_procedencia_id;
    }

    /**
     * @return mixed
     */
    public function getTipoMuestraId()
    {
        return $this->tipo_muestra_id;
    }

    /**
     * @param mixed $tipo_muestra_id
     */
    public function setTipoMuestraId($tipo_muestra_id)
    {
        $this->tipo_muestra_id = $tipo_muestra_id;
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
    public function getFechaEnvioLab()
    {
        return $this->fecha_envio_lab;
    }

    /**
     * @param mixed $fecha_envio_lab
     */
    public function setFechaEnvioLab($fecha_envio_lab)
    {
        $this->fecha_envio_lab = $fecha_envio_lab;
    }

    /**
     * @return mixed
     */
    public function getCantidadMuestrasLab()
    {
        return $this->cantidad_muestras_lab;
    }

    /**
     * @param mixed $cantidad_muestras_lab
     */
    public function setCantidadMuestrasLab($cantidad_muestras_lab)
    {
        $this->cantidad_muestras_lab = $cantidad_muestras_lab;
    }

    /**
     * @return mixed
     */
    public function getCantidadContraMuestra()
    {
        return $this->cantidad_contra_muestra;
    }

    /**
     * @param mixed $cantidad_contra_muestra
     */
    public function setCantidadContraMuestra($cantidad_contra_muestra)
    {
        $this->cantidad_contra_muestra = $cantidad_contra_muestra;
    }

    /**
     * @return mixed
     */
    public function getUltimoInsumoAplicadoId()
    {
        return $this->ultimo_insumo_aplicado_id;
    }

    /**
     * @param mixed $ultimo_insumo_aplicado_id
     */
    public function setUltimoInsumoAplicadoId($ultimo_insumo_aplicado_id)
    {
        $this->ultimo_insumo_aplicado_id = $ultimo_insumo_aplicado_id;
    }

    /**
     * @return mixed
     */
    public function getProduccionEstimada()
    {
        return $this->produccion_estimada;
    }

    /**
     * @param mixed $produccion_estimada
     */
    public function setProduccionEstimada($produccion_estimada)
    {
        $this->produccion_estimada = $produccion_estimada;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimaAplicacion()
    {
        return $this->fecha_ultima_aplicacion;
    }

    /**
     * @param mixed $fecha_ultima_aplicacion
     */
    public function setFechaUltimaAplicacion($fecha_ultima_aplicacion)
    {
        $this->fecha_ultima_aplicacion = $fecha_ultima_aplicacion;
    }

    /**
     * @return mixed
     */
    public function getTecnicaMuestreo()
    {
        return $this->tecnica_muestreo;
    }

    /**
     * @param mixed $tecnica_muestreo
     */
    public function setTecnicaMuestreo($tecnica_muestreo)
    {
        $this->tecnica_muestreo = $tecnica_muestreo;
    }

    /**
     * @return mixed
     */
    public function getMedioRefrigeracion()
    {
        return $this->medio_refrigeracion;
    }

    /**
     * @param mixed $medio_refrigeracion
     */
    public function setMedioRefrigeracion($medio_refrigeracion)
    {
        $this->medio_refrigeracion = $medio_refrigeracion;
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


}