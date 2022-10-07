<?php
/**
 * Modelo MiembrosAsociacionesModelo
 *
 * Este archivo se complementa con el archivo   MiembrosAsociacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    MiembrosAsociacionesModelo
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MiembrosAsociacionesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idMiembroAsociacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la asociación a la que pertenece el operador
     */
    protected $idAsociacion;

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
     *      Identificador del operador
     */
    protected $identificadorMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del operador
     */
    protected $nombreMiembro;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_certificacion_bpa";

    /**
     * Nombre de la tabla: miembros_asociaciones
     */
    private $tabla = "miembros_asociaciones";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_miembro_asociacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificacion_bpa"."miembros_asociaciones_id_miembro_asociacion_seq';

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
            throw new \Exception('Clase Modelo: MiembrosAsociacionesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: MiembrosAsociacionesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificacion_bpa
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idMiembroAsociacion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idMiembroAsociacion
     * @return IdMiembroAsociacion
     */
    public function setIdMiembroAsociacion($idMiembroAsociacion)
    {
        $this->idMiembroAsociacion = (integer) $idMiembroAsociacion;
        return $this;
    }

    /**
     * Get idMiembroAsociacion
     *
     * @return null|Integer
     */
    public function getIdMiembroAsociacion()
    {
        return $this->idMiembroAsociacion;
    }

    /**
     * Set idAsociacion
     *
     * Identificador de la asociación a la que pertenece el operador
     *
     * @parámetro Integer $idAsociacion
     * @return IdAsociacion
     */
    public function setIdAsociacion($idAsociacion)
    {
        $this->idAsociacion = (integer) $idAsociacion;
        return $this;
    }

    /**
     * Get idAsociacion
     *
     * @return null|Integer
     */
    public function getIdAsociacion()
    {
        return $this->idAsociacion;
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
     * Identificador del operador
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
     * Nombre del operador
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
     * @return MiembrosAsociacionesModelo
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
