<?php
/**
 * Modelo FuncionariosModelo
 *
 * Este archivo se complementa con el archivo   FuncionariosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-03
 * @uses    FuncionariosModelo
 * @package Estructura
 * @subpackage Modelos
 */
namespace Agrodb\Estructura\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FuncionariosModelo extends ModeloBase
{

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Llave primaria de esta tabla y foránea de la tabla g_estructura.area
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Llave primaria de esta tabla y foránea de la tabla g_usuario.usuarios
     */
    protected $identificador;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      0 -> Usuario Comun
     *      1-> Usuario administrador
     *      2-> Usuario SuperAdministrador
     */
    protected $administrador;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Campo que determina si un funcionario se encuentra activo dentro del sistema siendo:
     *      1->activo
     *      0->inactivo
     */
    protected $activo;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Llave foránea de la tabla g_catalogos.localizacion
     */
    protected $idProvincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Llave foránea de la tabla g_catalogos.localizacion
     */
    protected $idCanton;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Llave foránea de la tabla g_catalogos.localizacion
     */
    protected $idOficina;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Campo que determina si el funcionario se encuentra activo dentro del contrato
     */
    protected $estado;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_estructura";

    /**
     * Nombre de la tabla: funcionarios
     */
    private $tabla = "funcionarios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_area";

    /**
     * Secuencia
     */
    private $secuencial = '"Funcionarios_"id_area_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     *
     * @parámetro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
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
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: FuncionariosModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parámetro  string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: FuncionariosModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parámetro  array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            $key_original = $key;
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
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
    public function getPrepararDatos()
    {
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
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_estructura
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idArea
     *
     * Llave primaria de esta tabla y foránea de la tabla g_estructura.area
     *
     * @parámetro String $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (string) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|String
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set identificador
     *
     * Llave primaria de esta tabla y foránea de la tabla g_usuario.usuarios
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (string) $identificador;
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
     * Set administrador
     *
     * 0 -> Usuario Comun
     * 1-> Usuario administrador
     * 2-> Usuario SuperAdministrador
     *
     * @parámetro Integer $administrador
     * @return Administrador
     */
    public function setAdministrador($administrador)
    {
        $this->administrador = (integer) $administrador;
        return $this;
    }

    /**
     * Get administrador
     *
     * @return null|Integer
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Set activo
     *
     * Campo que determina si un funcionario se encuentra activo dentro del sistema siendo:
     * 1->activo
     * 0->inactivo
     *
     * @parámetro Integer $activo
     * @return Activo
     */
    public function setActivo($activo)
    {
        $this->activo = (integer) $activo;
        return $this;
    }

    /**
     * Get activo
     *
     * @return null|Integer
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set idProvincia
     *
     * Llave foránea de la tabla g_catalogos.localizacion
     *
     * @parámetro Integer $idProvincia
     * @return IdProvincia
     */
    public function setIdProvincia($idProvincia)
    {
        $this->idProvincia = (integer) $idProvincia;
        return $this;
    }

    /**
     * Get idProvincia
     *
     * @return null|Integer
     */
    public function getIdProvincia()
    {
        return $this->idProvincia;
    }

    /**
     * Set idCanton
     *
     * Llave foránea de la tabla g_catalogos.localizacion
     *
     * @parámetro Integer $idCanton
     * @return IdCanton
     */
    public function setIdCanton($idCanton)
    {
        $this->idCanton = (integer) $idCanton;
        return $this;
    }

    /**
     * Get idCanton
     *
     * @return null|Integer
     */
    public function getIdCanton()
    {
        return $this->idCanton;
    }

    /**
     * Set idOficina
     *
     * Llave foránea de la tabla g_catalogos.localizacion
     *
     * @parámetro Integer $idOficina
     * @return IdOficina
     */
    public function setIdOficina($idOficina)
    {
        $this->idOficina = (integer) $idOficina;
        return $this;
    }

    /**
     * Get idOficina
     *
     * @return null|Integer
     */
    public function getIdOficina()
    {
        return $this->idOficina;
    }

    /**
     * Set estado
     *
     * Campo que determina si el funcionario se encuentra activo dentro del contrato
     *
     * @parámetro Integer $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (integer) $estado;
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|Integer
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     *
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
     *
     * @param
     *            string Where|array $where
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
     * @param int $id
     * @return FuncionariosModelo
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
     * Busca una lista de acuerdo a los parámetros <params> enviados.
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
