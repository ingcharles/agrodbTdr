<?php
/**
 * Modelo DetalleCambioInventarioModelo
 *
 * Este archivo se complementa con el archivo   DetalleCambioInventarioLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    DetalleCambioInventarioModelo
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleCambioInventarioModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador únicod el registro
     */
    protected $idDetalleInventario;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro origen de la distribucion
     */
    protected $idDistribucion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro destino del inventario al que se realiza el cambio
     */
    protected $idInventario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad original del registro inventario
     */
    protected $cantidadOriginal;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad del producto que se va a cambiar del inventario
     */
    protected $valorActualizar;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Valor actual del regsitro de inventario
     */
    protected $valorActual;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de opeación que se realiza con los valores:
     *      - Incremento (Devolución de producto)
     *      - Disminucion (Asignación de producto)
     */
    protected $operacion;

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
     * Nombre de la tabla: detalle_cambio_inventario
     */
    private $tabla = "detalle_cambio_inventario";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_detalle_inventario";

    /**
     * Secuencia
     */
    private $secuencial = 'g_registro_entrega_producto"."detalle_cambio_inventario_id_detalle_inventario_seq';

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
            throw new \Exception('Clase Modelo: DetalleCambioInventarioModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DetalleCambioInventarioModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDetalleInventario
     *
     * Identificador únicod el registro
     *
     * @parámetro Integer $idDetalleInventario
     * @return IdDetalleInventario
     */
    public function setIdDetalleInventario($idDetalleInventario)
    {
        $this->idDetalleInventario = (integer) $idDetalleInventario;
        return $this;
    }

    /**
     * Get idDetalleInventario
     *
     * @return null|Integer
     */
    public function getIdDetalleInventario()
    {
        return $this->idDetalleInventario;
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
     * Set idDistribucion
     *
     * Identificador del registro origen de la distribucion
     *
     * @parámetro Integer $idDistribucion
     * @return IdDistribucion
     */
    public function setIdDistribucion($idDistribucion)
    {
        $this->idDistribucion = (integer) $idDistribucion;
        return $this;
    }

    /**
     * Get idDistribucion
     *
     * @return null|Integer
     */
    public function getIdDistribucion()
    {
        return $this->idDistribucion;
    }

    /**
     * Set idInventario
     *
     * Identificador del registro destino del inventario al que se realiza el cambio
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
     * Set cantidadOriginal
     *
     * Cantidad original del registro inventario
     *
     * @parámetro Integer $cantidadOriginal
     * @return CantidadOriginal
     */
    public function setCantidadOriginal($cantidadOriginal)
    {
        $this->cantidadOriginal = (integer) $cantidadOriginal;
        return $this;
    }

    /**
     * Get cantidadOriginal
     *
     * @return null|Integer
     */
    public function getCantidadOriginal()
    {
        return $this->cantidadOriginal;
    }

    /**
     * Set valorActualizar
     *
     * Cantidad del producto que se va a cambiar del inventario
     *
     * @parámetro Integer $valorActualizar
     * @return ValorActualizar
     */
    public function setValorActualizar($valorActualizar)
    {
        $this->valorActualizar = (integer) $valorActualizar;
        return $this;
    }

    /**
     * Get valorActualizar
     *
     * @return null|Integer
     */
    public function getValorActualizar()
    {
        return $this->valorActualizar;
    }

    /**
     * Set valorActual
     *
     * Valor actual del regsitro de inventario
     *
     * @parámetro Integer $valorActual
     * @return ValorActual
     */
    public function setValorActual($valorActual)
    {
        $this->valorActual = (integer) $valorActual;
        return $this;
    }

    /**
     * Get valorActual
     *
     * @return null|Integer
     */
    public function getValorActual()
    {
        return $this->valorActual;
    }

    /**
     * Set operacion
     *
     * Tipo de opeación que se realiza con los valores:
     * - Incremento (Devolución de producto)
     * - Disminucion (Asignación de producto)
     *
     * @parámetro String $operacion
     * @return Operacion
     */
    public function setOperacion($operacion)
    {
        $this->operacion = (string) $operacion;
        return $this;
    }

    /**
     * Get operacion
     *
     * @return null|String
     */
    public function getOperacion()
    {
        return $this->operacion;
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
     * @return DetalleCambioInventarioModelo
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
