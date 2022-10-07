<?php
/**
 * Modelo RegistroMovimientosModelo
 *
 * Este archivo se complementa con el archivo   RegistroMovimientosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-11
 * @uses    RegistroMovimientosModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RegistroMovimientosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idRegistro;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreEmisor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipoUsuario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idMovilizacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEquino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidosOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidosEspecieOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroTotalOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroActualOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidosDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidosEspecieDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroTotalDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroActualDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $motivo;

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
     * Nombre de la tabla: registro_movimientos
     */
    private $tabla = "registro_movimientos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_registro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."registro_movimientos_id_registro_seq';

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
            throw new \Exception('Clase Modelo: RegistroMovimientosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: RegistroMovimientosModelo. Propiedad especificada invalida: get' . $name);
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
     *
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
     * Set nombreEmisor
     *
     *
     *
     * @parámetro String $nombreEmisor
     * @return NombreEmisor
     */
    public function setNombreEmisor($nombreEmisor)
    {
        $this->nombreEmisor = (string) $nombreEmisor;
        return $this;
    }

    /**
     * Get nombreEmisor
     *
     * @return null|String
     */
    public function getNombreEmisor()
    {
        return $this->nombreEmisor;
    }

    /**
     * Set tipoUsuario
     *
     *
     *
     * @parámetro String $tipoUsuario
     * @return TipoUsuario
     */
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = (string) $tipoUsuario;
        return $this;
    }

    /**
     * Get tipoUsuario
     *
     * @return null|String
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    /**
     * Set idMovilizacion
     *
     *
     *
     * @parámetro Integer $idMovilizacion
     * @return IdMovilizacion
     */
    public function setIdMovilizacion($idMovilizacion)
    {
        $this->idMovilizacion = (integer) $idMovilizacion;
        return $this;
    }

    /**
     * Get idMovilizacion
     *
     * @return null|Integer
     */
    public function getIdMovilizacion()
    {
        return $this->idMovilizacion;
    }

    /**
     * Set idEquino
     *
     *
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
     * Set idCatastroPredioEquidosOrigen
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidosOrigen
     * @return IdCatastroPredioEquidosOrigen
     */
    public function setIdCatastroPredioEquidosOrigen($idCatastroPredioEquidosOrigen)
    {
        $this->idCatastroPredioEquidosOrigen = (integer) $idCatastroPredioEquidosOrigen;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidosOrigen
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidosOrigen()
    {
        return $this->idCatastroPredioEquidosOrigen;
    }

    /**
     * Set idCatastroPredioEquidosEspecieOrigen
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidosEspecieOrigen
     * @return IdCatastroPredioEquidosEspecieOrigen
     */
    public function setIdCatastroPredioEquidosEspecieOrigen($idCatastroPredioEquidosEspecieOrigen)
    {
        $this->idCatastroPredioEquidosEspecieOrigen = (integer) $idCatastroPredioEquidosEspecieOrigen;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidosEspecieOrigen
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidosEspecieOrigen()
    {
        return $this->idCatastroPredioEquidosEspecieOrigen;
    }

    /**
     * Set numeroTotalOrigen
     *
     *
     *
     * @parámetro Integer $numeroTotalOrigen
     * @return NumeroTotalOrigen
     */
    public function setNumeroTotalOrigen($numeroTotalOrigen)
    {
        $this->numeroTotalOrigen = (integer) $numeroTotalOrigen;
        return $this;
    }

    /**
     * Get numeroTotalOrigen
     *
     * @return null|Integer
     */
    public function getNumeroTotalOrigen()
    {
        return $this->numeroTotalOrigen;
    }

    /**
     * Set numeroActualOrigen
     *
     *
     *
     * @parámetro Integer $numeroActualOrigen
     * @return NumeroActualOrigen
     */
    public function setNumeroActualOrigen($numeroActualOrigen)
    {
        $this->numeroActualOrigen = (integer) $numeroActualOrigen;
        return $this;
    }

    /**
     * Get numeroActualOrigen
     *
     * @return null|Integer
     */
    public function getNumeroActualOrigen()
    {
        return $this->numeroActualOrigen;
    }

    /**
     * Set idCatastroPredioEquidosDestino
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidosDestino
     * @return IdCatastroPredioEquidosDestino
     */
    public function setIdCatastroPredioEquidosDestino($idCatastroPredioEquidosDestino)
    {
        $this->idCatastroPredioEquidosDestino = (integer) $idCatastroPredioEquidosDestino;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidosDestino
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidosDestino()
    {
        return $this->idCatastroPredioEquidosDestino;
    }

    /**
     * Set idCatastroPredioEquidosEspecieDestino
     *
     *
     *
     * @parámetro Integer $idCatastroPredioEquidosEspecieDestino
     * @return IdCatastroPredioEquidosEspecieDestino
     */
    public function setIdCatastroPredioEquidosEspecieDestino($idCatastroPredioEquidosEspecieDestino)
    {
        $this->idCatastroPredioEquidosEspecieDestino = (integer) $idCatastroPredioEquidosEspecieDestino;
        return $this;
    }

    /**
     * Get idCatastroPredioEquidosEspecieDestino
     *
     * @return null|Integer
     */
    public function getIdCatastroPredioEquidosEspecieDestino()
    {
        return $this->idCatastroPredioEquidosEspecieDestino;
    }

    /**
     * Set numeroTotalDestino
     *
     *
     *
     * @parámetro Integer $numeroTotalDestino
     * @return NumeroTotalDestino
     */
    public function setNumeroTotalDestino($numeroTotalDestino)
    {
        $this->numeroTotalDestino = (integer) $numeroTotalDestino;
        return $this;
    }

    /**
     * Get numeroTotalDestino
     *
     * @return null|Integer
     */
    public function getNumeroTotalDestino()
    {
        return $this->numeroTotalDestino;
    }

    /**
     * Set numeroActualDestino
     *
     *
     *
     * @parámetro Integer $numeroActualDestino
     * @return NumeroActualDestino
     */
    public function setNumeroActualDestino($numeroActualDestino)
    {
        $this->numeroActualDestino = (integer) $numeroActualDestino;
        return $this;
    }

    /**
     * Get numeroActualDestino
     *
     * @return null|Integer
     */
    public function getNumeroActualDestino()
    {
        return $this->numeroActualDestino;
    }

    /**
     * Set motivo
     *
     *
     *
     * @parámetro String $motivo
     * @return Motivo
     */
    public function setMotivo($motivo)
    {
        $this->motivo = (string) $motivo;
        return $this;
    }

    /**
     * Get motivo
     *
     * @return null|String
     */
    public function getMotivo()
    {
        return $this->motivo;
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
     * @return RegistroMovimientosModelo
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
