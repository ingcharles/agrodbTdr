<?php

/**
 * Modelo AuditoriaLabLogModelo
 *
 * Este archivo se complementa con el archivo   AuditoriaLabLogLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       AuditoriaLabLogModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AuditoriaLabLogModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificación de la tabla
     */
    protected $logId;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * OID es un identificador unico de cada objeto (llamese tabla,columna, tipo de dato etc)
     */
    protected $logRelid;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del esquema
     */
    protected $logSchema;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la tabla que se realizó la operación
     */
    protected $logTable;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del usuario de la base de datos
     */
    protected $logSessionUser;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha cuando ocurre el evento
     */
    protected $logWhen;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Ip del cliente de base de datos
     */
    protected $logClientAddr;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de operación
     */
    protected $logOperation;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valores antiguos
     */
    protected $logOldValues;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valores nuevos
     */
    protected $logNewValues;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Todos los valores antiguos
     */
    protected $logOldAll;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Todos los valores nuevos
     */
    protected $logNewAll;

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
     * Nombre de la tabla: auditoria_lab_log
     * 
     */
    Private $tabla = "auditoria_lab_log";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "log_id";

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
            throw new \Exception('Clase Modelo: AuditoriaLabLogModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: AuditoriaLabLogModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set logId
     *
     * Identificación de la tabla
     *
     * @parámetro Integer $logId
     * @return LogId
     */
    public function setLogId($logId) {
        $this->logId = (Integer) $logId;
        return $this;
    }

    /**
     * Get logId
     *
     * @return null|Integer
     */
    public function getLogId() {
        return $this->logId;
    }

    /**
     * Set logRelid
     *
     * OID es un identificador unico de cada objeto (llamese tabla,columna, tipo de dato etc)
     *
     * @parámetro String $logRelid
     * @return LogRelid
     */
    public function setLogRelid($logRelid) {
        $this->logRelid = (String) $logRelid;
        return $this;
    }

    /**
     * Get logRelid
     *
     * @return null|String
     */
    public function getLogRelid() {
        return $this->logRelid;
    }

    /**
     * Set logSchema
     *
     * Nombre del esquema
     *
     * @parámetro String $logSchema
     * @return LogSchema
     */
    public function setLogSchema($logSchema) {
        $this->logSchema = (String) $logSchema;
        return $this;
    }

    /**
     * Get logSchema
     *
     * @return null|String
     */
    public function getLogSchema() {
        return $this->logSchema;
    }

    /**
     * Set logTable
     *
     * Nombre de la tabla que se realizó la operación
     *
     * @parámetro String $logTable
     * @return LogTable
     */
    public function setLogTable($logTable) {
        $this->logTable = (String) $logTable;
        return $this;
    }

    /**
     * Get logTable
     *
     * @return null|String
     */
    public function getLogTable() {
        return $this->logTable;
    }

    /**
     * Set logSessionUser
     *
     * Nombre del usuario de la base de datos
     *
     * @parámetro String $logSessionUser
     * @return LogSessionUser
     */
    public function setLogSessionUser($logSessionUser) {
        $this->logSessionUser = (String) $logSessionUser;
        return $this;
    }

    /**
     * Get logSessionUser
     *
     * @return null|String
     */
    public function getLogSessionUser() {
        return $this->logSessionUser;
    }

    /**
     * Set logWhen
     *
     * Fecha cuando ocurre el evento
     *
     * @parámetro Date $logWhen
     * @return LogWhen
     */
    public function setLogWhen($logWhen) {
        $this->logWhen = (String) $logWhen;
        return $this;
    }

    /**
     * Get logWhen
     *
     * @return null|Date
     */
    public function getLogWhen() {
        return $this->logWhen;
    }

    /**
     * Set logClientAddr
     *
     * Ip del cliente de base de datos
     *
     * @parámetro String $logClientAddr
     * @return LogClientAddr
     */
    public function setLogClientAddr($logClientAddr) {
        $this->logClientAddr = (String) $logClientAddr;
        return $this;
    }

    /**
     * Get logClientAddr
     *
     * @return null|String
     */
    public function getLogClientAddr() {
        return $this->logClientAddr;
    }

    /**
     * Set logOperation
     *
     * Tipo de operación
     *
     * @parámetro String $logOperation
     * @return LogOperation
     */
    public function setLogOperation($logOperation) {
        $this->logOperation = (String) $logOperation;
        return $this;
    }

    /**
     * Get logOperation
     *
     * @return null|String
     */
    public function getLogOperation() {
        return $this->logOperation;
    }

    /**
     * Set logOldValues
     *
     * Valores antiguos
     *
     * @parámetro String $logOldValues
     * @return LogOldValues
     */
    public function setLogOldValues($logOldValues) {
        $this->logOldValues = (String) $logOldValues;
        return $this;
    }

    /**
     * Get logOldValues
     *
     * @return null|String
     */
    public function getLogOldValues() {
        return $this->logOldValues;
    }

    /**
     * Set logNewValues
     *
     * Valores nuevos
     *
     * @parámetro String $logNewValues
     * @return LogNewValues
     */
    public function setLogNewValues($logNewValues) {
        $this->logNewValues = (String) $logNewValues;
        return $this;
    }

    /**
     * Get logNewValues
     *
     * @return null|String
     */
    public function getLogNewValues() {
        return $this->logNewValues;
    }

    /**
     * Set logOldAll
     *
     * Todos los valores antiguos
     *
     * @parámetro String $logOldAll
     * @return LogOldAll
     */
    public function setLogOldAll($logOldAll) {
        $this->logOldAll = (String) $logOldAll;
        return $this;
    }

    /**
     * Get logOldAll
     *
     * @return null|String
     */
    public function getLogOldAll() {
        return $this->logOldAll;
    }

    /**
     * Set logNewAll
     *
     * Todos los valores nuevos
     *
     * @parámetro String $logNewAll
     * @return LogNewAll
     */
    public function setLogNewAll($logNewAll) {
        $this->logNewAll = (String) $logNewAll;
        return $this;
    }

    /**
     * Get logNewAll
     *
     * @return null|String
     */
    public function getLogNewAll() {
        return $this->logNewAll;
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
     * @return AuditoriaLabLogModelo
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
