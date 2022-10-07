<?php
/**
 * Modelo MiembrosModelo
 *
 * Este archivo se complementa con el archivo   MiembrosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-15
 * @uses    MiembrosModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MiembrosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro
     */
    protected $idMiembro;

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
     *      Identificador del miembro de la asociación
     */
    protected $identificadorMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del miembro de la asociación
     */
    protected $nombreMiembro;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idOrganizacionEcuestre;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del catastro de predio de équidos en donde está registrada la información del usuario y sus animales
     */
    protected $idCatastroPredioEquidos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estadoMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Información del motivo por el cual se cambia de estado el registro
     */
    protected $motivoCambio;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de modificación del registro
     */
    protected $fechaModificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Documento de respaldo de modificación de estado del registro
     */
    protected $rutaArchivo;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_pasaporte_equino";

    /**
     * Nombre de la tabla: miembros
     */
    private $tabla = "miembros";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_miembro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."miembros_id_miembro_seq';

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
            throw new \Exception('Clase Modelo: MiembrosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: MiembrosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_pasaporte_equino
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idMiembro
     *
     * Identificador del registro
     *
     * @parámetro Integer $idMiembro
     * @return IdMiembro
     */
    public function setIdMiembro($idMiembro)
    {
        $this->idMiembro = (integer) $idMiembro;
        return $this;
    }

    /**
     * Get idMiembro
     *
     * @return null|Integer
     */
    public function getIdMiembro()
    {
        return $this->idMiembro;
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
     * Set identificadorMiembro
     *
     * Identificador del miembro de la asociación
     *
     * @parámetro String $identificadorMiembro
     * @return IdentificadorMiembro
     */
    public function setIdentificadorMiembro($identificadorMiembro)
    {
        $this->identificadorMiembro = (string) $identificadorMiembro;
        return $this;
    }

    /**
     * Get identificadorMiembro
     *
     * @return null|String
     */
    public function getIdentificadorMiembro()
    {
        return $this->identificadorMiembro;
    }

    /**
     * Set nombreMiembro
     *
     * Nombre del miembro de la asociación
     *
     * @parámetro String $nombreMiembro
     * @return NombreMiembro
     */
    public function setNombreMiembro($nombreMiembro)
    {
        $this->nombreMiembro = (string) $nombreMiembro;
        return $this;
    }

    /**
     * Get nombreMiembro
     *
     * @return null|String
     */
    public function getNombreMiembro()
    {
        return $this->nombreMiembro;
    }

    /**
     * Set idOrganizacionEcuestre
     *
     *
     *
     * @parámetro Integer $idOrganizacionEcuestre
     * @return IdOrganizacionEcuestre
     */
    public function setIdOrganizacionEcuestre($idOrganizacionEcuestre)
    {
        $this->idOrganizacionEcuestre = (integer) $idOrganizacionEcuestre;
        return $this;
    }

    /**
     * Get idOrganizacionEcuestre
     *
     * @return null|Integer
     */
    public function getIdOrganizacionEcuestre()
    {
        return $this->idOrganizacionEcuestre;
    }

    /**
     * Set idCatastroPredioEquidos
     *
     * Identificador del catastro de predio de équidos en donde está registrada la información del usuario y sus animales
     *
     * @parámetro Integer $idCatastroPredioEquidos
     * @return IdCatastroPredioEquidos
     */
    public function setIdCatastroPredioEquidos($idCatastroPredioEquidos)
    {
        $this->idCatastroPredioEquidos = (integer) $idCatastroPredioEquidos;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidos
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidos()
    {
        return $this->idCatastroPredioEquidos;
    }

    /**
     * Set estadoMiembro
     *
     *
     *
     * @parámetro String $estadoMiembro
     * @return EstadoMiembro
     */
    public function setEstadoMiembro($estadoMiembro)
    {
        $this->estadoMiembro = (string) $estadoMiembro;
        return $this;
    }

    /**
     * Get estadoMiembro
     *
     * @return null|String
     */
    public function getEstadoMiembro()
    {
        return $this->estadoMiembro;
    }

    /**
     * Set motivoCambio
     *
     * Información del motivo por el cual se cambia de estado el registro
     *
     * @parámetro String $motivoCambio
     * @return MotivoCambio
     */
    public function setMotivoCambio($motivoCambio)
    {
        $this->motivoCambio = (string) $motivoCambio;
        return $this;
    }

    /**
     * Get motivoCambio
     *
     * @return null|String
     */
    public function getMotivoCambio()
    {
        return $this->motivoCambio;
    }

    /**
     * Set fechaModificacion
     *
     * Fecha de modificación del registro
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
     * Set rutaArchivo
     *
     * Documento de respaldo de modificación de estado del registro
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
     * @return MiembrosModelo
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