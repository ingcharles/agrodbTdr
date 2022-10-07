<?php
/**
 * Modelo FormaAdministracionModelo
 *
 * Este archivo se complementa con el archivo   FormaAdministracionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    FormaAdministracionModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FormaAdministracionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idFormaAdministracion;

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
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la especie seleccionada
     */
    protected $idEspecieDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la especificación de la especie seleccionada
     */
    protected $nombreEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información del animal como raza, fase etárea, etc
     */
    protected $caracteristicasAnimal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la descripción de la cantidad del producto a aplicar al animal
     */
    protected $cantidadProducto;

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
     * Nombre de la tabla: forma_administracion
     */
    private $tabla = "forma_administracion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_forma_administracion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."forma_administracion_id_forma_administracion_seq';

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
            throw new \Exception('Clase Modelo: FormaAdministracionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FormaAdministracionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idFormaAdministracion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idFormaAdministracion
     * @return IdFormaAdministracion
     */
    public function setIdFormaAdministracion($idFormaAdministracion)
    {
        $this->idFormaAdministracion = (integer) $idFormaAdministracion;
        return $this;
    }

    /**
     * Get idFormaAdministracion
     *
     * @return null|Integer
     */
    public function getIdFormaAdministracion()
    {
        return $this->idFormaAdministracion;
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
     * Set idEspecieDestino
     *
     * Identificador de la especie seleccionada
     *
     * @parámetro Integer $idEspecieDestino
     * @return IdEspecieDestino
     */
    public function setIdEspecieDestino($idEspecieDestino)
    {
        $this->idEspecieDestino = (integer) $idEspecieDestino;
        return $this;
    }

    /**
     * Get idEspecieDestino
     *
     * @return null|Integer
     */
    public function getIdEspecieDestino()
    {
        return $this->idEspecieDestino;
    }

    /**
     * Set nombreEspecie
     *
     * Permitirá ingresar la especificación de la especie seleccionada
     *
     * @parámetro String $nombreEspecie
     * @return NombreEspecie
     */
    public function setNombreEspecie($nombreEspecie)
    {
        $this->nombreEspecie = (string) $nombreEspecie;
        return $this;
    }

    /**
     * Get nombreEspecie
     *
     * @return null|String
     */
    public function getNombreEspecie()
    {
        return $this->nombreEspecie;
    }

    /**
     * Set caracteristicasAnimal
     *
     * Permitirá ingresar información del animal como raza, fase etárea, etc
     *
     * @parámetro String $caracteristicasAnimal
     * @return CaracteristicasAnimal
     */
    public function setCaracteristicasAnimal($caracteristicasAnimal)
    {
        $this->caracteristicasAnimal = (string) $caracteristicasAnimal;
        return $this;
    }

    /**
     * Get caracteristicasAnimal
     *
     * @return null|String
     */
    public function getCaracteristicasAnimal()
    {
        return $this->caracteristicasAnimal;
    }

    /**
     * Set cantidadProducto
     *
     * Permitirá ingresar la descripción de la cantidad del producto a aplicar al animal
     *
     * @parámetro String $cantidadProducto
     * @return CantidadProducto
     */
    public function setCantidadProducto($cantidadProducto)
    {
        $this->cantidadProducto = (string) $cantidadProducto;
        return $this;
    }

    /**
     * Get cantidadProducto
     *
     * @return null|String
     */
    public function getCantidadProducto()
    {
        return $this->cantidadProducto;
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
     * @return FormaAdministracionModelo
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
