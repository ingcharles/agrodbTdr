<?php
/**
 * Modelo CatastroPredioEquidosModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-16
 * @uses    CatastroPredioEquidosModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
namespace Agrodb\ProgramasControlOficial\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CatastroPredioEquidosModelo extends ModeloBase
{

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
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numSolicitud;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fecha;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePredio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePropietario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $cedulaPropietario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $telefonoPropietario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $correoElectronicoPropietario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreAdministrador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $cedulaAdministrador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $telefonoAdministrador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $correoElectronicoAdministrador;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCanton;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $canton;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idParroquia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $parroquia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $direccionPredio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $utmX;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $utmY;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $utmZ;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $altitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $extension;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $latitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $longitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $zona;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorModificacion;

    /**
     *
     * @var Date Campo requerido
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
    protected $nuevaInspeccion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaNuevaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorCierre;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCierre;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $observaciones;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $imagenMapa;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $rutaInforme;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del sitio equivalente en el registro de operador
     */
    protected $idSitio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del área equivalente en el registro de operador
     */
    protected $idArea;

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
     * Nombre de la tabla: catastro_predio_equidos
     */
    private $tabla = "catastro_predio_equidos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_catastro_predio_equidos";

    /**
     * Secuencia
     */
    private $secuencial = 'g_programas_control_oficial"."catastro_predio_equidos_id_catastro_predio_equidos_seq';

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
            throw new \Exception('Clase Modelo: CatastroPredioEquidosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CatastroPredioEquidosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set numSolicitud
     *
     *
     *
     * @parámetro String $numSolicitud
     * @return NumSolicitud
     */
    public function setNumSolicitud($numSolicitud)
    {
        $this->numSolicitud = (string) $numSolicitud;
        return $this;
    }

    /**
     * Get numSolicitud
     *
     * @return null|String
     */
    public function getNumSolicitud()
    {
        return $this->numSolicitud;
    }

    /**
     * Set fecha
     *
     *
     *
     * @parámetro Date $fecha
     * @return Fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = (string) $fecha;
        return $this;
    }

    /**
     * Get fecha
     *
     * @return null|Date
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set nombrePredio
     *
     *
     *
     * @parámetro String $nombrePredio
     * @return NombrePredio
     */
    public function setNombrePredio($nombrePredio)
    {
        $this->nombrePredio = (string) $nombrePredio;
        return $this;
    }

    /**
     * Get nombrePredio
     *
     * @return null|String
     */
    public function getNombrePredio()
    {
        return $this->nombrePredio;
    }

    /**
     * Set nombrePropietario
     *
     *
     *
     * @parámetro String $nombrePropietario
     * @return NombrePropietario
     */
    public function setNombrePropietario($nombrePropietario)
    {
        $this->nombrePropietario = (string) $nombrePropietario;
        return $this;
    }

    /**
     * Get nombrePropietario
     *
     * @return null|String
     */
    public function getNombrePropietario()
    {
        return $this->nombrePropietario;
    }

    /**
     * Set cedulaPropietario
     *
     *
     *
     * @parámetro String $cedulaPropietario
     * @return CedulaPropietario
     */
    public function setCedulaPropietario($cedulaPropietario)
    {
        $this->cedulaPropietario = (string) $cedulaPropietario;
        return $this;
    }

    /**
     * Get cedulaPropietario
     *
     * @return null|String
     */
    public function getCedulaPropietario()
    {
        return $this->cedulaPropietario;
    }

    /**
     * Set telefonoPropietario
     *
     *
     *
     * @parámetro String $telefonoPropietario
     * @return TelefonoPropietario
     */
    public function setTelefonoPropietario($telefonoPropietario)
    {
        $this->telefonoPropietario = (string) $telefonoPropietario;
        return $this;
    }

    /**
     * Get telefonoPropietario
     *
     * @return null|String
     */
    public function getTelefonoPropietario()
    {
        return $this->telefonoPropietario;
    }

    /**
     * Set correoElectronicoPropietario
     *
     *
     *
     * @parámetro String $correoElectronicoPropietario
     * @return CorreoElectronicoPropietario
     */
    public function setCorreoElectronicoPropietario($correoElectronicoPropietario)
    {
        $this->correoElectronicoPropietario = (string) $correoElectronicoPropietario;
        return $this;
    }

    /**
     * Get correoElectronicoPropietario
     *
     * @return null|String
     */
    public function getCorreoElectronicoPropietario()
    {
        return $this->correoElectronicoPropietario;
    }

    /**
     * Set nombreAdministrador
     *
     *
     *
     * @parámetro String $nombreAdministrador
     * @return NombreAdministrador
     */
    public function setNombreAdministrador($nombreAdministrador)
    {
        $this->nombreAdministrador = (string) $nombreAdministrador;
        return $this;
    }

    /**
     * Get nombreAdministrador
     *
     * @return null|String
     */
    public function getNombreAdministrador()
    {
        return $this->nombreAdministrador;
    }

    /**
     * Set cedulaAdministrador
     *
     *
     *
     * @parámetro String $cedulaAdministrador
     * @return CedulaAdministrador
     */
    public function setCedulaAdministrador($cedulaAdministrador)
    {
        $this->cedulaAdministrador = (string) $cedulaAdministrador;
        return $this;
    }

    /**
     * Get cedulaAdministrador
     *
     * @return null|String
     */
    public function getCedulaAdministrador()
    {
        return $this->cedulaAdministrador;
    }

    /**
     * Set telefonoAdministrador
     *
     *
     *
     * @parámetro String $telefonoAdministrador
     * @return TelefonoAdministrador
     */
    public function setTelefonoAdministrador($telefonoAdministrador)
    {
        $this->telefonoAdministrador = (string) $telefonoAdministrador;
        return $this;
    }

    /**
     * Get telefonoAdministrador
     *
     * @return null|String
     */
    public function getTelefonoAdministrador()
    {
        return $this->telefonoAdministrador;
    }

    /**
     * Set correoElectronicoAdministrador
     *
     *
     *
     * @parámetro String $correoElectronicoAdministrador
     * @return CorreoElectronicoAdministrador
     */
    public function setCorreoElectronicoAdministrador($correoElectronicoAdministrador)
    {
        $this->correoElectronicoAdministrador = (string) $correoElectronicoAdministrador;
        return $this;
    }

    /**
     * Get correoElectronicoAdministrador
     *
     * @return null|String
     */
    public function getCorreoElectronicoAdministrador()
    {
        return $this->correoElectronicoAdministrador;
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
     * Set provincia
     *
     *
     *
     * @parámetro String $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
        return $this;
    }

    /**
     * Get provincia
     *
     * @return null|String
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set idCanton
     *
     *
     *
     * @parámetro Integer $idCanton
     * @return IdCanton
     */
    public function setIdCanton($idCanton)
    {
        $this->idCanton = (integer) $idCanton;
        return $this;
    }

    /**
     * Get idCanton
     *
     * @return null|Integer
     */
    public function getIdCanton()
    {
        return $this->idCanton;
    }

    /**
     * Set canton
     *
     *
     *
     * @parámetro String $canton
     * @return Canton
     */
    public function setCanton($canton)
    {
        $this->canton = (string) $canton;
        return $this;
    }

    /**
     * Get canton
     *
     * @return null|String
     */
    public function getCanton()
    {
        return $this->canton;
    }

    /**
     * Set idParroquia
     *
     *
     *
     * @parámetro Integer $idParroquia
     * @return IdParroquia
     */
    public function setIdParroquia($idParroquia)
    {
        $this->idParroquia = (integer) $idParroquia;
        return $this;
    }

    /**
     * Get idParroquia
     *
     * @return null|Integer
     */
    public function getIdParroquia()
    {
        return $this->idParroquia;
    }

    /**
     * Set parroquia
     *
     *
     *
     * @parámetro String $parroquia
     * @return Parroquia
     */
    public function setParroquia($parroquia)
    {
        $this->parroquia = (string) $parroquia;
        return $this;
    }

    /**
     * Get parroquia
     *
     * @return null|String
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Set direccionPredio
     *
     *
     *
     * @parámetro String $direccionPredio
     * @return DireccionPredio
     */
    public function setDireccionPredio($direccionPredio)
    {
        $this->direccionPredio = (string) $direccionPredio;
        return $this;
    }

    /**
     * Get direccionPredio
     *
     * @return null|String
     */
    public function getDireccionPredio()
    {
        return $this->direccionPredio;
    }

    /**
     * Set utmX
     *
     *
     *
     * @parámetro String $utmX
     * @return UtmX
     */
    public function setUtmX($utmX)
    {
        $this->utmX = (string) $utmX;
        return $this;
    }

    /**
     * Get utmX
     *
     * @return null|String
     */
    public function getUtmX()
    {
        return $this->utmX;
    }

    /**
     * Set utmY
     *
     *
     *
     * @parámetro String $utmY
     * @return UtmY
     */
    public function setUtmY($utmY)
    {
        $this->utmY = (string) $utmY;
        return $this;
    }

    /**
     * Get utmY
     *
     * @return null|String
     */
    public function getUtmY()
    {
        return $this->utmY;
    }

    /**
     * Set utmZ
     *
     *
     *
     * @parámetro String $utmZ
     * @return UtmZ
     */
    public function setUtmZ($utmZ)
    {
        $this->utmZ = (string) $utmZ;
        return $this;
    }

    /**
     * Get utmZ
     *
     * @return null|String
     */
    public function getUtmZ()
    {
        return $this->utmZ;
    }

    /**
     * Set altitud
     *
     *
     *
     * @parámetro String $altitud
     * @return Altitud
     */
    public function setAltitud($altitud)
    {
        $this->altitud = (string) $altitud;
        return $this;
    }

    /**
     * Get altitud
     *
     * @return null|String
     */
    public function getAltitud()
    {
        return $this->altitud;
    }

    /**
     * Set extension
     *
     *
     *
     * @parámetro String $extension
     * @return Extension
     */
    public function setExtension($extension)
    {
        $this->extension = (string) $extension;
        return $this;
    }

    /**
     * Get extension
     *
     * @return null|String
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set latitud
     *
     *
     *
     * @parámetro String $latitud
     * @return Latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = (string) $latitud;
        return $this;
    }

    /**
     * Get latitud
     *
     * @return null|String
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     *
     *
     * @parámetro String $longitud
     * @return Longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = (string) $longitud;
        return $this;
    }

    /**
     * Get longitud
     *
     * @return null|String
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set zona
     *
     *
     *
     * @parámetro String $zona
     * @return Zona
     */
    public function setZona($zona)
    {
        $this->zona = (string) $zona;
        return $this;
    }

    /**
     * Get zona
     *
     * @return null|String
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set estado
     *
     *
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
     * Set identificadorModificacion
     *
     *
     *
     * @parámetro String $identificadorModificacion
     * @return IdentificadorModificacion
     */
    public function setIdentificadorModificacion($identificadorModificacion)
    {
        $this->identificadorModificacion = (string) $identificadorModificacion;
        return $this;
    }

    /**
     * Get identificadorModificacion
     *
     * @return null|String
     */
    public function getIdentificadorModificacion()
    {
        return $this->identificadorModificacion;
    }

    /**
     * Set fechaModificacion
     *
     *
     *
     * @parámetro Date $fechaModificacion
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
     * @return null|Date
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set nuevaInspeccion
     *
     *
     *
     * @parámetro String $nuevaInspeccion
     * @return NuevaInspeccion
     */
    public function setNuevaInspeccion($nuevaInspeccion)
    {
        $this->nuevaInspeccion = (string) $nuevaInspeccion;
        return $this;
    }

    /**
     * Get nuevaInspeccion
     *
     * @return null|String
     */
    public function getNuevaInspeccion()
    {
        return $this->nuevaInspeccion;
    }

    /**
     * Set fechaNuevaInspeccion
     *
     *
     *
     * @parámetro Date $fechaNuevaInspeccion
     * @return FechaNuevaInspeccion
     */
    public function setFechaNuevaInspeccion($fechaNuevaInspeccion)
    {
        $this->fechaNuevaInspeccion = (string) $fechaNuevaInspeccion;
        return $this;
    }

    /**
     * Get fechaNuevaInspeccion
     *
     * @return null|Date
     */
    public function getFechaNuevaInspeccion()
    {
        return $this->fechaNuevaInspeccion;
    }

    /**
     * Set identificadorCierre
     *
     *
     *
     * @parámetro String $identificadorCierre
     * @return IdentificadorCierre
     */
    public function setIdentificadorCierre($identificadorCierre)
    {
        $this->identificadorCierre = (string) $identificadorCierre;
        return $this;
    }

    /**
     * Get identificadorCierre
     *
     * @return null|String
     */
    public function getIdentificadorCierre()
    {
        return $this->identificadorCierre;
    }

    /**
     * Set fechaCierre
     *
     *
     *
     * @parámetro Date $fechaCierre
     * @return FechaCierre
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = (string) $fechaCierre;
        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return null|Date
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set observaciones
     *
     *
     *
     * @parámetro String $observaciones
     * @return Observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = (string) $observaciones;
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set imagenMapa
     *
     *
     *
     * @parámetro String $imagenMapa
     * @return ImagenMapa
     */
    public function setImagenMapa($imagenMapa)
    {
        $this->imagenMapa = (string) $imagenMapa;
        return $this;
    }

    /**
     * Get imagenMapa
     *
     * @return null|String
     */
    public function getImagenMapa()
    {
        return $this->imagenMapa;
    }

    /**
     * Set rutaInforme
     *
     *
     *
     * @parámetro String $rutaInforme
     * @return RutaInforme
     */
    public function setRutaInforme($rutaInforme)
    {
        $this->rutaInforme = (string) $rutaInforme;
        return $this;
    }

    /**
     * Get rutaInforme
     *
     * @return null|String
     */
    public function getRutaInforme()
    {
        return $this->rutaInforme;
    }

    /**
     * Set idSitio
     *
     * Identificador del sitio equivalente en el registro de operador
     *
     * @parámetro Integer $idSitio
     * @return IdSitio
     */
    public function setIdSitio($idSitio)
    {
        $this->idSitio = (integer) $idSitio;
        return $this;
    }

    /**
     * Get idSitio
     *
     * @return null|Integer
     */
    public function getIdSitio()
    {
        return $this->idSitio;
    }

    /**
     * Set idArea
     *
     * Identificador del área equivalente en el registro de operador
     *
     * @parámetro Integer $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (integer) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|Integer
     */
    public function getIdArea()
    {
        return $this->idArea;
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
     * @return CatastroPredioEquidosModelo
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
