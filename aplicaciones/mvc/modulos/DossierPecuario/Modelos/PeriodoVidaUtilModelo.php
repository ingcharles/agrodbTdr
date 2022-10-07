<?php
/**
 * Modelo PeriodoVidaUtilModelo
 *
 * Este archivo se complementa con el archivo   PeriodoVidaUtilLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PeriodoVidaUtilModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PeriodoVidaUtilModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idPeriodoVidaUtil;

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
     *      Permitirá ingresar la información de la descripción del envase del producto
     */
    protected $descripcionEnvase;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el tiempo de vida útl del producto
     */
    protected $periodoVidaUtil;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de tiempo
     */
    protected $idUnidadTiempo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Símbolo de la unidad de tiempo del período de vida útil
     */
    protected $nombreUnidadPeriodoVida;

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
     * Nombre de la tabla: periodo_vida_util
     */
    private $tabla = "periodo_vida_util";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_periodo_vida_util";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."periodo_vida_util_id_periodo_vida_util_seq';

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
            throw new \Exception('Clase Modelo: PeriodoVidaUtilModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PeriodoVidaUtilModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idPeriodoVidaUtil
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idPeriodoVidaUtil
     * @return IdPeriodoVidaUtil
     */
    public function setIdPeriodoVidaUtil($idPeriodoVidaUtil)
    {
        $this->idPeriodoVidaUtil = (integer) $idPeriodoVidaUtil;
        return $this;
    }

    /**
     * Get idPeriodoVidaUtil
     *
     * @return null|Integer
     */
    public function getIdPeriodoVidaUtil()
    {
        return $this->idPeriodoVidaUtil;
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
     * Set descripcionEnvase
     *
     * Permitirá ingresar la información de la descripción del envase del producto
     *
     * @parámetro String $descripcionEnvase
     * @return DescripcionEnvase
     */
    public function setDescripcionEnvase($descripcionEnvase)
    {
        $this->descripcionEnvase = (string) $descripcionEnvase;
        return $this;
    }

    /**
     * Get descripcionEnvase
     *
     * @return null|String
     */
    public function getDescripcionEnvase()
    {
        return $this->descripcionEnvase;
    }

    /**
     * Set periodoVidaUtil
     *
     * Permitirá ingresar el tiempo de vida útl del producto
     *
     * @parámetro String $periodoVidaUtil
     * @return PeriodoVidaUtil
     */
    public function setPeriodoVidaUtil($periodoVidaUtil)
    {
        $this->periodoVidaUtil = (string) $periodoVidaUtil;
        return $this;
    }

    /**
     * Get periodoVidaUtil
     *
     * @return null|String
     */
    public function getPeriodoVidaUtil()
    {
        return $this->periodoVidaUtil;
    }

    /**
     * Set idUnidadTiempo
     *
     * Identificador de la unidad de tiempo
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
     * Set nombreUnidadPeriodoVida
     *
     * Símbolo de la unidad de tiempo del período de vida útil
     *
     * @parámetro String $nombreUnidadPeriodoVida
     * @return NombreUnidadPeriodoVida
     */
    public function setNombreUnidadPeriodoVida($nombreUnidadPeriodoVida)
    {
        $this->nombreUnidadPeriodoVida = (string) $nombreUnidadPeriodoVida;
        return $this;
    }

    /**
     * Get nombreUnidadPeriodoVida
     *
     * @return null|String
     */
    public function getNombreUnidadPeriodoVida()
    {
        return $this->nombreUnidadPeriodoVida;
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
     * @return PeriodoVidaUtilModelo
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
