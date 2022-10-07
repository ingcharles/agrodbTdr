<?php
/**
 * Modelo InventarioProductosModelo
 *
 * Este archivo se complementa con el archivo   InventarioProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    InventarioProductosModelo
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class InventarioProductosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idInventario;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
     */
    protected $identificador;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto de distribución
     */
    protected $idProductoDistribucion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto de distribución
     */
    protected $nombreProductoDistribucion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad del producto ingresado al inventario
     */
    protected $cantidad;
    
    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad original asignada al producto
     */
    protected $cantidadAsignada;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Orden en el que se crean los registros de ingreso para el producto
     */
    protected $orden;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Motivo de ingreso de productos al catálogo:
     *      - nuevo
     *      - devolucion
     */
    protected $tipoRegistro;

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
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Unidad de medida del producto de distribución
     */
    protected $unidad;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_registro_entrega_producto";

    /**
     * Nombre de la tabla: inventario_productos
     */
    private $tabla = "inventario_productos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_inventario";

    /**
     * Secuencia
     */
    private $secuencial = 'g_registro_entrega_producto"."inventario_productos_id_inventario_seq';

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
            throw new \Exception('Clase Modelo: InventarioProductosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: InventarioProductosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_registro_entrega_producto
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idInventario
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idInventario
     * @return IdInventario
     */
    public function setIdInventario($idInventario)
    {
        $this->idInventario = (integer) $idInventario;
        return $this;
    }

    /**
     * Get idInventario
     *
     * @return null|Integer
     */
    public function getIdInventario()
    {
        return $this->idInventario;
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
     * Set identificador
     *
     * Identificador del usuario que crea el registro
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
     * Set idProductoDistribucion
     *
     * Identificador del producto de distribución
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
     * Set nombreProductoDistribucion
     *
     * Nombre del producto de distribución
     *
     * @parámetro String $nombreProductoDistribucion
     * @return NombreProductoDistribucion
     */
    public function setNombreProductoDistribucion($nombreProductoDistribucion)
    {
        $this->nombreProductoDistribucion = (string) $nombreProductoDistribucion;
        return $this;
    }

    /**
     * Get nombreProductoDistribucion
     *
     * @return null|String
     */
    public function getNombreProductoDistribucion()
    {
        return $this->nombreProductoDistribucion;
    }

    /**
     * Set cantidad
     *
     * Cantidad del producto ingresado al inventario
     *
     * @parámetro Integer $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = (integer) $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|Integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadAsignada
     *
     * Cantidad original asignada al producto
     *
     * @parámetro Integer $cantidad
     * @return Cantidad
     */
    public function setCantidadAsignada($cantidadAsignada)
    {
        $this->cantidadAsignada = (integer) $cantidadAsignada;
        return $this;
    }
    
    /**
     * Get cantidadAsignada
     *
     * @return null|Integer
     */
    public function getCantidadAsignada()
    {
        return $this->cantidadAsignada;
    }
    
    /**
     * Set orden
     *
     * Orden en el que se crean los registros de ingreso para el producto
     *
     * @parámetro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = (integer) $orden;
        return $this;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set tipoRegistro
     *
     * Motivo de ingreso de productos al catálogo:
     * - nuevo
     * - devolucion
     *
     * @parámetro String $tipoRegistro
     * @return TipoRegistro
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = (string) $tipoRegistro;
        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return null|String
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
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
     * @return InventarioProductosModelo
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
