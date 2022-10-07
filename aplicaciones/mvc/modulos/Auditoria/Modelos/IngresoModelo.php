<?php
/**
 * Modelo IngresoModelo
 *
 * Este archivo se complementa con el archivo   IngresoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       IngresoModelo
 * @package auditoria
 * @subpackage Modelos
 */
namespace Agrodb\Auditoria\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class IngresoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      llave primaria de tabla ingreso
     */
    protected $idIngreso;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idLog;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificador;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaInicio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $accion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaFin;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $intento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $ipAcceso;

    /**
     * Campos del formulario
     * 
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_auditoria";

    /**
     * Nombre de la tabla: ingreso
     */
    private $tabla = "ingreso";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_ingreso";

    /**
     * Secuencia
     */
    private $secuencial = '"Ingreso_"id_ingreso_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro array|null $datos
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
     * @parámetro string $name
     * @parámetro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: IngresoModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parámetro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: IngresoModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parámetro array $datos
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
     * 
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_auditoria
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idIngreso
     *
     * llave primaria de tabla ingreso
     *
     * @parámetro Integer $idIngreso
     * 
     * @return IdIngreso
     */
    public function setIdIngreso($idIngreso)
    {
        $this->idIngreso = (integer) $idIngreso;
        return $this;
    }

    /**
     * Get idIngreso
     *
     * @return null|Integer
     */
    public function getIdIngreso()
    {
        return $this->idIngreso;
    }

    /**
     * Set idLog
     *
     * Identificador de la tabla log llave foranea
     *
     * @parámetro Integer $idLog
     * 
     * @return IdLog
     */
    public function setIdLog($idLog)
    {
        $this->idLog = (integer) $idLog;
        return $this;
    }

    /**
     * Get idLog
     *
     * @return null|Integer
     */
    public function getIdLog()
    {
        return $this->idLog;
    }

    /**
     * Set identificador
     *
     * Identificador del usuario
     *
     * @parámetro String $identificador
     * 
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
     * Set fechaInicio
     *
     * Fecha de ingreso de regitro
     *
     * @parámetro Date $fechaInicio
     * 
     * @return FechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = (string) $fechaInicio;
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return null|Date
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set accion
     *
     * Observación realizada
     *
     * @parámetro String $accion
     * 
     * @return Accion
     */
    public function setAccion($accion)
    {
        $this->accion = (string) $accion;
        return $this;
    }

    /**
     * Get accion
     *
     * @return null|String
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set fechaFin
     *
     * Fecha de finalización
     *
     * @parámetro Date $fechaFin
     * 
     * @return FechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = (string) $fechaFin;
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return null|Date
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set intento
     *
     * Cantidad de intentos realizados por el usuario
     *
     * @parámetro Integer $intento
     * 
     * @return Intento
     */
    public function setIntento($intento)
    {
        $this->intento = (integer) $intento;
        return $this;
    }

    /**
     * Get intento
     *
     * @return null|Integer
     */
    public function getIntento()
    {
        return $this->intento;
    }

    /**
     * Set tipo
     *
     * Tipo de proceso ejecutado: SIN_USUARIO, INACTIVO, ERROR, EXITO, LOG
     *
     * @parámetro String $tipo
     * 
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = (string) $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set ipAcceso
     *
     * Ip de acceso/registro del usaurio
     *
     * @parámetro String $ipAcceso
     * 
     * @return IpAcceso
     */
    public function setIpAcceso($ipAcceso)
    {
        $this->ipAcceso = (string) $ipAcceso;
        return $this;
    }

    /**
     * Get ipAcceso
     *
     * @return null|String
     */
    public function getIpAcceso()
    {
        return $this->ipAcceso;
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
     * @return IngresoModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
