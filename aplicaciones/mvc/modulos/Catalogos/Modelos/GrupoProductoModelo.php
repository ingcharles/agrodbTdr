<?php
/**
 * Modelo GrupoProductoModelo
 *
 * Este archivo se complementa con el archivo   GrupoProductoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    GrupoProductoModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class GrupoProductoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idGrupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código de identificación del grupo de producto
     */
    protected $codigoGrupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del grupo de producto
     */
    protected $grupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código del área al que pertenece el producto
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoGrupoProducto;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: grupo_producto
     */
    private $tabla = "grupo_producto";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_grupo_producto";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."grupo_producto_id_grupo_producto_seq';

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
            throw new \Exception('Clase Modelo: GrupoProductoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: GrupoProductoModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_catalogos
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idGrupoProducto
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idGrupoProducto
     * @return IdGrupoProducto
     */
    public function setIdGrupoProducto($idGrupoProducto)
    {
        $this->idGrupoProducto = (integer) $idGrupoProducto;
        return $this;
    }

    /**
     * Get idGrupoProducto
     *
     * @return null|Integer
     */
    public function getIdGrupoProducto()
    {
        return $this->idGrupoProducto;
    }

    /**
     * Set codigoGrupoProducto
     *
     * Código de identificación del grupo de producto
     *
     * @parámetro String $codigoGrupoProducto
     * @return CodigoGrupoProducto
     */
    public function setCodigoGrupoProducto($codigoGrupoProducto)
    {
        $this->codigoGrupoProducto = (string) $codigoGrupoProducto;
        return $this;
    }

    /**
     * Get codigoGrupoProducto
     *
     * @return null|String
     */
    public function getCodigoGrupoProducto()
    {
        return $this->codigoGrupoProducto;
    }

    /**
     * Set grupoProducto
     *
     * Nombre del grupo de producto
     *
     * @parámetro String $grupoProducto
     * @return GrupoProducto
     */
    public function setGrupoProducto($grupoProducto)
    {
        $this->grupoProducto = (string) $grupoProducto;
        return $this;
    }

    /**
     * Get grupoProducto
     *
     * @return null|String
     */
    public function getGrupoProducto()
    {
        return $this->grupoProducto;
    }

    /**
     * Set idArea
     *
     * Código del área al que pertenece el producto
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
     * Set estadoGrupoProducto
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoGrupoProducto
     * @return EstadoGrupoProducto
     */
    public function setEstadoGrupoProducto($estadoGrupoProducto)
    {
        $this->estadoGrupoProducto = (string) $estadoGrupoProducto;
        return $this;
    }

    /**
     * Get estadoGrupoProducto
     *
     * @return null|String
     */
    public function getEstadoGrupoProducto()
    {
        return $this->estadoGrupoProducto;
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
     * @return GrupoProductoModelo
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
