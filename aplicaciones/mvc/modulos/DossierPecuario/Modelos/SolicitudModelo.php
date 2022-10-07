<?php
/**
 * Modelo SolicitudModelo
 *
 * Este archivo se complementa con el archivo   SolicitudLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    SolicitudModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudModelo extends ModeloBase
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
     *      Código de la solicitud:
     *      RIP-AÑO-SECUENCIAL cinco dígitos
     */
    protected $idExpediente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único para identificación de un producto veterinario registrado una vez se aprueba la solicitud
     */
    protected $codigoProductoFinal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número secuencial para la generación del número de expediente
     */
    protected $secuencia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
     */
    protected $identificador;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia del operador
     */
    protected $idProvinciaOperador;

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
     *      Tipo de solicitud requerida por el usuario:
     *      - Registro
     *      - Modificación
     *      - Reevaluación
     */
    protected $tipoSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Indica si en la solicitud se requiere un cambio del titular del producto aprobado
     */
    protected $cambioTitular;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del Grupo de Producto
     */
    protected $idGrupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del grupo de producto
     */
    protected $grupoProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del subtipo de producto
     */
    protected $idSubtipoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Codificación del subtipo de producto seleccionado
     */
    protected $codificacionSubtipoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto veterinario a registrar
     */
    protected $nombreProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la clasificación del producto
     */
    protected $idClasificacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de la línea biológica del producto
     */
    protected $lineaBiologica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el pH del producto
     */
    protected $ph;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la viscosis del producto
     */
    protected $viscosidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la densidad del producto
     */
    protected $densidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del método biológico del producto
     */
    protected $metodoBiologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitira ingresar la información del método microbiológico del producto
     */
    protected $metodoMicrobiologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del control de inocuidad del producto
     */
    protected $controlInocuidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de agente etiológico susceptible (aplica para antisépticos, desinfectantes, sanitizantes y paguicidas)
     */
    protected $agenteEtiologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del método de fabricación del producto pecuario
     */
    protected $metodoFabricacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar las característcas físicas, químicas y organolépticas del producto pecuario
     */
    protected $caracteristicasFisQuimOrg;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Características físicas y químicas del producto
     */
    protected $caracteristicasFisQuim;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de las pruebas biológicas del producto
     */
    protected $pruebasBiologicas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de las pruebas microbiológicas del producto
     */
    protected $pruebasMicrobiologicas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del método bromatológico del producto pecuario
     */
    protected $metodoBromatologico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del método físico, químico del producto pecuario
     */
    protected $metodoFisQuim;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el esquema de vacunación
     */
    protected $esquemaVacunacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del tiempo necesario para conferir inmunidad
     */
    protected $tiempoInmunidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del tiempo mínimo de duración de la inmunidad
     */
    protected $tiempoMinimoInmunidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Bandera para informar si el producto requiere de preparación
     */
    protected $requierePreparacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la explicación de la preparación del producto
     */
    protected $detallePreparacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el tiempo de duración máxima del producto
     */
    protected $duracionMaxima;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de tiempo de duración máxima del producto
     */
    protected $idTiempoDuracionMaxima;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar el símbolo de la unidad de tiempo seleccionada
     */
    protected $nombreUnidadTiempoDuracion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de la duración máxima del producto después de la reconstitución
     */
    protected $duracionMaximaReconstitucion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la inforamción de almacenamiento del producto una vez abierto
     */
    protected $condicionesAlmacenamientoAbierto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la informaciónd e farmacocinética del producto
     */
    protected $farmacocinetica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá la información de farmacodinámica del producto
     */
    protected $farmacodinamica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de efectos colaterales, locales o generales, incompatibilidades, antagonismos, y contraindicaciones del producto pecuario
     */
    protected $efectosColaterales;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de toxicidad del producto
     */
    protected $toxicidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la categoría toxicológica del producto
     */
    protected $idCategoriaToxicologica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la temperatura de almacenamiento del producto
     */
    protected $temperaturaAlmacenamiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de la humedad de almacenamiento del producto
     */
    protected $humedadAlmacenamiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de recomendaciones de conservación del producto
     */
    protected $recomendacionConservacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del control de residuos de medicamentos
     */
    protected $controlResiduos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de principios de la técnica usada en el producto
     */
    protected $principiosTecnica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de detección de antígenos o anticuerpos en el producto
     */
    protected $deteccionAntigenos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de muestras usadas por la técnica en el producto
     */
    protected $muestrasUsadas;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de las pruebas físico químicas del producto
     */
    protected $pruebasFisQuim;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la inocuidad y esterilidad del producto
     */
    protected $inocuidadEsterilidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la sensibilidad del producto
     */
    protected $sensibilidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la especificidad del producto
     */
    protected $especificidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la repetibilidad del producto
     */
    protected $datosRepetibilidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la especificidad analítica del producto
     */
    protected $datosEspecificidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de la sensibilidad analítica del producto
     */
    protected $datosSensibilidad;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de anticuerpos de vacunación o infección del producto
     */
    protected $determinacionAnticuerpos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información microorganismos del producto
     */
    protected $determinacionMicroorganismos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de estados fisiológicos del producto
     */
    protected $determinacionEstadosFisiologicos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información de datos clínicos del producto
     */
    protected $determinacionDatosClinicos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información del uso correcto del producto
     */
    protected $modoUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar información del resultado e interpretaciones en el producto
     */
    protected $resultadoInterpretacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de las precauciones generales del producto
     */
    protected $precaucionesGenerales;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de las causas que pueden hacer variar la calidad del producto
     */
    protected $variacionCalidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la declaración de venta del producto
     */
    protected $idDeclaracionVenta;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar las observaciones del usuario con respecto al producto
     */
    protected $observacionesProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la solicitud:
     *      - Creado
     *      - pago
     *      - Recibida
     *      - EnTramite
     *      - Aprobado
     *      - Rechazado
     *      - Modificado
     *      - Subsanar
     */
    protected $estadoSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que realiza la revisión de la solicitud:
     *      - Financiero
     *      - CRIA
     */
    protected $identificadorRevisor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la fase de revisión en la que está la solicitud:
     *      - Financiero
     *      - Administrador
     *      - Tecnico
     *      - Usuario
     */
    protected $faseRevision;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de revisión de la solicitud
     */
    protected $fechaRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observación del revisor sobre el proceso efectuado
     */
    protected $observacionRevision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia de revisión de la solicitud
     */
    protected $idProvinciaRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del técnico asignado para la revisión
     */
    protected $identificadorTecnico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta al documento de Expediente de Registro de Producto
     */
    protected $rutaExpediente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta al documento de puntos mínimos
     */
    protected $rutaPuntosMinimos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta al documento de Certificado de Registro de Producto
     */
    protected $rutaCertificado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta al documento de cambios realizados por el usuario en subsanación
     */
    protected $rutaCambiosUsuario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del documento con observaciones del técnico para realizar la subsanación
     */
    protected $rutaDocumentoSubsanacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar las observaciones sobre los cambios realizados por el usuario
     */
    protected $observacionesCambios;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Contador de días restantes para proceso de subsanción. Aplica para procesos de Registro y Reevaluación
     */
    protected $tiempoSubsanacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud original para reemplazo en modificación y reevaluación
     */
    protected $idSolicitudOriginal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del nuevo titular del producto, cuando se especifica en el campo cambio_titular el valor Si.
     *      Al aprobar la solicitud se sobreescribe el valor del campo identificador con esta información.
     */
    protected $identificadorTitular;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Indica si se requiere un cambio de subtipo de producto (Para modificación y reevaluación):
     *      - Si
     *      - No
     */
    protected $cambioSubtipo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de inactivación o modificación de antigénica
     */
    protected $controlInactivacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información de control eficacia inmunológica y potencia del producto
     */
    protected $controlEficaciaInmunologica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Permitirá ingresar la información del control de adyuvantes del producto
     */
    protected $controlAdyuvantes;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_dossier_pecuario_mvc";

    /**
     * Nombre de la tabla: solicitud
     */
    private $tabla = "solicitud";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_solicitud";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."solicitud_id_solicitud_seq';

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
            throw new \Exception('Clase Modelo: SolicitudModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SolicitudModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_dossier_pecuario_mvc
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
     * Set idExpediente
     *
     * Código de la solicitud:
     * RIP-AÑO-SECUENCIAL cinco dígitos
     *
     * @parámetro String $idExpediente
     * @return IdExpediente
     */
    public function setIdExpediente($idExpediente)
    {
        $this->idExpediente = (string) $idExpediente;
        return $this;
    }

    /**
     * Get idExpediente
     *
     * @return null|String
     */
    public function getIdExpediente()
    {
        return $this->idExpediente;
    }

    /**
     * Set codigoProductoFinal
     *
     * Código único para identificación de un producto veterinario registrado una vez se aprueba la solicitud
     *
     * @parámetro String $codigoProductoFinal
     * @return CodigoProductoFinal
     */
    public function setCodigoProductoFinal($codigoProductoFinal)
    {
        $this->codigoProductoFinal = (string) $codigoProductoFinal;
        return $this;
    }

    /**
     * Get codigoProductoFinal
     *
     * @return null|String
     */
    public function getCodigoProductoFinal()
    {
        return $this->codigoProductoFinal;
    }

    /**
     * Set secuencia
     *
     * Número secuencial para la generación del número de expediente
     *
     * @parámetro String $secuencia
     * @return Secuencia
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = (string) $secuencia;
        return $this;
    }

    /**
     * Get secuencia
     *
     * @return null|String
     */
    public function getSecuencia()
    {
        return $this->secuencia;
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
     * Set idProvinciaOperador
     *
     * Identificador de la provincia del operador
     *
     * @parámetro Integer $idProvinciaOperador
     * @return IdProvinciaOperador
     */
    public function setIdProvinciaOperador($idProvinciaOperador)
    {
        $this->idProvinciaOperador = (integer) $idProvinciaOperador;
        return $this;
    }

    /**
     * Get idProvinciaOperador
     *
     * @return null|Integer
     */
    public function getIdProvinciaOperador()
    {
        return $this->idProvinciaOperador;
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
     * Set tipoSolicitud
     *
     * Tipo de solicitud requerida por el usuario:
     * - Registro
     * - Modificación
     * - Reevaluación
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
     * Set cambioTitular
     *
     * Indica si en la solicitud se requiere un cambio del titular del producto aprobado
     *
     * @parámetro String $cambioTitular
     * @return CambioTitular
     */
    public function setCambioTitular($cambioTitular)
    {
        $this->cambioTitular = (string) $cambioTitular;
        return $this;
    }

    /**
     * Get cambioTitular
     *
     * @return null|String
     */
    public function getCambioTitular()
    {
        return $this->cambioTitular;
    }

    /**
     * Set idGrupoProducto
     *
     * Identificador del Grupo de Producto
     *
     * @parámetro Integer $idGrupoProducto
     * @return IdGrupoProducto
     */
    public function setIdGrupoProducto($idGrupoProducto)
    {
        $this->idGrupoProducto = (integer) $idGrupoProducto;
        return $this;
    }

    /**
     * Get idGrupoProducto
     *
     * @return null|Integer
     */
    public function getIdGrupoProducto()
    {
        return $this->idGrupoProducto;
    }

    /**
     * Set grupoProducto
     *
     * Nombre del grupo de producto
     *
     * @parámetro String $grupoProducto
     * @return GrupoProducto
     */
    public function setGrupoProducto($grupoProducto)
    {
        $this->grupoProducto = (string) $grupoProducto;
        return $this;
    }

    /**
     * Get grupoProducto
     *
     * @return null|String
     */
    public function getGrupoProducto()
    {
        return $this->grupoProducto;
    }

    /**
     * Set idSubtipoProducto
     *
     * Identificador del subtipo de producto
     *
     * @parámetro Integer $idSubtipoProducto
     * @return IdSubtipoProducto
     */
    public function setIdSubtipoProducto($idSubtipoProducto)
    {
        $this->idSubtipoProducto = (integer) $idSubtipoProducto;
        return $this;
    }

    /**
     * Get idSubtipoProducto
     *
     * @return null|Integer
     */
    public function getIdSubtipoProducto()
    {
        return $this->idSubtipoProducto;
    }

    /**
     * Set codificacionSubtipoProducto
     *
     * Codificación del subtipo de producto seleccionado
     *
     * @parámetro String $codificacionSubtipoProducto
     * @return CodificacionSubtipoProducto
     */
    public function setCodificacionSubtipoProducto($codificacionSubtipoProducto)
    {
        $this->codificacionSubtipoProducto = (string) $codificacionSubtipoProducto;
        return $this;
    }

    /**
     * Get codificacionSubtipoProducto
     *
     * @return null|String
     */
    public function getCodificacionSubtipoProducto()
    {
        return $this->codificacionSubtipoProducto;
    }

    /**
     * Set nombreProducto
     *
     * Nombre del producto veterinario a registrar
     *
     * @parámetro String $nombreProducto
     * @return NombreProducto
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = (string) $nombreProducto;
        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return null|String
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set idClasificacion
     *
     * Identificador de la clasificación del producto
     *
     * @parámetro Integer $idClasificacion
     * @return IdClasificacion
     */
    public function setIdClasificacion($idClasificacion)
    {
        $this->idClasificacion = (integer) $idClasificacion;
        return $this;
    }

    /**
     * Get idClasificacion
     *
     * @return null|Integer
     */
    public function getIdClasificacion()
    {
        return $this->idClasificacion;
    }

    /**
     * Set lineaBiologica
     *
     * Permitirá ingresar la información de la línea biológica del producto
     *
     * @parámetro String $lineaBiologica
     * @return LineaBiologica
     */
    public function setLineaBiologica($lineaBiologica)
    {
        $this->lineaBiologica = (string) $lineaBiologica;
        return $this;
    }

    /**
     * Get lineaBiologica
     *
     * @return null|String
     */
    public function getLineaBiologica()
    {
        return $this->lineaBiologica;
    }

    /**
     * Set ph
     *
     * Permitirá ingresar el pH del producto
     *
     * @parámetro String $ph
     * @return Ph
     */
    public function setPh($ph)
    {
        $this->ph = (string) $ph;
        return $this;
    }

    /**
     * Get ph
     *
     * @return null|String
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * Set viscosidad
     *
     * Permitirá ingresar información de la viscosis del producto
     *
     * @parámetro String $viscosidad
     * @return Viscosidad
     */
    public function setViscosidad($viscosidad)
    {
        $this->viscosidad = (string) $viscosidad;
        return $this;
    }

    /**
     * Get viscosidad
     *
     * @return null|String
     */
    public function getViscosidad()
    {
        return $this->viscosidad;
    }

    /**
     * Set densidad
     *
     * Permitirá ingresar la densidad del producto
     *
     * @parámetro String $densidad
     * @return Densidad
     */
    public function setDensidad($densidad)
    {
        $this->densidad = (string) $densidad;
        return $this;
    }

    /**
     * Get densidad
     *
     * @return null|String
     */
    public function getDensidad()
    {
        return $this->densidad;
    }

    /**
     * Set metodoBiologico
     *
     * Permitirá ingresar la información del método biológico del producto
     *
     * @parámetro String $metodoBiologico
     * @return MetodoBiologico
     */
    public function setMetodoBiologico($metodoBiologico)
    {
        $this->metodoBiologico = (string) $metodoBiologico;
        return $this;
    }

    /**
     * Get metodoBiologico
     *
     * @return null|String
     */
    public function getMetodoBiologico()
    {
        return $this->metodoBiologico;
    }

    /**
     * Set metodoMicrobiologico
     *
     * Permitira ingresar la información del método microbiológico del producto
     *
     * @parámetro String $metodoMicrobiologico
     * @return MetodoMicrobiologico
     */
    public function setMetodoMicrobiologico($metodoMicrobiologico)
    {
        $this->metodoMicrobiologico = (string) $metodoMicrobiologico;
        return $this;
    }

    /**
     * Get metodoMicrobiologico
     *
     * @return null|String
     */
    public function getMetodoMicrobiologico()
    {
        return $this->metodoMicrobiologico;
    }

    /**
     * Set controlInocuidad
     *
     * Permitirá ingresar la información del control de inocuidad del producto
     *
     * @parámetro String $controlInocuidad
     * @return ControlInocuidad
     */
    public function setControlInocuidad($controlInocuidad)
    {
        $this->controlInocuidad = (string) $controlInocuidad;
        return $this;
    }

    /**
     * Get controlInocuidad
     *
     * @return null|String
     */
    public function getControlInocuidad()
    {
        return $this->controlInocuidad;
    }

    /**
     * Set agenteEtiologico
     *
     * Permitirá ingresar la información de agente etiológico susceptible (aplica para antisépticos, desinfectantes, sanitizantes y paguicidas)
     *
     * @parámetro String $agenteEtiologico
     * @return AgenteEtiologico
     */
    public function setAgenteEtiologico($agenteEtiologico)
    {
        $this->agenteEtiologico = (string) $agenteEtiologico;
        return $this;
    }

    /**
     * Get agenteEtiologico
     *
     * @return null|String
     */
    public function getAgenteEtiologico()
    {
        return $this->agenteEtiologico;
    }

    /**
     * Set metodoFabricacion
     *
     * Permitirá ingresar la información del método de fabricación del producto pecuario
     *
     * @parámetro String $metodoFabricacion
     * @return MetodoFabricacion
     */
    public function setMetodoFabricacion($metodoFabricacion)
    {
        $this->metodoFabricacion = (string) $metodoFabricacion;
        return $this;
    }

    /**
     * Get metodoFabricacion
     *
     * @return null|String
     */
    public function getMetodoFabricacion()
    {
        return $this->metodoFabricacion;
    }

    /**
     * Set caracteristicasFisQuimOrg
     *
     * Permitirá ingresar las característcas físicas, químicas y organolépticas del producto pecuario
     *
     * @parámetro String $caracteristicasFisQuimOrg
     * @return CaracteristicasFisQuimOrg
     */
    public function setCaracteristicasFisQuimOrg($caracteristicasFisQuimOrg)
    {
        $this->caracteristicasFisQuimOrg = (string) $caracteristicasFisQuimOrg;
        return $this;
    }

    /**
     * Get caracteristicasFisQuimOrg
     *
     * @return null|String
     */
    public function getCaracteristicasFisQuimOrg()
    {
        return $this->caracteristicasFisQuimOrg;
    }

    /**
     * Set caracteristicasFisQuim
     *
     * Características físicas y químicas del producto
     *
     * @parámetro String $caracteristicasFisQuim
     * @return CaracteristicasFisQuim
     */
    public function setCaracteristicasFisQuim($caracteristicasFisQuim)
    {
        $this->caracteristicasFisQuim = (string) $caracteristicasFisQuim;
        return $this;
    }

    /**
     * Get caracteristicasFisQuim
     *
     * @return null|String
     */
    public function getCaracteristicasFisQuim()
    {
        return $this->caracteristicasFisQuim;
    }

    /**
     * Set pruebasBiologicas
     *
     * Permitirá ingresar la información de las pruebas biológicas del producto
     *
     * @parámetro String $pruebasBiologicas
     * @return PruebasBiologicas
     */
    public function setPruebasBiologicas($pruebasBiologicas)
    {
        $this->pruebasBiologicas = (string) $pruebasBiologicas;
        return $this;
    }

    /**
     * Get pruebasBiologicas
     *
     * @return null|String
     */
    public function getPruebasBiologicas()
    {
        return $this->pruebasBiologicas;
    }

    /**
     * Set pruebasMicrobiologicas
     *
     * Permitirá ingresar la información de las pruebas microbiológicas del producto
     *
     * @parámetro String $pruebasMicrobiologicas
     * @return PruebasMicrobiologicas
     */
    public function setPruebasMicrobiologicas($pruebasMicrobiologicas)
    {
        $this->pruebasMicrobiologicas = (string) $pruebasMicrobiologicas;
        return $this;
    }

    /**
     * Get pruebasMicrobiologicas
     *
     * @return null|String
     */
    public function getPruebasMicrobiologicas()
    {
        return $this->pruebasMicrobiologicas;
    }

    /**
     * Set metodoBromatologico
     *
     * Permitirá ingresar la información del método bromatológico del producto pecuario
     *
     * @parámetro String $metodoBromatologico
     * @return MetodoBromatologico
     */
    public function setMetodoBromatologico($metodoBromatologico)
    {
        $this->metodoBromatologico = (string) $metodoBromatologico;
        return $this;
    }

    /**
     * Get metodoBromatologico
     *
     * @return null|String
     */
    public function getMetodoBromatologico()
    {
        return $this->metodoBromatologico;
    }

    /**
     * Set metodoFisQuim
     *
     * Permitirá ingresar la información del método físico, químico del producto pecuario
     *
     * @parámetro String $metodoFisQuim
     * @return MetodoFisQuim
     */
    public function setMetodoFisQuim($metodoFisQuim)
    {
        $this->metodoFisQuim = (string) $metodoFisQuim;
        return $this;
    }

    /**
     * Get metodoFisQuim
     *
     * @return null|String
     */
    public function getMetodoFisQuim()
    {
        return $this->metodoFisQuim;
    }

    /**
     * Set esquemaVacunacion
     *
     * Permitirá ingresar el esquema de vacunación
     *
     * @parámetro String $esquemaVacunacion
     * @return EsquemaVacunacion
     */
    public function setEsquemaVacunacion($esquemaVacunacion)
    {
        $this->esquemaVacunacion = (string) $esquemaVacunacion;
        return $this;
    }

    /**
     * Get esquemaVacunacion
     *
     * @return null|String
     */
    public function getEsquemaVacunacion()
    {
        return $this->esquemaVacunacion;
    }

    /**
     * Set tiempoInmunidad
     *
     * Permitirá ingresar la información del tiempo necesario para conferir inmunidad
     *
     * @parámetro String $tiempoInmunidad
     * @return TiempoInmunidad
     */
    public function setTiempoInmunidad($tiempoInmunidad)
    {
        $this->tiempoInmunidad = (string) $tiempoInmunidad;
        return $this;
    }

    /**
     * Get tiempoInmunidad
     *
     * @return null|String
     */
    public function getTiempoInmunidad()
    {
        return $this->tiempoInmunidad;
    }

    /**
     * Set tiempoMinimoInmunidad
     *
     * Permitirá ingresar la información del tiempo mínimo de duración de la inmunidad
     *
     * @parámetro String $tiempoMinimoInmunidad
     * @return TiempoMinimoInmunidad
     */
    public function setTiempoMinimoInmunidad($tiempoMinimoInmunidad)
    {
        $this->tiempoMinimoInmunidad = (string) $tiempoMinimoInmunidad;
        return $this;
    }

    /**
     * Get tiempoMinimoInmunidad
     *
     * @return null|String
     */
    public function getTiempoMinimoInmunidad()
    {
        return $this->tiempoMinimoInmunidad;
    }

    /**
     * Set requierePreparacion
     *
     * Bandera para informar si el producto requiere de preparación
     *
     * @parámetro String $requierePreparacion
     * @return RequierePreparacion
     */
    public function setRequierePreparacion($requierePreparacion)
    {
        $this->requierePreparacion = (string) $requierePreparacion;
        return $this;
    }

    /**
     * Get requierePreparacion
     *
     * @return null|String
     */
    public function getRequierePreparacion()
    {
        return $this->requierePreparacion;
    }

    /**
     * Set detallePreparacion
     *
     * Permitirá ingresar la explicación de la preparación del producto
     *
     * @parámetro String $detallePreparacion
     * @return DetallePreparacion
     */
    public function setDetallePreparacion($detallePreparacion)
    {
        $this->detallePreparacion = (string) $detallePreparacion;
        return $this;
    }

    /**
     * Get detallePreparacion
     *
     * @return null|String
     */
    public function getDetallePreparacion()
    {
        return $this->detallePreparacion;
    }

    /**
     * Set duracionMaxima
     *
     * Permitirá ingresar el tiempo de duración máxima del producto
     *
     * @parámetro String $duracionMaxima
     * @return DuracionMaxima
     */
    public function setDuracionMaxima($duracionMaxima)
    {
        $this->duracionMaxima = (double) $duracionMaxima;
        return $this;
    }

    /**
     * Get duracionMaxima
     *
     * @return null|String
     */
    public function getDuracionMaxima()
    {
        return $this->duracionMaxima;
    }

    /**
     * Set idTiempoDuracionMaxima
     *
     * Identificador de la unidad de tiempo de duración máxima del producto
     *
     * @parámetro Integer $idTiempoDuracionMaxima
     * @return IdTiempoDuracionMaxima
     */
    public function setIdTiempoDuracionMaxima($idTiempoDuracionMaxima)
    {
        $this->idTiempoDuracionMaxima = (integer) $idTiempoDuracionMaxima;
        return $this;
    }

    /**
     * Get idTiempoDuracionMaxima
     *
     * @return null|Integer
     */
    public function getIdTiempoDuracionMaxima()
    {
        return $this->idTiempoDuracionMaxima;
    }

    /**
     * Set nombreUnidadTiempoDuracion
     *
     * Permitirá ingresar el símbolo de la unidad de tiempo seleccionada
     *
     * @parámetro String $nombreUnidadTiempoDuracion
     * @return NombreUnidadTiempoDuracion
     */
    public function setNombreUnidadTiempoDuracion($nombreUnidadTiempoDuracion)
    {
        $this->nombreUnidadTiempoDuracion = (string) $nombreUnidadTiempoDuracion;
        return $this;
    }

    /**
     * Get nombreUnidadTiempoDuracion
     *
     * @return null|String
     */
    public function getNombreUnidadTiempoDuracion()
    {
        return $this->nombreUnidadTiempoDuracion;
    }

    /**
     * Set duracionMaximaReconstitucion
     *
     * Permitirá ingresar la información de la duración máxima del producto después de la reconstitución
     *
     * @parámetro String $duracionMaximaReconstitucion
     * @return DuracionMaximaReconstitucion
     */
    public function setDuracionMaximaReconstitucion($duracionMaximaReconstitucion)
    {
        $this->duracionMaximaReconstitucion = (string) $duracionMaximaReconstitucion;
        return $this;
    }

    /**
     * Get duracionMaximaReconstitucion
     *
     * @return null|String
     */
    public function getDuracionMaximaReconstitucion()
    {
        return $this->duracionMaximaReconstitucion;
    }

    /**
     * Set condicionesAlmacenamientoAbierto
     *
     * Permitirá ingresar la inforamción de almacenamiento del producto una vez abierto
     *
     * @parámetro String $condicionesAlmacenamientoAbierto
     * @return CondicionesAlmacenamientoAbierto
     */
    public function setCondicionesAlmacenamientoAbierto($condicionesAlmacenamientoAbierto)
    {
        $this->condicionesAlmacenamientoAbierto = (string) $condicionesAlmacenamientoAbierto;
        return $this;
    }

    /**
     * Get condicionesAlmacenamientoAbierto
     *
     * @return null|String
     */
    public function getCondicionesAlmacenamientoAbierto()
    {
        return $this->condicionesAlmacenamientoAbierto;
    }

    /**
     * Set farmacocinetica
     *
     * Permitirá ingresar la informaciónd e farmacocinética del producto
     *
     * @parámetro String $farmacocinetica
     * @return Farmacocinetica
     */
    public function setFarmacocinetica($farmacocinetica)
    {
        $this->farmacocinetica = (string) $farmacocinetica;
        return $this;
    }

    /**
     * Get farmacocinetica
     *
     * @return null|String
     */
    public function getFarmacocinetica()
    {
        return $this->farmacocinetica;
    }

    /**
     * Set farmacodinamica
     *
     * Permitirá la información de farmacodinámica del producto
     *
     * @parámetro String $farmacodinamica
     * @return Farmacodinamica
     */
    public function setFarmacodinamica($farmacodinamica)
    {
        $this->farmacodinamica = (string) $farmacodinamica;
        return $this;
    }

    /**
     * Get farmacodinamica
     *
     * @return null|String
     */
    public function getFarmacodinamica()
    {
        return $this->farmacodinamica;
    }

    /**
     * Set efectosColaterales
     *
     * Permitirá ingresar la información de efectos colaterales, locales o generales, incompatibilidades, antagonismos, y contraindicaciones del producto pecuario
     *
     * @parámetro String $efectosColaterales
     * @return EfectosColaterales
     */
    public function setEfectosColaterales($efectosColaterales)
    {
        $this->efectosColaterales = (string) $efectosColaterales;
        return $this;
    }

    /**
     * Get efectosColaterales
     *
     * @return null|String
     */
    public function getEfectosColaterales()
    {
        return $this->efectosColaterales;
    }

    /**
     * Set toxicidad
     *
     * Permitirá ingresar la información de toxicidad del producto
     *
     * @parámetro String $toxicidad
     * @return Toxicidad
     */
    public function setToxicidad($toxicidad)
    {
        $this->toxicidad = (string) $toxicidad;
        return $this;
    }

    /**
     * Get toxicidad
     *
     * @return null|String
     */
    public function getToxicidad()
    {
        return $this->toxicidad;
    }

    /**
     * Set idCategoriaToxicologica
     *
     * Identificador de la categoría toxicológica del producto
     *
     * @parámetro Integer $idCategoriaToxicologica
     * @return IdCategoriaToxicologica
     */
    public function setIdCategoriaToxicologica($idCategoriaToxicologica)
    {
        $this->idCategoriaToxicologica = (integer) $idCategoriaToxicologica;
        return $this;
    }

    /**
     * Get idCategoriaToxicologica
     *
     * @return null|Integer
     */
    public function getIdCategoriaToxicologica()
    {
        return $this->idCategoriaToxicologica;
    }

    /**
     * Set temperaturaAlmacenamiento
     *
     * Permitirá ingresar información de la temperatura de almacenamiento del producto
     *
     * @parámetro String $temperaturaAlmacenamiento
     * @return TemperaturaAlmacenamiento
     */
    public function setTemperaturaAlmacenamiento($temperaturaAlmacenamiento)
    {
        $this->temperaturaAlmacenamiento = (string) $temperaturaAlmacenamiento;
        return $this;
    }

    /**
     * Get temperaturaAlmacenamiento
     *
     * @return null|String
     */
    public function getTemperaturaAlmacenamiento()
    {
        return $this->temperaturaAlmacenamiento;
    }

    /**
     * Set humedadAlmacenamiento
     *
     * Permitirá ingresar la información de la humedad de almacenamiento del producto
     *
     * @parámetro String $humedadAlmacenamiento
     * @return HumedadAlmacenamiento
     */
    public function setHumedadAlmacenamiento($humedadAlmacenamiento)
    {
        $this->humedadAlmacenamiento = (string) $humedadAlmacenamiento;
        return $this;
    }

    /**
     * Get humedadAlmacenamiento
     *
     * @return null|String
     */
    public function getHumedadAlmacenamiento()
    {
        return $this->humedadAlmacenamiento;
    }

    /**
     * Set recomendacionConservacion
     *
     * Permitirá ingresar la información de recomendaciones de conservación del producto
     *
     * @parámetro String $recomendacionConservacion
     * @return RecomendacionConservacion
     */
    public function setRecomendacionConservacion($recomendacionConservacion)
    {
        $this->recomendacionConservacion = (string) $recomendacionConservacion;
        return $this;
    }

    /**
     * Get recomendacionConservacion
     *
     * @return null|String
     */
    public function getRecomendacionConservacion()
    {
        return $this->recomendacionConservacion;
    }

    /**
     * Set controlResiduos
     *
     * Permitirá ingresar la información del control de residuos de medicamentos
     *
     * @parámetro String $controlResiduos
     * @return ControlResiduos
     */
    public function setControlResiduos($controlResiduos)
    {
        $this->controlResiduos = (string) $controlResiduos;
        return $this;
    }

    /**
     * Get controlResiduos
     *
     * @return null|String
     */
    public function getControlResiduos()
    {
        return $this->controlResiduos;
    }

    /**
     * Set principiosTecnica
     *
     * Permitirá ingresar la información de principios de la técnica usada en el producto
     *
     * @parámetro String $principiosTecnica
     * @return PrincipiosTecnica
     */
    public function setPrincipiosTecnica($principiosTecnica)
    {
        $this->principiosTecnica = (string) $principiosTecnica;
        return $this;
    }

    /**
     * Get principiosTecnica
     *
     * @return null|String
     */
    public function getPrincipiosTecnica()
    {
        return $this->principiosTecnica;
    }

    /**
     * Set deteccionAntigenos
     *
     * Permitirá ingresar la información de detección de antígenos o anticuerpos en el producto
     *
     * @parámetro String $deteccionAntigenos
     * @return DeteccionAntigenos
     */
    public function setDeteccionAntigenos($deteccionAntigenos)
    {
        $this->deteccionAntigenos = (string) $deteccionAntigenos;
        return $this;
    }

    /**
     * Get deteccionAntigenos
     *
     * @return null|String
     */
    public function getDeteccionAntigenos()
    {
        return $this->deteccionAntigenos;
    }

    /**
     * Set muestrasUsadas
     *
     * Permitirá ingresar la información de muestras usadas por la técnica en el producto
     *
     * @parámetro String $muestrasUsadas
     * @return MuestrasUsadas
     */
    public function setMuestrasUsadas($muestrasUsadas)
    {
        $this->muestrasUsadas = (string) $muestrasUsadas;
        return $this;
    }

    /**
     * Get muestrasUsadas
     *
     * @return null|String
     */
    public function getMuestrasUsadas()
    {
        return $this->muestrasUsadas;
    }

    /**
     * Set pruebasFisQuim
     *
     * Permitirá ingresar información de las pruebas físico químicas del producto
     *
     * @parámetro String $pruebasFisQuim
     * @return PruebasFisQuim
     */
    public function setPruebasFisQuim($pruebasFisQuim)
    {
        $this->pruebasFisQuim = (string) $pruebasFisQuim;
        return $this;
    }

    /**
     * Get pruebasFisQuim
     *
     * @return null|String
     */
    public function getPruebasFisQuim()
    {
        return $this->pruebasFisQuim;
    }

    /**
     * Set inocuidadEsterilidad
     *
     * Permitirá ingresar información de la inocuidad y esterilidad del producto
     *
     * @parámetro String $inocuidadEsterilidad
     * @return InocuidadEsterilidad
     */
    public function setInocuidadEsterilidad($inocuidadEsterilidad)
    {
        $this->inocuidadEsterilidad = (string) $inocuidadEsterilidad;
        return $this;
    }

    /**
     * Get inocuidadEsterilidad
     *
     * @return null|String
     */
    public function getInocuidadEsterilidad()
    {
        return $this->inocuidadEsterilidad;
    }

    /**
     * Set sensibilidad
     *
     * Permitirá ingresar información de la sensibilidad del producto
     *
     * @parámetro String $sensibilidad
     * @return Sensibilidad
     */
    public function setSensibilidad($sensibilidad)
    {
        $this->sensibilidad = (string) $sensibilidad;
        return $this;
    }

    /**
     * Get sensibilidad
     *
     * @return null|String
     */
    public function getSensibilidad()
    {
        return $this->sensibilidad;
    }

    /**
     * Set especificidad
     *
     * Permitirá ingresar información de la especificidad del producto
     *
     * @parámetro String $especificidad
     * @return Especificidad
     */
    public function setEspecificidad($especificidad)
    {
        $this->especificidad = (string) $especificidad;
        return $this;
    }

    /**
     * Get especificidad
     *
     * @return null|String
     */
    public function getEspecificidad()
    {
        return $this->especificidad;
    }

    /**
     * Set datosRepetibilidad
     *
     * Permitirá ingresar información de la repetibilidad del producto
     *
     * @parámetro String $datosRepetibilidad
     * @return DatosRepetibilidad
     */
    public function setDatosRepetibilidad($datosRepetibilidad)
    {
        $this->datosRepetibilidad = (string) $datosRepetibilidad;
        return $this;
    }

    /**
     * Get datosRepetibilidad
     *
     * @return null|String
     */
    public function getDatosRepetibilidad()
    {
        return $this->datosRepetibilidad;
    }

    /**
     * Set datosEspecificidad
     *
     * Permitirá ingresar información de la especificidad analítica del producto
     *
     * @parámetro String $datosEspecificidad
     * @return DatosEspecificidad
     */
    public function setDatosEspecificidad($datosEspecificidad)
    {
        $this->datosEspecificidad = (string) $datosEspecificidad;
        return $this;
    }

    /**
     * Get datosEspecificidad
     *
     * @return null|String
     */
    public function getDatosEspecificidad()
    {
        return $this->datosEspecificidad;
    }

    /**
     * Set datosSensibilidad
     *
     * Permitirá ingresar información de la sensibilidad analítica del producto
     *
     * @parámetro String $datosSensibilidad
     * @return DatosSensibilidad
     */
    public function setDatosSensibilidad($datosSensibilidad)
    {
        $this->datosSensibilidad = (string) $datosSensibilidad;
        return $this;
    }

    /**
     * Get datosSensibilidad
     *
     * @return null|String
     */
    public function getDatosSensibilidad()
    {
        return $this->datosSensibilidad;
    }

    /**
     * Set determinacionAnticuerpos
     *
     * Permitirá ingresar información de anticuerpos de vacunación o infección del producto
     *
     * @parámetro String $determinacionAnticuerpos
     * @return DeterminacionAnticuerpos
     */
    public function setDeterminacionAnticuerpos($determinacionAnticuerpos)
    {
        $this->determinacionAnticuerpos = (string) $determinacionAnticuerpos;
        return $this;
    }

    /**
     * Get determinacionAnticuerpos
     *
     * @return null|String
     */
    public function getDeterminacionAnticuerpos()
    {
        return $this->determinacionAnticuerpos;
    }

    /**
     * Set determinacionMicroorganismos
     *
     * Permitirá ingresar información microorganismos del producto
     *
     * @parámetro String $determinacionMicroorganismos
     * @return DeterminacionMicroorganismos
     */
    public function setDeterminacionMicroorganismos($determinacionMicroorganismos)
    {
        $this->determinacionMicroorganismos = (string) $determinacionMicroorganismos;
        return $this;
    }

    /**
     * Get determinacionMicroorganismos
     *
     * @return null|String
     */
    public function getDeterminacionMicroorganismos()
    {
        return $this->determinacionMicroorganismos;
    }

    /**
     * Set determinacionEstadosFisiologicos
     *
     * Permitirá ingresar información de estados fisiológicos del producto
     *
     * @parámetro String $determinacionEstadosFisiologicos
     * @return DeterminacionEstadosFisiologicos
     */
    public function setDeterminacionEstadosFisiologicos($determinacionEstadosFisiologicos)
    {
        $this->determinacionEstadosFisiologicos = (string) $determinacionEstadosFisiologicos;
        return $this;
    }

    /**
     * Get determinacionEstadosFisiologicos
     *
     * @return null|String
     */
    public function getDeterminacionEstadosFisiologicos()
    {
        return $this->determinacionEstadosFisiologicos;
    }

    /**
     * Set determinacionDatosClinicos
     *
     * Permitirá ingresar información de datos clínicos del producto
     *
     * @parámetro String $determinacionDatosClinicos
     * @return DeterminacionDatosClinicos
     */
    public function setDeterminacionDatosClinicos($determinacionDatosClinicos)
    {
        $this->determinacionDatosClinicos = (string) $determinacionDatosClinicos;
        return $this;
    }

    /**
     * Get determinacionDatosClinicos
     *
     * @return null|String
     */
    public function getDeterminacionDatosClinicos()
    {
        return $this->determinacionDatosClinicos;
    }

    /**
     * Set modoUso
     *
     * Permitirá ingresar información del uso correcto del producto
     *
     * @parámetro String $modoUso
     * @return ModoUso
     */
    public function setModoUso($modoUso)
    {
        $this->modoUso = (string) $modoUso;
        return $this;
    }

    /**
     * Get modoUso
     *
     * @return null|String
     */
    public function getModoUso()
    {
        return $this->modoUso;
    }

    /**
     * Set resultadoInterpretacion
     *
     * Permitirá ingresar información del resultado e interpretaciones en el producto
     *
     * @parámetro String $resultadoInterpretacion
     * @return ResultadoInterpretacion
     */
    public function setResultadoInterpretacion($resultadoInterpretacion)
    {
        $this->resultadoInterpretacion = (string) $resultadoInterpretacion;
        return $this;
    }

    /**
     * Get resultadoInterpretacion
     *
     * @return null|String
     */
    public function getResultadoInterpretacion()
    {
        return $this->resultadoInterpretacion;
    }

    /**
     * Set precaucionesGenerales
     *
     * Permitirá ingresar la información de las precauciones generales del producto
     *
     * @parámetro String $precaucionesGenerales
     * @return PrecaucionesGenerales
     */
    public function setPrecaucionesGenerales($precaucionesGenerales)
    {
        $this->precaucionesGenerales = (string) $precaucionesGenerales;
        return $this;
    }

    /**
     * Get precaucionesGenerales
     *
     * @return null|String
     */
    public function getPrecaucionesGenerales()
    {
        return $this->precaucionesGenerales;
    }

    /**
     * Set variacionCalidad
     *
     * Permitirá ingresar la información de las causas que pueden hacer variar la calidad del producto
     *
     * @parámetro String $variacionCalidad
     * @return VariacionCalidad
     */
    public function setVariacionCalidad($variacionCalidad)
    {
        $this->variacionCalidad = (string) $variacionCalidad;
        return $this;
    }

    /**
     * Get variacionCalidad
     *
     * @return null|String
     */
    public function getVariacionCalidad()
    {
        return $this->variacionCalidad;
    }

    /**
     * Set idDeclaracionVenta
     *
     * Identificador de la declaración de venta del producto
     *
     * @parámetro Integer $idDeclaracionVenta
     * @return IdDeclaracionVenta
     */
    public function setIdDeclaracionVenta($idDeclaracionVenta)
    {
        $this->idDeclaracionVenta = (integer) $idDeclaracionVenta;
        return $this;
    }

    /**
     * Get idDeclaracionVenta
     *
     * @return null|Integer
     */
    public function getIdDeclaracionVenta()
    {
        return $this->idDeclaracionVenta;
    }

    /**
     * Set observacionesProducto
     *
     * Permitirá ingresar las observaciones del usuario con respecto al producto
     *
     * @parámetro String $observacionesProducto
     * @return ObservacionesProducto
     */
    public function setObservacionesProducto($observacionesProducto)
    {
        $this->observacionesProducto = (string) $observacionesProducto;
        return $this;
    }

    /**
     * Get observacionesProducto
     *
     * @return null|String
     */
    public function getObservacionesProducto()
    {
        return $this->observacionesProducto;
    }

    /**
     * Set estadoSolicitud
     *
     * Estado de la solicitud:
     * - Creado
     * - pago
     * - Recibida
     * - EnTramite
     * - Aprobado
     * - Rechazado
     * - Modificado
     * - Subsanar
     *
     * @parámetro String $estadoSolicitud
     * @return EstadoSolicitud
     */
    public function setEstadoSolicitud($estadoSolicitud)
    {
        $this->estadoSolicitud = (string) $estadoSolicitud;
        return $this;
    }

    /**
     * Get estadoSolicitud
     *
     * @return null|String
     */
    public function getEstadoSolicitud()
    {
        return $this->estadoSolicitud;
    }

    /**
     * Set identificadorRevisor
     *
     * Identificador del usuario que realiza la revisión de la solicitud:
     * - Financiero
     * - CRIA
     *
     * @parámetro String $identificadorRevisor
     * @return IdentificadorRevisor
     */
    public function setIdentificadorRevisor($identificadorRevisor)
    {
        $this->identificadorRevisor = (string) $identificadorRevisor;
        return $this;
    }

    /**
     * Get identificadorRevisor
     *
     * @return null|String
     */
    public function getIdentificadorRevisor()
    {
        return $this->identificadorRevisor;
    }

    /**
     * Set faseRevision
     *
     * Nombre de la fase de revisión en la que está la solicitud:
     * - Financiero
     * - Administrador
     * - Tecnico
     * - Usuario
     *
     * @parámetro String $faseRevision
     * @return FaseRevision
     */
    public function setFaseRevision($faseRevision)
    {
        $this->faseRevision = (string) $faseRevision;
        return $this;
    }

    /**
     * Get faseRevision
     *
     * @return null|String
     */
    public function getFaseRevision()
    {
        return $this->faseRevision;
    }

    /**
     * Set fechaRevision
     *
     * Fecha de revisión de la solicitud
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
     * Set observacionRevision
     *
     * Observación del revisor sobre el proceso efectuado
     *
     * @parámetro String $observacionRevision
     * @return ObservacionRevision
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
     * Set idProvinciaRevision
     *
     * Identificador de la provincia de revisión de la solicitud
     *
     * @parámetro Integer $idProvinciaRevision
     * @return IdProvinciaRevision
     */
    public function setIdProvinciaRevision($idProvinciaRevision)
    {
        $this->idProvinciaRevision = (integer) $idProvinciaRevision;
        return $this;
    }

    /**
     * Get idProvinciaRevision
     *
     * @return null|Integer
     */
    public function getIdProvinciaRevision()
    {
        return $this->idProvinciaRevision;
    }

    /**
     * Set identificadorTecnico
     *
     * Identificador del técnico asignado para la revisión
     *
     * @parámetro String $identificadorTecnico
     * @return IdentificadorTecnico
     */
    public function setIdentificadorTecnico($identificadorTecnico)
    {
        $this->identificadorTecnico = (string) $identificadorTecnico;
        return $this;
    }

    /**
     * Get identificadorTecnico
     *
     * @return null|String
     */
    public function getIdentificadorTecnico()
    {
        return $this->identificadorTecnico;
    }

    /**
     * Set rutaExpediente
     *
     * Ruta al documento de Expediente de Registro de Producto
     *
     * @parámetro String $rutaExpediente
     * @return RutaExpediente
     */
    public function setRutaExpediente($rutaExpediente)
    {
        $this->rutaExpediente = (string) $rutaExpediente;
        return $this;
    }

    /**
     * Get rutaExpediente
     *
     * @return null|String
     */
    public function getRutaExpediente()
    {
        return $this->rutaExpediente;
    }

    /**
     * Set rutaPuntosMinimos
     *
     * Ruta al documento de puntos mínimos
     *
     * @parámetro String $rutaPuntosMinimos
     * @return RutaPuntosMinimos
     */
    public function setRutaPuntosMinimos($rutaPuntosMinimos)
    {
        $this->rutaPuntosMinimos = (string) $rutaPuntosMinimos;
        return $this;
    }

    /**
     * Get rutaPuntosMinimos
     *
     * @return null|String
     */
    public function getRutaPuntosMinimos()
    {
        return $this->rutaPuntosMinimos;
    }

    /**
     * Set rutaCertificado
     *
     * Ruta al documento de Certificado de Registro de Producto
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
     * Set rutaCambiosUsuario
     *
     * Ruta al documento de cambios realizados por el usuario en subsanación
     *
     * @parámetro String $rutaCambiosUsuario
     * @return RutaCambiosUsuario
     */
    public function setRutaCambiosUsuario($rutaCambiosUsuario)
    {
        $this->rutaCambiosUsuario = (string) $rutaCambiosUsuario;
        return $this;
    }

    /**
     * Get rutaCambiosUsuario
     *
     * @return null|String
     */
    public function getRutaCambiosUsuario()
    {
        return $this->rutaCambiosUsuario;
    }

    /**
     * Set rutaDocumentoSubsanacion
     *
     * Ruta del documento con observaciones del técnico para realizar la subsanación
     *
     * @parámetro String $rutaDocumentoSubsanacion
     * @return RutaDocumentoSubsanacion
     */
    public function setRutaDocumentoSubsanacion($rutaDocumentoSubsanacion)
    {
        $this->rutaDocumentoSubsanacion = (string) $rutaDocumentoSubsanacion;
        return $this;
    }

    /**
     * Get rutaDocumentoSubsanacion
     *
     * @return null|String
     */
    public function getRutaDocumentoSubsanacion()
    {
        return $this->rutaDocumentoSubsanacion;
    }

    /**
     * Set observacionesCambios
     *
     * Permitirá ingresar las observaciones sobre los cambios realizados por el usuario
     *
     * @parámetro String $observacionesCambios
     * @return ObservacionesCambios
     */
    public function setObservacionesCambios($observacionesCambios)
    {
        $this->observacionesCambios = (string) $observacionesCambios;
        return $this;
    }

    /**
     * Get observacionesCambios
     *
     * @return null|String
     */
    public function getObservacionesCambios()
    {
        return $this->observacionesCambios;
    }

    /**
     * Set tiempoSubsanacion
     *
     * Contador de días restantes para proceso de subsanción. Aplica para procesos de Registro y Reevaluación
     *
     * @parámetro Integer $tiempoSubsanacion
     * @return TiempoSubsanacion
     */
    public function setTiempoSubsanacion($tiempoSubsanacion)
    {
        $this->tiempoSubsanacion = (integer) $tiempoSubsanacion;
        return $this;
    }

    /**
     * Get tiempoSubsanacion
     *
     * @return null|Integer
     */
    public function getTiempoSubsanacion()
    {
        return $this->tiempoSubsanacion;
    }

    /**
     * Set idSolicitudOriginal
     *
     * Identificador de la solicitud original para reemplazo en modificación y reevaluación
     *
     * @parámetro Integer $idSolicitudOriginal
     * @return IdSolicitudOriginal
     */
    public function setIdSolicitudOriginal($idSolicitudOriginal)
    {
        $this->idSolicitudOriginal = (integer) $idSolicitudOriginal;
        return $this;
    }

    /**
     * Get idSolicitudOriginal
     *
     * @return null|Integer
     */
    public function getIdSolicitudOriginal()
    {
        return $this->idSolicitudOriginal;
    }

    /**
     * Set identificadorTitular
     *
     * Identificador del nuevo titular del producto, cuando se especifica en el campo cambio_titular el valor Si.
     * Al aprobar la solicitud se sobreescribe el valor del campo identificador con esta información.
     *
     * @parámetro String $identificadorTitular
     * @return IdentificadorTitular
     */
    public function setIdentificadorTitular($identificadorTitular)
    {
        $this->identificadorTitular = (string) $identificadorTitular;
        return $this;
    }

    /**
     * Get identificadorTitular
     *
     * @return null|String
     */
    public function getIdentificadorTitular()
    {
        return $this->identificadorTitular;
    }

    /**
     * Set cambioSubtipo
     *
     * Indica si se requiere un cambio de subtipo de producto (Para modificación y reevaluación):
     * - Si
     * - No
     *
     * @parámetro String $cambioSubtipo
     * @return CambioSubtipo
     */
    public function setCambioSubtipo($cambioSubtipo)
    {
        $this->cambioSubtipo = (string) $cambioSubtipo;
        return $this;
    }

    /**
     * Get cambioSubtipo
     *
     * @return null|String
     */
    public function getCambioSubtipo()
    {
        return $this->cambioSubtipo;
    }

    /**
     * Set controlInactivacion
     *
     * Permitirá ingresar la información de inactivación o modificación de antigénica
     *
     * @parámetro String $controlInactivacion
     * @return ControlInactivacion
     */
    public function setControlInactivacion($controlInactivacion)
    {
        $this->controlInactivacion = (string) $controlInactivacion;
        return $this;
    }

    /**
     * Get controlInactivacion
     *
     * @return null|String
     */
    public function getControlInactivacion()
    {
        return $this->controlInactivacion;
    }

    /**
     * Set controlEficaciaInmunologica
     *
     * Permitirá ingresar la información de control eficacia inmunológica y potencia del producto
     *
     * @parámetro String $controlEficaciaInmunologica
     * @return ControlEficaciaInmunologica
     */
    public function setControlEficaciaInmunologica($controlEficaciaInmunologica)
    {
        $this->controlEficaciaInmunologica = (string) $controlEficaciaInmunologica;
        return $this;
    }

    /**
     * Get controlEficaciaInmunologica
     *
     * @return null|String
     */
    public function getControlEficaciaInmunologica()
    {
        return $this->controlEficaciaInmunologica;
    }

    /**
     * Set controlAdyuvantes
     *
     * Permitirá ingresar la información del control de adyuvantes del producto
     *
     * @parámetro String $controlAdyuvantes
     * @return ControlAdyuvantes
     */
    public function setControlAdyuvantes($controlAdyuvantes)
    {
        $this->controlAdyuvantes = (string) $controlAdyuvantes;
        return $this;
    }

    /**
     * Get controlAdyuvantes
     *
     * @return null|String
     */
    public function getControlAdyuvantes()
    {
        return $this->controlAdyuvantes;
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
     * @return SolicitudModelo
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
