<?php
/**
 * Modelo TiempoRetiroModelo
 *
 * Este archivo se complementa con el archivo   TiempoRetiroLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    TiempoRetiroModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class TiempoRetiroModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idTiempoRetiro;

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
     *      Información del ingrediente activo de la fórmula maestra
     */
    protected $ingredienteActivo;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto de consumo
     */
    protected $idProductoConsumo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Cantidad de tiempo para el retiro de productos
     */
    protected $tiempoRetiro;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de tiempo
     */
    protected $idUnidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de tiempo
     */
    protected $nombreUnidad;

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
     * Nombre de la tabla: tiempo_retiro
     */
    private $tabla = "tiempo_retiro";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_tiempo_retiro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."tiempo_retiro_id_tiempo_retiro_seq';

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
            throw new \Exception('Clase Modelo: TiempoRetiroModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: TiempoRetiroModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idTiempoRetiro
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idTiempoRetiro
     * @return IdTiempoRetiro
     */
    public function setIdTiempoRetiro($idTiempoRetiro)
    {
        $this->idTiempoRetiro = (integer) $idTiempoRetiro;
        return $this;
    }

    /**
     * Get idTiempoRetiro
     *
     * @return null|Integer
     */
    public function getIdTiempoRetiro()
    {
        return $this->idTiempoRetiro;
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
     * Set ingredienteActivo
     *
     * Información del ingrediente activo de la fórmula maestra
     *
     * @parámetro String $ingredienteActivo
     * @return IngredienteActivo
     */
    public function setIngredienteActivo($ingredienteActivo)
    {
        $this->ingredienteActivo = (string) $ingredienteActivo;
        return $this;
    }

    /**
     * Get ingredienteActivo
     *
     * @return null|String
     */
    public function getIngredienteActivo()
    {
        return $this->ingredienteActivo;
    }

    /**
     * Set idProductoConsumo
     *
     * Identificador del producto de consumo
     *
     * @parámetro Integer $idProductoConsumo
     * @return IdProductoConsumo
     */
    public function setIdProductoConsumo($idProductoConsumo)
    {
        $this->idProductoConsumo = (integer) $idProductoConsumo;
        return $this;
    }

    /**
     * Get idProductoConsumo
     *
     * @return null|Integer
     */
    public function getIdProductoConsumo()
    {
        return $this->idProductoConsumo;
    }

    /**
     * Set tiempoRetiro
     *
     * Cantidad de tiempo para el retiro de productos
     *
     * @parámetro String $tiempoRetiro
     * @return TiempoRetiro
     */
    public function setTiempoRetiro($tiempoRetiro)
    {
        $this->tiempoRetiro = (string) $tiempoRetiro;
        return $this;
    }

    /**
     * Get tiempoRetiro
     *
     * @return null|String
     */
    public function getTiempoRetiro()
    {
        return $this->tiempoRetiro;
    }

    /**
     * Set idUnidad
     *
     * Identificador de la unidad de tiempo
     *
     * @parámetro Integer $idUnidad
     * @return IdUnidad
     */
    public function setIdUnidad($idUnidad)
    {
        $this->idUnidad = (integer) $idUnidad;
        return $this;
    }

    /**
     * Get idUnidad
     *
     * @return null|Integer
     */
    public function getIdUnidad()
    {
        return $this->idUnidad;
    }

    /**
     * Set nombreUnidad
     *
     * Permitirá ingresar el símbolo de la unidad de tiempo
     *
     * @parámetro String $nombreUnidad
     * @return NombreUnidad
     */
    public function setNombreUnidad($nombreUnidad)
    {
        $this->nombreUnidad = (string) $nombreUnidad;
        return $this;
    }

    /**
     * Get nombreUnidad
     *
     * @return null|String
     */
    public function getNombreUnidad()
    {
        return $this->nombreUnidad;
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
     * @return TiempoRetiroModelo
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
