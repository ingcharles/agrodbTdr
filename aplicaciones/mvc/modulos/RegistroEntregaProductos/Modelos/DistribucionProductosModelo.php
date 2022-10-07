<?php
/**
 * Modelo DistribucionProductosModelo
 *
 * Este archivo se complementa con el archivo   DistribucionProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    DistribucionProductosModelo
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DistribucionProductosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idDistribucion;

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
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la entidad a la que se asigna el producto:
     *      - Agrocalidad
     *      - Entidades externas (MAG, etc)
     */
    protected $entidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia donde se asignará el producto
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se asignará el producto
     */
    protected $provincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad del producto asignado
     */
    protected $cantidadAsignada;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad inicial del producto asignado
     */
    protected $cantidadInicial;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad final del producto asignado
     */
    protected $cantidadFinal;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad del producto que puede ser usado para entrega a los usuarios
     */
    protected $cantidadDisponible;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto a asignar
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto a asignar
     */
    protected $producto;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de modificación del registro. Se actualiza en caso de requerir devolver los productos sobrantes al inventario.
     */
    protected $fechaModificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que realiza la modificación de valores. Se actualiza en caso de requerir devolver los productos sobrantes al inventario.
     */
    protected $identificadorModificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - activo
     *      - inactivo
     *      - modificado
     */
    protected $estado;

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
     * Nombre de la tabla: distribucion_productos
     */
    private $tabla = "distribucion_productos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_distribucion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_registro_entrega_producto"."distribucion_productos_id_distribucion_seq';

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
            throw new \Exception('Clase Modelo: DistribucionProductosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DistribucionProductosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDistribucion
     *
     * Identificador único del registro
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
     * Set entidad
     *
     * Nombre de la entidad a la que se asigna el producto:
     * - Agrocalidad
     * - Entidades externas (MAG, etc)
     *
     * @parámetro String $entidad
     * @return Entidad
     */
    public function setEntidad($entidad)
    {
        $this->entidad = (string) $entidad;
        return $this;
    }

    /**
     * Get entidad
     *
     * @return null|String
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set idProvincia
     *
     * Identificador de la provincia donde se asignará el producto
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
     * Set provincia
     *
     * Nombre de la provincia donde se asignará el producto
     *
     * @parámetro String $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
        return $this;
    }

    /**
     * Get provincia
     *
     * @return null|String
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set cantidadAsignada
     *
     * Cantidad del producto asignado
     *
     * @parámetro Integer $cantidadAsignada
     * @return CantidadAsignada
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
     * Set cantidadInicial
     *
     * Cantidad inicial del producto asignado
     *
     * @parámetro Integer $cantidadInicial
     * @return CantidadInicial
     */
    public function setCantidadInicial($cantidadInicial)
    {
        $this->cantidadInicial = (integer) $cantidadInicial;
        return $this;
    }

    /**
     * Get cantidadInicial
     *
     * @return null|Integer
     */
    public function getCantidadInicial()
    {
        return $this->cantidadInicial;
    }

    /**
     * Set cantidadFinal
     *
     * Cantidad final del producto asignado
     *
     * @parámetro Integer $cantidadFinal
     * @return CantidadFinal
     */
    public function setCantidadFinal($cantidadFinal)
    {
        $this->cantidadFinal = (integer) $cantidadFinal;
        return $this;
    }

    /**
     * Get cantidadFinal
     *
     * @return null|Integer
     */
    public function getCantidadFinal()
    {
        return $this->cantidadFinal;
    }

    /**
     * Set cantidadDisponible
     *
     * Cantidad del producto que puede ser usado para entrega a los usuarios
     *
     * @parámetro Integer $cantidadDisponible
     * @return CantidadDisponible
     */
    public function setCantidadDisponible($cantidadDisponible)
    {
        $this->cantidadDisponible = (integer) $cantidadDisponible;
        return $this;
    }

    /**
     * Get cantidadDisponible
     *
     * @return null|Integer
     */
    public function getCantidadDisponible()
    {
        return $this->cantidadDisponible;
    }

    /**
     * Set idProducto
     *
     * Identificador del producto a asignar
     *
     * @parámetro Integer $idProducto
     * @return IdProducto
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = (integer) $idProducto;
        return $this;
    }

    /**
     * Get idProducto
     *
     * @return null|Integer
     */
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * Set producto
     *
     * Nombre del producto a asignar
     *
     * @parámetro String $producto
     * @return Producto
     */
    public function setProducto($producto)
    {
        $this->producto = (string) $producto;
        return $this;
    }

    /**
     * Get producto
     *
     * @return null|String
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set fechaModificacion
     *
     * Fecha de modificación del registro. Se actualiza en caso de requerir devolver los productos sobrantes al inventario.
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string) $fechaModificacion;
        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return null|Date
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set identificadorModificacion
     *
     * Identificador del usuario que realiza la modificación de valores. Se actualiza en caso de requerir devolver los productos sobrantes al inventario.
     *
     * @parámetro String $identificadorModificacion
     * @return IdentificadorModificacion
     */
    public function setIdentificadorModificacion($identificadorModificacion)
    {
        $this->identificadorModificacion = (string) $identificadorModificacion;
        return $this;
    }

    /**
     * Get identificadorModificacion
     *
     * @return null|String
     */
    public function getIdentificadorModificacion()
    {
        return $this->identificadorModificacion;
    }

    /**
     * Set estado
     *
     * Estado del registro:
     * - activo
     * - inactivo
     * - modificado
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
     * @return DistribucionProductosModelo
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
