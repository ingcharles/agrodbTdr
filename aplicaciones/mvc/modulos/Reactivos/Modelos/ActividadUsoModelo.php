<?php

/**
 * Modelo ActividadUsoModelo
 *
 * Este archivo se complementa con el archivo   ActividadUsoLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ActividadUsoModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use Agrodb\Core\Constantes;
use Agrodb\Laboratorios\Controladores\BaseControlador as Base;

class ActividadUsoModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idActividadUso;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idServicio;

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
    protected $idLaboratoriosProvincia;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidad;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fecha;

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
    protected $tipoProcedimiento;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $observaciones;
    protected $nombre;

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
     * Nombre de la tabla: actividad_uso
     * 
     */
    Private $tabla = "actividad_uso";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_actividad_uso";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."actividad_uso_id_actividad_uso_seq';

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
            throw new \Exception('Clase Modelo: ActividadUsoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ActividadUsoModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set idRecetaAnalisis
     *
     *
     *
     * @parÃ¡metro Integer $idRecetaAnalisis
     * @return IdRecetaAnalisis
     */
    public function setIdActividadUso($idActividadUso) {
        $this->idActividadUso = (Integer) $idActividadUso;
        
        return $this;
    }

    /**
     * Get idRecetaAnalisis
     *
     * @return null|Integer
     */
    public function getIdActividadUso() {
        return $this->idActividadUso;
    }

    /**
     * Set idServicio
     *
     *
     *
     * @parÃ¡metro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio) {
        $this->idServicio = (Integer) $idServicio;
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
     * Set idLaboratorio
     *
     *
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
     * Get idLaboratoriosProvincia
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvincia()
    {
        return $this->idLaboratoriosProvincia;
    }

    /**
     * Set idLaboratoriosProvincia
     *
     *
     *
     * @parámetro Integer $idLaboratorioProvincia
     * @return IdLaboratoriosProvincia
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia)
    {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
    }

    /**
     * Set idReactivoLaboratorio
     *
     *
     *
     * @parÃ¡metro Integer $idReactivoLaboratorio
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
     * Set cantidad
     *
     *
     *
     * @parÃ¡metro Integer $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|Integer
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * Set fecha
     *
     *
     *
     * @parÃ¡metro Date $fecha
     * @return Fecha
     */
    public function setFecha($fecha) {
        $this->fecha = ValidarDatos::validarFecha($fecha, $this->tabla,  " Fecha", self::NO_REQUERIDO, 0);
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
     * Set estado
     *
     *
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado) {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, "Estado", self::REQUERIDO, 8);
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
     * Set tipoProcedimiento
     *
     *
     *
     * @parÃ¡metro String $tipoProcedimiento
     * @return TipoProcedimiento
     */
    public function setTipoProcedimiento($tipoProcedimiento) {
        $this->tipoProcedimiento = ValidarDatos::validarAlfa($tipoProcedimiento, $this->tabla, "Tipo Procedimiento", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get tipoProcedimiento
     *
     * @return null|String
     */
    public function getTipoProcedimiento() {
        return $this->tipoProcedimiento;
    }

    /**
     * Set observaciones
     *
     *
     *
     * @parÃ¡metro String $observaciones
     * @return Observaciones
     */
    public function setObservaciones($observaciones) {
        $this->observaciones = ValidarDatos::validarAlfa($observaciones, $this->tabla, "Observaciones", self::NO_REQUERIDO, 1024);
        return $this; 
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getObservaciones() {
        return $this->observaciones;
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
     * @return ActividadUsoModelo
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
