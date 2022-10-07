<?php

/**
 * Modelo SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       SolicitudesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudesModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSolicitud;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idPersona;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLocalizacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $identificador;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idDistribucionMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $oficioExoneracion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $numMuestrasExoneradas;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $tipoSolicitud;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idProcesoExterno;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $moduloExterno;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaEnvio;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaRecepcion;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaFinalEstimada;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaFinalReal;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $muestreoNacional;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $estado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $exoneracion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $usuarioGuia;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $numFactura;

    /**
     * Campos del formulario 
     * @var array 
     */
    Private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: solicitudes
     * 
     */
    Private $tabla = "solicitudes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_solicitud";

    /**
     * Secuencia
     */
    private $secuencial = "";

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos))
        {
            $this->setOptions($datos);
        }
        $features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
        parent::__construct($this->esquema, $this->tabla, $features);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parámetro  string $name 
     * @parámetro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parámetro  string $name 
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parámetro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
            $key_original = $key;
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
                $this->$method($value);
                $this->campos[$key_original] = $key;
            }
        }
        return $this;
    }

    /**
     * Recupera los datos validados del modelo y lo retorna en un arreglo
     *  
     * @return Array  
     */
    public function getPrepararDatos()
    {
        $claseArray = get_object_vars($this);
        foreach ($this->campos as $key => $value)
        {
            $this->campos[$key] = $claseArray[lcfirst($value)];
        }
        return $this->campos;
    }

    /**
     * Set $esquema
     *
     * Nombre del esquema del módulo 
     *
     * @parámetro $esquema
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSolicitud
     *
     *
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = (Integer) $idSolicitud;
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
    }

    /**
     * Set idPersona
     *
     *
     *
     * @parámetro Integer $idPersona
     * @return IdPersona
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = (Integer) $idPersona;
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return null|Integer
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set idLocalizacion
     *
     *
     *
     * @parámetro Integer $idLocalizacion
     * @return IdLocalizacion
     */
    public function setIdLocalizacion($idLocalizacion)
    {
        $this->idLocalizacion = (Integer) $idLocalizacion;
        return $this;
    }

    /**
     * Get idLocalizacion
     *
     * @return null|Integer
     */
    public function getIdLocalizacion()
    {
        return $this->idLocalizacion;
    }

    /**
     * Set identificador
     *
     *
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (String) $identificador;
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set idDistribucionMuestra
     *
     *
     *
     * @parámetro Integer $idDistribucionMuestra
     * @return IdDistribucionMuestra
     */
    public function setIdDistribucionMuestra($idDistribucionMuestra)
    {
        $this->idDistribucionMuestra = (Integer) $idDistribucionMuestra;
        return $this;
    }

    /**
     * Get idDistribucionMuestra
     *
     * @return null|Integer
     */
    public function getIdDistribucionMuestra()
    {
        return $this->idDistribucionMuestra;
    }

    /**
     * Set codigo
     *
     *
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = (String) $codigo;
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set oficioExoneracion
     *
     *
     *
     * @parámetro String $oficioExoneracion
     * @return OficioExoneracion
     */
    public function setOficioExoneracion($oficioExoneracion)
    {
        $this->oficioExoneracion = (String) $oficioExoneracion;
        return $this;
    }

    /**
     * Get oficioExoneracion
     *
     * @return null|String
     */
    public function getOficioExoneracion()
    {
        return $this->oficioExoneracion;
    }

    /**
     * Set numMuestrasExoneradas
     *
     *
     *
     * @parámetro Integer $numMuestrasExoneradas
     * @return NumMuestrasExoneradas
     */
    public function setNumMuestrasExoneradas($numMuestrasExoneradas)
    {
        $this->numMuestrasExoneradas = (Integer) $numMuestrasExoneradas;
        return $this;
    }

    /**
     * Get numMuestrasExoneradas
     *
     * @return null|Integer
     */
    public function getNumMuestrasExoneradas()
    {
        return $this->numMuestrasExoneradas;
    }

    /**
     * Set tipoSolicitud
     *
     *
     *
     * @parámetro String $tipoSolicitud
     * @return TipoSolicitud
     */
    public function setTipoSolicitud($tipoSolicitud)
    {
        $this->tipoSolicitud = (String) $tipoSolicitud;
        return $this;
    }

    /**
     * Get tipoSolicitud
     *
     * @return null|String
     */
    public function getTipoSolicitud()
    {
        return $this->tipoSolicitud;
    }

    /**
     * Set idProcesoExterno
     *
     *
     *
     * @parámetro Integer $idProcesoExterno
     * @return IdProcesoExterno
     */
    public function setIdProcesoExterno($idProcesoExterno)
    {
        $this->idProcesoExterno = (Integer) $idProcesoExterno;
        return $this;
    }

    /**
     * Get idProcesoExterno
     *
     * @return null|Integer
     */
    public function getIdProcesoExterno()
    {
        return $this->idProcesoExterno;
    }

    /**
     * Set moduloExterno
     *
     *
     *
     * @parámetro String $moduloExterno
     * @return ModuloExterno
     */
    public function setModuloExterno($moduloExterno)
    {
        $this->moduloExterno = (String) $moduloExterno;
        return $this;
    }

    /**
     * Get moduloExterno
     *
     * @return null|String
     */
    public function getModuloExterno()
    {
        return $this->moduloExterno;
    }

    /**
     * Set fechaEnvio
     *
     *
     *
     * @parámetro Date $fechaEnvio
     * @return FechaEnvio
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = (String) $fechaEnvio;
        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return null|Date
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set fechaRecepcion
     *
     *
     *
     * @parámetro Date $fechaRecepcion
     * @return FechaRecepcion
     */
    public function setFechaRecepcion($fechaRecepcion)
    {
        $this->fechaRecepcion = (String) $fechaRecepcion;
        return $this;
    }

    /**
     * Get fechaRecepcion
     *
     * @return null|Date
     */
    public function getFechaRecepcion()
    {
        return $this->fechaRecepcion;
    }

    /**
     * Set fechaFinalEstimada
     *
     *
     *
     * @parámetro Date $fechaFinalEstimada
     * @return FechaFinalEstimada
     */
    public function setFechaFinalEstimada($fechaFinalEstimada)
    {
        $this->fechaFinalEstimada = (String) $fechaFinalEstimada;
        return $this;
    }

    /**
     * Get fechaFinalEstimada
     *
     * @return null|Date
     */
    public function getFechaFinalEstimada()
    {
        return $this->fechaFinalEstimada;
    }

    /**
     * Set fechaFinalReal
     *
     *
     *
     * @parámetro Date $fechaFinalReal
     * @return FechaFinalReal
     */
    public function setFechaFinalReal($fechaFinalReal)
    {
        $this->fechaFinalReal = (String) $fechaFinalReal;
        return $this;
    }

    /**
     * Get fechaFinalReal
     *
     * @return null|Date
     */
    public function getFechaFinalReal()
    {
        return $this->fechaFinalReal;
    }

    /**
     * Set fechaRegistro
     *
     *
     *
     * @parámetro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = (String) $fechaRegistro;
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return null|Date
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set muestreoNacional
     *
     *
     *
     * @parámetro String $muestreoNacional
     * @return MuestreoNacional
     */
    public function setMuestreoNacional($muestreoNacional)
    {
        $this->muestreoNacional = (String) $muestreoNacional;
        return $this;
    }

    /**
     * Get muestreoNacional
     *
     * @return null|String
     */
    public function getMuestreoNacional()
    {
        return $this->muestreoNacional;
    }

    /**
     * Set estado
     *
     *
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (String) $estado;
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set exoneracion
     *
     *
     *
     * @parámetro String $exoneracion
     * @return Exoneracion
     */
    public function setExoneracion($exoneracion)
    {
        $this->exoneracion = (String) $exoneracion;
        return $this;
    }

    /**
     * Get exoneracion
     *
     * @return null|String
     */
    public function getExoneracion()
    {
        return $this->exoneracion;
    }

    /**
     * Set usuarioGuia
     *
     *
     *
     * @parámetro String $usuarioGuia
     * @return UsuarioGuia
     */
    public function setUsuarioGuia($usuarioGuia)
    {
        $this->usuarioGuia = (String) $usuarioGuia;
        return $this;
    }

    /**
     * Get usuarioGuia
     *
     * @return null|String
     */
    public function getUsuarioGuia()
    {
        return $this->usuarioGuia;
    }

    /**
     * Set numFactura
     *
     *
     *
     * @parámetro String $numFactura
     * @return NumFactura
     */
    public function setNumFactura($numFactura)
    {
        $this->numFactura = (String) $numFactura;
        return $this;
    }

    /**
     * Get numFactura
     *
     * @return null|String
     */
    public function getNumFactura()
    {
        return $this->numFactura;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(Array $datos, $id)
    {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        return parent::borrar($this->clavePrimaria . " = " . $id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return SolicitudesModelo
     */
    public function buscar($id)
    {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
        return $this;
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return parent::buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return parent::buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function ejecutarConsulta($consulta)
    {
        return parent::ejecutarConsulta($consulta);
    }

}
