<?php
/**
 * Modelo PeriodoRetiroModelo
 *
 * Este archivo se complementa con el archivo   PeriodoRetiroLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PeriodoRetiroModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PeriodoRetiroModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idPeriodoRetiro;

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
    protected $fechaSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la especie
     */
    protected $idEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de descripciónd e la especie seleccionada
     */
    protected $nombreEspecie;

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
     *      Permitirá ingresar el tiempo de retiro del producto
     */
    protected $tiempoRetiro;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de tiempo de retiro del producto
     */
    protected $idUnidadTiempo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Almacenará el símbolo de la unidad de tiempo seleccionada
     */
    protected $nombreUnidadTiempo;

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
     * Nombre de la tabla: periodo_retiro
     */
    private $tabla = "periodo_retiro";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_periodo_retiro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."periodo_retiro_id_periodo_retiro_seq';

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
            throw new \Exception('Clase Modelo: PeriodoRetiroModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PeriodoRetiroModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idPeriodoRetiro
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idPeriodoRetiro
     * @return IdPeriodoRetiro
     */
    public function setIdPeriodoRetiro($idPeriodoRetiro)
    {
        $this->idPeriodoRetiro = (integer) $idPeriodoRetiro;
        return $this;
    }

    /**
     * Get idPeriodoRetiro
     *
     * @return null|Integer
     */
    public function getIdPeriodoRetiro()
    {
        return $this->idPeriodoRetiro;
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
     * Set fechaSolicitud
     *
     * Fecha de creación del registro
     *
     * @parámetro Date $fechaSolicitud
     * @return FechaSolicitud
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = (string) $fechaSolicitud;
        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return null|Date
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set idEspecie
     *
     * Identificador de la especie
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
     * Permitirá ingresar la información de descripciónd e la especie seleccionada
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
     * Permitirá ingresar el tiempo de retiro del producto
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
     * Set idUnidadTiempo
     *
     * Identificador de la unidad de tiempo de retiro del producto
     *
     * @parámetro Integer $idUnidadTiempo
     * @return IdUnidadTiempo
     */
    public function setIdUnidadTiempo($idUnidadTiempo)
    {
        $this->idUnidadTiempo = (integer) $idUnidadTiempo;
        return $this;
    }

    /**
     * Get idUnidadTiempo
     *
     * @return null|Integer
     */
    public function getIdUnidadTiempo()
    {
        return $this->idUnidadTiempo;
    }

    /**
     * Set nombreUnidadTiempo
     *
     * Almacenará el símbolo de la unidad de tiempo seleccionada
     *
     * @parámetro String $nombreUnidadTiempo
     * @return NombreUnidadTiempo
     */
    public function setNombreUnidadTiempo($nombreUnidadTiempo)
    {
        $this->nombreUnidadTiempo = (string) $nombreUnidadTiempo;
        return $this;
    }

    /**
     * Get nombreUnidadTiempo
     *
     * @return null|String
     */
    public function getNombreUnidadTiempo()
    {
        return $this->nombreUnidadTiempo;
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
     * @return PeriodoRetiroModelo
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
