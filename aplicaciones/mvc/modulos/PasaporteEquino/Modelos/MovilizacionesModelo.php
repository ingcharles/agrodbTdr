<?php
/**
 * Modelo MovilizacionesModelo
 *
 * Este archivo se complementa con el archivo   MovilizacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-11
 * @uses    MovilizacionesModelo
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MovilizacionesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idMovilizacion;

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
    protected $numeroMovilizacion;

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
    protected $idAsociacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreAsociacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreMiembro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provinciaSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idEquino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $pasaporteEquino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idUbicacionActual;

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
    protected $codigoUbicacionOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreUbicacionOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorPropietarioOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePropietarioOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProvinciaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provinciaOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCantonOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $cantonOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idParroquiaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $parroquiaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $direccionOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idSitioOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idAreaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $operacionOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipoDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorPropietarioDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePropietarioDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idUbicacionDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoUbicacionDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreUbicacionDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProvinciaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provinciaDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCantonDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $cantonDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idParroquiaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $parroquiaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $direccionDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idSitioDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idAreaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $operacionDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorSolicitante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreSolicitante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $medioTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $placaTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePropietarioTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorConductor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreConductor;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaInicioMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $observacionTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $rutaCertificado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estadoMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estadoFiscalizacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaFinMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $secuencialMovilizacion;

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
     * Nombre de la tabla: movilizaciones
     */
    private $tabla = "movilizaciones";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_movilizacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_pasaporte_equino"."movilizaciones_id_movilizacion_seq';

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
            throw new \Exception('Clase Modelo: MovilizacionesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: MovilizacionesModelo. Propiedad especificada invalida: get' . $name);
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
     * Set numeroMovilizacion
     *
     *
     *
     * @parámetro String $numeroMovilizacion
     * @return NumeroMovilizacion
     */
    public function setNumeroMovilizacion($numeroMovilizacion)
    {
        $this->numeroMovilizacion = (string) $numeroMovilizacion;
        return $this;
    }

    /**
     * Get numeroMovilizacion
     *
     * @return null|String
     */
    public function getNumeroMovilizacion()
    {
        return $this->numeroMovilizacion;
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
     * Set idAsociacion
     *
     *
     *
     * @parámetro Integer $idAsociacion
     * @return IdAsociacion
     */
    public function setIdAsociacion($idAsociacion)
    {
        $this->idAsociacion = (integer) $idAsociacion;
        return $this;
    }

    /**
     * Get idAsociacion
     *
     * @return null|Integer
     */
    public function getIdAsociacion()
    {
        return $this->idAsociacion;
    }

    /**
     * Set nombreAsociacion
     *
     *
     *
     * @parámetro String $nombreAsociacion
     * @return NombreAsociacion
     */
    public function setNombreAsociacion($nombreAsociacion)
    {
        $this->nombreAsociacion = (string) $nombreAsociacion;
        return $this;
    }

    /**
     * Get nombreAsociacion
     *
     * @return null|String
     */
    public function getNombreAsociacion()
    {
        return $this->nombreAsociacion;
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
     * Set identificadorMiembro
     *
     *
     *
     * @parámetro String $identificadorMiembro
     * @return IdentificadorMiembro
     */
    public function setIdentificadorMiembro($identificadorMiembro)
    {
        $this->identificadorMiembro = (string) $identificadorMiembro;
        return $this;
    }

    /**
     * Get identificadorMiembro
     *
     * @return null|String
     */
    public function getIdentificadorMiembro()
    {
        return $this->identificadorMiembro;
    }

    /**
     * Set nombreMiembro
     *
     *
     *
     * @parámetro String $nombreMiembro
     * @return NombreMiembro
     */
    public function setNombreMiembro($nombreMiembro)
    {
        $this->nombreMiembro = (string) $nombreMiembro;
        return $this;
    }

    /**
     * Get nombreMiembro
     *
     * @return null|String
     */
    public function getNombreMiembro()
    {
        return $this->nombreMiembro;
    }

    /**
     * Set provinciaSolicitud
     *
     *
     *
     * @parámetro String $provinciaSolicitud
     * @return ProvinciaSolicitud
     */
    public function setProvinciaSolicitud($provinciaSolicitud)
    {
        $this->provinciaSolicitud = (string) $provinciaSolicitud;
        return $this;
    }

    /**
     * Get provinciaSolicitud
     *
     * @return null|String
     */
    public function getProvinciaSolicitud()
    {
        return $this->provinciaSolicitud;
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
     * Set pasaporteEquino
     *
     *
     *
     * @parámetro String $pasaporteEquino
     * @return PasaporteEquino
     */
    public function setPasaporteEquino($pasaporteEquino)
    {
        $this->pasaporteEquino = (string) $pasaporteEquino;
        return $this;
    }

    /**
     * Get pasaporteEquino
     *
     * @return null|String
     */
    public function getPasaporteEquino()
    {
        return $this->pasaporteEquino;
    }

    /**
     * Set idUbicacionActual
     *
     *
     *
     * @parámetro Integer $idUbicacionActual
     * @return IdUbicacionActual
     */
    public function setIdUbicacionActual($idUbicacionActual)
    {
        $this->idUbicacionActual = (integer) $idUbicacionActual;
        return $this;
    }

    /**
     * Get idUbicacionActual
     *
     * @return null|Integer
     */
    public function getIdUbicacionActual()
    {
        return $this->idUbicacionActual;
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
     * Set codigoUbicacionOrigen
     *
     *
     *
     * @parámetro String $codigoUbicacionOrigen
     * @return CodigoUbicacionOrigen
     */
    public function setCodigoUbicacionOrigen($codigoUbicacionOrigen)
    {
        $this->codigoUbicacionOrigen = (string) $codigoUbicacionOrigen;
        return $this;
    }

    /**
     * Get codigoUbicacionOrigen
     *
     * @return null|String
     */
    public function getCodigoUbicacionOrigen()
    {
        return $this->codigoUbicacionOrigen;
    }

    /**
     * Set nombreUbicacionOrigen
     *
     *
     *
     * @parámetro String $nombreUbicacionOrigen
     * @return NombreUbicacionOrigen
     */
    public function setNombreUbicacionOrigen($nombreUbicacionOrigen)
    {
        $this->nombreUbicacionOrigen = (string) $nombreUbicacionOrigen;
        return $this;
    }

    /**
     * Get nombreUbicacionOrigen
     *
     * @return null|String
     */
    public function getNombreUbicacionOrigen()
    {
        return $this->nombreUbicacionOrigen;
    }

    /**
     * Set identificadorPropietarioOrigen
     *
     *
     *
     * @parámetro String $identificadorPropietarioOrigen
     * @return IdentificadorPropietarioOrigen
     */
    public function setIdentificadorPropietarioOrigen($identificadorPropietarioOrigen)
    {
        $this->identificadorPropietarioOrigen = (string) $identificadorPropietarioOrigen;
        return $this;
    }

    /**
     * Get identificadorPropietarioOrigen
     *
     * @return null|String
     */
    public function getIdentificadorPropietarioOrigen()
    {
        return $this->identificadorPropietarioOrigen;
    }

    /**
     * Set nombrePropietarioOrigen
     *
     *
     *
     * @parámetro String $nombrePropietarioOrigen
     * @return NombrePropietarioOrigen
     */
    public function setNombrePropietarioOrigen($nombrePropietarioOrigen)
    {
        $this->nombrePropietarioOrigen = (string) $nombrePropietarioOrigen;
        return $this;
    }

    /**
     * Get nombrePropietarioOrigen
     *
     * @return null|String
     */
    public function getNombrePropietarioOrigen()
    {
        return $this->nombrePropietarioOrigen;
    }

    /**
     * Set idProvinciaOrigen
     *
     *
     *
     * @parámetro Integer $idProvinciaOrigen
     * @return IdProvinciaOrigen
     */
    public function setIdProvinciaOrigen($idProvinciaOrigen)
    {
        $this->idProvinciaOrigen = (integer) $idProvinciaOrigen;
        return $this;
    }

    /**
     * Get idProvinciaOrigen
     *
     * @return null|Integer
     */
    public function getIdProvinciaOrigen()
    {
        return $this->idProvinciaOrigen;
    }

    /**
     * Set provinciaOrigen
     *
     *
     *
     * @parámetro String $provinciaOrigen
     * @return ProvinciaOrigen
     */
    public function setProvinciaOrigen($provinciaOrigen)
    {
        $this->provinciaOrigen = (string) $provinciaOrigen;
        return $this;
    }

    /**
     * Get provinciaOrigen
     *
     * @return null|String
     */
    public function getProvinciaOrigen()
    {
        return $this->provinciaOrigen;
    }

    /**
     * Set idCantonOrigen
     *
     *
     *
     * @parámetro Integer $idCantonOrigen
     * @return IdCantonOrigen
     */
    public function setIdCantonOrigen($idCantonOrigen)
    {
        $this->idCantonOrigen = (integer) $idCantonOrigen;
        return $this;
    }

    /**
     * Get idCantonOrigen
     *
     * @return null|Integer
     */
    public function getIdCantonOrigen()
    {
        return $this->idCantonOrigen;
    }

    /**
     * Set cantonOrigen
     *
     *
     *
     * @parámetro String $cantonOrigen
     * @return CantonOrigen
     */
    public function setCantonOrigen($cantonOrigen)
    {
        $this->cantonOrigen = (string) $cantonOrigen;
        return $this;
    }

    /**
     * Get cantonOrigen
     *
     * @return null|String
     */
    public function getCantonOrigen()
    {
        return $this->cantonOrigen;
    }

    /**
     * Set idParroquiaOrigen
     *
     *
     *
     * @parámetro Integer $idParroquiaOrigen
     * @return IdParroquiaOrigen
     */
    public function setIdParroquiaOrigen($idParroquiaOrigen)
    {
        $this->idParroquiaOrigen = (integer) $idParroquiaOrigen;
        return $this;
    }

    /**
     * Get idParroquiaOrigen
     *
     * @return null|Integer
     */
    public function getIdParroquiaOrigen()
    {
        return $this->idParroquiaOrigen;
    }

    /**
     * Set parroquiaOrigen
     *
     *
     *
     * @parámetro String $parroquiaOrigen
     * @return ParroquiaOrigen
     */
    public function setParroquiaOrigen($parroquiaOrigen)
    {
        $this->parroquiaOrigen = (string) $parroquiaOrigen;
        return $this;
    }

    /**
     * Get parroquiaOrigen
     *
     * @return null|String
     */
    public function getParroquiaOrigen()
    {
        return $this->parroquiaOrigen;
    }

    /**
     * Set direccionOrigen
     *
     *
     *
     * @parámetro String $direccionOrigen
     * @return DireccionOrigen
     */
    public function setDireccionOrigen($direccionOrigen)
    {
        $this->direccionOrigen = (string) $direccionOrigen;
        return $this;
    }

    /**
     * Get direccionOrigen
     *
     * @return null|String
     */
    public function getDireccionOrigen()
    {
        return $this->direccionOrigen;
    }

    /**
     * Set idSitioOrigen
     *
     *
     *
     * @parámetro Integer $idSitioOrigen
     * @return IdSitioOrigen
     */
    public function setIdSitioOrigen($idSitioOrigen)
    {
        $this->idSitioOrigen = (integer) $idSitioOrigen;
        return $this;
    }

    /**
     * Get idSitioOrigen
     *
     * @return null|Integer
     */
    public function getIdSitioOrigen()
    {
        return $this->idSitioOrigen;
    }

    /**
     * Set idAreaOrigen
     *
     *
     *
     * @parámetro Integer $idAreaOrigen
     * @return IdAreaOrigen
     */
    public function setIdAreaOrigen($idAreaOrigen)
    {
        $this->idAreaOrigen = (integer) $idAreaOrigen;
        return $this;
    }

    /**
     * Get idAreaOrigen
     *
     * @return null|Integer
     */
    public function getIdAreaOrigen()
    {
        return $this->idAreaOrigen;
    }

    /**
     * Set operacionOrigen
     *
     *
     *
     * @parámetro String $operacionOrigen
     * @return OperacionOrigen
     */
    public function setOperacionOrigen($operacionOrigen)
    {
        $this->operacionOrigen = (string) $operacionOrigen;
        return $this;
    }

    /**
     * Get operacionOrigen
     *
     * @return null|String
     */
    public function getOperacionOrigen()
    {
        return $this->operacionOrigen;
    }

    /**
     * Set tipoDestino
     *
     *
     *
     * @parámetro String $tipoDestino
     * @return TipoDestino
     */
    public function setTipoDestino($tipoDestino)
    {
        $this->tipoDestino = (string) $tipoDestino;
        return $this;
    }

    /**
     * Get tipoDestino
     *
     * @return null|String
     */
    public function getTipoDestino()
    {
        return $this->tipoDestino;
    }

    /**
     * Set identificadorPropietarioDestino
     *
     *
     *
     * @parámetro String $identificadorPropietarioDestino
     * @return IdentificadorPropietarioDestino
     */
    public function setIdentificadorPropietarioDestino($identificadorPropietarioDestino)
    {
        $this->identificadorPropietarioDestino = (string) $identificadorPropietarioDestino;
        return $this;
    }

    /**
     * Get identificadorPropietarioDestino
     *
     * @return null|String
     */
    public function getIdentificadorPropietarioDestino()
    {
        return $this->identificadorPropietarioDestino;
    }

    /**
     * Set nombrePropietarioDestino
     *
     *
     *
     * @parámetro String $nombrePropietarioDestino
     * @return NombrePropietarioDestino
     */
    public function setNombrePropietarioDestino($nombrePropietarioDestino)
    {
        $this->nombrePropietarioDestino = (string) $nombrePropietarioDestino;
        return $this;
    }

    /**
     * Get nombrePropietarioDestino
     *
     * @return null|String
     */
    public function getNombrePropietarioDestino()
    {
        return $this->nombrePropietarioDestino;
    }

    /**
     * Set idUbicacionDestino
     *
     *
     *
     * @parámetro Integer $idUbicacionDestino
     * @return IdUbicacionDestino
     */
    public function setIdUbicacionDestino($idUbicacionDestino)
    {
        $this->idUbicacionDestino = (integer) $idUbicacionDestino;
        return $this;
    }

    /**
     * Get idUbicacionDestino
     *
     * @return null|Integer
     */
    public function getIdUbicacionDestino()
    {
        return $this->idUbicacionDestino;
    }

    /**
     * Set codigoUbicacionDestino
     *
     *
     *
     * @parámetro String $codigoUbicacionDestino
     * @return CodigoUbicacionDestino
     */
    public function setCodigoUbicacionDestino($codigoUbicacionDestino)
    {
        $this->codigoUbicacionDestino = (string) $codigoUbicacionDestino;
        return $this;
    }

    /**
     * Get codigoUbicacionDestino
     *
     * @return null|String
     */
    public function getCodigoUbicacionDestino()
    {
        return $this->codigoUbicacionDestino;
    }

    /**
     * Set nombreUbicacionDestino
     *
     *
     *
     * @parámetro String $nombreUbicacionDestino
     * @return NombreUbicacionDestino
     */
    public function setNombreUbicacionDestino($nombreUbicacionDestino)
    {
        $this->nombreUbicacionDestino = (string) $nombreUbicacionDestino;
        return $this;
    }

    /**
     * Get nombreUbicacionDestino
     *
     * @return null|String
     */
    public function getNombreUbicacionDestino()
    {
        return $this->nombreUbicacionDestino;
    }

    /**
     * Set idProvinciaDestino
     *
     *
     *
     * @parámetro Integer $idProvinciaDestino
     * @return IdProvinciaDestino
     */
    public function setIdProvinciaDestino($idProvinciaDestino)
    {
        $this->idProvinciaDestino = (integer) $idProvinciaDestino;
        return $this;
    }

    /**
     * Get idProvinciaDestino
     *
     * @return null|Integer
     */
    public function getIdProvinciaDestino()
    {
        return $this->idProvinciaDestino;
    }

    /**
     * Set provinciaDestino
     *
     *
     *
     * @parámetro String $provinciaDestino
     * @return ProvinciaDestino
     */
    public function setProvinciaDestino($provinciaDestino)
    {
        $this->provinciaDestino = (string) $provinciaDestino;
        return $this;
    }

    /**
     * Get provinciaDestino
     *
     * @return null|String
     */
    public function getProvinciaDestino()
    {
        return $this->provinciaDestino;
    }

    /**
     * Set idCantonDestino
     *
     *
     *
     * @parámetro Integer $idCantonDestino
     * @return IdCantonDestino
     */
    public function setIdCantonDestino($idCantonDestino)
    {
        $this->idCantonDestino = (integer) $idCantonDestino;
        return $this;
    }

    /**
     * Get idCantonDestino
     *
     * @return null|Integer
     */
    public function getIdCantonDestino()
    {
        return $this->idCantonDestino;
    }

    /**
     * Set cantonDestino
     *
     *
     *
     * @parámetro String $cantonDestino
     * @return CantonDestino
     */
    public function setCantonDestino($cantonDestino)
    {
        $this->cantonDestino = (string) $cantonDestino;
        return $this;
    }

    /**
     * Get cantonDestino
     *
     * @return null|String
     */
    public function getCantonDestino()
    {
        return $this->cantonDestino;
    }

    /**
     * Set idParroquiaDestino
     *
     *
     *
     * @parámetro Integer $idParroquiaDestino
     * @return IdParroquiaDestino
     */
    public function setIdParroquiaDestino($idParroquiaDestino)
    {
        $this->idParroquiaDestino = (integer) $idParroquiaDestino;
        return $this;
    }

    /**
     * Get idParroquiaDestino
     *
     * @return null|Integer
     */
    public function getIdParroquiaDestino()
    {
        return $this->idParroquiaDestino;
    }

    /**
     * Set parroquiaDestino
     *
     *
     *
     * @parámetro String $parroquiaDestino
     * @return ParroquiaDestino
     */
    public function setParroquiaDestino($parroquiaDestino)
    {
        $this->parroquiaDestino = (string) $parroquiaDestino;
        return $this;
    }

    /**
     * Get parroquiaDestino
     *
     * @return null|String
     */
    public function getParroquiaDestino()
    {
        return $this->parroquiaDestino;
    }

    /**
     * Set direccionDestino
     *
     *
     *
     * @parámetro String $direccionDestino
     * @return DireccionDestino
     */
    public function setDireccionDestino($direccionDestino)
    {
        $this->direccionDestino = (string) $direccionDestino;
        return $this;
    }

    /**
     * Get direccionDestino
     *
     * @return null|String
     */
    public function getDireccionDestino()
    {
        return $this->direccionDestino;
    }

    /**
     * Set idSitioDestino
     *
     *
     *
     * @parámetro Integer $idSitioDestino
     * @return IdSitioDestino
     */
    public function setIdSitioDestino($idSitioDestino)
    {
        $this->idSitioDestino = (integer) $idSitioDestino;
        return $this;
    }

    /**
     * Get idSitioDestino
     *
     * @return null|Integer
     */
    public function getIdSitioDestino()
    {
        return $this->idSitioDestino;
    }

    /**
     * Set idAreaDestino
     *
     *
     *
     * @parámetro Integer $idAreaDestino
     * @return IdAreaDestino
     */
    public function setIdAreaDestino($idAreaDestino)
    {
        $this->idAreaDestino = (integer) $idAreaDestino;
        return $this;
    }

    /**
     * Get idAreaDestino
     *
     * @return null|Integer
     */
    public function getIdAreaDestino()
    {
        return $this->idAreaDestino;
    }

    /**
     * Set operacionDestino
     *
     *
     *
     * @parámetro String $operacionDestino
     * @return OperacionDestino
     */
    public function setOperacionDestino($operacionDestino)
    {
        $this->operacionDestino = (string) $operacionDestino;
        return $this;
    }

    /**
     * Get operacionDestino
     *
     * @return null|String
     */
    public function getOperacionDestino()
    {
        return $this->operacionDestino;
    }

    /**
     * Set identificadorSolicitante
     *
     *
     *
     * @parámetro String $identificadorSolicitante
     * @return IdentificadorSolicitante
     */
    public function setIdentificadorSolicitante($identificadorSolicitante)
    {
        $this->identificadorSolicitante = (string) $identificadorSolicitante;
        return $this;
    }

    /**
     * Get identificadorSolicitante
     *
     * @return null|String
     */
    public function getIdentificadorSolicitante()
    {
        return $this->identificadorSolicitante;
    }

    /**
     * Set nombreSolicitante
     *
     *
     *
     * @parámetro String $nombreSolicitante
     * @return NombreSolicitante
     */
    public function setNombreSolicitante($nombreSolicitante)
    {
        $this->nombreSolicitante = (string) $nombreSolicitante;
        return $this;
    }

    /**
     * Get nombreSolicitante
     *
     * @return null|String
     */
    public function getNombreSolicitante()
    {
        return $this->nombreSolicitante;
    }

    /**
     * Set medioTransporte
     *
     *
     *
     * @parámetro String $medioTransporte
     * @return MedioTransporte
     */
    public function setMedioTransporte($medioTransporte)
    {
        $this->medioTransporte = (string) $medioTransporte;
        return $this;
    }

    /**
     * Get medioTransporte
     *
     * @return null|String
     */
    public function getMedioTransporte()
    {
        return $this->medioTransporte;
    }

    /**
     * Set placaTransporte
     *
     *
     *
     * @parámetro String $placaTransporte
     * @return PlacaTransporte
     */
    public function setPlacaTransporte($placaTransporte)
    {
        $this->placaTransporte = (string) $placaTransporte;
        return $this;
    }

    /**
     * Get placaTransporte
     *
     * @return null|String
     */
    public function getPlacaTransporte()
    {
        return $this->placaTransporte;
    }

    /**
     * Set nombrePropietarioTransporte
     *
     *
     *
     * @parámetro String $nombrePropietarioTransporte
     * @return NombrePropietarioTransporte
     */
    public function setNombrePropietarioTransporte($nombrePropietarioTransporte)
    {
        $this->nombrePropietarioTransporte = (string) $nombrePropietarioTransporte;
        return $this;
    }

    /**
     * Get nombrePropietarioTransporte
     *
     * @return null|String
     */
    public function getNombrePropietarioTransporte()
    {
        return $this->nombrePropietarioTransporte;
    }

    /**
     * Set identificadorConductor
     *
     *
     *
     * @parámetro String $identificadorConductor
     * @return IdentificadorConductor
     */
    public function setIdentificadorConductor($identificadorConductor)
    {
        $this->identificadorConductor = (string) $identificadorConductor;
        return $this;
    }

    /**
     * Get identificadorConductor
     *
     * @return null|String
     */
    public function getIdentificadorConductor()
    {
        return $this->identificadorConductor;
    }

    /**
     * Set nombreConductor
     *
     *
     *
     * @parámetro String $nombreConductor
     * @return NombreConductor
     */
    public function setNombreConductor($nombreConductor)
    {
        $this->nombreConductor = (string) $nombreConductor;
        return $this;
    }

    /**
     * Get nombreConductor
     *
     * @return null|String
     */
    public function getNombreConductor()
    {
        return $this->nombreConductor;
    }

    /**
     * Set fechaInicioMovilizacion
     *
     *
     *
     * @parámetro Date $fechaInicioMovilizacion
     * @return FechaInicioMovilizacion
     */
    public function setFechaInicioMovilizacion($fechaInicioMovilizacion)
    {
        $this->fechaInicioMovilizacion = (string) $fechaInicioMovilizacion;
        return $this;
    }

    /**
     * Get fechaInicioMovilizacion
     *
     * @return null|Date
     */
    public function getFechaInicioMovilizacion()
    {
        return $this->fechaInicioMovilizacion;
    }

    /**
     * Set observacionTransporte
     *
     *
     *
     * @parámetro String $observacionTransporte
     * @return ObservacionTransporte
     */
    public function setObservacionTransporte($observacionTransporte)
    {
        $this->observacionTransporte = (string) $observacionTransporte;
        return $this;
    }

    /**
     * Get observacionTransporte
     *
     * @return null|String
     */
    public function getObservacionTransporte()
    {
        return $this->observacionTransporte;
    }

    /**
     * Set rutaCertificado
     *
     *
     *
     * @parámetro String $rutaCertificado
     * @return RutaCertificado
     */
    public function setRutaCertificado($rutaCertificado)
    {
        $this->rutaCertificado = (string) $rutaCertificado;
        return $this;
    }

    /**
     * Get rutaCertificado
     *
     * @return null|String
     */
    public function getRutaCertificado()
    {
        return $this->rutaCertificado;
    }

    /**
     * Set estadoMovilizacion
     *
     *
     *
     * @parámetro String $estadoMovilizacion
     * @return EstadoMovilizacion
     */
    public function setEstadoMovilizacion($estadoMovilizacion)
    {
        $this->estadoMovilizacion = (string) $estadoMovilizacion;
        return $this;
    }

    /**
     * Get estadoMovilizacion
     *
     * @return null|String
     */
    public function getEstadoMovilizacion()
    {
        return $this->estadoMovilizacion;
    }

    /**
     * Set estadoFiscalizacion
     *
     *
     *
     * @parámetro String $estadoFiscalizacion
     * @return EstadoFiscalizacion
     */
    public function setEstadoFiscalizacion($estadoFiscalizacion)
    {
        $this->estadoFiscalizacion = (string) $estadoFiscalizacion;
        return $this;
    }

    /**
     * Get estadoFiscalizacion
     *
     * @return null|String
     */
    public function getEstadoFiscalizacion()
    {
        return $this->estadoFiscalizacion;
    }

    /**
     * Set fechaFinMovilizacion
     *
     *
     *
     * @parámetro Date $fechaFinMovilizacion
     * @return FechaFinMovilizacion
     */
    public function setFechaFinMovilizacion($fechaFinMovilizacion)
    {
        $this->fechaFinMovilizacion = (string) $fechaFinMovilizacion;
        return $this;
    }

    /**
     * Get fechaFinMovilizacion
     *
     * @return null|Date
     */
    public function getFechaFinMovilizacion()
    {
        return $this->fechaFinMovilizacion;
    }

    /**
     * Set secuencialMovilizacion
     *
     *
     *
     * @parámetro String $secuencialMovilizacion
     * @return SecuencialMovilizacion
     */
    public function setSecuencialMovilizacion($secuencialMovilizacion)
    {
        $this->secuencialMovilizacion = (string) $secuencialMovilizacion;
        return $this;
    }

    /**
     * Get secuencialMovilizacion
     *
     * @return null|String
     */
    public function getSecuencialMovilizacion()
    {
        return $this->secuencialMovilizacion;
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
     * @return MovilizacionesModelo
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
