<?php

/**
 * Modelo UsuariosPerfilesModelo
 *
 * Este archivo se complementa con el archivo   UsuariosPerfilesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       UsuariosPerfilesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Usuarios\Modelos;

use Agrodb\Core\ModeloBase;

class UsuariosPerfilesModelo extends ModeloBase {

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $identificador;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idPerfil;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_usuario";

    /**
     * Nombre de la tabla: usuarios_perfiles
     * 
     */
    Private $tabla = "usuarios_perfiles";

 
 

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
        parent::__construct($this->esquema, $this->tabla);
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
            throw new \Exception('Clase Modelo: UsuariosPerfilesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: UsuariosPerfilesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_usuario
     *
     * @return null|
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set identificador
     *
     *
     *
     * @parámetro String $identificador
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
     * Set idPerfil
     *
     *
     *
     * @parámetro Integer $idPerfil
     * @return IdPerfil
     */
    public function setIdPerfil($idPerfil) {
        
        $this->idPerfil = (Integer) $idPerfil;
        return $this;
    }

    /**
     * Get idPerfil
     *
     * @return null|Integer
     */
    public function getIdPerfil() {
        return $this->idPerfil;
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
     * Borra el registro por medio de identificador e id perfil
     * @param string Where|array $where
     * @return int
     */
    
    public function borrarPorIdentificadorPerfil($identificador, $idPerfil) {
    	return parent::borrar("identificador = '" . $identificador . "' and id_perfil = " .$idPerfil);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return UsuariosPerfilesModelo
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
