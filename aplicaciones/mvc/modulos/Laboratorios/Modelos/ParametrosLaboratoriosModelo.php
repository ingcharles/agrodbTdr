<?php

/**
 * Modelo ParametrosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   ParametrosLaboratoriosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ParametrosLaboratoriosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;


class ParametrosLaboratoriosModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla parametros_servicio
     */
    protected $idParametrosLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $fkIdLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Dirección de diagnóstico
     */
    protected $idDireccion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Codigo del parametro, este es utilizado en la programacion por lo que una vez establecido no debe ser cambiado.
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre que identifique el parametro
     */
    protected $nombre;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Descripcion del parametro, de lo que hace o comportamiento dentro del sistema
     */
    protected $descripcion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor principal que toma el parametro
     */
    protected $valorAux1;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor auxiliar del parametro
     */
    protected $valorAux2;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor auxiliar del parametro
     */
    protected $valorAux3;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado que puede tomar un parametro
     */
    protected $estado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Se puede poner codigo auxiliar para ejecutar el parametro
     */
    protected $atributosExtras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Indica se el parametro es obligatorio
     */
    protected $obligatorio;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: parametros_laboratorios
     * 
     */
    Private $tabla = "parametros_laboratorios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_parametros_laboratorio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."parametros_laboratorios_id_parametros_laboratorio_seq';

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
            throw new \Exception('Clase Modelo: ParametrosLaboratoriosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ParametrosLaboratoriosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idParametrosLaboratorio
     *
     * Secuencial (PK) de la tabla parametros_servicio
     *
     * @parámetro Integer $idParametrosLaboratorio
     * @return IdParametrosLaboratorio
     */
    public function setIdParametrosLaboratorio($idParametrosLaboratorio) {
        $this->idParametrosLaboratorio = $idParametrosLaboratorio;
        return $this;
    }

    /**
     * Get idParametrosLaboratorio
     *
     * @return null|Integer
     */
    public function getIdParametrosLaboratorio() {
        return $this->idParametrosLaboratorio;
    }

    /**
     * Set fkIdLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parámetro Integer $fkIdLaboratorio
     * @return FkIdLaboratorio
     */
    public function setFkIdLaboratorio($fkIdLaboratorio) {
        $this->fkIdLaboratorio = $fkIdLaboratorio;
        return $this;
    }

    /**
     * Get fkIdLaboratorio
     *
     * @return null|Integer
     */
    public function getFkIdLaboratorio() {
        return $this->fkIdLaboratorio;
    }

    /**
     * Set idDireccion
     *
     * Dirección de diagnóstico
     *
     * @parámetro Integer $idDireccion
     * @return IdDireccion
     */
    public function setIdDireccion($idDireccion) {
        $this->idDireccion = $idDireccion;
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
     * Set idLaboratorio
     *
     *
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio) {
        $this->idLaboratorio = $idLaboratorio;
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
     * Set codigo
     *
     * Codigo del parametro, este es utilizado en la programacion por lo que una vez establecido no debe ser cambiado.
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo) {
        $this->codigo = ValidarDatos::validarAlfaEsp($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 16);
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
     * Set nombre
     *
     * Nombre que identifique el parametro
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre) {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, "Nombre", self::REQUERIDO, 256);
        return $this;
    }

    /**
     * Get nombre
     *
     * @return null|String
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * Descripcion del parametro, de lo que hace o comportamiento dentro del sistema
     *
     * @parámetro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = ValidarDatos::validarAlfa($descripcion, $this->tabla, "Descripción", self::NO_REQUERIDO, 0);
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
     * Set valorAux1
     *
     * Valor principal que toma el parametro
     *
     * @parámetro String $valorAux1
     * @return ValorAux1
     */
    public function setValorAux1($valorAux1) {
        $this->valorAux1 = ValidarDatos::validarAlfaEsp($valorAux1, $this->tabla, " Valor Auxiliar 1", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get valorAux1
     *
     * @return null|String
     */
    public function getValorAux1() {
        return $this->valorAux1;
    }

    /**
     * Set valorAux2
     *
     * Valor auxiliar del parametro
     *
     * @parámetro String $valorAux2
     * @return ValorAux2
     */
    public function setValorAux2($valorAux2) {
        $this->valorAux2 = ValidarDatos::validarAlfaEsp($valorAux2, $this->tabla, "Valor Auxiliar 2", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get valorAux2
     *
     * @return null|String
     */
    public function getValorAux2() {
        return $this->valorAux2;
    }

    /**
     * Set valorAux3
     *
     * Valor auxiliar del parametro
     *
     * @parámetro String $valorAux3
     * @return ValorAux3
     */
    public function setValorAux3($valorAux3) {
        $this->valorAux3 = ValidarDatos::validarAlfaEsp($valorAux3, $this->tabla, "Valor Auxiliar 3", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get valorAux3
     *
     * @return null|String
     */
    public function getValorAux3() {
        return $this->valorAux3;
    }

    /**
     * Set estado
     *
     * Estado que puede tomar un parametro
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado) {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 8);
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
     * Set atributosExtras
     *
     * Se puede poner codigo auxiliar para ejecutar el parametro
     *
     * @parámetro String $atributosExtras
     * @return AtributosExtras
     */
    public function setAtributosExtras($atributosExtras) {
        $this->atributosExtras = ValidarDatos::validarAlfaEsp($atributosExtras, $this->tabla, " Atributos Extras", self::NO_REQUERIDO, 1024);
        return $this;
    }

    /**
     * Get atributosExtras
     *
     * @return null|String
     */
    public function getAtributosExtras() {
        return $this->atributosExtras;
    }

    /**
     * Set obligatorio
     *
     * Indica se el parametro es obligatorio
     *
     * @parámetro String $obligatorio
     * @return Obligatorio
     */
    public function setObligatorio($obligatorio) {
        $this->obligatorio = ValidarDatos::validarAlfa($obligatorio, $this->tabla, " Obligatorio", self::REQUERIDO,0);
        return $this;
    }

    /**
     * Get obligatorio
     *
     * @return null|String
     */
    public function getObligatorio() {
        return $this->obligatorio;
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
     * @return ParametrosLaboratoriosModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
