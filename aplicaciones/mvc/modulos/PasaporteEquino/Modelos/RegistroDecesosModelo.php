<?php
/**
 * Modelo RegistroDecesosModelo
 *
 * Este archivo se complementa con el archivo   RegistroDecesosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-08
 * @uses    RegistroDecesosModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RegistroDecesosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idRegistro;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registo
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro del equino
     */
    protected $idEquino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del catastro de predio de équidos
     */
    protected $idCatastroPredioEquidos;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del catastro de predio de équidos especie del que se restará el animal
     */
    protected $idCatastroPredioEquidosEspecie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de equinos registrados antes del deceso
     */
    protected $numeroTotal;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de equinos tras el registro del deceso
     */
    protected $numeroActual;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que realiza el registro del deceso
     */
    protected $identificador;

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
     * Nombre de la tabla: registro_decesos
     */
    private $tabla = "registro_decesos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_registro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."registro_decesos_id_registro_seq';

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
            throw new \Exception('Clase Modelo: RegistroDecesosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: RegistroDecesosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idRegistro
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idRegistro
     * @return IdRegistro
     */
    public function setIdRegistro($idRegistro)
    {
        $this->idRegistro = (integer) $idRegistro;
        return $this;
    }

    /**
     * Get idRegistro
     *
     * @return null|Integer
     */
    public function getIdRegistro()
    {
        return $this->idRegistro;
    }

    /**
     * Set fechaCreacion
     *
     * Fecha de creación del registo
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
     * Set idEquino
     *
     * Identificador del registro del equino
     *
     * @parámetro Integer $idEquino
     * @return IdEquino
     */
    public function setIdEquino($idEquino)
    {
        $this->idEquino = (integer) $idEquino;
        return $this;
    }

    /**
     * Get idEquino
     *
     * @return null|Integer
     */
    public function getIdEquino()
    {
        return $this->idEquino;
    }

    /**
     * Set idCatastroPredioEquidos
     *
     * Identificador del catastro de predio de équidos
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
     * Set idCatastroPredioEquidosEspecie
     *
     * Identificador del catastro de predio de équidos especie del que se restará el animal
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
     * Set numeroTotal
     *
     * Número de equinos registrados antes del deceso
     *
     * @parámetro Integer $numeroTotal
     * @return NumeroTotal
     */
    public function setNumeroTotal($numeroTotal)
    {
        $this->numeroTotal = (integer) $numeroTotal;
        return $this;
    }

    /**
     * Get numeroTotal
     *
     * @return null|Integer
     */
    public function getNumeroTotal()
    {
        return $this->numeroTotal;
    }

    /**
     * Set numeroActual
     *
     * Número de equinos tras el registro del deceso
     *
     * @parámetro Integer $numeroActual
     * @return NumeroActual
     */
    public function setNumeroActual($numeroActual)
    {
        $this->numeroActual = (integer) $numeroActual;
        return $this;
    }

    /**
     * Get numeroActual
     *
     * @return null|Integer
     */
    public function getNumeroActual()
    {
        return $this->numeroActual;
    }

    /**
     * Set identificador
     *
     * Identificador del usuario que realiza el registro del deceso
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
     * @return RegistroDecesosModelo
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
