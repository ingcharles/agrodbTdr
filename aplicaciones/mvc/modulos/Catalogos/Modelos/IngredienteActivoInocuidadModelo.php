<?php
/**
 * Modelo IngredienteActivoInocuidadModelo
 *
 * Este archivo se complementa con el archivo   IngredienteActivoInocuidadLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    IngredienteActivoInocuidadModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class IngredienteActivoInocuidadModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idIngredienteActivo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $ingredienteActivo;

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
    protected $ingredienteQuimico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $cas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $restriccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estadoIngredienteActivo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $formulaQuimica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $grupoQuimico;

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
     * Nombre de la tabla: ingrediente_activo_inocuidad
     */
    private $tabla = "ingrediente_activo_inocuidad";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_ingrediente_activo";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."ingrediente_activo_inocuidad_id_ingrediente_activo_seq';

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
            throw new \Exception('Clase Modelo: IngredienteActivoInocuidadModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: IngredienteActivoInocuidadModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idIngredienteActivo
     *
     *
     *
     * @parámetro Integer $idIngredienteActivo
     * @return IdIngredienteActivo
     */
    public function setIdIngredienteActivo($idIngredienteActivo)
    {
        $this->idIngredienteActivo = (integer) $idIngredienteActivo;
        return $this;
    }

    /**
     * Get idIngredienteActivo
     *
     * @return null|Integer
     */
    public function getIdIngredienteActivo()
    {
        return $this->idIngredienteActivo;
    }

    /**
     * Set ingredienteActivo
     *
     *
     *
     * @parámetro String $ingredienteActivo
     * @return IngredienteActivo
     */
    public function setIngredienteActivo($ingredienteActivo)
    {
        $this->ingredienteActivo = (string) $ingredienteActivo;
        return $this;
    }

    /**
     * Get ingredienteActivo
     *
     * @return null|String
     */
    public function getIngredienteActivo()
    {
        return $this->ingredienteActivo;
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
     * Set ingredienteQuimico
     *
     *
     *
     * @parámetro String $ingredienteQuimico
     * @return IngredienteQuimico
     */
    public function setIngredienteQuimico($ingredienteQuimico)
    {
        $this->ingredienteQuimico = (string) $ingredienteQuimico;
        return $this;
    }

    /**
     * Get ingredienteQuimico
     *
     * @return null|String
     */
    public function getIngredienteQuimico()
    {
        return $this->ingredienteQuimico;
    }

    /**
     * Set cas
     *
     *
     *
     * @parámetro String $cas
     * @return Cas
     */
    public function setCas($cas)
    {
        $this->cas = (string) $cas;
        return $this;
    }

    /**
     * Get cas
     *
     * @return null|String
     */
    public function getCas()
    {
        return $this->cas;
    }

    /**
     * Set restriccion
     *
     *
     *
     * @parámetro String $restriccion
     * @return Restriccion
     */
    public function setRestriccion($restriccion)
    {
        $this->restriccion = (string) $restriccion;
        return $this;
    }

    /**
     * Get restriccion
     *
     * @return null|String
     */
    public function getRestriccion()
    {
        return $this->restriccion;
    }

    /**
     * Set estadoIngredienteActivo
     *
     *
     *
     * @parámetro String $estadoIngredienteActivo
     * @return EstadoIngredienteActivo
     */
    public function setEstadoIngredienteActivo($estadoIngredienteActivo)
    {
        $this->estadoIngredienteActivo = (String) $estadoIngredienteActivo;
        return $this;
    }

    /**
     * Get estadoIngredienteActivo
     *
     * @return null|String
     */
    public function getEstadoIngredienteActivo()
    {
        return $this->estadoIngredienteActivo;
    }

    /**
     * Set formulaQuimica
     *
     *
     *
     * @parámetro String $formulaQuimica
     * @return FormulaQuimica
     */
    public function setFormulaQuimica($formulaQuimica)
    {
        $this->formulaQuimica = (string) $formulaQuimica;
        return $this;
    }

    /**
     * Get formulaQuimica
     *
     * @return null|String
     */
    public function getFormulaQuimica()
    {
        return $this->formulaQuimica;
    }

    /**
     * Set grupoQuimico
     *
     *
     *
     * @parámetro String $grupoQuimico
     * @return GrupoQuimico
     */
    public function setGrupoQuimico($grupoQuimico)
    {
        $this->grupoQuimico = (string) $grupoQuimico;
        return $this;
    }

    /**
     * Get grupoQuimico
     *
     * @return null|String
     */
    public function getGrupoQuimico()
    {
        return $this->grupoQuimico;
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
     * @return IngredienteActivoInocuidadModelo
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
