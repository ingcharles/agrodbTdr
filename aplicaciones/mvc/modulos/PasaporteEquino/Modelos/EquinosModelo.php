<?php
/**
 * Modelo EquinosModelo
 *
 * Este archivo se complementa con el archivo   EquinosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-03
 * @uses    EquinosModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EquinosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEquino;

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
    protected $pasaporte;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idOrganizacionEcuestre;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idMiembro;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCatastroPredioEquidos;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEspecie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idRaza;

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
    protected $nombreEquino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $ubicacionActual;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estadoEquino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $motivoCambio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaModificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $rutaHojaFiliacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $sexo;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaNacimiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipoIdentificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $detalleIdentificacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaDeceso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $causaMuerte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $motivoDeceso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fotoFrente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fotoAtras;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fotoDerecha;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fotoIzquierda;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $rutaMotivoCambio;

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
     * Nombre de la tabla: equinos
     */
    private $tabla = "equinos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_equino";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."equinos_id_equino_seq';

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
            throw new \Exception('Clase Modelo: EquinosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: EquinosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set pasaporte
     *
     *
     *
     * @parámetro String $pasaporte
     * @return Pasaporte
     */
    public function setPasaporte($pasaporte)
    {
        $this->pasaporte = (string) $pasaporte;
        return $this;
    }

    /**
     * Get pasaporte
     *
     * @return null|String
     */
    public function getPasaporte()
    {
        return $this->pasaporte;
    }

    /**
     * Set idOrganizacionEcuestre
     *
     *
     *
     * @parámetro Integer $idOrganizacionEcuestre
     * @return IdOrganizacionEcuestre
     */
    public function setIdOrganizacionEcuestre($idOrganizacionEcuestre)
    {
        $this->idOrganizacionEcuestre = (integer) $idOrganizacionEcuestre;
        return $this;
    }

    /**
     * Get idOrganizacionEcuestre
     *
     * @return null|Integer
     */
    public function getIdOrganizacionEcuestre()
    {
        return $this->idOrganizacionEcuestre;
    }

    /**
     * Set idMiembro
     *
     *
     *
     * @parámetro Integer $idMiembro
     * @return IdMiembro
     */
    public function setIdMiembro($idMiembro)
    {
        $this->idMiembro = (integer) $idMiembro;
        return $this;
    }

    /**
     * Get idMiembro
     *
     * @return null|Integer
     */
    public function getIdMiembro()
    {
        return $this->idMiembro;
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
     * Set nombreEquino
     *
     *
     *
     * @parámetro String $nombreEquino
     * @return NombreEquino
     */
    public function setNombreEquino($nombreEquino)
    {
        $this->nombreEquino = (string) $nombreEquino;
        return $this;
    }

    /**
     * Get nombreEquino
     *
     * @return null|String
     */
    public function getNombreEquino()
    {
        return $this->nombreEquino;
    }

    /**
     * Set ubicacionActual
     *
     *
     *
     * @parámetro Integer $ubicacionActual
     * @return UbicacionActual
     */
    public function setUbicacionActual($ubicacionActual)
    {
        $this->ubicacionActual = (integer) $ubicacionActual;
        return $this;
    }

    /**
     * Get ubicacionActual
     *
     * @return null|Integer
     */
    public function getUbicacionActual()
    {
        return $this->ubicacionActual;
    }

    /**
     * Set estadoEquino
     *
     *
     *
     * @parámetro String $estadoEquino
     * @return EstadoEquino
     */
    public function setEstadoEquino($estadoEquino)
    {
        $this->estadoEquino = (string) $estadoEquino;
        return $this;
    }

    /**
     * Get estadoEquino
     *
     * @return null|String
     */
    public function getEstadoEquino()
    {
        return $this->estadoEquino;
    }

    /**
     * Set motivoCambio
     *
     *
     *
     * @parámetro String $motivoCambio
     * @return MotivoCambio
     */
    public function setMotivoCambio($motivoCambio)
    {
        $this->motivoCambio = (string) $motivoCambio;
        return $this;
    }

    /**
     * Get motivoCambio
     *
     * @return null|String
     */
    public function getMotivoCambio()
    {
        return $this->motivoCambio;
    }

    /**
     * Set fechaModificacion
     *
     *
     *
     * @parámetro String $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string) $fechaModificacion;
        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return null|String
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set rutaHojaFiliacion
     *
     *
     *
     * @parámetro String $rutaHojaFiliacion
     * @return RutaHojaFiliacion
     */
    public function setRutaHojaFiliacion($rutaHojaFiliacion)
    {
        $this->rutaHojaFiliacion = (string) $rutaHojaFiliacion;
        return $this;
    }

    /**
     * Get rutaHojaFiliacion
     *
     * @return null|String
     */
    public function getRutaHojaFiliacion()
    {
        return $this->rutaHojaFiliacion;
    }

    /**
     * Set sexo
     *
     *
     *
     * @parámetro String $sexo
     * @return Sexo
     */
    public function setSexo($sexo)
    {
        $this->sexo = (string) $sexo;
        return $this;
    }

    /**
     * Get sexo
     *
     * @return null|String
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set fechaNacimiento
     *
     *
     *
     * @parámetro Date $fechaNacimiento
     * @return FechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = (string) $fechaNacimiento;
        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return null|Date
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set tipoIdentificacion
     *
     *
     *
     * @parámetro String $tipoIdentificacion
     * @return TipoIdentificacion
     */
    public function setTipoIdentificacion($tipoIdentificacion)
    {
        $this->tipoIdentificacion = (string) $tipoIdentificacion;
        return $this;
    }

    /**
     * Get tipoIdentificacion
     *
     * @return null|String
     */
    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    /**
     * Set detalleIdentificacion
     *
     *
     *
     * @parámetro String $detalleIdentificacion
     * @return DetalleIdentificacion
     */
    public function setDetalleIdentificacion($detalleIdentificacion)
    {
        $this->detalleIdentificacion = (string) $detalleIdentificacion;
        return $this;
    }

    /**
     * Get detalleIdentificacion
     *
     * @return null|String
     */
    public function getDetalleIdentificacion()
    {
        return $this->detalleIdentificacion;
    }

    /**
     * Set fechaDeceso
     *
     *
     *
     * @parámetro Date $fechaDeceso
     * @return FechaDeceso
     */
    public function setFechaDeceso($fechaDeceso)
    {
        $this->fechaDeceso = (string) $fechaDeceso;
        return $this;
    }

    /**
     * Get fechaDeceso
     *
     * @return null|Date
     */
    public function getFechaDeceso()
    {
        return $this->fechaDeceso;
    }

    /**
     * Set causaMuerte
     *
     *
     *
     * @parámetro String $causaMuerte
     * @return CausaMuerte
     */
    public function setCausaMuerte($causaMuerte)
    {
        $this->causaMuerte = (string) $causaMuerte;
        return $this;
    }

    /**
     * Get causaMuerte
     *
     * @return null|String
     */
    public function getCausaMuerte()
    {
        return $this->causaMuerte;
    }

    /**
     * Set motivoDeceso
     *
     *
     *
     * @parámetro String $motivoDeceso
     * @return MotivoDeceso
     */
    public function setMotivoDeceso($motivoDeceso)
    {
        $this->motivoDeceso = (string) $motivoDeceso;
        return $this;
    }

    /**
     * Get motivoDeceso
     *
     * @return null|String
     */
    public function getMotivoDeceso()
    {
        return $this->motivoDeceso;
    }

    /**
     * Set fotoFrente
     *
     *
     *
     * @parámetro String $fotoFrente
     * @return FotoFrente
     */
    public function setFotoFrente($fotoFrente)
    {
        $this->fotoFrente = (string) $fotoFrente;
        return $this;
    }

    /**
     * Get fotoFrente
     *
     * @return null|String
     */
    public function getFotoFrente()
    {
        return $this->fotoFrente;
    }

    /**
     * Set fotoAtras
     *
     *
     *
     * @parámetro String $fotoAtras
     * @return FotoAtras
     */
    public function setFotoAtras($fotoAtras)
    {
        $this->fotoAtras = (string) $fotoAtras;
        return $this;
    }

    /**
     * Get fotoAtras
     *
     * @return null|String
     */
    public function getFotoAtras()
    {
        return $this->fotoAtras;
    }

    /**
     * Set fotoDerecha
     *
     *
     *
     * @parámetro String $fotoDerecha
     * @return FotoDerecha
     */
    public function setFotoDerecha($fotoDerecha)
    {
        $this->fotoDerecha = (string) $fotoDerecha;
        return $this;
    }

    /**
     * Get fotoDerecha
     *
     * @return null|String
     */
    public function getFotoDerecha()
    {
        return $this->fotoDerecha;
    }

    /**
     * Set fotoIzquierda
     *
     *
     *
     * @parámetro String $fotoIzquierda
     * @return FotoIzquierda
     */
    public function setFotoIzquierda($fotoIzquierda)
    {
        $this->fotoIzquierda = (string) $fotoIzquierda;
        return $this;
    }

    /**
     * Get fotoIzquierda
     *
     * @return null|String
     */
    public function getFotoIzquierda()
    {
        return $this->fotoIzquierda;
    }

    /**
     * Set rutaMotivoCambio
     *
     *
     *
     * @parámetro String $rutaMotivoCambio
     * @return RutaMotivoCambio
     */
    public function setRutaMotivoCambio($rutaMotivoCambio)
    {
        $this->rutaMotivoCambio = (string) $rutaMotivoCambio;
        return $this;
    }

    /**
     * Get rutaMotivoCambio
     *
     * @return null|String
     */
    public function getRutaMotivoCambio()
    {
        return $this->rutaMotivoCambio;
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
     * @return EquinosModelo
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
