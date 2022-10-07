<?php
/**
 * Modelo TramitesModelo
 *
 * Este archivo se complementa con el archivo   TramitesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    TramitesModelo
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class TramitesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del trámite
     */
    protected $idTramite;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único de identificación de trámite creado por el sistema
     */
    protected $numeroTramite;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha y hora de la creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la ventanilla en la que se registra el trámite
     */
    protected $idVentanilla;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Nombre de la ventanilla en la que se registra el trámite
     */
    protected $nombreVentanilla;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
     */
    protected $identificador;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Nombre del usuario que crea el registro
     */
    protected $nombreEmpleado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la persona que envía el trámite
     */
    protected $remitente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de referencia del trámite
     */
    protected $oficioMemo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de factura remitida en el trámite
     */
    protected $factura;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de guía de correo, memorando que referencia al trámite recibido (derivado)
     */
    protected $guiaQuipux;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Asunto del trámite detallado por el usuario
     */
    protected $asunto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Detalle de anexos remitidos con el trámite
     */
    protected $anexos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la persona a la que está dirigido el trámite
     */
    protected $destinatario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de destino a la que está dirigido el trámite
     */
    protected $idUnidadDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de oficio con el que se registra el trámite externo en el Sistema de Gestión Documental para su atención
     */
    protected $quipuxAgr;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Indica si el trámite tiene una derivación desde otra ventanilla
     */
    protected $derivado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro
     */
    protected $estadoTramite;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Indica si la información del trámite fue impreso en la bitácora para entrega
     */
    protected $bitacora;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en la que se generó la bitácora de entrega para el trámite
     */
    protected $fechaImpresion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Detalle de los documentos entregados por Agrocalidad para el cierre del trámite
     */
    protected $documentosEntregados;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en que se entregan documentos al usuario
     */
    protected $fechaEntrega;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones de cierre del trámite
     */
    protected $observaciones;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de cierre del trámite que registra el cambio de estado a cerrado
     */
    protected $fechaCierre;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de destino donde se encuentra actualmente el trámite
     */
    protected $idUnidadDestinoActual;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la unidad de destino donde se encuentra actualmente el trámite
     */
    protected $unidadDestinoActual;
    
    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Origen del trámite
     */
    protected $origenTramite;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_seguimiento_documental";

    /**
     * Nombre de la tabla: tramites
     */
    private $tabla = "tramites";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_tramite";

    /**
     * Secuencia
     */
    private $secuencial = 'g_seguimiento_documental"."tramites_id_tramite_seq';

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
            throw new \Exception('Clase Modelo: TramitesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: TramitesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_seguimiento_documental
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idTramite
     *
     * Identificador único del trámite
     *
     * @parámetro Integer $idTramite
     * @return IdTramite
     */
    public function setIdTramite($idTramite)
    {
        $this->idTramite = (integer) $idTramite;
        return $this;
    }

    /**
     * Get idTramite
     *
     * @return null|Integer
     */
    public function getIdTramite()
    {
        return $this->idTramite;
    }

    /**
     * Set numeroTramite
     *
     * Código único de identificación de trámite creado por el sistema
     *
     * @parámetro String $numeroTramite
     * @return NumeroTramite
     */
    public function setNumeroTramite($numeroTramite)
    {
        $this->numeroTramite = (string) $numeroTramite;
        return $this;
    }

    /**
     * Get numeroTramite
     *
     * @return null|String
     */
    public function getNumeroTramite()
    {
        return $this->numeroTramite;
    }

    /**
     * Set fechaCreacion
     *
     * Fecha y hora de la creación del registro
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
     * Set idVentanilla
     *
     * Identificador de la ventanilla en la que se registra el trámite
     *
     * @parámetro Integer $idVentanilla
     * @return IdVentanilla
     */
    public function setIdVentanilla($idVentanilla)
    {
        $this->idVentanilla = (integer) $idVentanilla;
        return $this;
    }

    /**
     * Get idVentanilla
     *
     * @return null|Integer
     */
    public function getIdVentanilla()
    {
        return $this->idVentanilla;
    }

    /**
     * Set nombreVentanilla
     *
     * Nombre de la ventanilla a la que se envía la valija
     *
     * @parámetro String $nombreVentanilla
     * @return NombreVentanilla
     */
    public function setNombreVentanilla($nombreVentanilla)
    {
        $this->nombreVentanilla = (string) $nombreVentanilla;
        return $this;
    }

    /**
     * Get nombreVentanilla
     *
     * @return null|string
     */
    public function getNombreVentanilla()
    {
        return $this->nombreVentanilla;
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
     * Set nombreEmpleado
     *
     * Nombre del usuario que crea el registro
     *
     * @parámetro String $nombreEmpleado
     * @return Identificador
     */
    public function setNombreEmpleado($nombreEmpleado)
    {
        $this->nombreEmpleado = (string) $nombreEmpleado;
        return $this;
    }

    /**
     * Get nombreEmpleado
     *
     * @return null|String
     */
    public function getNombreEmpleado()
    {
        return $this->nombreEmpleado;
    }

    /**
     * Set remitente
     *
     * Nombre de la persona que envía el trámite
     *
     * @parámetro String $remitente
     * @return Remitente
     */
    public function setRemitente($remitente)
    {
        $this->remitente = (string) $remitente;
        return $this;
    }

    /**
     * Get remitente
     *
     * @return null|String
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * Set oficioMemo
     *
     * Número de referencia del trámite
     *
     * @parámetro String $oficioMemo
     * @return OficioMemo
     */
    public function setOficioMemo($oficioMemo)
    {
        $this->oficioMemo = (string) $oficioMemo;
        return $this;
    }

    /**
     * Get oficioMemo
     *
     * @return null|String
     */
    public function getOficioMemo()
    {
        return $this->oficioMemo;
    }

    /**
     * Set factura
     *
     * Número de factura remitida en el trámite
     *
     * @parámetro String $factura
     * @return Factura
     */
    public function setFactura($factura)
    {
        $this->factura = (string) $factura;
        return $this;
    }

    /**
     * Get factura
     *
     * @return null|String
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set guiaQuipux
     *
     * Número de guía de correo, memorando que referencia al trámite recibido (derivado)
     *
     * @parámetro String $guiaQuipux
     * @return GuiaQuipux
     */
    public function setGuiaQuipux($guiaQuipux)
    {
        $this->guiaQuipux = (string) $guiaQuipux;
        return $this;
    }

    /**
     * Get guiaQuipux
     *
     * @return null|String
     */
    public function getGuiaQuipux()
    {
        return $this->guiaQuipux;
    }

    /**
     * Set asunto
     *
     * Asunto del trámite detallado por el usuario
     *
     * @parámetro String $asunto
     * @return Asunto
     */
    public function setAsunto($asunto)
    {
        $this->asunto = (string) $asunto;
        return $this;
    }

    /**
     * Get asunto
     *
     * @return null|String
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set anexos
     *
     * Detalle de anexos remitidos con el trámite
     *
     * @parámetro String $anexos
     * @return Anexos
     */
    public function setAnexos($anexos)
    {
        $this->anexos = (string) $anexos;
        return $this;
    }

    /**
     * Get anexos
     *
     * @return null|String
     */
    public function getAnexos()
    {
        return $this->anexos;
    }

    /**
     * Set destinatario
     *
     * Nombre de la persona a la que está dirigido el trámite
     *
     * @parámetro String $destinatario
     * @return Destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = (string) $destinatario;
        return $this;
    }

    /**
     * Get destinatario
     *
     * @return null|String
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set idUnidadDestino
     *
     * Identificador de la unidad de destino a la que está dirigido el trámite
     *
     * @parámetro String $idUnidadDestino
     * @return IdUnidadDestino
     */
    public function setIdUnidadDestino($idUnidadDestino)
    {
        $this->idUnidadDestino = (string) $idUnidadDestino;
        return $this;
    }

    /**
     * Get idUnidadDestino
     *
     * @return null|String
     */
    public function getIdUnidadDestino()
    {
        return $this->idUnidadDestino;
    }

    /**
     * Set quipuxAgr
     *
     * Número de oficio con el que se registra el trámite externo en el Sistema de Gestión Documental para su atención
     *
     * @parámetro String $quipuxAgr
     * @return QuipuxAgr
     */
    public function setQuipuxAgr($quipuxAgr)
    {
        $this->quipuxAgr = (string) $quipuxAgr;
        return $this;
    }

    /**
     * Get quipuxAgr
     *
     * @return null|String
     */
    public function getQuipuxAgr()
    {
        return $this->quipuxAgr;
    }

    /**
     * Set derivado
     *
     * Indica si el trámite tiene una derivación desde otra ventanilla
     *
     * @parámetro String $derivado
     * @return Derivado
     */
    public function setDerivado($derivado)
    {
        $this->derivado = (string) $derivado;
        return $this;
    }

    /**
     * Get derivado
     *
     * @return null|String
     */
    public function getDerivado()
    {
        return $this->derivado;
    }

    /**
     * Set estadoTramite
     *
     * Estado del registro
     *
     * @parámetro String $estadoTramite
     * @return Estado
     */
    public function setEstadoTramite($estadoTramite)
    {
        $this->estadoTramite = (string) $estadoTramite;
        return $this;
    }

    /**
     * Get estadoTramite
     *
     * @return null|String
     */
    public function getEstadoTramite()
    {
        return $this->estadoTramite;
    }

    /**
     * Set bitacora
     *
     * Indica si la información del trámite fue impreso en la bitácora para entrega
     *
     * @parámetro String $bitacora
     * @return Bitacora
     */
    public function setBitacora($bitacora)
    {
        $this->bitacora = (string) $bitacora;
        return $this;
    }

    /**
     * Get bitacora
     *
     * @return null|String
     */
    public function getBitacora()
    {
        return $this->bitacora;
    }

    /**
     * Set fechaImpresion
     *
     * Fecha en la que se generó la bitácora de entrega para el trámite
     *
     * @parámetro Date $fechaImpresion
     * @return FechaImpresion
     */
    public function setFechaImpresion($fechaImpresion)
    {
        $this->fechaImpresion = (string) $fechaImpresion;
        return $this;
    }

    /**
     * Get fechaImpresion
     *
     * @return null|Date
     */
    public function getFechaImpresion()
    {
        return $this->fechaImpresion;
    }

    /**
     * Set documentosEntregados
     *
     * Detalle de los documentos entregados por Agrocalidad para el cierre del trámite
     *
     * @parámetro String $documentosEntregados
     * @return DocumentosEntregados
     */
    public function setDocumentosEntregados($documentosEntregados)
    {
        $this->documentosEntregados = (string) $documentosEntregados;
        return $this;
    }

    /**
     * Get documentosEntregados
     *
     * @return null|String
     */
    public function getDocumentosEntregados()
    {
        return $this->documentosEntregados;
    }

    /**
     * Set fechaEntrega
     *
     * Fecha en que se entregan documentos al usuario
     *
     * @parámetro Date $fechaEntrega
     * @return FechaEntrega
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = (string) $fechaEntrega;
        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return null|Date
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set observaciones
     *
     * Observaciones de cierre del trámite
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
     * Set fechaCierre
     *
     * Fecha de cierre del trámite que registra el cambio de estado a cerrado
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
     * Set idUnidadDestinoActual
     *
     * Identificador de la unidad de destino donde se encuentra actualmente el trámite
     *
     * @parámetro String $idUnidadDestinoActual
     * @return IdUnidadDestinoActual
     */
    public function setIdUnidadDestinoActual($idUnidadDestinoActual)
    {
        $this->idUnidadDestinoActual = (string) $idUnidadDestinoActual;
        return $this;
    }
    
    /**
     * Get idUnidadDestinoActual
     *
     * @return null|String
     */
    public function getIdUnidadDestinoActual()
    {
        return $this->idUnidadDestinoActual;
    }
    
    /**
     * Set unidadDestinoActual
     *
     * Nombre de la unidad de destino donde se encuentra actualmente el trámite
     *
     * @parámetro String $unidadDestinoActual
     * @return UnidadDestinoActual
     */
    public function setUnidadDestinoActual($unidadDestinoActual)
    {
        $this->unidadDestinoActual = (string) $unidadDestinoActual;
        return $this;
    }
    
    /**
     * Get unidadDestinoActual
     *
     * @return null|String
     */
    public function getUnidadDestinoActual()
    {
        return $this->unidadDestinoActual;
    }
    
        
    /**
     * Set origenTramite
     *
     * Nombre del origen del trámite
     *
     * @parámetro String $origenTramite
     * @return OrigenTramite
     */
    public function setOrigenTramite($origenTramite)
    {
        $this->origenTramite = (string) $origenTramite;
        return $this;
    }
    
    /**
     * Get origenTramite
     *
     * @return null|string
     */
    public function getOrigenTramite()
    {
        return $this->origenTramite;
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
     * @return TramitesModelo
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
