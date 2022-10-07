<?php
/**
 * Modelo EstadoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   EstadoAnalisisLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       EstadoAnalisisModelo
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EstadoAnalisisModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Clave primaria
     */
    protected $idEstadoAnalisis;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Detalle solicitud
     */
    protected $idDetalleSolicitud;

    /**
     *
     * @var Date Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Fecha de registro
     */
    protected $fecha;

    /**
     *
     * @var String Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Esato
     */
    protected $estado;

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: estado_analisis
     */
    private $tabla = "estado_analisis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_estado_analisis";

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
        parent::__construct($this->esquema, $this->tabla);
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
            throw new \Exception('Clase Modelo: EstadoAnalisisModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: EstadoAnalisisModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idEstadoAnalisis
     *
     * Identificador de la tabla estado_analisis
     *
     * @parÃ¡metro Integer $idEstadoAnalisis
     *
     * @return IdEstadoAnalisis
     */
    public function setIdEstadoAnalisis($idEstadoAnalisis)
    {
        $this->idEstadoAnalisis = (integer) $idEstadoAnalisis;
        return $this;
    }

    /**
     * Get idEstadoAnalisis
     *
     * @return null|Integer
     */
    public function getIdEstadoAnalisis()
    {
        return $this->idEstadoAnalisis;
    }

    /**
     * Set idDetalleSolicitud
     *
     * Clave forÃ¡nea de la tabla detalle_solicitud
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
     * Set fecha
     *
     * Fecha de registro
     *
     * @parÃ¡metro Date $fecha
     *
     * @return Fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = ValidarDatos::validarFecha($fecha, $this->tabla, "Fecha de Registro", self::REQUERIDO, 0);
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
     * Set estado
     *
     * Estado del registro
     *
     * @parÃ¡metro String $estado
     *
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, "Estado", self::REQUERIDO,16);
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
     * @return EstadoAnalisisModelo
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