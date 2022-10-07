<?php

/**
 * Modelo DistribucionMuestrasModelo
 *
 * Este archivo se complementa con el archivo   DistribucionMuestrasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DistribucionMuestrasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;

class DistribucionMuestrasModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla distribucion _muestra
     */
    protected $idDistribucionMuestra;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * id_laboratorios_provincia
     */
    protected $idLaboratoriosProvincia;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla localizacion
     */
    protected $idLocalizacion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * id_servicio
     */
    protected $idServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * direccion
     */
    protected $idDireccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * estado
     */
    protected $estadoRegistro;

    /**
     * Mensaje que se mostrará al usuario cuando el servicio no esté disponible o por cualquier otro motivo
     * @var type 
     */
    protected $mensajePublico;

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
     * Nombre de la tabla: distribucion_muestras
     * 
     */
    Private $tabla = "distribucion_muestras";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_distribucion_muestra";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."distribucion_muestras_id_distribucion_muestra_seq';

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
            throw new \Exception('Clase Modelo: DistribucionMuestrasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DistribucionMuestrasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDistribucionMuestra
     *
     * Identificador de la tabla distribucion _muestra
     *
     * @parámetro Integer $idDistribucionMuestra
     * @return IdDistribucionMuestra
     */
    public function setIdDistribucionMuestra($idDistribucionMuestra) {
        $this->idDistribucionMuestra = (Integer) $idDistribucionMuestra;
        return $this;
    }

    /**
     * Get idDistribucionMuestra
     *
     * @return null|Integer
     */
    public function getIdDistribucionMuestra() {
        return $this->idDistribucionMuestra;
    }

    /**
     * Set idLaboratoriosProvincia
     *
     * id_laboratorios_provincia
     *
     * @parámetro Integer $idLaboratoriosProvincia
     * @return IdLaboratoriosProvincia
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia) {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
    }

    /**
     * Get idLaboratoriosProvincia
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvincia() {
        return $this->idLaboratoriosProvincia;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
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
     * Set idLocalizacion
     *
     * Identificador de la tabla localizacion
     *
     * @parámetro Integer $idLocalizacion
     * @return IdLocalizacion
     */
    public function setIdLocalizacion($idLocalizacion) {
        $this->idLocalizacion = (Integer) $idLocalizacion;
        return $this;
    }

    /**
     * Get idLocalizacion
     *
     * @return null|Integer
     */
    public function getIdLocalizacion() {
        return $this->idLocalizacion;
    }

    /**
     * Set idServicio
     *
     * id_servicio
     *
     * @parámetro Integer $idServicio
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
     * Set direccion
     *
     * direccion
     *
     * @parámetro Integer $direccion
     * @return Direccion
     */
    public function setIdDireccion($idDireccion) {
        $this->idDireccion = (Integer) $idDireccion;
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|Integer
     */
    public function getIdDireccion() {
        return $this->idDireccion;
    }

    /**
     * Set estado
     *
     * estado
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstadoRegistro($estadoRegistro) {
        $this->estadoRegistro = (String) $estadoRegistro;
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstadoRegistro() {
        return $this->estadoRegistro;
    }

    /*
     * Mensaje que se mostrará al usuario cuando el servicio no esté disponible o por cualquier otro motivo
     */

    public function setMensajePublico($mensajePublico) {
        $this->mensajePublico = $mensajePublico;
        return $this;
    }

    /**
     * Mensaje que se mostrará al usuario cuando el servicio no esté disponible o por cualquier otro motivo
     * @return type
     */
    public function getMensajePublico() {
        return $this->mensajePublico;
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
     * @return DistribucionMuestrasModelo
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
