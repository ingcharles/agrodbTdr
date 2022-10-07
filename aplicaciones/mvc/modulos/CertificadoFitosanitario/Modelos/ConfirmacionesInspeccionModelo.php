<?php
/**
 * Modelo ConfirmacionesInspeccionModelo
 *
 * Este archivo se complementa con el archivo   ConfirmacionesInspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ConfirmacionesInspeccionModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ConfirmacionesInspeccionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idConfirmacionInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorInspector;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProvinciaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provinciaInspeccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idAreaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreAreaInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaConfirmacionInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $horaConfirmacionInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
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
     * Nombre de la tabla: confirmaciones_inspeccion
     */
    private $tabla = "confirmaciones_inspeccion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_confirmacion_inspeccion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificado_fitosanitario"."confirmaciones_inspeccion_id_confirmacion_inspeccion_seq';

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
            throw new \Exception('Clase Modelo: ConfirmacionesInspeccionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ConfirmacionesInspeccionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idConfirmacionInspeccion
     *
     *
     *
     * @parámetro Integer $idConfirmacionInspeccion
     * @return IdConfirmacionInspeccion
     */
    public function setIdConfirmacionInspeccion($idConfirmacionInspeccion)
    {
        $this->idConfirmacionInspeccion = (integer) $idConfirmacionInspeccion;
        return $this;
    }

    /**
     * Get idConfirmacionInspeccion
     *
     * @return null|Integer
     */
    public function getIdConfirmacionInspeccion()
    {
        return $this->idConfirmacionInspeccion;
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
     * Set identificadorInspector
     *
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     * Set fechaConfirmacionInspeccion
     *
     *
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
     *
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
     * Set estado
     *
     *
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
     * @return ConfirmacionesInspeccionModelo
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
