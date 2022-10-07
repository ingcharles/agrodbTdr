<?php

/**
 * Modelo SolicitudRequerimientoModelo
 *
 * Este archivo se complementa con el archivo   SolicitudRequerimientoLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       SolicitudRequerimientoModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use Agrodb\Core\Constantes;

class SolicitudRequerimientoModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSolicitudRequerimiento;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $codigo;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidadSolicitada;

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
    protected $observacion;

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
     * Nombre de la tabla: solicitud_requerimiento
     * 
     */
    Private $tabla = "solicitud_requerimiento";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_solicitud_requerimiento";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."solicitud_requerimiento_id_solicitud_requerimiento_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null) {
        if (is_array($datos)) {
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
    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: SolicitudRequerimientoModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parámetro  string $name 
     * @retorna mixed
     */
    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: SolicitudRequerimientoModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parámetro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos) {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            $key_original = $key;
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
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
    public function getPrepararDatos() {
        $claseArray = get_object_vars($this);
        foreach ($this->campos as $key => $value) {
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
    public function setEsquema($esquema) {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set idSolicitudRequerimiento
     *
     *
     *
     * @parámetro Integer $idSolicitudRequerimiento
     * @return IdSolicitudRequerimiento
     */
    public function setIdSolicitudRequerimiento($idSolicitudRequerimiento) {
        $this->idSolicitudRequerimiento = (Integer) $idSolicitudRequerimiento;
        return $this;
    }

    /**
     * Get idSolicitudRequerimiento
     *
     * @return null|Integer
     */
    public function getIdSolicitudRequerimiento() {
        return $this->idSolicitudRequerimiento;
    }

    /**
     * Set idLaboratorio
     *
     *
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio) {
        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio() {
        return $this->idLaboratorio;
    }

    /**
     * Set idReactivoLaboratorio
     *
     *
     *
     * @parámetro Integer $idReactivoLaboratorio
     * @return IdReactivoLaboratorio
     */
    public function setIdReactivoLaboratorio($idReactivoLaboratorio) {
        $this->idReactivoLaboratorio = (Integer) $idReactivoLaboratorio;
        return $this;
    }

    /**
     * Get idReactivoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdReactivoLaboratorio() {
        return $this->idReactivoLaboratorio;
    }

    /**
     * Set codigo
     *
     *
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo) {
        $this->codigo = ValidarDatos::validarAlfa($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 8);
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set cantidadSolicitada
     *
     *
     *
     * @parámetro Decimal $cantidadSolicitada
     * @return CantidadSolicitada
     */
    public function setCantidadSolicitada($cantidadSolicitada) {
        $this->cantidadSolicitada = ValidarDatos::validarDecimal($cantidadSolicitada, $this->tabla, " Cantidad Solicitada", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidadSolicitada
     *
     * @return null|Decimal
     */
    public function getCantidadSolicitada() {
        return $this->cantidadSolicitada;
    }

    /**
     * Set fechaSolicitud
     *
     *
     *
     * @parámetro Date $fechaSolicitud
     * @return FechaSolicitud
     */
    public function setFechaSolicitud($fechaSolicitud) {
        $this->fechaSolicitud = ValidarDatos::validarFecha($fechaSolicitud, $this->tabla, " Fecha Solicitud", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return null|Date
     */
    public function getFechaSolicitud() {
        return $this->fechaSolicitud;
    }

    /**
     * Set observacion
     *
     *
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion) {
        $this->observacion = ValidarDatos::validarAlfa($observacion, $this->tabla, " Observación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion() {
        return $this->observacion;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos) {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(Array $datos, $id) {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id) {
        return parent::borrar($this->clavePrimaria . " = " . $id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return SolicitudRequerimientoModelo
     */
    public function buscar($id) {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
        return $this;
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo() {
        return parent::buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null) {
        return parent::buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function ejecutarConsulta($consulta) {
        return parent::ejecutarConsulta($consulta);
    }

}
