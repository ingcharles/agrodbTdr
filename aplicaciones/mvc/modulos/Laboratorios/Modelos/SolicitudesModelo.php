<?php

/**
 * Modelo SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       SolicitudesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Clave primaria
     */
    protected $idSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ³digo
     */
    protected $codigo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      No. Memorando
     */
    protected $oficioExoneracion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      NÃºmero de muestras exoneradas
     */
    protected $numMuestrasExoneradas;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Tipo de solicitud
     */
    protected $tipoSolicitud;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idProcesoExterno;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $moduloExterno;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de envÃ­o
     */
    protected $fechaEnvio;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha recepciÃ³n
     */
    protected $fechaRecepcion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de entrega
     */
    protected $fechaFinalEstimada;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha real de entrega
     */
    protected $fechaFinalReal;

    /**
     *
     * @var Date Campo requerido
     *      Campo oculto en el formulario o manejado internamente
     *      Fecha de registro
     */
    protected $fechaRegistro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de autorizaciÃ³n
     */
    protected $tipoAutorizacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $muestreoNacional;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Exoneracion
     */
    protected $exoneracion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $usuarioGuia;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $requiereNuevaMuestra;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      NomArchivoOficio
     */
    protected $nomArchivoOficio;

    /**
     * Clase hija
     *
     * @var DetalleSolicitudesModelo
     */
    protected $detalleSolicitud;

    /**
     * Clase hija
     *
     * @var Muestras
     */
    protected $muestras;

    /**
     * Clase hija
     *
     * @var Personas
     */
    protected $personas;

    /**
     * Es necesario guardar el formulario en una variable para los formularios dinÃ¡micos
     *
     * @var Array $_POST
     */
    protected $datosForm;

    /**
     * Campos del formulario 
     * @var array 
     */
    Private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

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
    private $secuencial = 'g_laboratorios"."solicitudes_id_solicitud_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos))
        {
            $this->setOptions($datos);
            // llenamos el detalle de la solicitud
            $this->setDetalleSolicitud($datos);
            $this->setMuestras($datos);
            $this->setPersonas($datos); //Datos persona de facturacion
            $this->setDatosForm($datos);
        }
        $features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
        parent::__construct($this->esquema, $this->tabla, $features);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @parÃ¡metro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: SolicitudesModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parÃ¡metro array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
            $key_original = $key;
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
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
        foreach ($this->campos as $key => $value)
        {
            $this->campos[$key] = $claseArray[lcfirst($value)];
        }
        return $this->campos;
    }

    /**
     * 
     * @return type
     */
    function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * 
     * @param type $esquema
     */
    function setEsquema($esquema)
    {
        $this->esquema = $esquema;
    }

    /**
     * Set idSolicitud
     *
     * Secuencial (PK) de la tabla de solicitud
     *
     * @parÃ¡metro Integer $idSolicitud
     *
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
     * Set codigo
     *
     * CÃ³digo que identifique la solicitud y el paquete de la(s) muestra(s)
     *
     * @parÃ¡metro String $codigo
     *
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfa($codigo, $this->tabla, " Código", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set oficioExoneracion
     *
     * En caso de existir exoneraciÃ³n se debe registrar el nÃºmero de oficio o memo donde se autoriza
     *
     * @parÃ¡metro String $oficioExoneracion
     *
     * @return OficioExoneracion
     */
    public function setOficioExoneracion($oficioExoneracion)
    {
        $this->oficioExoneracion = ValidarDatos::validarAlfaEsp($oficioExoneracion, $this->tabla, " N°. Oficio Exoneración", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get oficioExoneracion
     *
     * @return null|String
     */
    public function getOficioExoneracion()
    {
        return $this->oficioExoneracion;
    }

    /**
     * Set numMuestrasExoneradas
     *
     * NÃºmero de muestras exoneradas. Este dato es utilizado para el control cuando se realiza un anÃ¡lisis de forma parcial
     *
     * @parÃ¡metro Integer $numMuestrasExoneradas
     *
     * @return NumMuestrasExoneradas
     */
    public function setNumMuestrasExoneradas($numMuestrasExoneradas)
    {
        $this->numMuestrasExoneradas = $numMuestrasExoneradas;
        return $this;
    }

    /**
     * Get numMuestrasExoneradas
     *
     * @return null|Integer
     */
    public function getNumMuestrasExoneradas()
    {
        return $this->numMuestrasExoneradas;
    }

    /**
     * Set tipoSolicitud
     *
     * Identifica el motivo de la solicitud, viene de un catÃ¡logo prestablecido: Solicitud Cliente Externo(CE). Solicitud Cliente Interno(CI) . Solicitud Confirmatorio (CI). Solicitud Confirmatorio (CE)
     *
     * @parÃ¡metro Integer $tipoSolicitud
     *
     * @return TipoSolicitud
     */
    public function setTipoSolicitud($tipoSolicitud)
    {
        $this->tipoSolicitud = ValidarDatos::validarAlfa($tipoSolicitud, $this->tabla, " Tipo de Solicitud", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get tipoSolicitud
     *
     * @return null|Integer
     */
    public function getTipoSolicitud()
    {
        return $this->tipoSolicitud;
    }

    /**
     * Set idProcesoExterno
     *
     *
     *
     * @parámetro Integer $idProcesoExterno
     * @return IdProcesoExterno
     */
    public function setIdProcesoExterno($idProcesoExterno)
    {
        $this->idProcesoExterno = (Integer) $idProcesoExterno;
        return $this;
    }

    /**
     * Get idProcesoExterno
     *
     * @return null|Integer
     */
    public function getIdProcesoExterno()
    {
        return $this->idProcesoExterno;
    }

    /**
     * Set moduloExterno
     *
     *
     *
     * @parámetro String $moduloExterno
     * @return ModuloExterno
     */
    public function setModuloExterno($moduloExterno)
    {
        $this->moduloExterno = ValidarDatos::validarAlfaEsp($moduloExterno, $this->tabla, " Nódulo externo", self::NO_REQUERIDO, 64);
        return $this;
    }

    /**
     * Get moduloExterno
     *
     * @return null|String
     */
    public function getModuloExterno()
    {
        return $this->moduloExterno;
    }

    /**
     * Set fechaEnvio
     *
     * Fecha que el cliente envÃ­a la solicitud, esta fecha inicia el proceso.
     *
     * @parÃ¡metro Date $fechaEnvio
     *
     * @return FechaEnvio
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = ValidarDatos::validarFecha($fechaEnvio, $this->tabla, " Fecha de Envío", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return null|Date
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set fechaRecepcion
     *
     * Fecha que recaudador recepta la solicitud y muestra
     *
     * @parÃ¡metro Date $fechaRecepcion
     *
     * @return FechaRecepcion
     */
    public function setFechaRecepcion($fechaRecepcion)
    {
        $this->fechaRecepcion = ValidarDatos::validarFecha($fechaRecepcion, $this->tabla, " Fecha de Recepción", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaRecepcion
     *
     * @return null|Date
     */
    public function getFechaRecepcion()
    {
        return $this->fechaRecepcion;
    }

    /**
     * Set fechaFinalEstimada
     *
     * Fecha que se estima que puede ser entregado el informe del anÃ¡lisis.
     *
     * @parÃ¡metro Date $fechaFinalEstimada
     *
     * @return FechaFinalEstimada
     */
    public function setFechaFinalEstimada($fechaFinalEstimada)
    {
        $this->fechaFinalEstimada = ValidarDatos::validarFecha($fechaFinalEstimada, $this->tabla, " Fecha Final Estimada", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinalEstimada
     *
     * @return null|Date
     */
    public function getFechaFinalEstimada()
    {
        return $this->fechaFinalEstimada;
    }

    /**
     * Set fechaFinalReal
     *
     * Fecha que el cliente finaliza el trÃ¡mite, esta fecha finaliza el proceso y es actualizada cuando se envÃ­a el informe del resultado de anÃ¡lisis.
     *
     * @parÃ¡metro Date $fechaFinalReal
     *
     * @return FechaFinalReal
     */
    public function setFechaFinalReal($fechaFinalReal)
    {
        $this->fechaFinalReal = ValidarDatos::validarFecha($fechaFinalReal, $this->tabla, " Fecha Final Real", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinalReal
     *
     * @return null|Date
     */
    public function getFechaFinalReal()
    {
        return $this->fechaFinalReal;
    }

    /**
     * Set fechaRegistro
     *
     * Indica la fecha cuando se registrÃ³ la solicitud
     *
     * @parÃ¡metro Date $fechaRegistro
     *
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = ValidarDatos::validarFecha($fechaRegistro, $this->tabla, " Fecha de Registro", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return null|Date
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set muestreoNacional
     *
     * Indica si la solicitud  corresponde a un muestreo nacional , donde por lo general requieren mas de un tecnico para el ingreso de muestras
     *
     * @parÃ¡metro String $muestreoNacional
     *
     * @return TipoAutorizacion
     */
    public function setMuestreoNacional($muestreoNacional)
    {
        $this->muestreoNacional = ValidarDatos::validarAlfaEsp($muestreoNacional, $this->tabla, " Muestreo Nacional", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get muestreoNacional
     *
     * @return null|String
     */
    public function getMuestreoNacional()
    {
        return $this->muestreoNacional;
    }

    /**
     * Set tipoAutorizacion
     *
     * Si requiere algÃºn tipo de autorizaciÃ³n. Ej, pago posterior
     *
     * @parÃ¡metro String $tipoAutorizacion
     *
     * @return TipoAutorizacion
     */
    public function setTipoAutorizacion($tipoAutorizacion)
    {
        $this->tipoAutorizacion = ValidarDatos::validarAlfa($tipoAutorizacion, $this->tabla, " Tipo de Autorización", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get tipoAutorizacion
     *
     * @return null|String
     */
    public function getTipoAutorizacion()
    {
        return $this->tipoAutorizacion;
    }

    /**
     * Set estado
     *
     * Indica en los estados que tiene una solicitud en el siguiente orden: Registrado. - Estado inicial y estÃ¡ por defecto. Enviado. - Cuando el cliente acepta que toda la informaciÃ³n ingresada es la correcta y presiona el botÃ³n enviar solicitud. En proceso. - Cuando se genera la orden de trabajo. Finalizado. - Cuando se envÃ­a el informe de resultado de anÃ¡lisis al cliente. Estados alternativos. Devuelto. - Cuando el analista considera que la muestra o los datos deben ser subsanados. En espera. - En caso de que exista algÃºn documento o muestra por presentar al presentar lo requerido la solicitud continua con el siguiente estado.
     *
     * @parÃ¡metro String $estado
     *
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 16);
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
     * Set exoneracion
     *
     * SI/NO aplica exoneraciÃ³n de pago
     *
     * @parÃ¡metro String $exoneracion
     *
     * @return Exoneracion
     */
    public function setExoneracion($exoneracion)
    {
        $this->exoneracion = ValidarDatos::validarAlfa($exoneracion, $this->tabla, " Tiene exoneración", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get exoneracion
     *
     * @return null|String
     */
    public function getExoneracion()
    {
        return $this->exoneracion;
    }

    /**
     * Set usuarioGuia
     *
     *
     *
     * @parámetro String $usuarioGuia
     * @return UsuarioGuia
     */
    public function setUsuarioGuia($usuarioGuia)
    {
        $this->usuarioGuia = ValidarDatos::validarAlfa($usuarioGuia, $this->tabla, " Usuario GUIA", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get usuarioGuia
     *
     * @return null|String
     */
    public function getUsuarioGuia()
    {
        return $this->usuarioGuia;
    }

    /**
     * Set requiereNuevaMuestra
     *
     *
     *
     * @parámetro String $requiereNuevaMuestra
     * @return RequiereNuevaMuestra
     */
    public function setRequiereNuevaMuestra($requiereNuevaMuestra)
    {
        $this->requiereNuevaMuestra = ValidarDatos::validarAlfa($requiereNuevaMuestra, $this->tabla, " Requiere nueva muestra", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get requiereNuevaMuestra
     *
     * @return null|String
     */
    public function getRequiereNuevaMuestra()
    {
        return $this->requiereNuevaMuestra;
    }

    /**
     * Set nomArchivoOficio
     *
     * Nombre del archivo del oficio de exoneracion
     *
     * @parÃ¡metro String $nomArchivoOficio
     *
     * @return NomArchivoOficio
     */
    public function setNomArchivoOficio($nomArchivoOficio)
    {
        $this->nomArchivoOficio = ValidarDatos::validarAlfaEsp($nomArchivoOficio, $this->tabla, " Nombre Archivo Oficio exoneración", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get nomArchivoOficio
     *
     * @return null|String
     */
    public function getNomArchivoOficio()
    {
        return $this->nomArchivoOficio;
    }

    /**
     * Llena de datos a la tabla de detalle de solicitud
     *
     * @param Array $datos
     */
    public function setDetalleSolicitud($datos)
    {
        $this->detalleSolicitud = new DetalleSolicitudesModelo($datos);
    }

    /**
     * Retorna el modelo de la tabla detalle_solicitudes
     *
     * @return \Agrodb\Laboratorios\Modelos\DetalleSolicitudesModelo
     */
    public function getDetalleSolicitud()
    {
        if (null === $this->detalleSolicitud)
        {
            $this->detalleSolicitud = new DetalleSolicitudesModelo();
        }
        return $this->detalleSolicitud;
    }

    /**
     * Llena los datos de la tabla muestras
     *
     * @param Array $datos
     *
     */
    public function setMuestras($datos)
    {
        $this->muestras = new MuestrasModelo($datos);
    }

    /**
     * Retorna el modelo de la tabla muestras
     *
     * @return \Agrodb\Laboratorios\Modelos\Muestras
     */
    public function getMuestras()
    {
        if (null === $this->muestras)
        {
            $this->muestras = new MuestrasModelo();
        }
        return $this->muestras;
    }

    /**
     * Llena los datos de la tabla muestras
     *
     * @param Array $datos
     *
     */
    public function setPersonas($datos)
    {
        if (is_array($datos))
        {
            $this->personas = new PersonasModelo($datos);
        } else
        {
            $this->personas = $datos;
        }
    }

    /**
     * Retorna el modelo de la tabla muestras
     *
     * @return \Agrodb\Laboratorios\Modelos\Muestras
     */
    public function getPersonas()
    {
        if (null === $this->personas)
        {
            $this->personas = new PersonasModelo();
        }
        return $this->personas;
    }

    /**
     *
     * @param Array $datosForm
     */
    public function setDatosForm($datosForm)
    {
        $this->datosForm = $datosForm;
    }

    /**
     * Datos del formulario
     *
     * @return Array
     */
    public function getDatosForm()
    {
        return $this->datosForm;
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
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
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
