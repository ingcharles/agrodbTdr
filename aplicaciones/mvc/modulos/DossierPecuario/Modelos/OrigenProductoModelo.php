<?php
/**
 * Modelo OrigenProductoModelo
 *
 * Este archivo se complementa con el archivo   OrigenProductoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    OrigenProductoModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class OrigenProductoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idOrigenProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud de registro de producto
     */
    protected $idSolicitud;

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
     *      Tipo de origen de fabricación del producto:
     *      - Titular del registro
     *      - Elaborador por contrato nacional
     *      - Extranjero
     */
    protected $origenFabricacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del fabricante del producto, solo para titular del registro y elaborador por contrato nacional
     */
    protected $identificadorFabricante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del fabricante del producto, razón social o nombre del fabricante extranjero
     */
    protected $nombreFabricante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Dirección del fabricante, alica para titular del registro y elaborador por contrato nacional
     */
    protected $direccionFabricante;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia del fabricante
     */
    protected $idProvinciaFabricante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia del fabricante
     */
    protected $provinciaFabricante;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro del fabricante del módulo Fabricantes en el Exterior
     */
    protected $idFabricanteExtranjero;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del país de origen del producto
     */
    protected $idPais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del país de origen del producto
     */
    protected $pais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Listado de tipos de productos autorizados al fabricante
     */
    protected $tipoProductoFabricante;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_dossier_pecuario_mvc";

    /**
     * Nombre de la tabla: origen_producto
     */
    private $tabla = "origen_producto";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_origen_producto";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."origen_producto_id_origen_producto_seq';

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
            throw new \Exception('Clase Modelo: OrigenProductoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: OrigenProductoModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_dossier_pecuario_mvc
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idOrigenProducto
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idOrigenProducto
     * @return IdOrigenProducto
     */
    public function setIdOrigenProducto($idOrigenProducto)
    {
        $this->idOrigenProducto = (integer) $idOrigenProducto;
        return $this;
    }

    /**
     * Get idOrigenProducto
     *
     * @return null|Integer
     */
    public function getIdOrigenProducto()
    {
        return $this->idOrigenProducto;
    }

    /**
     * Set idSolicitud
     *
     * Identificador de la solicitud de registro de producto
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = (integer) $idSolicitud;
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
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
     * Set origenFabricacion
     *
     * Tipo de origen de fabricación del producto:
     * - Titular del registro
     * - Elaborador por contrato nacional
     * - Extranjero
     *
     * @parámetro String $origenFabricacion
     * @return OrigenFabricacion
     */
    public function setOrigenFabricacion($origenFabricacion)
    {
        $this->origenFabricacion = (string) $origenFabricacion;
        return $this;
    }

    /**
     * Get origenFabricacion
     *
     * @return null|String
     */
    public function getOrigenFabricacion()
    {
        return $this->origenFabricacion;
    }

    /**
     * Set identificadorFabricante
     *
     * Identificador del fabricante del producto, solo para titular del registro y elaborador por contrato nacional
     *
     * @parámetro String $identificadorFabricante
     * @return IdentificadorFabricante
     */
    public function setIdentificadorFabricante($identificadorFabricante)
    {
        $this->identificadorFabricante = (string) $identificadorFabricante;
        return $this;
    }

    /**
     * Get identificadorFabricante
     *
     * @return null|String
     */
    public function getIdentificadorFabricante()
    {
        return $this->identificadorFabricante;
    }

    /**
     * Set nombreFabricante
     *
     * Nombre del fabricante del producto, razón social o nombre del fabricante extranjero
     *
     * @parámetro String $nombreFabricante
     * @return NombreFabricante
     */
    public function setNombreFabricante($nombreFabricante)
    {
        $this->nombreFabricante = (string) $nombreFabricante;
        return $this;
    }

    /**
     * Get nombreFabricante
     *
     * @return null|String
     */
    public function getNombreFabricante()
    {
        return $this->nombreFabricante;
    }

    /**
     * Set direccionFabricante
     *
     * Dirección del fabricante, alica para titular del registro y elaborador por contrato nacional
     *
     * @parámetro String $direccionFabricante
     * @return DireccionFabricante
     */
    public function setDireccionFabricante($direccionFabricante)
    {
        $this->direccionFabricante = (string) $direccionFabricante;
        return $this;
    }

    /**
     * Get direccionFabricante
     *
     * @return null|String
     */
    public function getDireccionFabricante()
    {
        return $this->direccionFabricante;
    }

    /**
     * Set idProvinciaFabricante
     *
     * Identificador de la provincia del fabricante
     *
     * @parámetro Integer $idProvinciaFabricante
     * @return IdProvinciaFabricante
     */
    public function setIdProvinciaFabricante($idProvinciaFabricante)
    {
        $this->idProvinciaFabricante = (integer) $idProvinciaFabricante;
        return $this;
    }

    /**
     * Get idProvinciaFabricante
     *
     * @return null|Integer
     */
    public function getIdProvinciaFabricante()
    {
        return $this->idProvinciaFabricante;
    }

    /**
     * Set provinciaFabricante
     *
     * Nombre de la provincia del fabricante
     *
     * @parámetro String $provinciaFabricante
     * @return ProvinciaFabricante
     */
    public function setProvinciaFabricante($provinciaFabricante)
    {
        $this->provinciaFabricante = (string) $provinciaFabricante;
        return $this;
    }

    /**
     * Get provinciaFabricante
     *
     * @return null|String
     */
    public function getProvinciaFabricante()
    {
        return $this->provinciaFabricante;
    }

    /**
     * Set idFabricanteExtranjero
     *
     * Identificador del registro del fabricante del módulo Fabricantes en el Exterior
     *
     * @parámetro Integer $idFabricanteExtranjero
     * @return IdFabricanteExtranjero
     */
    public function setIdFabricanteExtranjero($idFabricanteExtranjero)
    {
        $this->idFabricanteExtranjero = (integer) $idFabricanteExtranjero;
        return $this;
    }

    /**
     * Get idFabricanteExtranjero
     *
     * @return null|Integer
     */
    public function getIdFabricanteExtranjero()
    {
        return $this->idFabricanteExtranjero;
    }

    /**
     * Set idPais
     *
     * Identificador del país de origen del producto
     *
     * @parámetro Integer $idPais
     * @return IdPais
     */
    public function setIdPais($idPais)
    {
        $this->idPais = (integer) $idPais;
        return $this;
    }

    /**
     * Get idPais
     *
     * @return null|Integer
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Set pais
     *
     * Nombre del país de origen del producto
     *
     * @parámetro String $pais
     * @return Pais
     */
    public function setPais($pais)
    {
        $this->pais = (string) $pais;
        return $this;
    }

    /**
     * Get pais
     *
     * @return null|String
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set tipoProductoFabricante
     *
     * Listado de tipos de productos autorizados al fabricante
     *
     * @parámetro String $tipoProductoFabricante
     * @return TipoProductoFabricante
     */
    public function setTipoProductoFabricante($tipoProductoFabricante)
    {
        $this->tipoProductoFabricante = (string) $tipoProductoFabricante;
        return $this;
    }

    /**
     * Get tipoProductoFabricante
     *
     * @return null|String
     */
    public function getTipoProductoFabricante()
    {
        return $this->tipoProductoFabricante;
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
     * @return OrigenProductoModelo
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
