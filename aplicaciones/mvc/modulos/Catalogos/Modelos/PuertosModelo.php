<?php
/**
 * Modelo PuertosModelo
 *
 * Este archivo se complementa con el archivo   PuertosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    PuertosModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PuertosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idPuerto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePuerto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idPais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoPuerto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoPais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipoPuerto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreProvincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProvincia;

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
     * Nombre de la tabla: puertos
     */
    private $tabla = "puertos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_puerto";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."puertos_id_puerto_seq';

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
            throw new \Exception('Clase Modelo: PuertosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PuertosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idPuerto
     *
     *
     *
     * @parámetro Integer $idPuerto
     * @return IdPuerto
     */
    public function setIdPuerto($idPuerto)
    {
        $this->idPuerto = (integer) $idPuerto;
        return $this;
    }

    /**
     * Get idPuerto
     *
     * @return null|Integer
     */
    public function getIdPuerto()
    {
        return $this->idPuerto;
    }

    /**
     * Set nombrePuerto
     *
     *
     *
     * @parámetro String $nombrePuerto
     * @return NombrePuerto
     */
    public function setNombrePuerto($nombrePuerto)
    {
        $this->nombrePuerto = (string) $nombrePuerto;
        return $this;
    }

    /**
     * Get nombrePuerto
     *
     * @return null|String
     */
    public function getNombrePuerto()
    {
        return $this->nombrePuerto;
    }

    /**
     * Set idPais
     *
     *
     *
     * @parámetro Integer $idPais
     * @return IdPais
     */
    public function setIdPais($idPais)
    {
        $this->idPais = (integer) $idPais;
        return $this;
    }

    /**
     * Get idPais
     *
     * @return null|Integer
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Set codigoPuerto
     *
     *
     *
     * @parámetro String $codigoPuerto
     * @return CodigoPuerto
     */
    public function setCodigoPuerto($codigoPuerto)
    {
        $this->codigoPuerto = (string) $codigoPuerto;
        return $this;
    }

    /**
     * Get codigoPuerto
     *
     * @return null|String
     */
    public function getCodigoPuerto()
    {
        return $this->codigoPuerto;
    }

    /**
     * Set codigoPais
     *
     *
     *
     * @parámetro String $codigoPais
     * @return CodigoPais
     */
    public function setCodigoPais($codigoPais)
    {
        $this->codigoPais = (string) $codigoPais;
        return $this;
    }

    /**
     * Get codigoPais
     *
     * @return null|String
     */
    public function getCodigoPais()
    {
        return $this->codigoPais;
    }

    /**
     * Set tipoPuerto
     *
     *
     *
     * @parámetro String $tipoPuerto
     * @return TipoPuerto
     */
    public function setTipoPuerto($tipoPuerto)
    {
        $this->tipoPuerto = (string) $tipoPuerto;
        return $this;
    }

    /**
     * Get tipoPuerto
     *
     * @return null|String
     */
    public function getTipoPuerto()
    {
        return $this->tipoPuerto;
    }

    /**
     * Set nombreProvincia
     *
     *
     *
     * @parámetro String $nombreProvincia
     * @return NombreProvincia
     */
    public function setNombreProvincia($nombreProvincia)
    {
        $this->nombreProvincia = (string) $nombreProvincia;
        return $this;
    }

    /**
     * Get nombreProvincia
     *
     * @return null|String
     */
    public function getNombreProvincia()
    {
        return $this->nombreProvincia;
    }

    /**
     * Set idProvincia
     *
     *
     *
     * @parámetro Integer $idProvincia
     * @return IdProvincia
     */
    public function setIdProvincia($idProvincia)
    {
        $this->idProvincia = (integer) $idProvincia;
        return $this;
    }

    /**
     * Get idProvincia
     *
     * @return null|Integer
     */
    public function getIdProvincia()
    {
        return $this->idProvincia;
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
     * @return PuertosModelo
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
