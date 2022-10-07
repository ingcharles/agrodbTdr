<?php
/**
 * Modelo CategoriaEspecieModelo
 *
 * Este archivo se complementa con el archivo   CategoriaEspecieLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-22
 * @uses    CategoriaEspecieModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CategoriaEspecieModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idCategoriaEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la categoría de la especie
     */
    protected $categoriaEspecie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la especie a la que pertenece la catagoría
     */
    protected $idEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoCategoriaEspecie;

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
     * Nombre de la tabla: categoria_especie
     */
    private $tabla = "categoria_especie";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_categoria_especie";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."CategoriaEspecie_id_categoria_especie_seq';

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
            throw new \Exception('Clase Modelo: CategoriaEspecieModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CategoriaEspecieModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idCategoriaEspecie
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idCategoriaEspecie
     * @return IdCategoriaEspecie
     */
    public function setIdCategoriaEspecie($idCategoriaEspecie)
    {
        $this->idCategoriaEspecie = (integer) $idCategoriaEspecie;
        return $this;
    }

    /**
     * Get idCategoriaEspecie
     *
     * @return null|Integer
     */
    public function getIdCategoriaEspecie()
    {
        return $this->idCategoriaEspecie;
    }

    /**
     * Set categoriaEspecie
     *
     * Nombre de la categoría de la especie
     *
     * @parámetro String $categoriaEspecie
     * @return CategoriaEspecie
     */
    public function setCategoriaEspecie($categoriaEspecie)
    {
        $this->categoriaEspecie = (string) $categoriaEspecie;
        return $this;
    }

    /**
     * Get categoriaEspecie
     *
     * @return null|String
     */
    public function getCategoriaEspecie()
    {
        return $this->categoriaEspecie;
    }

    /**
     * Set idEspecie
     *
     * Identificador de la especie a la que pertenece la catagoría
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
     * Set estadoCategoriaEspecie
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoCategoriaEspecie
     * @return EstadoCategoriaEspecie
     */
    public function setEstadoCategoriaEspecie($estadoCategoriaEspecie)
    {
        $this->estadoCategoriaEspecie = (string) $estadoCategoriaEspecie;
        return $this;
    }

    /**
     * Get estadoCategoriaEspecie
     *
     * @return null|String
     */
    public function getEstadoCategoriaEspecie()
    {
        return $this->estadoCategoriaEspecie;
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
     * @return CategoriaEspecieModelo
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
