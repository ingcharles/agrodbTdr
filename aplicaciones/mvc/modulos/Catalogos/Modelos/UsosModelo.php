<?php
/**
 * Modelo UsosModelo
 *
 * Este archivo se complementa con el archivo   UsosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    UsosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class UsosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre científico del uso
     */
    protected $nombreUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoVue;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre común del uso
     */
    protected $nombreComunUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $clasificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoUso;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: usos
     */
    private $tabla = "usos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_uso";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."usos_id_uso_seq';

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
            throw new \Exception('Clase Modelo: UsosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: UsosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_catalogos
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idUso
     *
     *
     *
     * @parámetro Integer $idUso
     * @return IdUso
     */
    public function setIdUso($idUso)
    {
        $this->idUso = (integer) $idUso;
        return $this;
    }

    /**
     * Get idUso
     *
     * @return null|Integer
     */
    public function getIdUso()
    {
        return $this->idUso;
    }

    /**
     * Set nombreUso
     *
     * Nombre científico del uso
     *
     * @parámetro String $nombreUso
     * @return NombreUso
     */
    public function setNombreUso($nombreUso)
    {
        $this->nombreUso = (string) $nombreUso;
        return $this;
    }

    /**
     * Get nombreUso
     *
     * @return null|String
     */
    public function getNombreUso()
    {
        return $this->nombreUso;
    }

    /**
     * Set codigoVue
     *
     *
     *
     * @parámetro String $codigoVue
     * @return CodigoVue
     */
    public function setCodigoVue($codigoVue)
    {
        $this->codigoVue = (string) $codigoVue;
        return $this;
    }

    /**
     * Get codigoVue
     *
     * @return null|String
     */
    public function getCodigoVue()
    {
        return $this->codigoVue;
    }

    /**
     * Set idArea
     *
     *
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
     * Set codificacion
     *
     *
     *
     * @parámetro String $codificacion
     * @return Codificacion
     */
    public function setCodificacion($codificacion)
    {
        $this->codificacion = (string) $codificacion;
        return $this;
    }

    /**
     * Get codificacion
     *
     * @return null|String
     */
    public function getCodificacion()
    {
        return $this->codificacion;
    }

    /**
     * Set nombreComunUso
     *
     * Nombre común del uso
     *
     * @parámetro String $nombreComunUso
     * @return NombreComunUso
     */
    public function setNombreComunUso($nombreComunUso)
    {
        $this->nombreComunUso = (string) $nombreComunUso;
        return $this;
    }

    /**
     * Get nombreComunUso
     *
     * @return null|String
     */
    public function getNombreComunUso()
    {
        return $this->nombreComunUso;
    }

    /**
     * Set clasificacion
     *
     *
     *
     * @parámetro String $clasificacion
     * @return Clasificacion
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = (string) $clasificacion;
        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return null|String
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set estadoUso
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoUso
     * @return EstadoUso
     */
    public function setEstadoUso($estadoUso)
    {
        $this->estadoUso = (string) $estadoUso;
        return $this;
    }

    /**
     * Get estadoUso
     *
     * @return null|String
     */
    public function getEstadoUso()
    {
        return $this->estadoUso;
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
     * @return UsosModelo
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
