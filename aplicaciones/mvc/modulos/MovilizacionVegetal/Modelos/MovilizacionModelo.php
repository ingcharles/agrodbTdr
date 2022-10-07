<?php
/**
 * Modelo MovilizacionModelo
 *
 * Este archivo se complementa con el archivo   MovilizacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    MovilizacionModelo
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionVegetal\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MovilizacionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro.
     */
    protected $idMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del creador del registro (técnico AGR u operador por autoservicio)
     */
    protected $identificador;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número del permiso de movilización que contiene:
     *      -Código provincia sitio de origen
     *      -Código provincia sitio destino
     *      -Fecha emisión permiso movilización ddmmaa
     *      -Código secuencial permiso (5 dígitos)
     */
    protected $numeroPermiso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de solicitud de movilización: Fitosanitario
     */
    protected $tipoSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código de la provincia de emisión del permiso
     */
    protected $idProvinciaEmision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia de emisión del permiso
     */
    protected $provinciaEmision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código del cantón de emisión del permiso
     */
    protected $idCantonEmision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón de emisión del permiso
     */
    protected $cantonEmision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código de la oficina de emisión del permiso
     */
    protected $idOficinaEmision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la oficina de emisión del permiso
     */
    protected $oficinaEmision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código de la provincia de origen de la movilización
     */
    protected $idProvinciaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia de origen de la movilización
     */
    protected $provinciaOrigen;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón de origen del permiso
     */
    protected $cantonOrigen;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la parroquia de origen del permiso
     */
    protected $parroquiaOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operador de origen
     */
    protected $identificadorOperadorOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del operador de origen
     */
    protected $nombreOperadorOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código del sitio de origen
     */
    protected $idSitioOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del sitio de origen
     */
    protected $sitioOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único de identificación del sitio
     */
    protected $codigoSitioOrigen;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Código de la provincia de destino de la movilización
     */
    protected $idProvinciaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia de destino de la movilización
     */
    protected $provinciaDestino;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón de destino del permiso
     */
    protected $cantonDestino;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la parroquia de destino del permiso
     */
    protected $parroquiaDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operdor de destino
     */
    protected $identificadorOperadorDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del operador de destino
     */
    protected $nombreOperadorDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del sitio de destino
     */
    protected $idSitioDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del sitio de destino
     */
    protected $sitioDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único de identificación del sitio de destino
     */
    protected $codigoSitioDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del tipo de medio de transporte: Terrestre
     */
    protected $medioTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Placa del vehículo usado en la movilización
     */
    protected $placaTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del conductor del vehículo
     */
    protected $identificadorConductor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del conductor del vehículo
     */
    protected $nombreConductor;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de inicio de la movilizacion
     */
    protected $fechaInicioMovilizacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Hora de inicio de la movilización
     */
    protected $horaInicioMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones del medio de transporte
     */
    protected $observacionTransporte;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del archivo del certificado de movilización
     */
    protected $rutaCertificado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la movilización:
     *      -Vigente
     *      -Caducado
     */
    protected $estadoMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la última fiscalización de la movilización:
     *      -Fiscalizado
     *      -No fiscalizado
     */
    protected $estadoFiscalizacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de finalización de la movilizacion
     */
    protected $fechaFinMovilizacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Hora de finalización de la movilización
     */
    protected $horaFinMovilizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Valor secuencial para el número de permiso de movilización
     */
    protected $secuencialMovilizacion;

    /**
    * @var Integer
    * Campo requerido
    * Campo visible en el formulario
    * Código del área de origen
    */
    protected $idAreaOrigen;

    /**
    * @var String
    * Campo requerido
    * Campo visible en el formulario
    * Nombre del área de origen
    */
    protected $areaOrigen;

    /**
    * @var String
    * Campo requerido
    * Campo visible en el formulario
    * Código único de identificación del área de origen
    */
    protected $codigoAreaOrigen;

    /**
    * @var Integer
    * Campo requerido
    * Campo visible en el formulario
    * Código del área de destino
    */
    protected $idAreaDestino;

    /**
    * @var String
    * Campo requerido
    * Campo visible en el formulario
    * Nombre del área de destino
    */
    protected $areaDestino;

    /**
    * @var String
    * Campo requerido
    * Campo visible en el formulario
    * Código único de identificación del área de destino
    */
    protected $codigoAreaDestino;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_movilizacion_vegetal";

    /**
     * Nombre de la tabla: movilizacion
     */
    private $tabla = "movilizacion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_movilizacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_movilizacion_vegetal"."movilizacion_id_movilizacion_seq';

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
            throw new \Exception('Clase Modelo: MovilizacionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: MovilizacionModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_movilizacion_vegetal
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
     * Identificador único del registro.
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
     * Set identificador
     *
     * Identificador del creador del registro (técnico AGR u operador por autoservicio)
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
     * Fecha de creación del registro
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
     * Set numeroPermiso
     *
     * Número del permiso de movilización que contiene:
     * -Código provincia sitio de origen
     * -Código provincia sitio destino
     * -Fecha emisión permiso movilización ddmmaa
     * -Código secuencial permiso (5 dígitos)
     *
     * @parámetro String $numeroPermiso
     * @return NumeroPermiso
     */
    public function setNumeroPermiso($numeroPermiso)
    {
        $this->numeroPermiso = (string) $numeroPermiso;
        return $this;
    }

    /**
     * Get numeroPermiso
     *
     * @return null|String
     */
    public function getNumeroPermiso()
    {
        return $this->numeroPermiso;
    }

    /**
     * Set tipoSolicitud
     *
     * Tipo de solicitud de movilización: Fitosanitario
     *
     * @parámetro String $tipoSolicitud
     * @return TipoSolicitud
     */
    public function setTipoSolicitud($tipoSolicitud)
    {
        $this->tipoSolicitud = (string) $tipoSolicitud;
        return $this;
    }

    /**
     * Get tipoSolicitud
     *
     * @return null|String
     */
    public function getTipoSolicitud()
    {
        return $this->tipoSolicitud;
    }

    /**
     * Set idProvinciaEmision
     *
     * Código de la provincia de emisión del permiso
     *
     * @parámetro Integer $idProvinciaEmision
     * @return IdProvinciaEmision
     */
    public function setIdProvinciaEmision($idProvinciaEmision)
    {
        $this->idProvinciaEmision = (integer) $idProvinciaEmision;
        return $this;
    }

    /**
     * Get idProvinciaEmision
     *
     * @return null|Integer
     */
    public function getIdProvinciaEmision()
    {
        return $this->idProvinciaEmision;
    }

    /**
     * Set provinciaEmision
     *
     * Nombre de la provincia de emisión del permiso
     *
     * @parámetro String $provinciaEmision
     * @return ProvinciaEmision
     */
    public function setProvinciaEmision($provinciaEmision)
    {
        $this->provinciaEmision = (string) $provinciaEmision;
        return $this;
    }

    /**
     * Get provinciaEmision
     *
     * @return null|String
     */
    public function getProvinciaEmision()
    {
        return $this->provinciaEmision;
    }

    /**
     * Set idCantonEmision
     *
     * Código del cantón de emisión del permiso
     *
     * @parámetro Integer $idCantonEmision
     * @return IdCantonEmision
     */
    public function setIdCantonEmision($idCantonEmision)
    {
        $this->idCantonEmision = (integer) $idCantonEmision;
        return $this;
    }

    /**
     * Get idCantonEmision
     *
     * @return null|Integer
     */
    public function getIdCantonEmision()
    {
        return $this->idCantonEmision;
    }

    /**
     * Set cantonEmision
     *
     * Nombre del cantón de emisión del permiso
     *
     * @parámetro String $cantonEmision
     * @return CantonEmision
     */
    public function setCantonEmision($cantonEmision)
    {
        $this->cantonEmision = (string) $cantonEmision;
        return $this;
    }

    /**
     * Get cantonEmision
     *
     * @return null|String
     */
    public function getCantonEmision()
    {
        return $this->cantonEmision;
    }

    /**
     * Set idOficinaEmision
     *
     * Código de la oficina de emisión del permiso
     *
     * @parámetro Integer $idOficinaEmision
     * @return IdOficinaEmision
     */
    public function setIdOficinaEmision($idOficinaEmision)
    {
        $this->idOficinaEmision = (integer) $idOficinaEmision;
        return $this;
    }

    /**
     * Get idOficinaEmision
     *
     * @return null|Integer
     */
    public function getIdOficinaEmision()
    {
        return $this->idOficinaEmision;
    }

    /**
     * Set oficinaEmision
     *
     * Nombre de la oficina de emisión del permiso
     *
     * @parámetro String $oficinaEmision
     * @return OficinaEmision
     */
    public function setOficinaEmision($oficinaEmision)
    {
        $this->oficinaEmision = (string) $oficinaEmision;
        return $this;
    }

    /**
     * Get oficinaEmision
     *
     * @return null|String
     */
    public function getOficinaEmision()
    {
        return $this->oficinaEmision;
    }

    /**
     * Set idProvinciaOrigen
     *
     * Código de la provincia de origen de la movilización
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
     * Nombre de la provincia de origen de la movilización
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
     * Set cantonOrigen
     *
     * Nombre del cantón de origen del permiso
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
     * Set cantonDestino
     *
     * Nombre del cantón de destino del permiso
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
     * Set parroquiaOrigen
     *
     * Nombre de la parroquia de origen del permiso
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
     * Set parroquiaDestino
     *
     * Nombre de la parroquia de destino del permiso
     *
     * @parámetro String $cantonDestino
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
     * Set identificadorOperadorOrigen
     *
     * Identificador del operador de origen
     *
     * @parámetro String $identificadorOperadorOrigen
     * @return IdentificadorOperadorOrigen
     */
    public function setIdentificadorOperadorOrigen($identificadorOperadorOrigen)
    {
        $this->identificadorOperadorOrigen = (string) $identificadorOperadorOrigen;
        return $this;
    }

    /**
     * Get identificadorOperadorOrigen
     *
     * @return null|String
     */
    public function getIdentificadorOperadorOrigen()
    {
        return $this->identificadorOperadorOrigen;
    }

    /**
     * Set nombreOperadorOrigen
     *
     * Nombre del operador de origen
     *
     * @parámetro String $nombreOperadorOrigen
     * @return NombreOperadorOrigen
     */
    public function setNombreOperadorOrigen($nombreOperadorOrigen)
    {
        $this->nombreOperadorOrigen = (string) $nombreOperadorOrigen;
        return $this;
    }

    /**
     * Get nombreOperadorOrigen
     *
     * @return null|String
     */
    public function getNombreOperadorOrigen()
    {
        return $this->nombreOperadorOrigen;
    }

    /**
     * Set idSitioOrigen
     *
     * Código del sitio de origen
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
     * Set sitioOrigen
     *
     * Nombre del sitio de origen
     *
     * @parámetro String $sitioOrigen
     * @return SitioOrigen
     */
    public function setSitioOrigen($sitioOrigen)
    {
        $this->sitioOrigen = (string) $sitioOrigen;
        return $this;
    }

    /**
     * Get sitioOrigen
     *
     * @return null|String
     */
    public function getSitioOrigen()
    {
        return $this->sitioOrigen;
    }

    /**
     * Set codigoSitioOrigen
     *
     * Código único de identificación del sitio
     *
     * @parámetro String $codigoSitioOrigen
     * @return CodigoSitioOrigen
     */
    public function setCodigoSitioOrigen($codigoSitioOrigen)
    {
        $this->codigoSitioOrigen = (string) $codigoSitioOrigen;
        return $this;
    }

    /**
     * Get codigoSitioOrigen
     *
     * @return null|String
     */
    public function getCodigoSitioOrigen()
    {
        return $this->codigoSitioOrigen;
    }

    /**
     * Set idProvinciaDestino
     *
     * Código de la provincia de destino de la movilización
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
     * Nombre de la provincia de destino de la movilización
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
     * Set identificadorOperadorDestino
     *
     * Identificador del operdor de destino
     *
     * @parámetro String $identificadorOperadorDestino
     * @return IdentificadorOperadorDestino
     */
    public function setIdentificadorOperadorDestino($identificadorOperadorDestino)
    {
        $this->identificadorOperadorDestino = (string) $identificadorOperadorDestino;
        return $this;
    }

    /**
     * Get identificadorOperadorDestino
     *
     * @return null|String
     */
    public function getIdentificadorOperadorDestino()
    {
        return $this->identificadorOperadorDestino;
    }

    /**
     * Set nombreOperadorDestino
     *
     * Nombre del operador de destino
     *
     * @parámetro String $nombreOperadorDestino
     * @return NombreOperadorDestino
     */
    public function setNombreOperadorDestino($nombreOperadorDestino)
    {
        $this->nombreOperadorDestino = (string) $nombreOperadorDestino;
        return $this;
    }

    /**
     * Get nombreOperadorDestino
     *
     * @return null|String
     */
    public function getNombreOperadorDestino()
    {
        return $this->nombreOperadorDestino;
    }

    /**
     * Set idSitioDestino
     *
     * Identificador del sitio de destino
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
     * Set sitioDestino
     *
     * Nombre del sitio de destino
     *
     * @parámetro String $sitioDestino
     * @return SitioDestino
     */
    public function setSitioDestino($sitioDestino)
    {
        $this->sitioDestino = (string) $sitioDestino;
        return $this;
    }

    /**
     * Get sitioDestino
     *
     * @return null|String
     */
    public function getSitioDestino()
    {
        return $this->sitioDestino;
    }

    /**
     * Set codigoSitioDestino
     *
     * Código único de identificación del sitio de destino
     *
     * @parámetro String $codigoSitioDestino
     * @return CodigoSitioDestino
     */
    public function setCodigoSitioDestino($codigoSitioDestino)
    {
        $this->codigoSitioDestino = (string) $codigoSitioDestino;
        return $this;
    }

    /**
     * Get codigoSitioDestino
     *
     * @return null|String
     */
    public function getCodigoSitioDestino()
    {
        return $this->codigoSitioDestino;
    }

    /**
     * Set medioTransporte
     *
     * Nombre del tipo de medio de transporte: Terrestre
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
     * Placa del vehículo usado en la movilización
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
     * Set identificadorConductor
     *
     * Identificador del conductor del vehículo
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
     * Nombre del conductor del vehículo
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
     * Fecha de inicio de la movilizacion
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
     * Set horaInicioMovilizacion
     *
     * Hora de inicio de la movilización
     *
     * @parámetro Date $horaInicioMovilizacion
     * @return HoraInicioMovilizacion
     */
    public function setHoraInicioMovilizacion($horaInicioMovilizacion)
    {
        $this->horaInicioMovilizacion = (string) $horaInicioMovilizacion;
        return $this;
    }

    /**
     * Get horaInicioMovilizacion
     *
     * @return null|Date
     */
    public function getHoraInicioMovilizacion()
    {
        return $this->horaInicioMovilizacion;
    }

    /**
     * Set observacionTransporte
     *
     * Observaciones del medio de transporte
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
     * Ruta del archivo del certificado de movilización
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
     * Estado de la movilización:
     * -Vigente
     * -Caducado
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
     * Estado de la última fiscalización de la movilización:
     * -Fiscalizado
     * -No fiscalizado
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
     * Fecha de finalización de la movilizacion
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
     * Set horaFinMovilizacion
     *
     * Hora de finalización de la movilización
     *
     * @parámetro Date $horaFinMovilizacion
     * @return HoraFinMovilizacion
     */
    public function setHoraFinMovilizacion($horaFinMovilizacion)
    {
        $this->horaFinMovilizacion = (string) $horaFinMovilizacion;
        return $this;
    }

    /**
     * Get horaFinMovilizacion
     *
     * @return null|Date
     */
    public function getHoraFinMovilizacion()
    {
        return $this->horaFinMovilizacion;
    }

    /**
     * Set secuencialMovilizacion
     *
     * Valor secuencial para el número de permiso de movilización
     *
     * @parámetro String $secuencial
     * @return secuencial
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
	* Set idAreaOrigen
	*
	*Código del área de origen
	*
	* @parámetro Integer $idAreaOrigen
	* @return IdAreaOrigen
	*/
	public function setIdAreaOrigen($idAreaOrigen)
	{
	  $this->idAreaOrigen = (Integer) $idAreaOrigen;
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
	* Set areaOrigen
	*
	*Nombre del área de origen
	*
	* @parámetro String $areaOrigen
	* @return AreaOrigen
	*/
	public function setAreaOrigen($areaOrigen)
	{
	  $this->areaOrigen = (String) $areaOrigen;
	    return $this;
	}

	/**
	* Get areaOrigen
	*
	* @return null|String
	*/
	public function getAreaOrigen()
	{
		return $this->areaOrigen;
	}

	/**
	* Set codigoAreaOrigen
	*
	*Código único de identificación del área de origen
	*
	* @parámetro String $codigoAreaOrigen
	* @return CodigoAreaOrigen
	*/
	public function setCodigoAreaOrigen($codigoAreaOrigen)
	{
	  $this->codigoAreaOrigen = (String) $codigoAreaOrigen;
	    return $this;
	}

	/**
	* Get codigoAreaOrigen
	*
	* @return null|String
	*/
	public function getCodigoAreaOrigen()
	{
		return $this->codigoAreaOrigen;
	}

	/**
	* Set idAreaDestino
	*
	*Código del área de destino
	*
	* @parámetro Integer $idAreaDestino
	* @return IdAreaDestino
	*/
	public function setIdAreaDestino($idAreaDestino)
	{
	  $this->idAreaDestino = (Integer) $idAreaDestino;
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
	* Set areaDestino
	*
	*Nombre del área de destino
	*
	* @parámetro String $areaDestino
	* @return AreaDestino
	*/
	public function setAreaDestino($areaDestino)
	{
	  $this->areaDestino = (String) $areaDestino;
	    return $this;
	}

	/**
	* Get areaDestino
	*
	* @return null|String
	*/
	public function getAreaDestino()
	{
		return $this->areaDestino;
	}

	/**
	* Set codigoAreaDestino
	*
	*Código único de identificación del área de destino
	*
	* @parámetro String $codigoAreaDestino
	* @return CodigoAreaDestino
	*/
	public function setCodigoAreaDestino($codigoAreaDestino)
	{
	  $this->codigoAreaDestino = (String) $codigoAreaDestino;
	    return $this;
	}

	/**
	* Get codigoAreaDestino
	*
	* @return null|String
	*/
	public function getCodigoAreaDestino()
	{
		return $this->codigoAreaDestino;
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
     * @return MovilizacionModelo
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
