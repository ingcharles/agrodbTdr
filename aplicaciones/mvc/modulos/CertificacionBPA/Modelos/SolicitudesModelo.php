<?php
/**
 * Modelo SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    SolicitudesModelo
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
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
     *      Campo que identifica si la solicitud la realiza un operador de manera individual o es una asociación:
     *      - Si
     *      - No
     */
    protected $esAsociacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Detalle del tipo de solicitud:
     *      - Nacional
     *      - Equivalente
     */
    protected $tipoSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código del tipo de explotación que se realizará en la solicitud, es decir el área al que pertencen los productos:
     *      - SA Sanidad Animal
     *      - SV Sanidad Vegetal
     *      - IA Inocuidad de los Alimentos
     */
    protected $tipoExplotacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de animales registrados para las categorías de porcinos, vacas, aves, cuyes
     */
    protected $numAnimales;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operador, cédula o RUC del registro
     */
    protected $identificadorOperador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Razón social del operador
     */
    protected $razonSocial;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del representante legal
     */
    protected $identificadorRepresentanteLegal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombres completos del representante legal declarado
     */
    protected $nombreRepresentanteLegal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Correo electrónico del operador
     */
    protected $correo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Teléfono del contacto del operador
     */
    protected $telefono;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Dirección del operador
     */
    protected $direccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del representante técnico del operador
     */
    protected $identificadorRepresentanteTecnico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del representante técnico registrado
     */
    protected $nombreRepresentanteTecnico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Correo de contacto del representante técnico
     */
    protected $correoRepresentanteTecnico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Teléfono de contacto del representante técnico
     */
    protected $telefonoRepresentanteTecnico;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del sitio designado como unidad de producción
     */
    protected $idSitioUnidadProduccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del sitio designado como unidad de producción
     */
    protected $sitioUnidadProduccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia de la unidad de producción
     */
    protected $provinciaUnidadProduccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón de la unidad de producción
     */
    protected $cantonUnidadProduccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la parroquia de la unidad de producción
     */
    protected $parroquiaUnidadProduccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Dirección de la unidad de producción
     */
    protected $direccionUnidadProduccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Coordinadas UTM X de la unidad de producción
     */
    protected $utmX;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Coordinadas UTM Y de la unidad de producción
     */
    protected $utmY;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Zona de la unidad de producción
     */
    protected $altitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo del certificado solicitado:
     *      - Nacional
     *      - Global Gap
     *      - Flor Ecuador
     */
    protected $tipoCertificado;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de trabajadores registrados en el alcance
     */
    protected $numTrabajadores;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de certificado externo que se va a homologar
     */
    protected $codigoEquivalente;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de inicio de vigencia del certificado equivalente
     */
    protected $fechaInicioEquivalente;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de finalización de vigencia del certificado equivalente
     */
    protected $fechaFinEquivalente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones de la sección de alcance de la solicitud
     */
    protected $observacionAlcance;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del certificado equivalente cargado por el usuario
     */
    protected $rutaCertificadoEquivalente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Total de hectáreas que serán certificadas en la solicitud
     */
    protected $numHectareas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la solicitud:
     *      - enviado
     *      - RevisionDocumental
     *      - inspeccion
     *      - pago (módulo financiero)
     *      - RevisionCoordinador
     *      - Aprobado
     *      - Rechazado
     *      - Expirado
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del archivo con el formato del plan de acción remitido por el técnico
     */
    protected $rutaFormatoPlanAccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del archivo del plan de acción enviado por el usuario
     */
    protected $rutaPlanAccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Puntaje obtenido en la auditoría llevada a cabo en el proceso de inspección.
     *      - 100% aprobado
     *      - entre 75% y 99% subsanación pero se aprueba con plan de acción
     *      - < 75% rechazado
     */
    protected $porcentajeAuditoria;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de la auditoría planificada en la fase de Revisión Documental
     */
    protected $fechaAuditoriaProgramada;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Bandera que indica si la solicitud pasó por pago
     */
    protected $pasoPago;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones remitidas por los técnicos en las etapas de revisión
     */
    protected $observacionRevision;
    
    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la resolución con la que se realiza la aprobación de la solicitud
     */
    protected $idResolucion;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del certificado BPA generado por la solicitud
     */
    protected $rutaCertificado;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número del certificado emitido
     */
    protected $numeroCertificado;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de las auditorías posteriores planteadas por el técnico de revisión documental
     */
    protected $fechaAuditoriaComplementaria;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número secuencial nacional por tipo de solicitud
     */
    protected $numeroSecuencial;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se realizará la revisión de la solicitud
     */
    protected $provinciaRevision;
    
    /**
     *
     * @var String Campo requerido
     *      Ruta del archivo del checklist enviado por el técnico
     */
    protected $rutaChecklist;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de la última revisión realizada
     */
    protected $fechaRevision;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la fase de revisión por la que paso el documento:
     *      - Documental
     *      - Revision
     *      - Aprobacion
     */
    protected $tipoRevision;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en que se ejecutó la auditoría programada por el técnico
     */
    protected $fechaAuditoria;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha máxima hasta la que el usuario podrá realizar la subsanación de la solicitud requerida por el técnico
     */
    protected $fechaMaxRespuesta;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta a documento anexo para solicitudes nacionales
     */
    protected $anexoNacional;
    
    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_certificacion_bpa";

    /**
     * Nombre de la tabla: solicitudes
     */
    private $tabla = "solicitudes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_solicitud";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificacion_bpa"."solicitudes_id_solicitud_seq';

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
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificacion_bpa
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSolicitud
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = (integer) $idSolicitud;
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
    }

    /**
     * Set identificador
     *
     * Identificador del usuario que crea el registro
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
     * Set esAsociacion
     *
     * Campo que identifica si la solicitud la realiza un operador de manera individual o es una asociación:
     * - Si
     * - No
     *
     * @parámetro String $esAsociacion
     * @return EsAsociacion
     */
    public function setEsAsociacion($esAsociacion)
    {
        $this->esAsociacion = (string) $esAsociacion;
        return $this;
    }

    /**
     * Get esAsociacion
     *
     * @return null|String
     */
    public function getEsAsociacion()
    {
        return $this->esAsociacion;
    }

    /**
     * Set tipoSolicitud
     *
     * Detalle del tipo de solicitud:
     * - Nacional
     * - Equivalente
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
     * Set tipoExplotacion
     *
     * Código del tipo de explotación que se realizará en la solicitud, es decir el área al que pertencen los productos:
     * - SA Sanidad Animal
     * - SV Sanidad Vegetal
     * - IA Inocuidad de los Alimentos
     *
     * @parámetro String $tipoExplotacion
     * @return TipoExplotacion
     */
    public function setTipoExplotacion($tipoExplotacion)
    {
        $this->tipoExplotacion = (string) $tipoExplotacion;
        return $this;
    }

    /**
     * Get tipoExplotacion
     *
     * @return null|String
     */
    public function getTipoExplotacion()
    {
        return $this->tipoExplotacion;
    }

    /**
     * Set numAnimales
     *
     * Número de animales registrados para las categorías de porcinos, vacas, aves, cuyes
     *
     * @parámetro Integer $numAnimales
     * @return NumAnimales
     */
    public function setNumAnimales($numAnimales)
    {
        $this->numAnimales = (integer) $numAnimales;
        return $this;
    }

    /**
     * Get numAnimales
     *
     * @return null|Integer
     */
    public function getNumAnimales()
    {
        return $this->numAnimales;
    }

    /**
     * Set identificadorOperador
     *
     * Identificador del operador, cédula o RUC del registro
     *
     * @parámetro String $identificadorOperador
     * @return IdentificadorOperador
     */
    public function setIdentificadorOperador($identificadorOperador)
    {
        $this->identificadorOperador = (string) $identificadorOperador;
        return $this;
    }

    /**
     * Get identificadorOperador
     *
     * @return null|String
     */
    public function getIdentificadorOperador()
    {
        return $this->identificadorOperador;
    }

    /**
     * Set razonSocial
     *
     * Razón social del operador
     *
     * @parámetro String $razonSocial
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (string) $razonSocial;
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return null|String
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set identificadorRepresentanteLegal
     *
     * Identificador del representante legal
     *
     * @parámetro String $identificadorRepresentanteLegal
     * @return IdentificadorRepresentanteLegal
     */
    public function setIdentificadorRepresentanteLegal($identificadorRepresentanteLegal)
    {
        $this->identificadorRepresentanteLegal = (string) $identificadorRepresentanteLegal;
        return $this;
    }

    /**
     * Get identificadorRepresentanteLegal
     *
     * @return null|String
     */
    public function getIdentificadorRepresentanteLegal()
    {
        return $this->identificadorRepresentanteLegal;
    }

    /**
     * Set nombreRepresentanteLegal
     *
     * Nombres completos del representante legal declarado
     *
     * @parámetro String $nombreRepresentanteLegal
     * @return NombreRepresentanteLegal
     */
    public function setNombreRepresentanteLegal($nombreRepresentanteLegal)
    {
        $this->nombreRepresentanteLegal = (string) $nombreRepresentanteLegal;
        return $this;
    }

    /**
     * Get nombreRepresentanteLegal
     *
     * @return null|String
     */
    public function getNombreRepresentanteLegal()
    {
        return $this->nombreRepresentanteLegal;
    }

    /**
     * Set correo
     *
     * Correo electrónico del operador
     *
     * @parámetro String $correo
     * @return Correo
     */
    public function setCorreo($correo)
    {
        $this->correo = (string) $correo;
        return $this;
    }

    /**
     * Get correo
     *
     * @return null|String
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set telefono
     *
     * Teléfono del contacto del operador
     *
     * @parámetro String $telefono
     * @return Telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = (string) $telefono;
        return $this;
    }

    /**
     * Get telefono
     *
     * @return null|String
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set direccion
     *
     * Dirección del operador
     *
     * @parámetro String $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = (string) $direccion;
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|String
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set identificadorRepresentanteTecnico
     *
     * Identificador del representante técnico del operador
     *
     * @parámetro String $identificadorRepresentanteTecnico
     * @return IdentificadorRepresentanteTecnico
     */
    public function setIdentificadorRepresentanteTecnico($identificadorRepresentanteTecnico)
    {
        $this->identificadorRepresentanteTecnico = (string) $identificadorRepresentanteTecnico;
        return $this;
    }

    /**
     * Get identificadorRepresentanteTecnico
     *
     * @return null|String
     */
    public function getIdentificadorRepresentanteTecnico()
    {
        return $this->identificadorRepresentanteTecnico;
    }

    /**
     * Set nombreRepresentanteTecnico
     *
     * Nombre del representante técnico registrado
     *
     * @parámetro String $nombreRepresentanteTecnico
     * @return NombreRepresentanteTecnico
     */
    public function setNombreRepresentanteTecnico($nombreRepresentanteTecnico)
    {
        $this->nombreRepresentanteTecnico = (string) $nombreRepresentanteTecnico;
        return $this;
    }

    /**
     * Get nombreRepresentanteTecnico
     *
     * @return null|String
     */
    public function getNombreRepresentanteTecnico()
    {
        return $this->nombreRepresentanteTecnico;
    }

    /**
     * Set correoRepresentanteTecnico
     *
     * Correo de contacto del representante técnico
     *
     * @parámetro String $correoRepresentanteTecnico
     * @return CorreoRepresentanteTecnico
     */
    public function setCorreoRepresentanteTecnico($correoRepresentanteTecnico)
    {
        $this->correoRepresentanteTecnico = (string) $correoRepresentanteTecnico;
        return $this;
    }

    /**
     * Get correoRepresentanteTecnico
     *
     * @return null|String
     */
    public function getCorreoRepresentanteTecnico()
    {
        return $this->correoRepresentanteTecnico;
    }

    /**
     * Set telefonoRepresentanteTecnico
     *
     * Teléfono de contacto del representante técnico
     *
     * @parámetro String $telefonoRepresentanteTecnico
     * @return TelefonoRepresentanteTecnico
     */
    public function setTelefonoRepresentanteTecnico($telefonoRepresentanteTecnico)
    {
        $this->telefonoRepresentanteTecnico = (string) $telefonoRepresentanteTecnico;
        return $this;
    }

    /**
     * Get telefonoRepresentanteTecnico
     *
     * @return null|String
     */
    public function getTelefonoRepresentanteTecnico()
    {
        return $this->telefonoRepresentanteTecnico;
    }

    /**
     * Set idSitioUnidadProduccion
     *
     * Identificador del sitio designado como unidad de producción
     *
     * @parámetro Integer $idSitioUnidadProduccion
     * @return IdSitioUnidadProduccion
     */
    public function setIdSitioUnidadProduccion($idSitioUnidadProduccion)
    {
        $this->idSitioUnidadProduccion = (integer) $idSitioUnidadProduccion;
        return $this;
    }

    /**
     * Get idSitioUnidadProduccion
     *
     * @return null|Integer
     */
    public function getIdSitioUnidadProduccion()
    {
        return $this->idSitioUnidadProduccion;
    }

    /**
     * Set sitioUnidadProduccion
     *
     * Nombre del sitio designado como unidad de producción
     *
     * @parámetro String $sitioUnidadProduccion
     * @return SitioUnidadProduccion
     */
    public function setSitioUnidadProduccion($sitioUnidadProduccion)
    {
        $this->sitioUnidadProduccion = (string) $sitioUnidadProduccion;
        return $this;
    }

    /**
     * Get sitioUnidadProduccion
     *
     * @return null|String
     */
    public function getSitioUnidadProduccion()
    {
        return $this->sitioUnidadProduccion;
    }

    /**
     * Set provinciaUnidadProduccion
     *
     * Nombre de la provincia de la unidad de producción
     *
     * @parámetro String $provinciaUnidadProduccion
     * @return ProvinciaUnidadProduccion
     */
    public function setProvinciaUnidadProduccion($provinciaUnidadProduccion)
    {
        $this->provinciaUnidadProduccion = (string) $provinciaUnidadProduccion;
        return $this;
    }

    /**
     * Get provinciaUnidadProduccion
     *
     * @return null|String
     */
    public function getProvinciaUnidadProduccion()
    {
        return $this->provinciaUnidadProduccion;
    }

    /**
     * Set cantonUnidadProduccion
     *
     * Nombre del cantón de la unidad de producción
     *
     * @parámetro String $cantonUnidadProduccion
     * @return CantonUnidadProduccion
     */
    public function setCantonUnidadProduccion($cantonUnidadProduccion)
    {
        $this->cantonUnidadProduccion = (string) $cantonUnidadProduccion;
        return $this;
    }

    /**
     * Get cantonUnidadProduccion
     *
     * @return null|String
     */
    public function getCantonUnidadProduccion()
    {
        return $this->cantonUnidadProduccion;
    }

    /**
     * Set parroquiaUnidadProduccion
     *
     * Nombre de la parroquia de la unidad de producción
     *
     * @parámetro String $parroquiaUnidadProduccion
     * @return ParroquiaUnidadProduccion
     */
    public function setParroquiaUnidadProduccion($parroquiaUnidadProduccion)
    {
        $this->parroquiaUnidadProduccion = (string) $parroquiaUnidadProduccion;
        return $this;
    }

    /**
     * Get parroquiaUnidadProduccion
     *
     * @return null|String
     */
    public function getParroquiaUnidadProduccion()
    {
        return $this->parroquiaUnidadProduccion;
    }

    /**
     * Set direccionUnidadProduccion
     *
     * Dirección de la unidad de producción
     *
     * @parámetro String $direccionUnidadProduccion
     * @return DireccionUnidadProduccion
     */
    public function setDireccionUnidadProduccion($direccionUnidadProduccion)
    {
        $this->direccionUnidadProduccion = (string) $direccionUnidadProduccion;
        return $this;
    }

    /**
     * Get direccionUnidadProduccion
     *
     * @return null|String
     */
    public function getDireccionUnidadProduccion()
    {
        return $this->direccionUnidadProduccion;
    }

    /**
     * Set utmX
     *
     * Coordinadas UTM X de la unidad de producción
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
     * Coordinadas UTM Y de la unidad de producción
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
     * Set altitud
     *
     * Zona de la unidad de producción
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
     * Set tipoCertificado
     *
     * Tipo del certificado solicitado:
     * - Nacional
     * - Global Gap
     * - Flor Ecuador
     *
     * @parámetro String $tipoCertificado
     * @return TipoCertificado
     */
    public function setTipoCertificado($tipoCertificado)
    {
        $this->tipoCertificado = (string) $tipoCertificado;
        return $this;
    }

    /**
     * Get tipoCertificado
     *
     * @return null|String
     */
    public function getTipoCertificado()
    {
        return $this->tipoCertificado;
    }

    /**
     * Set numTrabajadores
     *
     * Número de trabajadores registrados en el alcance
     *
     * @parámetro Integer $numTrabajadores
     * @return NumTrabajadores
     */
    public function setNumTrabajadores($numTrabajadores)
    {
        $this->numTrabajadores = (integer) $numTrabajadores;
        return $this;
    }

    /**
     * Get numTrabajadores
     *
     * @return null|Integer
     */
    public function getNumTrabajadores()
    {
        return $this->numTrabajadores;
    }

    /**
     * Set codigoEquivalente
     *
     * Número de certificado externo que se va a homologar
     *
     * @parámetro String $codigoEquivalente
     * @return CodigoEquivalente
     */
    public function setCodigoEquivalente($codigoEquivalente)
    {
        $this->codigoEquivalente = (string) $codigoEquivalente;
        return $this;
    }

    /**
     * Get codigoEquivalente
     *
     * @return null|String
     */
    public function getCodigoEquivalente()
    {
        return $this->codigoEquivalente;
    }

    /**
     * Set fechaInicioEquivalente
     *
     * Fecha de inicio de vigencia del certificado equivalente
     *
     * @parámetro Date $fechaInicioEquivalente
     * @return FechaInicioEquivalente
     */
    public function setFechaInicioEquivalente($fechaInicioEquivalente)
    {
        $this->fechaInicioEquivalente = (string) $fechaInicioEquivalente;
        return $this;
    }

    /**
     * Get fechaInicioEquivalente
     *
     * @return null|Date
     */
    public function getFechaInicioEquivalente()
    {
        return $this->fechaInicioEquivalente;
    }

    /**
     * Set fechaFinEquivalente
     *
     * Fecha de finalización de vigencia del certificado equivalente
     *
     * @parámetro Date $fechaFinEquivalente
     * @return FechaFinEquivalente
     */
    public function setFechaFinEquivalente($fechaFinEquivalente)
    {
        $this->fechaFinEquivalente = (string) $fechaFinEquivalente;
        return $this;
    }

    /**
     * Get fechaFinEquivalente
     *
     * @return null|Date
     */
    public function getFechaFinEquivalente()
    {
        return $this->fechaFinEquivalente;
    }

    /**
     * Set observacionAlcance
     *
     * Observaciones de la sección de alcance de la solicitud
     *
     * @parámetro String $observacionAlcance
     * @return ObservacionAlcance
     */
    public function setObservacionAlcance($observacionAlcance)
    {
        $this->observacionAlcance = (string) $observacionAlcance;
        return $this;
    }

    /**
     * Get observacionAlcance
     *
     * @return null|String
     */
    public function getObservacionAlcance()
    {
        return $this->observacionAlcance;
    }

    /**
     * Set rutaCertificadoEquivalente
     *
     * Ruta del certificado equivalente cargado por el usuario
     *
     * @parámetro String $rutaCertificadoEquivalente
     * @return RutaCertificadoEquivalente
     */
    public function setRutaCertificadoEquivalente($rutaCertificadoEquivalente)
    {
        $this->rutaCertificadoEquivalente = (string) $rutaCertificadoEquivalente;
        return $this;
    }

    /**
     * Get rutaCertificadoEquivalente
     *
     * @return null|String
     */
    public function getRutaCertificadoEquivalente()
    {
        return $this->rutaCertificadoEquivalente;
    }

    /**
     * Set numHectareas
     *
     * Total de hectáreas que serán certificadas en la solicitud
     *
     * @parámetro String $numHectareas
     * @return NumHectareas
     */
    public function setNumHectareas($numHectareas)
    {
        $this->numHectareas = (string) $numHectareas;
        return $this;
    }

    /**
     * Get numHectareas
     *
     * @return null|String
     */
    public function getNumHectareas()
    {
        return $this->numHectareas;
    }

    /**
     * Set estado
     *
     * Estado de la solicitud:
     * - enviado
     * - RevisionDocumental
     * - inspeccion
     * - pago (módulo financiero)
     * - RevisionCoordinador
     * - Aprobado
     * - Rechazado
     * - Expirado
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
     * Set rutaFormatoPlanAccion
     *
     * Ruta del archivo con el formato del plan de acción remitido por el técnico
     *
     * @parámetro String $rutaFormatoPlanAccion
     * @return RutaFormatoPlanAccion
     */
    public function setRutaFormatoPlanAccion($rutaFormatoPlanAccion)
    {
        $this->rutaFormatoPlanAccion = (string) $rutaFormatoPlanAccion;
        return $this;
    }

    /**
     * Get rutaFormatoPlanAccion
     *
     * @return null|String
     */
    public function getRutaFormatoPlanAccion()
    {
        return $this->rutaFormatoPlanAccion;
    }

    /**
     * Set rutaPlanAccion
     *
     * Ruta del archivo del plan de acción enviado por el usuario
     *
     * @parámetro String $rutaPlanAccion
     * @return RutaPlanAccion
     */
    public function setRutaPlanAccion($rutaPlanAccion)
    {
        $this->rutaPlanAccion = (string) $rutaPlanAccion;
        return $this;
    }

    /**
     * Get rutaPlanAccion
     *
     * @return null|String
     */
    public function getRutaPlanAccion()
    {
        return $this->rutaPlanAccion;
    }

    /**
     * Set porcentajeAuditoria
     *
     * Puntaje obtenido en la auditoría llevada a cabo en el proceso de inspección.
     * - 100% aprobado
     * - entre 75% y 99% subsanación pero se aprueba con plan de acción
     * - < 75% rechazado
     *
     * @parámetro String $porcentajeAuditoria
     * @return PorcentajeAuditoria
     */
    public function setPorcentajeAuditoria($porcentajeAuditoria)
    {
        $this->porcentajeAuditoria = (string) $porcentajeAuditoria;
        return $this;
    }

    /**
     * Get porcentajeAuditoria
     *
     * @return null|String
     */
    public function getPorcentajeAuditoria()
    {
        return $this->porcentajeAuditoria;
    }
    
    /**
     * Set fechaAuditoriaProgramada
     *
     * Fecha de la auditoría planificada en la fase de Revisión Documental
     *
     * @parámetro Date $fechaAuditoriaProgramada
     * @return FechaAuditoriaProgramada
     */
    public function setFechaAuditoriaProgramada($fechaAuditoriaProgramada)
    {
        $this->fechaAuditoriaProgramada = (string) $fechaAuditoriaProgramada;
        return $this;
    }
    
    /**
     * Get fechaAuditoriaProgramada
     *
     * @return null|String
     */
    public function getFechaAuditoriaProgramada()
    {
        return $this->fechaAuditoriaProgramada;
    }
    
    /**
     * Set pasoPago
     *
     * Bandera que indica si la solicitud pasó por pago
     *
     * @parámetro String $fechaCreacion
     * @return FechaCreacion
     */
    public function setPasoPago($pasoPago)
    {
        $this->pasoPago = (string) $pasoPago;
        return $this;
    }
    
    /**
     * Get pasoPago
     *
     * @return null|Date
     */
    public function getPasoPago()
    {
        return $this->pasoPago;
    }
    
    /**
     * Set observacionAlcance
     *
     * Observaciones de la sección de alcance de la solicitud
     *
     * @parámetro String $observacionRevision
     * @return ObservacionAlcance
     */
    public function setObservacionRevision($observacionRevision)
    {
        $this->observacionRevision = (string) $observacionRevision;
        return $this;
    }

    /**
     * Get observacionRevision
     *
     * @return null|String
     */
    public function getObservacionRevision()
    {
        return $this->observacionRevision;
    }
    
    /**
     * Set idResolucion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idResolucion
     * @return IdResolucion
     */
    public function setIdResolucion($idResolucion)
    {
        $this->idResolucion = (integer) $idResolucion;
        return $this;
    }

    /**
     * Get idResolucion
     *
     * @return null|Integer
     */
    public function getIdResolucion()
    {
        return $this->idResolucion;
    }
    
    /**
     * Set rutaCertificado
     *
     * Ruta del certificado BPA generado por la solicitud
     *
     * @parámetro String $rutaCertificado
     * @return RutaCertificado
     */
    public function setRutaCertificado($rutaCertificado)
    {
        $this->rutaCertificado = (String) $rutaCertificado;
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
     * Set numeroCertificado
     *
     * Número del certificado emitido
     *
     * @parámetro String $numeroCertificado
     * @return NumeroCertificado
     */
    public function setNumeroCertificado($numeroCertificado)
    {
        $this->numeroCertificado = (String) $numeroCertificado;
        return $this;
    }

    /**
     * Get numeroCertificado
     *
     * @return null|String
     */
    public function getNumeroCertificado()
    {
        return $this->numeroCertificado;
    }    
    
    /**
     * Set fechaAuditoriaComplementaria
     *
     * FFecha de las auditorías posteriores planteadas por el técnico de revisión documental
     *
     * @parámetro Date $fechaAuditoriaComplementaria
     * @return FechaAuditoriaComplementaria
     */
    public function setFechaAuditoriaComplementaria($fechaAuditoriaComplementaria)
    {
        $this->fechaAuditoriaComplementaria = (string) $fechaAuditoriaComplementaria;
        return $this;
    }
    
    /**
     * Get fechaAuditoriaComplementaria
     *
     * @return null|String
     */
    public function getFechaAuditoriaComplementaria()
    {
        return $this->fechaAuditoriaComplementaria;
    }
    
    /**
     * Set numeroSecuencial
     *
     * Número secuencial nacional por tipo de solicitud
     *
     * @parámetro String $numeroSecuencial
     * @return NumeroSecuencial
     */
    public function setNumeroSecuencial($numeroSecuencial)
    {
        $this->numeroSecuencial = (String) $numeroSecuencial;
        return $this;
    }
    
    /**
     * Get numeroSecuencial
     *
     * @return null|String
     */
    public function getNumeroSecuencial()
    {
        return $this->numeroSecuencial;
    }    
    
    /**
     * Set provinciaRevision
     *
     * Número secuencial nacional por tipo de solicitud
     *
     * @parámetro String $provinciaRevision
     * @return ProvinciaRevision
     */
    public function setProvinciaRevision($provinciaRevision)
    {
        $this->provinciaRevision = (String) $provinciaRevision;
        return $this;
    }
    
    /**
     * Get provinciaRevision
     *
     * @return null|String
     */
    public function getProvinciaRevision()
    {
        return $this->provinciaRevision;
    } 
    
    /**
     * Set rutaChecklist
     *
     * Ruta del archivo del checklist enviado por el técnico
     *
     * @parámetro String $rutaChecklist
     * @return RutaChecklist
     */
    public function setRutaChecklist($rutaChecklist)
    {
        $this->rutaChecklist = (string) $rutaChecklist;
        return $this;
    }
    
    /**
     * Get rutaChecklist
     *
     * @return null|String
     */
    public function getRutaChecklist()
    {
        return $this->rutaChecklist;
    }
    
    /**
     * Set fechaRevision
     *
     * Fecha de la última revisión realizada
     *
     * @parámetro Date $fechaRevision
     * @return FechaRevision
     */
    public function setFechaRevision($fechaRevision)
    {
        $this->fechaRevision = (string) $fechaRevision;
        return $this;
    }
    
    /**
     * Get fechaRevision
     *
     * @return null|Date
     */
    public function getFechaRevision()
    {
        return $this->fechaRevision;
    }    
    
    /**
     * Set tipoRevision
     *
     * Nombre de la fase de revisión por la que paso el documento:
     * - Documental
     * - Inspeccion
     * - Aprobacion
     *
     * @parámetro String $tipoRevision
     * @return TipoRevision
     */
    public function setTipoRevision($tipoRevision)
    {
        $this->tipoRevision = (string) $tipoRevision;
        return $this;
    }
    
    /**
     * Get tipoRevision
     *
     * @return null|String
     */
    public function getTipoRevision()
    {
        return $this->tipoRevision;
    }
    
    /**
     * Set fechaAuditoria
     *
     * Fecha en que se ejecutó la auditoría programada por el técnico
     *
     * @parámetro Date $fechaAuditoria
     * @return FechaAuditoria
     */
    public function setFechaAuditoria($fechaAuditoria)
    {
        $this->fechaAuditoria = (string) $fechaAuditoria;
        return $this;
    }
    
    /**
     * Get fechaAuditoria
     *
     * @return null|Date
     */
    public function getFechaAuditoria()
    {
        return $this->fechaAuditoria;
    }    
    
    /**
    * Set fechaMaxRespuesta
    *
    * Fecha máxima hasta la que el usuario podrá realizar la subsanación de la solicitud requerida por el técnico
    *
    * @parámetro Date $fechaMaxRespuesta
    * @return FechaMaxRespuesta
    */
    public function setFechaMaxRespuesta($fechaMaxRespuesta)
    {
        $this->fechaMaxRespuesta = (string) $fechaMaxRespuesta;
        return $this;
    }
    
    /**
     * Get fechaMaxRespuesta
     *
     * @return null|Date
     */
    public function getFechaMaxRespuesta()
    {
        return $this->fechaMaxRespuesta;
    }  
    
    /**
     * Set anexoNacional
     *
     * Ruta a documento anexo para solicitudes nacionales
     *
     * @parámetro String $anexoNacional
     * @return AnexoNacional
     */
    public function setAnexoNacional($anexoNacional)
    {
        $this->anexoNacional = (string) $anexoNacional;
        return $this;
    }
    
    /**
     * Get anexoNacional
     *
     * @return null|String
     */
    public function getAnexoNacional()
    {
        return $this->anexoNacional;
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
     * @return SolicitudesModelo
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
