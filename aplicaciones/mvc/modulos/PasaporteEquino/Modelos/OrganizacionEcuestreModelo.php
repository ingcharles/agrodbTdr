<?php
/**
 * Modelo OrganizacionEcuestreModelo
 *
 * Este archivo se complementa con el archivo   OrganizacionEcuestreLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-03
 * @uses    OrganizacionEcuestreModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class OrganizacionEcuestreModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro
     */
    protected $idOrganizacionEcuestre;

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
     *      Identificador del operador que registró la organización ecuestre
     */
    protected $identificadorOrganizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Razón social del operador que registró la organización ecuestre
     */
    protected $razonSocial;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la organización ecuestre (sitio registrado)
     */
    protected $nombreAsociacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del área a la que pertenece la operación:
     *      - SA
     */
    protected $idArea;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la operación de Organización Ecuestre
     */
    protected $idGrupoOperacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoOrganizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provincia;

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
     * Nombre de la tabla: organizacion_ecuestre
     */
    private $tabla = "organizacion_ecuestre";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_organizacion_ecuestre";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."organizacion_ecuestre_id_organizacion_ecuestre_seq';

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
            throw new \Exception('Clase Modelo: OrganizacionEcuestreModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: OrganizacionEcuestreModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idOrganizacionEcuestre
     *
     * Identificador del registro
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
     * Set identificadorOrganizacion
     *
     * Identificador del operador que registró la organización ecuestre
     *
     * @parámetro String $identificadorOrganizacion
     * @return IdentificadorOrganizacion
     */
    public function setIdentificadorOrganizacion($identificadorOrganizacion)
    {
        $this->identificadorOrganizacion = (string) $identificadorOrganizacion;
        return $this;
    }

    /**
     * Get identificadorOrganizacion
     *
     * @return null|String
     */
    public function getIdentificadorOrganizacion()
    {
        return $this->identificadorOrganizacion;
    }

    /**
     * Set razonSocial
     *
     * Razón social del operador que registró la organización ecuestre
     *
     * @parámetro String $razonSocial
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (string) $razonSocial;
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return null|String
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set nombreAsociacion
     *
     * Nombre de la organización ecuestre (sitio registrado)
     *
     * @parámetro String $nombreAsociacion
     * @return NombreAsociacion
     */
    public function setNombreAsociacion($nombreAsociacion)
    {
        $this->nombreAsociacion = (string) $nombreAsociacion;
        return $this;
    }

    /**
     * Get nombreAsociacion
     *
     * @return null|String
     */
    public function getNombreAsociacion()
    {
        return $this->nombreAsociacion;
    }

    /**
     * Set idArea
     *
     * Identificador del área a la que pertenece la operación:
     * - SA
     *
     * @parámetro String $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (string) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|String
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set idGrupoOperacion
     *
     * Identificador de la operación de Organización Ecuestre
     *
     * @parámetro Integer $idGrupoOperacion
     * @return IdGrupoOperacion
     */
    public function setIdGrupoOperacion($idGrupoOperacion)
    {
        $this->idGrupoOperacion = (integer) $idGrupoOperacion;
        return $this;
    }

    /**
     * Get idGrupoOperacion
     *
     * @return null|Integer
     */
    public function getIdGrupoOperacion()
    {
        return $this->idGrupoOperacion;
    }

    /**
     * Set estadoOrganizacion
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoOrganizacion
     * @return EstadoOrganizacion
     */
    public function setEstadoOrganizacion($estadoOrganizacion)
    {
        $this->estadoOrganizacion = (string) $estadoOrganizacion;
        return $this;
    }

    /**
     * Get estadoOrganizacion
     *
     * @return null|String
     */
    public function getEstadoOrganizacion()
    {
        return $this->estadoOrganizacion;
    }

    /**
     * Set provincia
     *
     * Provincia en la que se encuentra ubicada la organización de acuerdo al registro de operador
     *
     * @parámetro String $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
        return $this;
    }

    /**
     * Get provincia
     *
     * @return null|String
     */
    public function getProvincia()
    {
        return $this->provincia;
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
     * @return OrganizacionEcuestreModelo
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
