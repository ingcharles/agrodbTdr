<?php

/**
 * Modelo UsuariosSolicitudModelo
 *
 * Este archivo se complementa con el archivo   UsuariosSolicitudLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       UsuariosSolicitudModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;


class UsuariosSolicitudModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de la tabla
     */
    protected $idUsuariosSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ©dula de identidad o pasaporte.
     */
    protected $identificador;
    
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
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del registro
     */
    protected $estado;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha del registro
     */
    protected $fechaInicio;
    
    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha del registro
     */
    protected $fechaFin;

    /**
     * @var String
     * Campo requerido
     * Campo tipo
     * Fecha del registro
     */
    protected $tipo;
    
    /**
     * @var String
     * Campo requerido
     * Campo motivo
     * Motivo
     */
    protected $motivo;

    /**
     * Tipo del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Tipo de la tabla: usuarios_laboratorio
     * 
     */
    Private $tabla = "usuarios_solicitud";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_usuarios_solicitud";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."usuarios_solicitud_id_usuarios_solicitud_seq';

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
            throw new \Exception('Clase Modelo: UsuariosSolicitudModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: UsuariosSolicitudModelo. Propiedad especificada invalida: get' . $name);
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
     * Tipo del esquema del mÃ³dulo 
     *
     * @parÃ¡metro $esquema
     * @return Tipo del esquema de la base de datos
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
     * Set idUsuariosSolicitud
     *
     * Id de la tabla
     *
     * @parÃ¡metro Integer $idUsuariosSolicitud
     * @return IdUsuariosSolicitud
     */
    public function setIdUsuariosSolicitud($idUsuariosSolicitud) {
        $this->idUsuariosSolicitud = (Integer) $idUsuariosSolicitud;
        return $this;
    }

    /**
     * Get idUsuariosSolicitud
     *
     * @return null|Integer
     */
    public function getIdUsuariosSolicitud() {
        return $this->idUsuariosSolicitud;
    }

    /**
     * Set identificador
     *
     * CÃ©dula de identidad o pasaporte.
     *
     * @parÃ¡metro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador) {
        $this->identificador = (String) $identificador;
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
     * Set idSolicitud
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud) {
        $this->idSolicitud = (Integer) $idSolicitud;
        return $this;
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
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud() {
        return $this->idSolicitud;
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
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO,8);
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
     * Set fechaInicio
     *
     * Fecha del registro
     *
     * @parÃ¡metro Date $fechaInicio
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
     * Fecha del registro
     *
     * @parÃ¡metro Date $fechaFin
     * @return FechaFin
     */
    public function setFechaFin($fechaFin) {
        $this->fechaFin = ValidarDatos::validarFecha($fechaFin, $this->tabla, " Fecha Fin", self::REQUERIDO, 0);
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
     * Obtiene el tipo del usuarios
     * @return type
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Tipo del usuarios
     * @param type $tipo
     * @return \Agrodb\Laboratorios\Modelos\UsuariosSolicitudModelo
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }
    
    /**
     * Obtiene el tipo del usuarios
     * @return type
     */
    public function getMotivo() {
        return $this->tipo;
    }

    /**
     * Motivo del usuarios
     * @param type $tipo
     * @return \Agrodb\Laboratorios\Modelos\UsuariosSolicitudModelo
     */
    public function setMotivo($tipo) {
        $this->tipo = $tipo;
        return $this;
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
     * @return UsuariosSolicitudModelo
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
