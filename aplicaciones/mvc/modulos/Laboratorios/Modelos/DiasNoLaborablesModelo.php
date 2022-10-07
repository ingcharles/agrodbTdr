<?php

/**
 * Modelo DiasNoLaborablesModelo
 *
 * Este archivo se complementa con el archivo   DiasNoLaborablesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DiasNoLaborablesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;


class DiasNoLaborablesModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de dias no laborables
     */
    protected $idDiasNoLaborables;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de laboratorio
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * AÃ±o
     */
    protected $anio;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha
     */
    protected $fecha;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Descripcion
     */
    protected $descripcion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado
     */
    protected $estado;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de direccion
     */
    protected $idDireccion;

    /**
     *  Campo requerido
     * Indica si aplica para todos los laboratorios o individual
     * @var type String
     */
    protected $alcance;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: dias_no_laborables
     * 
     */
    Private $tabla = "dias_no_laborables";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_dias_no_laborables";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."dias_no_laborables_id_dias_no_laborables_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
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
     * @parÃ¡metro  string $name 
     * @parÃ¡metro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: DiasNoLaborablesModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: DiasNoLaborablesModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parÃ¡metro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos) {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Set $esquema
     *
     * Nombre del esquema del mÃ³dulo 
     *
     * @parÃ¡metro $esquema
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
     * Set idDiasNoLaborables
     *
     * AÃ±o
     *
     * @parÃ¡metro Integer $idDiasNoLaborables
     * @return IdDiasNoLaborables
     */
    public function setIdDiasNoLaborables($idDiasNoLaborables) {
        if (empty($idDiasNoLaborables)) {
            $idDiasNoLaborables = "No informa";
        }
        $this->idDiasNoLaborables = (Integer) $idDiasNoLaborables;
        return $this;
    }

    /**
     * Get idDiasNoLaborables
     *
     * @return null|Integer
     */
    public function getIdDiasNoLaborables() {
        return $this->idDiasNoLaborables;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
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
     * Set anio
     *
     * AÃ±o
     *
     * @parÃ¡metro Integer $anio
     * @return Anio
     */
    public function setAnio($anio) {
        $this->anio = ValidarDatos::validarEntero($anio, $this->tabla, " Año", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get anio
     *
     * @return null|Integer
     */
    public function getAnio() {
        return $this->anio;
    }

    /**
     * Set fecha
     *
     * Indica la fecha que no es laborable
     *
     * @parÃ¡metro Date $fecha
     * @return Fecha
     */
    public function setFecha($fecha) {
        $this->fecha = ValidarDatos::validarFecha($fecha, $this->tabla, "Fecha no laborable", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fecha
     *
     * @return null|Date
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set descripcion
     *
     * Breve descripciÃ³n del dia no laborable
     *
     * @parÃ¡metro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = ValidarDatos::validarAlfa($descripcion, $this->tabla," Descripción", self::NO_REQUERIDO, 512);        
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return null|String
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * Estado del registro
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado) {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, "Estado", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set idDireccion
     *
     * Direccion de diagnÃ³stico
     *
     * @parÃ¡metro Integer $idDireccion
     * @return IdDireccion
     */
    public function setIdDireccion($idDireccion) {

        $this->idDireccion = (Integer) $idDireccion;
        return $this;
    }

    /**
     * Get idDireccion
     *
     * @return null|Integer
     */
    public function getIdDireccion() {
        return $this->idDireccion;
    }

    /**
     * Indica si aplica para todos los laboratorios o individual
     * @param string
     */
    function setAlcance($alcance) {
        $this->alcance = "";
        if ($alcance == "TODOS") {
            $this->alcance = "checked";
        }
        return $this;
    }

    /**
     * Indica si aplica para todos los laboratorios o individual
     * @return String
     */
    function getAlcance() {
        return $this->alcance;
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
     * @return DiasNoLaborablesModelo
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
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
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
