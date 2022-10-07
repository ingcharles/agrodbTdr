<?php
/**
 * Modelo DetalleSolicitudesModelo
 *
 * Este archivo se complementa con el archivo   DetalleSolicitudesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DetalleSolicitudesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleSolicitudesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Clave primaria
     */
    protected $idDetalleSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Tipo de anÃ¡lisis
     */
    protected $idServicio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Solicitud
     */
    protected $idSolicitud;

    /**
     *
     * @var Decimal Campo requerido
     *      Campo visible en el formulario
     *      Tiempo estimado
     */
    protected $tiempoEstimado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      ObservaciÃ³n
     */
    protected $observacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado
     */
    protected $estado;

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: detalle_solicitudes
     */
    private $tabla = "detalle_solicitudes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_detalle_solicitud";
    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."detalle_solicitudes_id_detalle_solicitud_seq';
    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro array|null $datos
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
     * @parÃ¡metro string $name
     * @parÃ¡metro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: DetalleSolicitudesModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: DetalleSolicitudesModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parÃ¡metro array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * 
     * @return type
     */
    function getEsquema() {
        return $this->esquema;
    }

    /**
     * 
     * @param type $esquema
     */
    function setEsquema($esquema) {
        $this->esquema = $esquema;
    }
    
    /**
     * Set idDetalleSolicitud
     *
     * Secuencial (PK) de la tabla de detalle_solicitud
     *
     * @parÃ¡metro Integer $idDetalleSolicitud
     *
     * @return IdDetalleSolicitud
     */
    public function setIdDetalleSolicitud($idDetalleSolicitud)
    {
        $this->idDetalleSolicitud = (integer) $idDetalleSolicitud;
        return $this;
    }

    /**
     * Get idDetalleSolicitud
     *
     * @return null|Integer
     */
    public function getIdDetalleSolicitud()
    {
        return $this->idDetalleSolicitud;
    }

    /**
     * Set idServicio
     *
     * Clave forÃ¡nea de la tabla servicios
     *
     * @parÃ¡metro Integer $idServicio
     *
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
        $this->idServicio =  array_filter(explode(',',$idServicio));
        return $this;
    }

    /**
     * Get idServicio
     *
     * @return null|Integer
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    /**
     * Set idSolicitud
     *
     * Clave forÃ¡nea de la tabla solicitud
     *
     * @parÃ¡metro Integer $idSolicitud
     *
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
     * Set tiempoEstimado
     *
     * Tiempo en que se estimÃ³ para prestar el servicio
     *
     * @parÃ¡metro Decimal $tiempoEstimado
     *
     * @return TiempoEstimado
     */
    public function setTiempoEstimado($tiempoEstimado)
    {
        $this->tiempoEstimado = ValidarDatos::validarDecimal($tiempoEstimado,$this->tiempoEstimado," Tiempo Estimado", self::NO_REQUERIDO,0);
        return $this;
    }

    /**
     * Get tiempoEstimado
     *
     * @return null|Decimal
     */
    public function getTiempoEstimado()
    {
        return $this->tiempoEstimado;
    }

    /**
     * Set observacion
     *
     * ObservaciÃ³n de cada servicio solicitado que se debe considerar para el anÃ¡lisis
     *
     * @parÃ¡metro String $observacion
     *
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = ValidarDatos::validarAlfa($observacion,$this->tabla," Observación", self::NO_REQUERIDO,0);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set estado
     *
     * Estado del registro puedo ser activo o borrado lÃ³gico
     *
     * @parÃ¡metro String $estado
     *
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado,  $this->tabla," Estado", self::REQUERIDO,16);
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
     * @return DetalleSolicitudesModelo
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
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
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
