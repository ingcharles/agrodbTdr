<?php
/**
 * Modelo FormulacionModelo
 *
 * Este archivo se complementa con el archivo   FormulacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    FormulacionModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FormulacionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idFormulacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $formulacion;

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
    protected $norma;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $sigla;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $vigencia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Información de estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoFormulacion;

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
     * Nombre de la tabla: formulacion
     */
    private $tabla = "formulacion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_formulacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."formulacion_id_formulacion_seq';

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
            throw new \Exception('Clase Modelo: FormulacionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FormulacionModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idFormulacion
     *
     *
     *
     * @parámetro Integer $idFormulacion
     * @return IdFormulacion
     */
    public function setIdFormulacion($idFormulacion)
    {
        $this->idFormulacion = (integer) $idFormulacion;
        return $this;
    }

    /**
     * Get idFormulacion
     *
     * @return null|Integer
     */
    public function getIdFormulacion()
    {
        return $this->idFormulacion;
    }

    /**
     * Set formulacion
     *
     *
     *
     * @parámetro String $formulacion
     * @return Formulacion
     */
    public function setFormulacion($formulacion)
    {
        $this->formulacion = (string) $formulacion;
        return $this;
    }

    /**
     * Get formulacion
     *
     * @return null|String
     */
    public function getFormulacion()
    {
        return $this->formulacion;
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
     * Set norma
     *
     *
     *
     * @parámetro String $norma
     * @return Norma
     */
    public function setNorma($norma)
    {
        $this->norma = (string) $norma;
        return $this;
    }

    /**
     * Get norma
     *
     * @return null|String
     */
    public function getNorma()
    {
        return $this->norma;
    }

    /**
     * Set sigla
     *
     *
     *
     * @parámetro String $sigla
     * @return Sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = (string) $sigla;
        return $this;
    }

    /**
     * Get sigla
     *
     * @return null|String
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set vigencia
     *
     *
     *
     * @parámetro String $vigencia
     * @return Vigencia
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = (string) $vigencia;
        return $this;
    }

    /**
     * Get vigencia
     *
     * @return null|String
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * Set estadoFormulacion
     *
     * Información de estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoFormulacion
     * @return EstadoFormulacion
     */
    public function setEstadoFormulacion($estadoFormulacion)
    {
        $this->estadoFormulacion = (string) $estadoFormulacion;
        return $this;
    }

    /**
     * Get estadoFormulacion
     *
     * @return null|String
     */
    public function getEstadoFormulacion()
    {
        return $this->estadoFormulacion;
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
     * @return FormulacionModelo
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
