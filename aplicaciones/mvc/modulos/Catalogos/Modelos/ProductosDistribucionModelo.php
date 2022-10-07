<?php
/**
 * Modelo ProductosDistribucionModelo
 *
 * Este archivo se complementa con el archivo   ProductosDistribucionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    ProductosDistribucionModelo
 * @package Distribucion_Entrega_Productos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProductosDistribucionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idProductoDistribucion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto para distribución
     */
    protected $productoDistribucion;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Unidad de medida del producto de distribución
     */
    protected $unidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - activo
     *      - inactivo
     */
    protected $estado;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

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
     * Nombre de la tabla: productos_distribucion
     */
    private $tabla = "productos_distribucion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_producto_distribucion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."productos_distribucion_id_producto_distribucion_seq';

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
            throw new \Exception('Clase Modelo: ProductosDistribucionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ProductosDistribucionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idProductoDistribucion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idProductoDistribucion
     * @return IdProductoDistribucion
     */
    public function setIdProductoDistribucion($idProductoDistribucion)
    {
        $this->idProductoDistribucion = (integer) $idProductoDistribucion;
        return $this;
    }

    /**
     * Get idProductoDistribucion
     *
     * @return null|Integer
     */
    public function getIdProductoDistribucion()
    {
        return $this->idProductoDistribucion;
    }

    /**
     * Set productoDistribucion
     *
     * Nombre del producto para distribución
     *
     * @parámetro String $productoDistribucion
     * @return ProductoDistribucion
     */
    public function setProductoDistribucion($productoDistribucion)
    {
        $this->productoDistribucion = (string) $productoDistribucion;
        return $this;
    }

    /**
     * Get productoDistribucion
     *
     * @return null|String
     */
    public function getProductoDistribucion()
    {
        return $this->productoDistribucion;
    }
    
    /**
     * Set unidad
     *
     * Unidad de medida del producto de distribución
     *
     * @parámetro String $unidad
     * @return Unidad
     */
    public function setUnidad($unidad)
    {
        $this->unidad = (string) $unidad;
        return $this;
    }
    
    /**
     * Get unidad
     *
     * @return null|String
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set estado
     *
     * Estado del registro:
     * - activo
     * - inactivo
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (string) $estado;
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
     * Set fechaCreacion
     *
     * Fecha de creación del registro
     *
     * @parámetro Date $fechaCreacion
     * @return FechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = (string) $fechaCreacion;
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return null|Date
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
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
     * @return ProductosDistribucionModelo
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
