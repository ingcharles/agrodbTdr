<?php
/**
 * Modelo InspeccionesModelo
 *
 * Este archivo se complementa con el archivo   InspeccionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    InspeccionesModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class InspeccionesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idInspeccion;

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
     *      Identificador del técnico que realiza la inspección
     */
    protected $identificadorInspector;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia donde se realiza la inspección
     */
    protected $idProvinciaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se realiza la inspección
     */
    protected $provinciaInspeccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de la solicitud que se va a revisar
     */
    protected $idSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del área de inspección, centro de acopio
     */
    protected $idAreaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del área que se inspecciona, centro de acopio
     */
    protected $nombreAreaInspeccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto
     */
    protected $idProductoInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto que se inspecciona
     */
    protected $nombreProductoInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en la que el técnico confirma que realizará la inspección
     */
    protected $fechaConfirmacionInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Hora en la que el técnico confirma que realizará la inspección
     */
    protected $horaConfirmacionInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del informe de inspección del técnico
     */
    protected $rutaArchivoInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones ingresadas por el técnico sobre su inspección
     */
    protected $observacionInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número del formulario de inspección realizado a través de tablets
     */
    protected $formularioInspeccionTablet;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de la inspección realizada (veces que se ejecuta la inspección)
     */
    protected $numInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Resultado de la inspección realizada por el técnico:
     *      - Documental (Aprobado)
     *      - Subsanacion
     *      - Rechazado
     */
    protected $estado;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_certificado_fitosanitario";

    /**
     * Nombre de la tabla: inspecciones
     */
    private $tabla = "inspecciones";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_inspeccion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificado_fitosanitario"."inspecciones_id_inspeccion_seq';

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
            throw new \Exception('Clase Modelo: InspeccionesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: InspeccionesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificado_fitosanitario
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idInspeccion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idInspeccion
     * @return IdInspeccion
     */
    public function setIdInspeccion($idInspeccion)
    {
        $this->idInspeccion = (integer) $idInspeccion;
        return $this;
    }

    /**
     * Get idInspeccion
     *
     * @return null|Integer
     */
    public function getIdInspeccion()
    {
        return $this->idInspeccion;
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
     * Set identificadorInspector
     *
     * Identificador del técnico que realiza la inspección
     *
     * @parámetro String $identificadorInspector
     * @return IdentificadorInspector
     */
    public function setIdentificadorInspector($identificadorInspector)
    {
        $this->identificadorInspector = (string) $identificadorInspector;
        return $this;
    }

    /**
     * Get identificadorInspector
     *
     * @return null|String
     */
    public function getIdentificadorInspector()
    {
        return $this->identificadorInspector;
    }

    /**
     * Set idProvinciaInspeccion
     *
     * Identificador de la provincia donde se realiza la inspección
     *
     * @parámetro Integer $idProvinciaInspeccion
     * @return IdProvinciaInspeccion
     */
    public function setIdProvinciaInspeccion($idProvinciaInspeccion)
    {
        $this->idProvinciaInspeccion = (integer) $idProvinciaInspeccion;
        return $this;
    }

    /**
     * Get idProvinciaInspeccion
     *
     * @return null|Integer
     */
    public function getIdProvinciaInspeccion()
    {
        return $this->idProvinciaInspeccion;
    }

    /**
     * Set provinciaInspeccion
     *
     * Nombre de la provincia donde se realiza la inspección
     *
     * @parámetro String $provinciaInspeccion
     * @return ProvinciaInspeccion
     */
    public function setProvinciaInspeccion($provinciaInspeccion)
    {
        $this->provinciaInspeccion = (string) $provinciaInspeccion;
        return $this;
    }

    /**
     * Get provinciaInspeccion
     *
     * @return null|String
     */
    public function getProvinciaInspeccion()
    {
        return $this->provinciaInspeccion;
    }

    /**
     * Set idSolicitud
     *
     * Identificador único de la solicitud que se va a revisar
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
     * Set idAreaInspeccion
     *
     * Identificador del área de inspección, centro de acopio
     *
     * @parámetro Integer $idAreaInspeccion
     * @return IdAreaInspeccion
     */
    public function setIdAreaInspeccion($idAreaInspeccion)
    {
        $this->idAreaInspeccion = (integer) $idAreaInspeccion;
        return $this;
    }

    /**
     * Get idAreaInspeccion
     *
     * @return null|Integer
     */
    public function getIdAreaInspeccion()
    {
        return $this->idAreaInspeccion;
    }

    /**
     * Set nombreAreaInspeccion
     *
     * Nombre del área que se inspecciona, centro de acopio
     *
     * @parámetro String $nombreAreaInspeccion
     * @return NombreAreaInspeccion
     */
    public function setNombreAreaInspeccion($nombreAreaInspeccion)
    {
        $this->nombreAreaInspeccion = (string) $nombreAreaInspeccion;
        return $this;
    }

    /**
     * Get nombreAreaInspeccion
     *
     * @return null|String
     */
    public function getNombreAreaInspeccion()
    {
        return $this->nombreAreaInspeccion;
    }

    /**
     * Set idProductoInspeccion
     *
     * Identificador del producto
     *
     * @parámetro Integer $idProductoInspeccion
     * @return IdProductoInspeccion
     */
    public function setIdProductoInspeccion($idProductoInspeccion)
    {
        $this->idProductoInspeccion = (integer) $idProductoInspeccion;
        return $this;
    }

    /**
     * Get idProductoInspeccion
     *
     * @return null|Integer
     */
    public function getIdProductoInspeccion()
    {
        return $this->idProductoInspeccion;
    }

    /**
     * Set nombreProductoInspeccion
     *
     * Nombre del producto que se inspecciona
     *
     * @parámetro String $nombreProductoInspeccion
     * @return NombreProductoInspeccion
     */
    public function setNombreProductoInspeccion($nombreProductoInspeccion)
    {
        $this->nombreProductoInspeccion = (string) $nombreProductoInspeccion;
        return $this;
    }

    /**
     * Get nombreProductoInspeccion
     *
     * @return null|String
     */
    public function getNombreProductoInspeccion()
    {
        return $this->nombreProductoInspeccion;
    }

    /**
     * Set fechaConfirmacionInspeccion
     *
     * Fecha en la que el técnico confirma que realizará la inspección
     *
     * @parámetro Date $fechaConfirmacionInspeccion
     * @return FechaConfirmacionInspeccion
     */
    public function setFechaConfirmacionInspeccion($fechaConfirmacionInspeccion)
    {
        $this->fechaConfirmacionInspeccion = (string) $fechaConfirmacionInspeccion;
        return $this;
    }

    /**
     * Get fechaConfirmacionInspeccion
     *
     * @return null|Date
     */
    public function getFechaConfirmacionInspeccion()
    {
        return $this->fechaConfirmacionInspeccion;
    }

    /**
     * Set horaConfirmacionInspeccion
     *
     * Hora en la que el técnico confirma que realizará la inspección
     *
     * @parámetro Date $horaConfirmacionInspeccion
     * @return HoraConfirmacionInspeccion
     */
    public function setHoraConfirmacionInspeccion($horaConfirmacionInspeccion)
    {
        $this->horaConfirmacionInspeccion = (string) $horaConfirmacionInspeccion;
        return $this;
    }

    /**
     * Get horaConfirmacionInspeccion
     *
     * @return null|Date
     */
    public function getHoraConfirmacionInspeccion()
    {
        return $this->horaConfirmacionInspeccion;
    }

    /**
     * Set rutaArchivoInspeccion
     *
     * Ruta del informe de inspección del técnico
     *
     * @parámetro String $rutaArchivoInspeccion
     * @return RutaArchivoInspeccion
     */
    public function setRutaArchivoInspeccion($rutaArchivoInspeccion)
    {
        $this->rutaArchivoInspeccion = (string) $rutaArchivoInspeccion;
        return $this;
    }

    /**
     * Get rutaArchivoInspeccion
     *
     * @return null|String
     */
    public function getRutaArchivoInspeccion()
    {
        return $this->rutaArchivoInspeccion;
    }

    /**
     * Set observacionInspeccion
     *
     * Observaciones ingresadas por el técnico sobre su inspección
     *
     * @parámetro String $observacionInspeccion
     * @return ObservacionInspeccion
     */
    public function setObservacionInspeccion($observacionInspeccion)
    {
        $this->observacionInspeccion = (string) $observacionInspeccion;
        return $this;
    }

    /**
     * Get observacionInspeccion
     *
     * @return null|String
     */
    public function getObservacionInspeccion()
    {
        return $this->observacionInspeccion;
    }

    /**
     * Set formularioInspeccionTablet
     *
     * Número del formulario de inspección realizado a través de tablets
     *
     * @parámetro String $formularioInspeccionTablet
     * @return FormularioInspeccionTablet
     */
    public function setFormularioInspeccionTablet($formularioInspeccionTablet)
    {
        $this->formularioInspeccionTablet = (string) $formularioInspeccionTablet;
        return $this;
    }

    /**
     * Get formularioInspeccionTablet
     *
     * @return null|String
     */
    public function getFormularioInspeccionTablet()
    {
        return $this->formularioInspeccionTablet;
    }

    /**
     * Set numInspeccion
     *
     * Número de la inspección realizada (veces que se ejecuta la inspección)
     *
     * @parámetro Integer $numInspeccion
     * @return NumInspeccion
     */
    public function setNumInspeccion($numInspeccion)
    {
        $this->numInspeccion = (integer) $numInspeccion;
        return $this;
    }

    /**
     * Get numInspeccion
     *
     * @return null|Integer
     */
    public function getNumInspeccion()
    {
        return $this->numInspeccion;
    }

    /**
     * Set estado
     *
     * Resultado de la inspección realizada por el técnico:
     * - Documental (Aprobado)
     * - Subsanacion
     * - Rechazado
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (string) $estado;
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
     * @return InspeccionesModelo
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
