<?php
/**
 * Modelo GuiasBuenasPracticasModelo
 *
 * Este archivo se complementa con el archivo   GuiasBuenasPracticasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    GuiasBuenasPracticasModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class GuiasBuenasPracticasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idGuiaBuenasPracticas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código para clasificación de resoluciones:
     *      - BPA -> Buenas Prácticas Agrícolas Sanidad Vegetal e Inocuidad
     *      - BPP -> Buenas Prácticas Pecuarias Sanidad Animal e Inocuidad
     */
    protected $tipo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la resolución
     */
    protected $nombreResolucion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de la resolución
     */
    protected $numeroResolucion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de generación de la resolución
     */
    protected $fechaResolucion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la resolución:
     *      - Activo
     *      - Inactivo
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
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: guias_buenas_practicas
     */
    private $tabla = "guias_buenas_practicas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_guia_buenas_practicas";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."guias_buenas_practicas_id_guia_buenas_practicas_seq';

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
            throw new \Exception('Clase Modelo: GuiasBuenasPracticasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: GuiasBuenasPracticasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idGuiaBuenasPracticas
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idGuiaBuenasPracticas
     * @return IdGuiaBuenasPracticas
     */
    public function setIdGuiaBuenasPracticas($idGuiaBuenasPracticas)
    {
        $this->idGuiaBuenasPracticas = (integer) $idGuiaBuenasPracticas;
        return $this;
    }

    /**
     * Get idGuiaBuenasPracticas
     *
     * @return null|Integer
     */
    public function getIdGuiaBuenasPracticas()
    {
        return $this->idGuiaBuenasPracticas;
    }

    /**
     * Set tipo
     *
     * Código para clasificación de resoluciones:
     * - BPA -> Buenas Prácticas Agrícolas Sanidad Vegetal e Inocuidad
     * - BPP -> Buenas Prácticas Pecuarias Sanidad Animal e Inocuidad
     *
     * @parámetro String $tipo
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = (string) $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set nombreResolucion
     *
     * Nombre de la resolución
     *
     * @parámetro String $nombreResolucion
     * @return NombreResolucion
     */
    public function setNombreResolucion($nombreResolucion)
    {
        $this->nombreResolucion = (string) $nombreResolucion;
        return $this;
    }

    /**
     * Get nombreResolucion
     *
     * @return null|String
     */
    public function getNombreResolucion()
    {
        return $this->nombreResolucion;
    }

    /**
     * Set numeroResolucion
     *
     * Número de la resolución
     *
     * @parámetro String $numeroResolucion
     * @return NumeroResolucion
     */
    public function setNumeroResolucion($numeroResolucion)
    {
        $this->numeroResolucion = (string) $numeroResolucion;
        return $this;
    }

    /**
     * Get numeroResolucion
     *
     * @return null|String
     */
    public function getNumeroResolucion()
    {
        return $this->numeroResolucion;
    }

    /**
     * Set fechaResolucion
     *
     * Fecha de generación de la resolución
     *
     * @parámetro Date $fechaResolucion
     * @return FechaResolucion
     */
    public function setFechaResolucion($fechaResolucion)
    {
        $this->fechaResolucion = (string) $fechaResolucion;
        return $this;
    }

    /**
     * Get fechaResolucion
     *
     * @return null|Date
     */
    public function getFechaResolucion()
    {
        return $this->fechaResolucion;
    }

    /**
     * Set estado
     *
     * Estado de la resolución:
     * - Activo
     * - Inactivo
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
     * @return GuiasBuenasPracticasModelo
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
