<?php

/**
 * Modelo UsuarioLaboratorioModelo
 *
 * Este archivo se complementa con el archivo   UsuarioLaboratorioLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       UsuarioLaboratorioModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class UsuarioLaboratorioModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de la tabla
     */
    protected $idUsuarioLaboratorio;

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
     * Clave primaria de la tabla laboratorios_provincia
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
     * DirecciÃ³n de diagnÃ³stico
     */
    protected $direccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Perfil de usuario asignado por el Sistema GUIA
     */
    protected $perfil;

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
    protected $fechaRegistro;

    /**
     * @var text
     * Campo requerido
     * Campo visible en el formulario en ambiente de desarrollo
     * Fecha del registro
     */
    protected $permisos;

    /**
     * @var text
     * Campo requerido
     * Campo nombre del usuario 
     * Fecha del registro
     */
    protected $nombre;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: usuario_laboratorio
     * 
     */
    Private $tabla = "usuario_laboratorio";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_usuario_laboratorio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."usuario_laboratorio_id_usuario_laboratorio_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos))
        {
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
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: UsuarioLaboratorioModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: UsuarioLaboratorioModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parÃ¡metro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
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
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idUsuarioLaboratorio
     *
     * Id de la tabla
     *
     * @parÃ¡metro Integer $idUsuarioLaboratorio
     * @return IdUsuarioLaboratorio
     */
    public function setIdUsuarioLaboratorio($idUsuarioLaboratorio)
    {
        $this->idUsuarioLaboratorio = (Integer) $idUsuarioLaboratorio;
        return $this;
    }

    /**
     * Get idUsuarioLaboratorio
     *
     * @return null|Integer
     */
    public function getIdUsuarioLaboratorio()
    {
        return $this->idUsuarioLaboratorio;
    }

    /**
     * Set identificador
     *
     * CÃ©dula de identidad o pasaporte.
     *
     * @parÃ¡metro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (String) $identificador;
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set idLaboratoriosProvincia
     *
     * Clave primaria de la tabla laboratorios_provincia
     *
     * @parÃ¡metro Integer $idLaboratoriosProvincia
     * @return IdLaboratoriosProvincia
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia)
    {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
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
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }

    /**
     * Set direccion
     *
     * DirecciÃ³n de diagnÃ³stico
     *
     * @parÃ¡metro Integer $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = ValidarDatos::validarEntero($direccion, $this->tabla, " Dirección", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|Integer
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set perfil
     *
     * Perfil de usuario asignado por el Sistema GUIA
     *
     * @parÃ¡metro String $perfil
     * @return Perfil
     */
    public function setPerfil($perfil)
    {
        $this->perfil = ValidarDatos::validarAlfa($perfil, $this->tabla, " Perfil", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get perfil
     *
     * @return null|String
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set estado
     *
     * Estado del registro
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 8);
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaRegistro
     *
     * Fecha del registro
     *
     * @parÃ¡metro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = ValidarDatos::validarFecha($fechaRegistro, $this->tabla, " Fecha de Registro", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return null|Date
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * CÃ³digo tipo json para configurar los permisos
     * @return type
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * json para configurar los permisos
     * @param type $permisos
     * @return \Agrodb\Laboratorios\Modelos\UsuarioLaboratorioModelo
     */
    public function setPermisos($permisos)
    {
        $this->permisos = $permisos;
        return $this;
    }

    /**
     * Obtiene el nombre del usuario
     * @return type
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Nombre del usuario
     * @param type $nombre
     * @return \Agrodb\Laboratorios\Modelos\UsuarioLaboratorioModelo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(Array $datos, $id)
    {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        return parent::borrar($this->clavePrimaria . " = " . $id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return UsuarioLaboratorioModelo
     */
    public function buscar($id)
    {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
        return $this;
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return parent::buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return parent::buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function ejecutarConsulta($consulta)
    {
        return parent::ejecutarConsulta($consulta);
    }

}
