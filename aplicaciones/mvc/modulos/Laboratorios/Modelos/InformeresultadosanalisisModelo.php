<?php

/**
 * Modelo InformeResultadosAnalisisModelo
 *
 * Este archivo se complementa con el archivo   InformeResultadosAnalisisLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       InformeResultadosAnalisisModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class InformeResultadosAnalisisModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla archivos_informe_analisis
     */
    protected $idInformeAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla ordenes_trabajos
     */
    protected $idOrdenTrabajo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del archivo
     */
    protected $estado;

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
     * Nombre de la tabla: informe_resultados_analisis
     * 
     */
    Private $tabla = "informe_resultados_analisis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_informe_analisis";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."informe_resultados_analisis_id_informe_analisis_seq';

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
            throw new \Exception('Clase Modelo: InformeResultadosAnalisisModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: InformeResultadosAnalisisModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idInformeAnalisis
     *
     * Secuencial (PK) de la tabla archivos_informe_analisis
     *
     * @parámetro Integer $idInformeAnalisis
     * @return IdInformeAnalisis
     */
    public function setIdInformeAnalisis($idInformeAnalisis) {
        $this->idInformeAnalisis = (Integer) $idInformeAnalisis;
        return $this;
    }

    /**
     * Get idInformeAnalisis
     *
     * @return null|Integer
     */
    public function getIdInformeAnalisis() {
        return $this->idInformeAnalisis;
    }

    /**
     * Set idOrdenTrabajo
     *
     * Secuencial (PK) de la tabla ordenes_trabajos
     *
     * @parámetro Integer $idOrdenTrabajo
     * @return IdOrdenTrabajo
     */
    public function setIdOrdenTrabajo($idOrdenTrabajo) {
        $this->idOrdenTrabajo = (Integer) $idOrdenTrabajo;
        return $this;
    }

    /**
     * Get idOrdenTrabajo
     *
     * @return null|Integer
     */
    public function getIdOrdenTrabajo() {
        return $this->idOrdenTrabajo;
    }

    /**
     * Set estado
     *
     * Estado del archivo
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado) {
        $this->estado = (String) $estado;
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
     * @return InformeResultadosAnalisisModelo
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
