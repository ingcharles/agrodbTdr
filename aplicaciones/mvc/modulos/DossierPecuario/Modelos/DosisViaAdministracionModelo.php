<?php
/**
 * Modelo DosisViaAdministracionModelo
 *
 * Este archivo se complementa con el archivo   DosisViaAdministracionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    DosisViaAdministracionModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DosisViaAdministracionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idDosisViaAdministracion;

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
     *      Identificador de la especie
     */
    protected $idEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la descripción de la especie seleccionada
     */
    protected $nombreEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de las características de raza, fase etárea o productiva, etc
     */
    protected $caracteristicasAnimal;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la vía de administración del producto
     */
    protected $idViaAdministracion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la cantidad de dosis del producto
     */
    protected $cantidadDosis;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de medida de la dosis
     */
    protected $idUnidadDosis;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de medida seleccionada o el texto ingresado por el usuario al seleccionar la opción Otro
     */
    protected $nombreUnidadDosis;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la cantidad del producto
     */
    protected $cantidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de medida del producto. Se usará 0 para Otro
     */
    protected $idUnidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de medida seleccionada o el texto ingresado por el usuario al seleccionar la opción Otro
     */
    protected $nombreUnidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la duración del producto
     */
    protected $duracion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de tiempo del producto
     */
    protected $idUnidadTiempo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de tiempo seleccionada
     */
    protected $nombreUnidadTiempo;

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
     * Nombre de la tabla: dosis_via_administracion
     */
    private $tabla = "dosis_via_administracion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_dosis_via_administracion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."dosis_via_administracion_id_dosis_via_administracion_seq';

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
            throw new \Exception('Clase Modelo: DosisViaAdministracionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DosisViaAdministracionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDosisViaAdministracion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idDosisViaAdministracion
     * @return IdDosisViaAdministracion
     */
    public function setIdDosisViaAdministracion($idDosisViaAdministracion)
    {
        $this->idDosisViaAdministracion = (integer) $idDosisViaAdministracion;
        return $this;
    }

    /**
     * Get idDosisViaAdministracion
     *
     * @return null|Integer
     */
    public function getIdDosisViaAdministracion()
    {
        return $this->idDosisViaAdministracion;
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
     * Set idEspecie
     *
     * Identificador de la especie
     *
     * @parámetro Integer $idEspecie
     * @return IdEspecie
     */
    public function setIdEspecie($idEspecie)
    {
        $this->idEspecie = (integer) $idEspecie;
        return $this;
    }

    /**
     * Get idEspecie
     *
     * @return null|Integer
     */
    public function getIdEspecie()
    {
        return $this->idEspecie;
    }

    /**
     * Set nombreEspecie
     *
     * Permitirá ingresar la descripción de la especie seleccionada
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
     * Permitirá ingresar la información de las características de raza, fase etárea o productiva, etc
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
     * Set idViaAdministracion
     *
     * Identificador de la vía de administración del producto
     *
     * @parámetro Integer $idViaAdministracion
     * @return IdViaAdministracion
     */
    public function setIdViaAdministracion($idViaAdministracion)
    {
        $this->idViaAdministracion = (integer) $idViaAdministracion;
        return $this;
    }

    /**
     * Get idViaAdministracion
     *
     * @return null|Integer
     */
    public function getIdViaAdministracion()
    {
        return $this->idViaAdministracion;
    }

    /**
     * Set cantidadDosis
     *
     * Permitirá ingresar la cantidad de dosis del producto
     *
     * @parámetro String $cantidadDosis
     * @return CantidadDosis
     */
    public function setCantidadDosis($cantidadDosis)
    {
        $this->cantidadDosis = (string) $cantidadDosis;
        return $this;
    }

    /**
     * Get cantidadDosis
     *
     * @return null|String
     */
    public function getCantidadDosis()
    {
        return $this->cantidadDosis;
    }

    /**
     * Set idUnidadDosis
     *
     * Identificador de la unidad de medida de la dosis
     *
     * @parámetro Integer $idUnidadDosis
     * @return IdUnidadDosis
     */
    public function setIdUnidadDosis($idUnidadDosis)
    {
        $this->idUnidadDosis = (integer) $idUnidadDosis;
        return $this;
    }

    /**
     * Get idUnidadDosis
     *
     * @return null|Integer
     */
    public function getIdUnidadDosis()
    {
        return $this->idUnidadDosis;
    }

    /**
     * Set nombreUnidadDosis
     *
     * Permitirá ingresar el símbolo de la unidad de medida seleccionada o el texto ingresado por el usuario al seleccionar la opción Otro
     *
     * @parámetro String $nombreUnidadDosis
     * @return NombreUnidadDosis
     */
    public function setNombreUnidadDosis($nombreUnidadDosis)
    {
        $this->nombreUnidadDosis = (string) $nombreUnidadDosis;
        return $this;
    }

    /**
     * Get nombreUnidadDosis
     *
     * @return null|String
     */
    public function getNombreUnidadDosis()
    {
        return $this->nombreUnidadDosis;
    }

    /**
     * Set cantidad
     *
     * Permitirá ingresar la cantidad del producto
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
     * Set idUnidad
     *
     * Identificador de la unidad de medida del producto. Se usará 0 para Otro
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
     * Permitirá ingresar el símbolo de la unidad de medida seleccionada o el texto ingresado por el usuario al seleccionar la opción Otro
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
     * Set duracion
     *
     * Permitirá ingresar la duración del producto
     *
     * @parámetro String $duracion
     * @return Duracion
     */
    public function setDuracion($duracion)
    {
        $this->duracion = (string) $duracion;
        return $this;
    }

    /**
     * Get duracion
     *
     * @return null|String
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set idUnidadTiempo
     *
     * Identificador de la unidad de tiempo del producto
     *
     * @parámetro Integer $idUnidadTiempo
     * @return IdUnidadTiempo
     */
    public function setIdUnidadTiempo($idUnidadTiempo)
    {
        $this->idUnidadTiempo = (integer) $idUnidadTiempo;
        return $this;
    }

    /**
     * Get idUnidadTiempo
     *
     * @return null|Integer
     */
    public function getIdUnidadTiempo()
    {
        return $this->idUnidadTiempo;
    }

    /**
     * Set nombreUnidadTiempo
     *
     * Permitirá ingresar el símbolo de la unidad de tiempo seleccionada
     *
     * @parámetro String $nombreUnidadTiempo
     * @return NombreUnidadTiempo
     */
    public function setNombreUnidadTiempo($nombreUnidadTiempo)
    {
        $this->nombreUnidadTiempo = (string) $nombreUnidadTiempo;
        return $this;
    }

    /**
     * Get nombreUnidadTiempo
     *
     * @return null|String
     */
    public function getNombreUnidadTiempo()
    {
        return $this->nombreUnidadTiempo;
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
     * @return DosisViaAdministracionModelo
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
