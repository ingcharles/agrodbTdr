<?php
/**
 * Modelo ComposicionModelo
 *
 * Este archivo se complementa con el archivo   ComposicionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    ComposicionModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ComposicionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idComposicion;

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
     *      Información de la cantidad del compuesto
     */
    protected $cada;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de medida, se usará 0 para el tipo Otro
     */
    protected $idUnidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de medida seleccionada (del catálogo de unidades de medida) o el nombre de la unidad de medida ingresada por el usuario
     */
    protected $nombreUnidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del tipo de componente
     */
    protected $idTipoComponente;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del nombre de componente o ingrediente activo
     */
    protected $idNombreComponente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Información de la cantidad del compuesto
     */
    protected $cantidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro del catálogo de unidades de unidades de medida, usará 0 para Otros
     */
    protected $idUnidadComponente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de medida seleccionada del catálogo o la información ingresada por el usuario en el caso de Otro
     */
    protected $nombreUnidadComponente;

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
     * Nombre de la tabla: composicion
     */
    private $tabla = "composicion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_composicion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."composicion_id_composicion_seq';

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
            throw new \Exception('Clase Modelo: ComposicionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ComposicionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idComposicion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idComposicion
     * @return IdComposicion
     */
    public function setIdComposicion($idComposicion)
    {
        $this->idComposicion = (integer) $idComposicion;
        return $this;
    }

    /**
     * Get idComposicion
     *
     * @return null|Integer
     */
    public function getIdComposicion()
    {
        return $this->idComposicion;
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
     * Set cada
     *
     * Información de la cantidad del compuesto
     *
     * @parámetro String $cada
     * @return Cada
     */
    public function setCada($cada)
    {
        $this->cada = (string) $cada;
        return $this;
    }

    /**
     * Get cada
     *
     * @return null|String
     */
    public function getCada()
    {
        return $this->cada;
    }

    /**
     * Set idUnidad
     *
     * Identificador de la unidad de medida, se usará 0 para el tipo Otro
     *
     * @parámetro Integer $idUnidad
     * @return IdUnidad
     */
    public function setIdUnidad($idUnidad)
    {
        $this->idUnidad = (integer) $idUnidad;
        return $this;
    }

    /**
     * Get idUnidad
     *
     * @return null|Integer
     */
    public function getIdUnidad()
    {
        return $this->idUnidad;
    }

    /**
     * Set nombreUnidad
     *
     * Permitirá ingresar el símbolo de la unidad de medida seleccionada (del catálogo de unidades de medida) o el nombre de la unidad de medida ingresada por el usuario
     *
     * @parámetro String $nombreUnidad
     * @return NombreUnidad
     */
    public function setNombreUnidad($nombreUnidad)
    {
        $this->nombreUnidad = (string) $nombreUnidad;
        return $this;
    }

    /**
     * Get nombreUnidad
     *
     * @return null|String
     */
    public function getNombreUnidad()
    {
        return $this->nombreUnidad;
    }

    /**
     * Set idTipoComponente
     *
     * Identificador del tipo de componente
     *
     * @parámetro Integer $idTipoComponente
     * @return IdTipoComponente
     */
    public function setIdTipoComponente($idTipoComponente)
    {
        $this->idTipoComponente = (integer) $idTipoComponente;
        return $this;
    }

    /**
     * Get idTipoComponente
     *
     * @return null|Integer
     */
    public function getIdTipoComponente()
    {
        return $this->idTipoComponente;
    }

    /**
     * Set idNombreComponente
     *
     * Identificador del nombre de componente o ingrediente activo
     *
     * @parámetro Integer $idNombreComponente
     * @return IdNombreComponente
     */
    public function setIdNombreComponente($idNombreComponente)
    {
        $this->idNombreComponente = (integer) $idNombreComponente;
        return $this;
    }

    /**
     * Get idNombreComponente
     *
     * @return null|Integer
     */
    public function getIdNombreComponente()
    {
        return $this->idNombreComponente;
    }

    /**
     * Set cantidad
     *
     * Información de la cantidad del compuesto
     *
     * @parámetro String $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = (string) $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|String
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set idUnidadComponente
     *
     * Identificador del registro del catálogo de unidades de unidades de medida, usará 0 para Otros
     *
     * @parámetro Integer $idUnidadComponente
     * @return IdUnidadComponente
     */
    public function setIdUnidadComponente($idUnidadComponente)
    {
        $this->idUnidadComponente = (integer) $idUnidadComponente;
        return $this;
    }

    /**
     * Get idUnidadComponente
     *
     * @return null|Integer
     */
    public function getIdUnidadComponente()
    {
        return $this->idUnidadComponente;
    }

    /**
     * Set nombreUnidadComponente
     *
     * Permitirá ingresar el símbolo de la unidad de medida seleccionada del catálogo o la información ingresada por el usuario en el caso de Otro
     *
     * @parámetro String $nombreUnidadComponente
     * @return NombreUnidadComponente
     */
    public function setNombreUnidadComponente($nombreUnidadComponente)
    {
        $this->nombreUnidadComponente = (string) $nombreUnidadComponente;
        return $this;
    }

    /**
     * Get nombreUnidadComponente
     *
     * @return null|String
     */
    public function getNombreUnidadComponente()
    {
        return $this->nombreUnidadComponente;
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
     * @return ComposicionModelo
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
