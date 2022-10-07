<?php

/**
 * Modelo SolicitudCabeceraModelo
 *
 * Este archivo se complementa con el archivo   SolicitudCabeceraLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       SolicitudCabeceraModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudCabeceraModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSolicitudCabecera;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idBodega;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratoriosProvincia;
    
    protected $idLaboratoriosProvinciaOrigen;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaSolicitud;

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
    protected $estado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $observacion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratorio;
    
    /**
     * Clase hija
     *
     * @var LaboratoriosProvincia
     */
    protected $laboratoriosProvincia;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_reactivos";

    /**
     * Nombre de la tabla: solicitud_cabecera
     * 
     */
    Private $tabla = "solicitud_cabecera";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_solicitud_cabecera";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."solicitud_cabecera_id_solicitud_cabecera_seq';

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
            throw new \Exception('Clase Modelo: SolicitudCabeceraModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SolicitudCabeceraModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSolicitudCabecera
     *
     *
     *
     * @parámetro Integer $idSolicitudCabecera
     * @return IdSolicitudCabecera
     */
    public function setIdSolicitudCabecera($idSolicitudCabecera)
    {
        $this->idSolicitudCabecera = (Integer) $idSolicitudCabecera;
        return $this;
    }

    /**
     * Get idSolicitudCabecera
     *
     * @return null|Integer
     */
    public function getIdSolicitudCabecera()
    {
        return $this->idSolicitudCabecera;
    }

    /**
     * Set idSolicitudCabecera
     *
     * @parámetro Integer $idBodega
     * @return IdBodega
     */
    public function setIdBodega($idBodega)
    {
        $this->idBodega = (Integer) $idBodega;
        return $this;
    }

    /**
     * Get idBodega
     *
     * @return null|Integer
     */
    public function getIdBodega()
    {
        return $this->idBodega;
    }

    /**
     * Set idSolicitudCabecera
     *
     * @parámetro Integer $idBodega
     * @return IdBodega
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia)
    {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
    }

    /**
     * Get idBodega
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvincia()
    {
        return $this->idLaboratoriosProvincia;
    }
    
    /**
     * Set idSolicitudCabecera
     *
     * @parámetro Integer $idBodega
     * @return IdBodega
     */
    public function setIdLaboratoriosProvinciaOrigen($idLaboratoriosProvinciaOrigen)
    {
        $this->idLaboratoriosProvinciaOrigen = (Integer) $idLaboratoriosProvinciaOrigen;
        return $this;
    }

    /**
     * Get idBodega
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvinciaOrigen()
    {
        return $this->idLaboratoriosProvinciaOrigen;
    }

    /**
     * Set fechaSolicitud
     *
     *
     *
     * @parámetro Date $fechaSolicitud
     * @return FechaSolicitud
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = ValidarDatos::validarFecha($fechaSolicitud, $this->tabla, " Fecha de Solicitud", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return null|Date
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
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
        $this->codigo = ValidarDatos::validarAlfa($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 32);
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
     * Set codigo
     *
     *
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigo 
     *
     * @return null|String
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set observacion
     *
     *
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = ValidarDatos::validarAlfa($observacion, $this->tabla, " Observación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set idLaboratorio
     *
     *
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }
    
    /**
     * Llena los datos de la tabla muestras
     *
     * @param Array $datos
     *
     */
    public function setLaboratoriosProvincia($datos)
    {
        if (is_array($datos))
        {
            $this->laboratoriosProvincia = new \Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaModelo($datos);
        } else
        {
            $this->laboratoriosProvincia = $datos;
        }
    }

    /**
     * Retorna el modelo de la tabla muestras
     *
     * @return \Agrodb\Laboratorios\Modelos\Muestras
     */
    public function getLaboratoriosProvincia()
    {
        if (null === $this->laboratoriosProvincia)
        {
            $this->laboratoriosProvincia = new \Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaModelo();
        }
        return $this->laboratoriosProvincia;
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
     * @return SolicitudCabeceraModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
