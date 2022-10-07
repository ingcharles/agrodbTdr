<?php

/**
 * Modelo RegistroManualModelo
 *
 * Este archivo se complementa con el archivo   RegistroManualLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       RegistroManualModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use Agrodb\Core\Constantes;

class RegistroManualModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idRegistroManual;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaInicio;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaFin;

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
     * Nombre de la tabla: registro_manual
     * 
     */
    Private $tabla = "registro_manual";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_registro_manual";

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
            throw new \Exception('Clase Modelo: RegistroManualModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: RegistroManualModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idRegistroManual
     *
     *
     *
     * @parámetro Integer $idRegistroManual
     * @return IdRegistroManual
     */
    public function setIdRegistroManual($idRegistroManual) {
        $this->idRegistroManual = (Integer) $idRegistroManual;
        return $this;
    }

    /**
     * Get idRegistroManual
     *
     * @return null|Integer
     */
    public function getIdRegistroManual() {
        return $this->idRegistroManual;
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
     * Set fechaInicio
     *
     *
     *
     * @parámetro Date $fechaInicio
     * @return FechaInicio
     */
    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = ValidarDatos::validarFecha($fechaInicio, $this->tabla, " Fecha Inicio", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return null|Date
     */
    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     *
     *
     * @parámetro Date $fechaFin
     * @return FechaFin 
     */
    public function setFechaFin($fechaFin) {
        $this->fechaFin = ValidarDatos::validarFecha($fechaFin, $this->tabla, " Fecha Fín", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return null|Date
     */
    public function getFechaFin() {
        return $this->fechaFin;
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
     * @return RegistroManualModelo
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
