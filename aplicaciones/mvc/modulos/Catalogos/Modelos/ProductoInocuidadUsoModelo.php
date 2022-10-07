<?php
/**
 * Modelo ProductoInocuidadUsoModelo
 *
 * Este archivo se complementa con el archivo   ProductoInocuidadUsoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-08-27
 * @uses    ProductoInocuidadUsoModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProductoInocuidadUsoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProductoUso;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idUso;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idAplicacionProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Especificación de la especie seleccionada
     */
    protected $nombreEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Describe la aplicación que se dará al producto:
     *      - Especie
     *      - Instalacion
     */
    protected $aplicadoA;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permite describir la instalación en la que se aplicará el producto
     */
    protected $instalacion;

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
     * Nombre de la tabla: producto_inocuidad_uso
     */
    private $tabla = "producto_inocuidad_uso";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_producto_uso";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."producto_inocuidad_uso_id_producto_uso_seq';

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
            throw new \Exception('Clase Modelo: ProductoInocuidadUsoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ProductoInocuidadUsoModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idProductoUso
     *
     *
     *
     * @parámetro Integer $idProductoUso
     * @return IdProductoUso
     */
    public function setIdProductoUso($idProductoUso)
    {
        $this->idProductoUso = (integer) $idProductoUso;
        return $this;
    }

    /**
     * Get idProductoUso
     *
     * @return null|Integer
     */
    public function getIdProductoUso()
    {
        return $this->idProductoUso;
    }

    /**
     * Set idProducto
     *
     *
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
     * Set idUso
     *
     *
     *
     * @parámetro Integer $idUso
     * @return IdUso
     */
    public function setIdUso($idUso)
    {
        $this->idUso = (integer) $idUso;
        return $this;
    }

    /**
     * Get idUso
     *
     * @return null|Integer
     */
    public function getIdUso()
    {
        return $this->idUso;
    }

    /**
     * Set idAplicacionProducto
     *
     *
     *
     * @parámetro Integer $idAplicacionProducto
     * @return IdAplicacionProducto
     */
    public function setIdAplicacionProducto($idAplicacionProducto)
    {
        $this->idAplicacionProducto = (integer) $idAplicacionProducto;
        return $this;
    }

    /**
     * Get idAplicacionProducto
     *
     * @return null|Integer
     */
    public function getIdAplicacionProducto()
    {
        return $this->idAplicacionProducto;
    }

    /**
     * Set idEspecie
     *
     *
     *
     * @parámetro Integer $idEspecie
     * @return IdEspecie
     */
    public function setIdEspecie($idEspecie)
    {
        $this->idEspecie = (integer) $idEspecie;
        return $this;
    }

    /**
     * Get idEspecie
     *
     * @return null|Integer
     */
    public function getIdEspecie()
    {
        return $this->idEspecie;
    }

    /**
     * Set nombreEspecie
     *
     * Especificación de la especie seleccionada
     *
     * @parámetro String $nombreEspecie
     * @return NombreEspecie
     */
    public function setNombreEspecie($nombreEspecie)
    {
        $this->nombreEspecie = (string) $nombreEspecie;
        return $this;
    }

    /**
     * Get nombreEspecie
     *
     * @return null|String
     */
    public function getNombreEspecie()
    {
        return $this->nombreEspecie;
    }

    /**
     * Set aplicadoA
     *
     * Describe la aplicación que se dará al producto:
     * - Especie
     * - Instalacion
     *
     * @parámetro String $aplicadoA
     * @return AplicadoA
     */
    public function setAplicadoA($aplicadoA)
    {
        $this->aplicadoA = (string) $aplicadoA;
        return $this;
    }

    /**
     * Get aplicadoA
     *
     * @return null|String
     */
    public function getAplicadoA()
    {
        return $this->aplicadoA;
    }

    /**
     * Set instalacion
     *
     * Permite describir la instalación en la que se aplicará el producto
     *
     * @parámetro String $instalacion
     * @return Instalacion
     */
    public function setInstalacion($instalacion)
    {
        $this->instalacion = (string) $instalacion;
        return $this;
    }

    /**
     * Get instalacion
     *
     * @return null|String
     */
    public function getInstalacion()
    {
        return $this->instalacion;
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
     * @return ProductoInocuidadUsoModelo
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
