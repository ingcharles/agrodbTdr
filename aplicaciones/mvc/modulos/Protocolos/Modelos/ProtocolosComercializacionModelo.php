<?php
/**
 * Modelo ProtocolosComercializacionModelo
 *
 * Este archivo se complementa con el archivo   ProtocolosComercializacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProtocolosComercializacionModelo
 * @package Protocolos
 * @subpackage Modelos
 */
namespace Agrodb\Protocolos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProtocolosComercializacionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProtocoloComercio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $declaracion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroResolucion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fecha;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $observacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $rutaArchivo;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idLocalizacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreProducto;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCreacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaModificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorCreacionProtocoloComercializacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorModificacionProtocoloComercializacion;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_protocolos";

    /**
     * Nombre de la tabla: protocolos_comercializacion
     */
    private $tabla = "protocolos_comercializacion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_protocolo_comercio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_protocolos"."ProtocolosComercializacion_id_protocolo_comercio_seq';

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
            throw new \Exception('Clase Modelo: ProtocolosComercializacionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ProtocolosComercializacionModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_protocolos
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idProtocoloComercio
     *
     *
     *
     * @parámetro Integer $idProtocoloComercio
     * @return IdProtocoloComercio
     */
    public function setIdProtocoloComercio($idProtocoloComercio)
    {
        $this->idProtocoloComercio = (integer) $idProtocoloComercio;
        return $this;
    }

    /**
     * Get idProtocoloComercio
     *
     * @return null|Integer
     */
    public function getIdProtocoloComercio()
    {
        return $this->idProtocoloComercio;
    }

    /**
     * Set declaracion
     *
     *
     *
     * @parámetro String $declaracion
     * @return Declaracion
     */
    public function setDeclaracion($declaracion)
    {
        $this->declaracion = (string) $declaracion;
        return $this;
    }

    /**
     * Get declaracion
     *
     * @return null|String
     */
    public function getDeclaracion()
    {
        return $this->declaracion;
    }

    /**
     * Set numeroResolucion
     *
     *
     *
     * @parámetro String $numeroResolucion
     * @return NumeroResolucion
     */
    public function setNumeroResolucion($numeroResolucion)
    {
        $this->numeroResolucion = (string) $numeroResolucion;
        return $this;
    }

    /**
     * Get numeroResolucion
     *
     * @return null|String
     */
    public function getNumeroResolucion()
    {
        return $this->numeroResolucion;
    }

    /**
     * Set fecha
     *
     *
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
     * Set observacion
     *
     *
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = (string) $observacion;
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
     * Set rutaArchivo
     *
     *
     *
     * @parámetro String $rutaArchivo
     * @return RutaArchivo
     */
    public function setRutaArchivo($rutaArchivo)
    {
        $this->rutaArchivo = (string) $rutaArchivo;
        return $this;
    }

    /**
     * Get rutaArchivo
     *
     * @return null|String
     */
    public function getRutaArchivo()
    {
        return $this->rutaArchivo;
    }

    /**
     * Set idLocalizacion
     *
     *
     *
     * @parámetro Integer $idLocalizacion
     * @return IdLocalizacion
     */
    public function setIdLocalizacion($idLocalizacion)
    {
        $this->idLocalizacion = (integer) $idLocalizacion;
        return $this;
    }

    /**
     * Get idLocalizacion
     *
     * @return null|Integer
     */
    public function getIdLocalizacion()
    {
        return $this->idLocalizacion;
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
     * Set nombrePais
     *
     *
     *
     * @parámetro String $nombrePais
     * @return NombrePais
     */
    public function setNombrePais($nombrePais)
    {
        $this->nombrePais = (string) $nombrePais;
        return $this;
    }

    /**
     * Get nombrePais
     *
     * @return null|String
     */
    public function getNombrePais()
    {
        return $this->nombrePais;
    }

    /**
     * Set nombreProducto
     *
     *
     *
     * @parámetro String $nombreProducto
     * @return NombreProducto
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = (string) $nombreProducto;
        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return null|String
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set fechaCreacion
     *
     *
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
     * Set fechaModificacion
     *
     *
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string) $fechaModificacion;
        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return null|Date
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set identificadorCreacionProtocoloComercializacion
     *
     *
     *
     * @parámetro String $identificadorCreacionProtocoloComercializacion
     * @return IdentificadorCreacionProtocoloComercializacion
     */
    public function setIdentificadorCreacionProtocoloComercializacion($identificadorCreacionProtocoloComercializacion)
    {
        $this->identificadorCreacionProtocoloComercializacion = (string) $identificadorCreacionProtocoloComercializacion;
        return $this;
    }

    /**
     * Get identificadorCreacionProtocoloComercializacion
     *
     * @return null|String
     */
    public function getIdentificadorCreacionProtocoloComercializacion()
    {
        return $this->identificadorCreacionProtocoloComercializacion;
    }

    /**
     * Set identificadorModificacionProtocoloComercializacion
     *
     *
     *
     * @parámetro String $identificadorModificacionProtocoloComercializacion
     * @return IdentificadorModificacionProtocoloComercializacion
     */
    public function setIdentificadorModificacionProtocoloComercializacion($identificadorModificacionProtocoloComercializacion)
    {
        $this->identificadorModificacionProtocoloComercializacion = (string) $identificadorModificacionProtocoloComercializacion;
        return $this;
    }

    /**
     * Get identificadorModificacionProtocoloComercializacion
     *
     * @return null|String
     */
    public function getIdentificadorModificacionProtocoloComercializacion()
    {
        return $this->identificadorModificacionProtocoloComercializacion;
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
     * @return ProtocolosComercializacionModelo
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
