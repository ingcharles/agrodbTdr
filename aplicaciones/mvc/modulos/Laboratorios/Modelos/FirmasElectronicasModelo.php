<?php

/**
 * Modelo FirmasElectronicasModelo
 *
 * Este archivo se complementa con el archivo   FirmasElectronicasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       FirmasElectronicasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FirmasElectronicasModelo extends ModeloBase {

    /**
     *
     * @var Integer Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Id de firma electronica
     */
    protected $idFirmaElectronica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador
     */
    protected $identificador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta
     */
    protected $ruta;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      ContraseÃ±a
     */
    protected $contrasenia;

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: firmas_electronicas
     */
    private $tabla = "firmas_electronicas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_firma_electronica";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."firmas_electronicas_id_firma_electronica_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro array|null $datos
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
     * @parÃ¡metro string $name
     * @parÃ¡metro mixed $value
     * @retorna void
     */
    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: FirmasElectronicasModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @retorna mixed
     */
    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: FirmasElectronicasModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parÃ¡metro array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos) {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
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
     * 
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema) {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set idFirmaElectronica
     *
     * Secuencial (PK) de la tabla firma_electronica
     *
     * @parÃ¡metro Integer $idFirmaElectronica
     * 
     * @return IdFirmaElectronica
     */
    public function setIdFirmaElectronica($idFirmaElectronica) {
       
        $this->idFirmaElectronica = (integer) $idFirmaElectronica;
        return $this;
    }

    /**
     * Get idFirmaElectronica
     *
     * @return null|Integer
     */
    public function getIdFirmaElectronica() {
        return $this->idFirmaElectronica;
    }

    /**
     * Set identificador
     *
     * Cedula de identidad o pasaporte.
     *
     * @parÃ¡metro String $identificador
     * 
     * @return Identificador
     */
    public function setIdentificador($identificador) {
        $this->identificador = ValidarDatos::validarAlfa($identificador, $this->tabla, "ID. Cédula o Pasaporte", self::REQUERIDO,13);
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador() {
        return $this->identificador;
    }

    /**
     * Set ruta
     *
     * Ruta fÃ­sica del certificado digital
     *
     * @parÃ¡metro String $ruta
     * 
     * @return Ruta
     */
    public function setRuta($ruta) {

    
       $this->ruta = (String) $ruta;  
        return $this;
    }

    /**
     * Get ruta
     *
     * @return null|String
     */
    public function getRuta() {
        return $this->ruta;
    }

    /**
     * Set estado
     *
     * Estado de la firma ACTIVO/INACTIVO
     *
     * @parÃ¡metro String $estado
     * 
     * @return Estado
     */
    public function setEstado($estado) {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado de la Firma", self::REQUERIDO,32);
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
     * Set contrasenia
     *
     * Guarda la contraseÃ±a de la firma electrÃ³nica
     *
     * @parÃ¡metro String $contrasenia
     * 
     * @return Contrasenia
     */
    public function setContrasenia($contrasenia) {
        $this->contrasenia = (String ) $contrasenia;
        return $this;
    }

    /**
     * Get contrasenia
     *
     * @return null|String
     */
    public function getContrasenia() {
        return $this->contrasenia;
    }

    /**
     * Guarda el registro actual
     * 
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos) {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * 
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(Array $datos, $id) {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     * 
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id) {
        return parent::borrar($this->clavePrimaria . " = " . $id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FirmasElectronicasModelo
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
