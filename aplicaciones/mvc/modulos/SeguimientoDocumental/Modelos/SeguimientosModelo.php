<?php
/**
 * Modelo SeguimientosModelo
 *
 * Este archivo se complementa con el archivo   SeguimientosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    SeguimientosModelo
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SeguimientosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del seguimiento
     */
    protected $idSeguimiento;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del trámite
     */
    protected $idTramite;

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
     *      Identificador de la ventanilla donde se registra el seguimiento
     */
    protected $idVentanilla;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
     */
    protected $identificador;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en que se realiza el seguimiento
     */
    protected $fecha;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la persona que recibe el trámite para su atención
     */
    protected $personaRecibe;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de destino donde se da atención al trámite y realiza el seguimiento
     */
    protected $idUnidadDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones del seguimiento realizado
     */
    protected $observacionesSeguimiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro
     */
    protected $estadoSeguimiento;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_seguimiento_documental";

    /**
     * Nombre de la tabla: seguimientos
     */
    private $tabla = "seguimientos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_seguimiento";

    /**
     * Secuencia
     */
    private $secuencial = 'g_seguimiento_documental"."seguimientos_id_seguimiento_seq';

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
            throw new \Exception('Clase Modelo: SeguimientosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SeguimientosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_seguimiento_documental
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSeguimiento
     *
     * Identificador único del seguimiento
     *
     * @parámetro Integer $idSeguimiento
     * @return IdSeguimiento
     */
    public function setIdSeguimiento($idSeguimiento)
    {
        $this->idSeguimiento = (integer) $idSeguimiento;
        return $this;
    }

    /**
     * Get idSeguimiento
     *
     * @return null|Integer
     */
    public function getIdSeguimiento()
    {
        return $this->idSeguimiento;
    }

    /**
     * Set idTramite
     *
     * Identificador del trámite
     *
     * @parámetro Integer $idTramite
     * @return IdTramite
     */
    public function setIdTramite($idTramite)
    {
        $this->idTramite = (integer) $idTramite;
        return $this;
    }

    /**
     * Get idTramite
     *
     * @return null|Integer
     */
    public function getIdTramite()
    {
        return $this->idTramite;
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
     * Set idVentanilla
     *
     * Identificador de la ventanilla donde se registra el seguimiento
     *
     * @parámetro Integer $idVentanilla
     * @return IdVentanilla
     */
    public function setIdVentanilla($idVentanilla)
    {
        $this->idVentanilla = (integer) $idVentanilla;
        return $this;
    }

    /**
     * Get idVentanilla
     *
     * @return null|Integer
     */
    public function getIdVentanilla()
    {
        return $this->idVentanilla;
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
     * Set fecha
     *
     * Fecha en que se realiza el seguimiento
     *
     * @parámetro Date $fecha
     * @return Fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = (string) $fecha;
        return $this;
    }

    /**
     * Get fecha
     *
     * @return null|Date
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set personaRecibe
     *
     * Nombre de la persona que recibe el trámite para su atención
     *
     * @parámetro String $personaRecibe
     * @return PersonaRecibe
     */
    public function setPersonaRecibe($personaRecibe)
    {
        $this->personaRecibe = (string) $personaRecibe;
        return $this;
    }

    /**
     * Get personaRecibe
     *
     * @return null|String
     */
    public function getPersonaRecibe()
    {
        return $this->personaRecibe;
    }

    /**
     * Set idUnidadDestino
     *
     * Identificador de la unidad de destino donde se da atención al trámite y realiza el seguimiento
     *
     * @parámetro String $idUnidadDestino
     * @return IdUnidadDestino
     */
    public function setIdUnidadDestino($idUnidadDestino)
    {
        $this->idUnidadDestino = (string) $idUnidadDestino;
        return $this;
    }

    /**
     * Get idUnidadDestino
     *
     * @return null|String
     */
    public function getIdUnidadDestino()
    {
        return $this->idUnidadDestino;
    }

    /**
     * Set observaciones
     *
     * Observaciones del seguimiento realizado
     *
     * @parámetro String $observacionesSeguimiento
     * @return ObservacionesSeguimiento
     */
    public function setObservacionesSeguimiento($observacionesSeguimiento)
    {
        $this->observacionesSeguimiento = (string) $observacionesSeguimiento;
        return $this;
    }

    /**
     * Get observacionesSeguimiento
     *
     * @return null|String
     */
    public function getObservacionesSeguimiento()
    {
        return $this->observacionesSeguimiento;
    }

    /**
     * Set estadoSeguimiento
     *
     * Estado del registro
     *
     * @parámetro String $estadoSeguimiento
     * @return EstadoSeguimiento
     */
    public function setEstadoSeguimiento($estadoSeguimiento)
    {
        $this->estadoSeguimiento = (string) $estadoSeguimiento;
        return $this;
    }

    /**
     * Get estadoSeguimiento
     *
     * @return null|String
     */
    public function getEstadoSeguimiento()
    {
        return $this->estadoSeguimiento;
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
     * @return SeguimientosModelo
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
