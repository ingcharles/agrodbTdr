<?php
/**
 * Modelo EfectosBiologicosModelo
 *
 * Este archivo se complementa con el archivo   EfectosBiologicosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    EfectosBiologicosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EfectosBiologicosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idEfectoBiologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del efecto biológico no deseado en productos veterinarios
     */
    protected $efectoBiologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoEfectoBiologico;

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
     * Nombre de la tabla: efectos_biologicos
     */
    private $tabla = "efectos_biologicos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_efecto_biologico";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."efectos_biologicos_id_efecto_biologico_seq';

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
            throw new \Exception('Clase Modelo: EfectosBiologicosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: EfectosBiologicosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idEfectoBiologico
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idEfectoBiologico
     * @return IdEfectoBiologico
     */
    public function setIdEfectoBiologico($idEfectoBiologico)
    {
        $this->idEfectoBiologico = (integer) $idEfectoBiologico;
        return $this;
    }

    /**
     * Get idEfectoBiologico
     *
     * @return null|Integer
     */
    public function getIdEfectoBiologico()
    {
        return $this->idEfectoBiologico;
    }

    /**
     * Set efectoBiologico
     *
     * Nombre del efecto biológico no deseado en productos veterinarios
     *
     * @parámetro String $efectoBiologico
     * @return EfectoBiologico
     */
    public function setEfectoBiologico($efectoBiologico)
    {
        $this->efectoBiologico = (string) $efectoBiologico;
        return $this;
    }

    /**
     * Get efectoBiologico
     *
     * @return null|String
     */
    public function getEfectoBiologico()
    {
        return $this->efectoBiologico;
    }

    /**
     * Set estadoEfectoBiologico
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoEfectoBiologico
     * @return EstadoEfectoBiologico
     */
    public function setEstadoEfectoBiologico($estadoEfectoBiologico)
    {
        $this->estadoEfectoBiologico = (string) $estadoEfectoBiologico;
        return $this;
    }

    /**
     * Get estadoEfectoBiologico
     *
     * @return null|String
     */
    public function getEstadoEfectoBiologico()
    {
        return $this->estadoEfectoBiologico;
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
     * @return EfectosBiologicosModelo
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
