<?php

/**
 * Modelo ParametrosServiciosModelo
 *
 * Este archivo se complementa con el archivo   ParametrosServiciosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ParametrosServiciosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ParametrosServiciosModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idParametrosServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Servicio
     */
    protected $idServicio;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * DirecciÃ³n
     */
    protected $idDireccion;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Laboratorio
     */
    protected $idLaboratorio;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * CÃ³digo
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Tipo de campo
     */
    protected $tipoCampo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre
     */
    protected $nombre;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * DescripciÃ³n
     */
    protected $descripcion;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Valor auxiliar
     */
    protected $valorAux1;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Valor auxiliar
     */
    protected $valorAux2;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Valor auxiliar
     */
    protected $valorAux3;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado
     */
    protected $estado;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Atributos
     */
    protected $atributosExtras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Obligatorio
     */
    protected $obligatorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden
     */
    protected $orden;

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
     * Nombre de la tabla: parametros_servicios
     * 
     */
    Private $tabla = "parametros_servicios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_parametros_servicio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."parametros_servicios_id_parametros_servicio_seq';

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
            throw new \Exception('Clase Modelo: ParametrosServiciosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ParametrosServiciosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idParametrosServicio
     *
     * Secuencial (PK) de la tabla parametros_servicio
     *
     * @parÃ¡metro Integer $idParametrosServicio
     * @return IdParametrosServicio
     */
    public function setIdParametrosServicio($idParametrosServicio) {
        $this->idParametrosServicio = $idParametrosServicio;
        return $this;
    }

    /**
     * Get idParametrosServicio
     *
     * @return null|Integer
     */
    public function getIdParametrosServicio() {
        return $this->idParametrosServicio;
    }

    /**
     * Set idServicio
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parÃ¡metro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio) {
        $this->idServicio = $idServicio;
        return $this;
    }

    /**
     * Get idServicio
     *
     * @return null|Integer
     */
    public function getIdServicio() {
        return $this->idServicio;
    }

    /**
     * Set idDireccion
     *
     * Secuencial (PK) de la tabla direccion
     *
     * @parÃ¡metro Integer $idDireccion
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
     * Secuencial (PK) de la tabla laboratorio
     *
     * @parÃ¡metro Integer $idLaboratorio
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
     * CÃ³digo del parÃ¡metro, este es utilizado en la programaciÃ³n por lo que una vez establecido no debe ser cambiado.
     *
     * @parÃ¡metro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo) {
        $this->codigo = ValidarDatos::validarAlfa($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 16);
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
     * Tipo de parámetro o camdo Ej. ARCHIVO identifica un archivo adjunto
     * @return type
     */
    public function getTipoCampo() {
        return $this->tipoCampo;
    }

    /**
     * Tipo de parámetro o camdo Ej. ARCHIVO identifica un archivo adjunto
     * @param type $tipoCampo
     * @return \Agrodb\Laboratorios\Modelos\ParametrosServiciosModelo
     */
    public function setTipoCampo($tipoCampo) {
        $this->tipoCampo = $tipoCampo;
        return $this;
    }

    /**
     * Set nombre
     *
     * Nombre que identifique el parÃ¡metro
     *
     * @parÃ¡metro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre) {
        $this->nombre = ValidarDatos::validarAlfa($nombre, $this->tabla, " Nombre", self::REQUERIDO, 256);
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
     * DescripciÃ³n del parÃ¡metro, de lo que hace o comportamiento dentro del sistema
     *
     * @parÃ¡metro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = ValidarDatos::validarAlfa($descripcion, $this->tabla, " Descripción", self::NO_REQUERIDO, 0);
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
     * Valor auxiliar del parÃ¡metro
     *
     * @parÃ¡metro String $valorAux1
     * @return ValorAux1
     */
    public function setValorAux1($valorAux1) {
        $this->valorAux1 = ValidarDatos::validarAlfa($valorAux1, $this->tabla, " Valor Auxiliar", self::NO_REQUERIDO, 256);
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
     * Valor auxiliar del parÃ¡metro
     *
     * @parÃ¡metro String $valorAux2
     * @return ValorAux2
     */
    public function setValorAux2($valorAux2) {
        $this->valorAux2 = ValidarDatos::validarAlfa($valorAux2, $this->tabla, " Valor Auxiliar 2", self::NO_REQUERIDO, 256);
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
     * Valor auxiliar del parÃ¡metro
     *
     * @parÃ¡metro String $valorAux3
     * @return ValorAux3
     */
    public function setValorAux3($valorAux3) {
        $this->valorAux3 = ValidarDatos::validarAlfa($valorAux3, $this->tabla, " Valor Auxiliar 3", self::NO_REQUERIDO, 256);
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
     * Estado que puede tomar un parÃ¡metro
     *
     * @parÃ¡metro String $estado
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
     * Se puede poner cÃ³digo auxiliar para ejecutar el parametro
     *
     * @parÃ¡metro String $atributosExtras
     * @return AtributosExtras
     */
    public function setAtributosExtras($atributosExtras) {
        $this->atributosExtras = ValidarDatos::validarAlfa($atributosExtras, $this->tabla, " Atributos Extras", self::NO_REQUERIDO, 0);
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
     * Indica se el parÃ¡metro es obligatorio
     *
     * @parÃ¡metro String $obligatorio
     * @return Obligatorio
     */
    public function setObligatorio($obligatorio) {
        $this->obligatorio = (String) $obligatorio;
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
     * Set orden
     *
     * Orden que deben presentarse en el formulario
     *
     * @parÃ¡metro Integer $orden
     * @return Orden
     */
    public function setOrden($orden) {
        $this->orden = ValidarDatos::validarEntero($orden, $this->tabla, " Orden", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden() {
        return $this->orden;
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
     * @return ParametrosServiciosModelo
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
