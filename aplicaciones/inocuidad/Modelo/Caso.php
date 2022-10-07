<?php
/**
 * Created by PhpStorm.
 * User: advance
 * Date: 2/1/18
 * Time: 9:48 PM
 */

class Caso implements JsonSerializable
{
private $id;
private $programaId;
private $fuenteId;
private $productoId;
private $paisId;
private $provinciaId;
private $inspectorId;
private $origenMercaderiaId;
private $tipoRequerimientoId;
private $fechaSolicitud;
private $nombreDenunciante;
private $datosDenunciante;
private $descripcionDenuncia;
private $observacion;
private $numeroMuestras;
private $nombrePrograma;
private $nombreProducto;
private $tipoRequerimiento;
private $fechaInspeccion;
private $fechaDenuncia;
private $fechaNotificacion;
private $id_grupo;
private $usuario_id;

    public function jsonSerialize() { return get_object_vars($this); }

    /**
     * Caso constructor.
     * @param $id
     * @param $programaId
     * @param $fuenteId
     * @param $productoId
     * @param $paisId
     * @param $provinciaId
     * @param $inspectorId
     * @param $origenMercaderiaId
     * @param $tipoRequerimientoId
     * @param $fechaSolicitud
     * @param $nombreDenunciante
     * @param $datosDenunciante
     * @param $descripcionDenuncia
     * @param $observacion
     * @param $numeroMuestras
     * @param $nombrePrograma
     * @param $nombreProducto
     * @param $tipoRequerimiento
     */
    public function __construct($id, $programaId, $fuenteId, $productoId, $paisId, $provinciaId, $inspectorId, $origenMercaderiaId, $tipoRequerimientoId, $fechaSolicitud, $nombreDenunciante, $datosDenunciante, $descripcionDenuncia, $observacion, $numeroMuestras, $nombrePrograma, $nombreProducto, $tipoRequerimiento)
    {
        $this->id = $id;
        $this->programaId = $programaId;
        $this->fuenteId = $fuenteId;
        $this->productoId = $productoId;
        $this->paisId = $paisId;
        $this->provinciaId = $provinciaId;
        $this->inspectorId = $inspectorId;
        $this->origenMercaderiaId = $origenMercaderiaId;
        $this->tipoRequerimientoId = $tipoRequerimientoId;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->nombreDenunciante = $nombreDenunciante;
        $this->datosDenunciante = $datosDenunciante;
        $this->descripcionDenuncia = $descripcionDenuncia;
        $this->observacion = $observacion;
        $this->numeroMuestras = $numeroMuestras;
        if($nombrePrograma!=null)
        $this->nombrePrograma = $nombrePrograma;
        if($nombreProducto!=null)
        $this->nombreProducto = $nombreProducto;
        if($tipoRequerimiento!=null)
        $this->tipoRequerimiento = $tipoRequerimiento;
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
    public function getProgramaId()
    {
        if(!isset($this->programaId)){
            return 0;
        }
        return $this->programaId;
    }

    /**
     * @param mixed $programaId
     */
    public function setProgramaId($programaId)
    {
        $this->programaId = $programaId;
    }

    /**
     * @return mixed
     */
    public function getFuenteId()
    {
        if(!isset($this->fuenteId)){
            return 0;
        }
        return $this->fuenteId;
    }

    /**
     * @param mixed $fuenteId
     */
    public function setFuenteId($fuenteId)
    {
        $this->fuenteId = $fuenteId;
    }

    /**
     * @return mixed
     */
    public function getProductoId()
    {
        if(!isset($this->productoId)){
            return 0;
        }
        return $this->productoId;
    }

    /**
     * @param mixed $productoId
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * @return mixed
     */
    public function getPaisId()
    {
        if(!isset($this->paisId)){
            return 0;
        }
        return $this->paisId;
    }

    /**
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {

        $this->paisId = $paisId;
    }

    /**
     * @return mixed
     */
    public function getProvinciaId()
    {
        if(!isset($this->provinciaId)){
        return 0;
        }
        return $this->provinciaId;
    }

    /**
     * @param mixed $provinciaId
     */
    public function setProvinciaId($provinciaId)
    {
        $this->provinciaId = $provinciaId;
    }

    /**
     * @return mixed
     */
    public function getInspectorId()
    {
        if(!isset($this->inspectorId)){
            return 0;
        }
        return $this->inspectorId;
    }

    /**
     * @param mixed $inspectorId
     */
    public function setInspectorId($inspectorId)
    {
        $this->inspectorId = $inspectorId;
    }

    /**
     * @return mixed
     */
    public function getOrigenMercaderiaId()
    {
        if(!isset($this->origenMercaderiaId)) {
            return 0;
        }
        return $this->origenMercaderiaId;
    }

    /**
     * @param mixed $origenMercaderiaId
     */
    public function setOrigenMercaderiaId($origenMercaderiaId)
    {
        $this->origenMercaderiaId = $origenMercaderiaId;
    }

    /**
     * @return mixed
     */
    public function getTipoRequerimientoId()
    {
        if(!isset($this->tipoRequerimientoId)){
            return 0;
        }
        return $this->tipoRequerimientoId;
    }

    /**
     * @param mixed $tipoRequerimientoId
     */
    public function setTipoRequerimientoId($tipoRequerimientoId)
    {
        $this->tipoRequerimientoId = $tipoRequerimientoId;
    }

    /**
     * @return mixed
     */
    public function getFechaSolicitud()
    {
        if(!isset($this->fechaSolicitud)){
            return new DateTime();
        }
        return $this->fechaSolicitud;
    }

    /**
     * @param mixed $fechaSolicitud
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;
    }

    /**
     * @return mixed
     */
    public function getNombreDenunciante()
    {
        if(!isset($this->nombreDenunciante)){
            return '';
        }
        return $this->nombreDenunciante;
    }

    /**
     * @param mixed $nombreDenunciante
     */
    public function setNombreDenunciante($nombreDenunciante)
    {
        $this->nombreDenunciante = $nombreDenunciante;
    }

    /**
     * @return mixed
     */
    public function getDatosDenunciante()
    {
        if(!isset($this->datosDenunciante)){
            return '';
        }
        return $this->datosDenunciante;
    }

    /**
     * @param mixed $datosDenunciante
     */
    public function setDatosDenunciante($datosDenunciante)
    {
        $this->datosDenunciante = $datosDenunciante;
    }

    /**
     * @return mixed
     */
    public function getDescripcionDenuncia()
    {
        if(!isset($this->descripcionDenuncia)){
        return '';
    }

        return $this->descripcionDenuncia;
    }

    /**
     * @param mixed $descripcionDenuncia
     */
    public function setDescripcionDenuncia($descripcionDenuncia)
    {
        $this->descripcionDenuncia = $descripcionDenuncia;
    }

    /**
     * @return mixed
     */
    public function getObservacion()
    {
        if(!isset($this->observacion)){
            return '';
        }
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
    public function getNumeroMuestras()
    {
        if(!isset($this->numeroMuestras)){
            return 0;
        }
        return $this->numeroMuestras;
    }

    /**
     * @param mixed $numeroMuestras
     */
    public function setNumeroMuestras($numeroMuestras)
    {
        $this->numeroMuestras = $numeroMuestras;
    }

    /**
     * @return mixed
     */
    public function getNombrePrograma()
    {
        return $this->nombrePrograma;
    }

    /**
     * @param mixed $nombrePrograma
     */
    public function setNombrePrograma($nombrePrograma)
    {
        $this->nombrePrograma = $nombrePrograma;
    }

    /**
     * @return mixed
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * @param mixed $nombreProducto
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;
    }


    /**
     * @return mixed
     */
    public function getTipoRequerimiento()
    {
        return $this->tipoRequerimiento;
    }

    /**
     * @param mixed $tipoRequerimiento
     */
    public function setTipoRequerimiento($tipoRequerimiento)
    {
        $this->tipoRequerimiento = $tipoRequerimiento;
    }

    /**
     * @return mixed
     */
    public function getFechaInspeccion()
    {
        return $this->fechaInspeccion;
    }

    /**
     * @param mixed $fechaInspeccion
     */
    public function setFechaInspeccion($fechaInspeccion)
    {
        $this->fechaInspeccion = $fechaInspeccion;
    }

    /**
     * @return mixed
     */
    public function getFechaDenuncia()
    {
        return $this->fechaDenuncia;
    }

    /**
     * @param mixed $fechaDenuncia
     */
    public function setFechaDenuncia($fechaDenuncia)
    {
        $this->fechaDenuncia = $fechaDenuncia;
    }

    /**
     * @return mixed
     */
    public function getFechaNotificacion()
    {
        return $this->fechaNotificacion;
    }

    /**
     * @param mixed $fechaNotificacion
     */
    public function setFechaNotificacion($fechaNotificacion)
    {
        $this->fechaNotificacion = $fechaNotificacion;
    }

    /**
     * @return mixed
     */
    public function getIdGrupo()
    {
        return $this->id_grupo;
    }

    /**
     * @param mixed $id_grupo
     */
    public function setIdGrupo($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    /**
     * @param mixed $usuario_id
     */
    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }


}