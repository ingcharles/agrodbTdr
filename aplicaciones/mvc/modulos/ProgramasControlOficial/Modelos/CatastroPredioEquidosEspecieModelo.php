<?php
/**
 * Modelo CatastroPredioEquidosEspecieModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosEspecieLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosEspecieModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
namespace Agrodb\ProgramasControlOficial\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CatastroPredioEquidosEspecieModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidosEspecie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificador;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEspecie;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreEspecie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idRaza;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreRaza;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCategoria;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreCategoria;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroAnimales;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_programas_control_oficial";

    /**
     * Nombre de la tabla: catastro_predio_equidos_especie
     */
    private $tabla = "catastro_predio_equidos_especie";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_catastro_predio_equidos_especie";

    /**
     * Secuencia
     */
    private $secuencial = 'g_programas_control_oficial"."catastro_predio_equidos_espec_id_catastro_predio_equidos_es_seq';

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
            throw new \Exception('Clase Modelo: CatastroPredioEquidosEspecieModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CatastroPredioEquidosEspecieModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_programas_control_oficial
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idCatastroPredioEquidosEspecie
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidosEspecie
     * @return IdCatastroPredioEquidosEspecie
     */
    public function setIdCatastroPredioEquidosEspecie($idCatastroPredioEquidosEspecie)
    {
        $this->idCatastroPredioEquidosEspecie = (integer) $idCatastroPredioEquidosEspecie;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidosEspecie
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidosEspecie()
    {
        return $this->idCatastroPredioEquidosEspecie;
    }

    /**
     * Set idCatastroPredioEquidos
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidos
     * @return IdCatastroPredioEquidos
     */
    public function setIdCatastroPredioEquidos($idCatastroPredioEquidos)
    {
        $this->idCatastroPredioEquidos = (integer) $idCatastroPredioEquidos;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidos
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidos()
    {
        return $this->idCatastroPredioEquidos;
    }

    /**
     * Set identificador
     *
     *
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (string) $identificador;
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set fechaCreacion
     *
     *
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
     *
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
     *
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
     * Set idRaza
     *
     *
     *
     * @parámetro Integer $idRaza
     * @return IdRaza
     */
    public function setIdRaza($idRaza)
    {
        $this->idRaza = (integer) $idRaza;
        return $this;
    }

    /**
     * Get idRaza
     *
     * @return null|Integer
     */
    public function getIdRaza()
    {
        return $this->idRaza;
    }

    /**
     * Set nombreRaza
     *
     *
     *
     * @parámetro String $nombreRaza
     * @return NombreRaza
     */
    public function setNombreRaza($nombreRaza)
    {
        $this->nombreRaza = (string) $nombreRaza;
        return $this;
    }

    /**
     * Get nombreRaza
     *
     * @return null|String
     */
    public function getNombreRaza()
    {
        return $this->nombreRaza;
    }

    /**
     * Set idCategoria
     *
     *
     *
     * @parámetro Integer $idCategoria
     * @return IdCategoria
     */
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = (integer) $idCategoria;
        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return null|Integer
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set nombreCategoria
     *
     *
     *
     * @parámetro String $nombreCategoria
     * @return NombreCategoria
     */
    public function setNombreCategoria($nombreCategoria)
    {
        $this->nombreCategoria = (string) $nombreCategoria;
        return $this;
    }

    /**
     * Get nombreCategoria
     *
     * @return null|String
     */
    public function getNombreCategoria()
    {
        return $this->nombreCategoria;
    }

    /**
     * Set numeroAnimales
     *
     *
     *
     * @parámetro Integer $numeroAnimales
     * @return NumeroAnimales
     */
    public function setNumeroAnimales($numeroAnimales)
    {
        $this->numeroAnimales = (integer) $numeroAnimales;
        return $this;
    }

    /**
     * Get numeroAnimales
     *
     * @return null|Integer
     */
    public function getNumeroAnimales()
    {
        return $this->numeroAnimales;
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
     * @return CatastroPredioEquidosEspecieModelo
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
