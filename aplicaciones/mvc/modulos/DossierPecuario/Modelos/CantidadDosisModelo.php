<?php
/**
 * Modelo CantidadDosisModelo
 *
 * Este archivo se complementa con el archivo   CantidadDosisLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    CantidadDosisModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CantidadDosisModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de registro
     */
    protected $idCantidadDosis;

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
    protected $fechaSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la cantidad de dosis del producto
     */
    protected $dosis;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de medida de la dosis, se ingresará 0 para Otros
     */
    protected $idUnidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresa el símbolo de la unidad seleccionada o el texto ingresado por el usuario con la opción Otro
     */
    protected $nombreUnidad;

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
     * Nombre de la tabla: cantidad_dosis
     */
    private $tabla = "cantidad_dosis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_cantidad_dosis";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."cantidad_dosis_id_cantidad_dosis_seq';

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
            throw new \Exception('Clase Modelo: CantidadDosisModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CantidadDosisModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idCantidadDosis
     *
     * Identificador único de registro
     *
     * @parámetro Integer $idCantidadDosis
     * @return IdCantidadDosis
     */
    public function setIdCantidadDosis($idCantidadDosis)
    {
        $this->idCantidadDosis = (integer) $idCantidadDosis;
        return $this;
    }

    /**
     * Get idCantidadDosis
     *
     * @return null|Integer
     */
    public function getIdCantidadDosis()
    {
        return $this->idCantidadDosis;
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
     * Set fechaSolicitud
     *
     * Fecha de creación del registro
     *
     * @parámetro Date $fechaSolicitud
     * @return FechaSolicitud
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = (string) $fechaSolicitud;
        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return null|Date
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set dosis
     *
     * Permitirá ingresar la cantidad de dosis del producto
     *
     * @parámetro String $dosis
     * @return Dosis
     */
    public function setDosis($dosis)
    {
        $this->dosis = (string) $dosis;
        return $this;
    }

    /**
     * Get dosis
     *
     * @return null|String
     */
    public function getDosis()
    {
        return $this->dosis;
    }

    /**
     * Set idUnidad
     *
     * Identificador de la unidad de medida de la dosis, se ingresará 0 para Otros
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
     * Permitirá ingresa el símbolo de la unidad seleccionada o el texto ingresado por el usuario con la opción Otro
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
     * @return CantidadDosisModelo
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
