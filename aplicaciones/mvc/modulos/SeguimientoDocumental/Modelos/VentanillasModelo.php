<?php
/**
 * Modelo VentanillasModelo
 *
 * Este archivo se complementa con el archivo   VentanillasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-13
 * @uses    VentanillasModelo
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class VentanillasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de la ventanilla
     */
    protected $idVentanilla;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha y hora de la creación del registro
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
     *      Nombre de la ventanilla
     */
    protected $nombre;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de destino a la que se asigna la ventanilla
     */
    protected $idUnidadDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único de identificación de la ventanilla
     */
    protected $codigoVentanilla;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia en que se encuentra la ventanilla
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro
     */
    protected $estadoVentanilla;

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
     * Nombre de la tabla: ventanillas
     */
    private $tabla = "ventanillas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_ventanilla";

    /**
     * Secuencia
     */
    private $secuencial = 'g_seguimiento_documental"."ventanillas_id_ventanilla_seq';

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
            throw new \Exception('Clase Modelo: VentanillasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: VentanillasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idVentanilla
     *
     * Identificador único de la ventanilla
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
     * Set fechaCreacion
     *
     * Fecha y hora de la creación del registro
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
     * Set nombre
     *
     * Nombre de la ventanilla
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = (string) $nombre;
        return $this;
    }

    /**
     * Get nombre
     *
     * @return null|String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set idUnidadDestino
     *
     * Identificador de la unidad de destino a la que se asigna la ventanilla
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
     * Set codigoVentanilla
     *
     * Código único de identificación de la ventanilla
     *
     * @parámetro String $codigoVentanilla
     * @return CodigoVentanilla
     */
    public function setCodigoVentanilla($codigoVentanilla)
    {
        $this->codigoVentanilla = (string) $codigoVentanilla;
        return $this;
    }

    /**
     * Get codigoVentanilla
     *
     * @return null|String
     */
    public function getCodigoVentanilla()
    {
        return $this->codigoVentanilla;
    }

    /**
     * Set idProvincia
     *
     * Identificador de la provincia en que se encuentra la ventanilla
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
     * Set estado
     *
     * Estado del registro
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstadoVentanilla($estadoVentanilla)
    {
        $this->estadoVentanilla = (string) $estadoVentanilla;
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstadoVentanilla()
    {
        return $this->estadoVentanilla;
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
     * @return VentanillasModelo
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
