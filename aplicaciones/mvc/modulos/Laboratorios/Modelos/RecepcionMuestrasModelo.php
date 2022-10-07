<?php

/**
 * Modelo RecepcionMuestrasModelo
 *
 * Este archivo se complementa con el archivo   RecepcionMuestrasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       RecepcionMuestrasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RecepcionMuestrasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Id
     */
    protected $idRecepcionMuestras;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idDetalleSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Clave primaria
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idOrdenTrabajo;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de sevicio
     */
    protected $idServicio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ©dula o RUC
     */
    protected $identificador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ©dula
     */
    protected $fkIdentificador;

    /**
     * Responsable de aprobar los resultados
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ©dula
     */
    protected $fk_identificador2;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de recepciÃ³n
     */
    protected $tipoRecepcion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ³digo de laboratorio de la muestra
     */
    protected $codigoLabMuestra;

    /**
     * @var Integer
     * Campo requerido
     * Id
     */
    protected $numeroMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ³digo de campo de la muestra ingresado por el usuario
     */
    protected $codigoUsuMuestra;
    
    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      ObservaciÃ³n
     */
    protected $observacionRecepcion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de recepciÃ³n
     */
    protected $fechaRecepcion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Es aceptada
     */
    protected $esAceptada;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha verificada
     */
    protected $fechaVerificada;

    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      Es idonea
     */
    protected $esIdonea;

    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      ObservaciÃ³n de laboratorio
     */
    protected $observacionVerificacion;

    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      No es idonea
     */
    protected $noIdoneaAnalisis;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de recepciÃ³n
     */
    protected $fechaNoIdoneaAnalisis;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      ConsevaciÃ³n de la muestra
     */
    protected $conservacionMuestra;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha que inicia el anÃ¡lisis
     */
    protected $fechaInicioAnalisis;

    /**
     * @var Date
     * Campo opcional
     * Campo visible en el formulario
     * Fecha que finaliza el anÃ¡lisis
     */
    protected $fechaFinAnalisis;

    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      ObservaciÃ³n de analisis
     */
    protected $observacionAnalisis;

    /**
     * Estado actual de la muestra
     * @var type 
     */
    protected $estadoActual;

    /**
     * El estado es APROBADO cuando el RT aprueba el resultado analizado caso contrario NO APROBADO
     * @var type 
     */
    protected $estadoAprobacion;

    /**
     *
     * @var String Campo opcional
     *      Campo visible en el formulario
     *      Observacion del estado de aprobacion. Obligatorio cuando el resultado no es aprobado.
     */
    protected $observacionAprobacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha toma
     */
    protected $fechaToma;

    /**
     *
     * @var String Campo
     *      Campo visible en el formulario
     *      Responsable de la toma de la muestra
     */
    protected $responsableToma;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     */
    protected $fechaFinAlmacenamiento;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     */
    protected $fechaDesecho;
    
    /**
     * @var Integer
     * Cuenta cada vez que el RT no aprueba.
     */
    protected $contadorNoAprobado;

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
     * Nombre de la tabla: recepcion_muestras
     */
    private $tabla = "recepcion_muestras";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_recepcion_muestras";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."recepcion_muestras_id_recepcion_muestras_seq';

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
            throw new \Exception('Clase Modelo: RecepcionMuestrasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: RecepcionMuestrasModelo. Propiedad especificada invalida: get' . $name);
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
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
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
     * Set $esquema
     *
     * Nombre del esquema del mÃ³dulo
     *
     * @parÃ¡metro $esquema
     * 
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idRecepcionMuestras
     *
     * Clave primaria
     *
     * @parÃ¡metro Integer $idRecepcionMuestras
     * 
     * @return IdRecepcionMuestras
     */
    public function setIdRecepcionMuestras($idRecepcionMuestras)
    {
        $this->idRecepcionMuestras = (integer) $idRecepcionMuestras;
        return $this;
    }

    /**
     * Get idRecepcionMuestras
     *
     * @return null|Integer
     */
    public function getIdRecepcionMuestras()
    {
        return $this->idRecepcionMuestras;
    }

    /**
     * Set idDetalleSolicitud
     *
     * Secuencial (PK) de la tabla de detalle_solicitud
     *
     * @parÃ¡metro Integer $idDetalleSolicitud
     * @return IdDetalleSolicitud
     */
    public function setIdDetalleSolicitud($idDetalleSolicitud)
    {
        $this->idDetalleSolicitud = (Integer) $idDetalleSolicitud;
        return $this;
    }

    /**
     * Get idDetalleSolicitud
     *
     * @return null|Integer
     */
    public function getIdDetalleSolicitud()
    {
        return $this->idDetalleSolicitud;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
     *
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }

    /**
     * Set idOrdenTrabajo
     *
     * Secuencial (PK) de la tabla ordenes_trabajos
     *
     * @parÃ¡metro Integer $idOrdenTrabajo
     * @return IdOrdenTrabajo
     */
    public function setIdOrdenTrabajo($idOrdenTrabajo)
    {
        $this->idOrdenTrabajo = (Integer) $idOrdenTrabajo;
        return $this;
    }

    /**
     * Get idOrdenTrabajo
     *
     * @return null|Integer
     */
    public function getIdOrdenTrabajo()
    {
        return $this->idOrdenTrabajo;
    }

    /**
     * Set idServicio
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parÃ¡metro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
        $this->idServicio = (Integer) $idServicio;
        return $this;
    }

    /**
     * Get idServicio
     *
     * @return null|Integer
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    /**
     * Set identificador
     *
     * CÃ©dula de identidad o pasaporte.
     *
     * @parÃ¡metro String $identificador
     * 
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = ValidarDatos::validarAlfa($identificador, $this->tabla, " CI o Pasaporte", self::NO_REQUERIDO, 16);
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
     * Set fkIdentificador
     *
     * Cedula de identidad o pasaporte.
     *
     * @parÃ¡metro String $fkIdentificador
     * 
     * @return FkIdentificador
     */
    public function setFkIdentificador($fkIdentificador)
    {
        $this->fkIdentificador = (string) $fkIdentificador;
        return $this;
    }

    /**
     * Get fkIdentificador
     *
     * @return null|String
     */
    public function getFkIdentificador()
    {
        return $this->fkIdentificador;
    }

    /**
     * Set fkIdentificador
     *
     * Cedula de identidad o pasaporte.
     *
     * @parÃ¡metro String $fkIdentificador
     * 
     * @return FkIdentificador
     */
    public function setFkIdentificador2($fkIdentificador2)
    {
        $this->fkIdentificador2 = (string) $fkIdentificador2;
        return $this;
    }

    /**
     * Get fkIdentificador
     *
     * @return null|String
     */
    public function getFkIdentificador2()
    {
        return $this->fkIdentificador2;
    }

    /**
     * Set tipoRecepcion
     *
     * Puede ser ingreso o reingreso
     *
     * @parÃ¡metro String $tipoRecepcion
     * 
     * @return TipoRecepcion
     */
    public function setTipoRecepcion($tipoRecepcion)
    {
        $this->tipoRecepcion = ValidarDatos::validarAlfa($tipoRecepcion, $this->tabla, " Tipo Recepción", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get tipoRecepcion
     *
     * @return null|String
     */
    public function getTipoRecepcion()
    {
        return $this->tipoRecepcion;
    }

    /**
     * Set codigoLabMuestra
     *
     * CÃ³digo de laboratorio de la muestra
     *
     * @parÃ¡metro String $codigoLabMuestra
     * @return CodigoLabMuestra
     */
    public function setCodigoLabMuestra($codigoLabMuestra)
    {
        $this->codigoLabMuestra = ValidarDatos::validarAlfaEsp($codigoLabMuestra, $this->tabla, " Código Lab. Muestra", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get codigoLabMuestra
     *
     * @return null|String
     */
    public function getCodigoLabMuestra()
    {
        return $this->codigoLabMuestra;
    }

    /**
     * Set numeroMuestra
     *
     * Numero de muestra
     *
     * @parÃ¡metro Integer $numeroMuestra
     * @return NumeroMuestra
     */
    public function setNumeroMuestra($numeroMuestra)
    {
        $this->numeroMuestra = (Integer) $numeroMuestra;
        return $this;
    }

    /**
     * Get numeroMuestra
     *
     * @return null|Integer
     */
    public function getNumeroMuestra()
    {
        return $this->numeroMuestra;
    }

    /**
     * Set codigoUsuMuestra
     *
     * CÃ³digo de campo de la muestra ingresado por el usuario
     *
     * @parÃ¡metro String $codigoUsuMuestra
     * @return CodigoUsuMuestra
     */
    public function setCodigoUsuMuestra($codigoUsuMuestra)
    {
        $this->codigoUsuMuestra = ValidarDatos::validarAlfaEsp($codigoUsuMuestra, $this->tabla, "codigo_usu_muestra", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get codigoUsuMuestra
     *
     * @return null|String
     */
    public function getCodigoUsuMuestra()
    {
        return $this->codigoUsuMuestra;
    }

    /**
     * Set observacionRecepcion
     *
     * ObservaciÃ³n en caso de no ser aceptada
     *
     * @parÃ¡metro String $observacionRecepcion
     * 
     * @return ObservacionRecepcion
     */
    public function setObservacionRecepcion($observacionRecepcion)
    {
        $this->observacionRecepcion = ValidarDatos::validarAlfaEsp($observacionRecepcion, $this->tabla, " Observación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionRecepcion
     *
     * @return null|String
     */
    public function getObservacionRecepcion()
    {
        return $this->observacionRecepcion;
    }

    /**
     * Set fechaRecepcion
     *
     * Fecha cuando la muestra es recibida
     *
     * @parÃ¡metro Date $fechaRecepcion
     * 
     * @return FechaRecepcion
     */
    public function setFechaRecepcion($fechaRecepcion)
    {
        $this->fechaRecepcion = ValidarDatos::validarFecha($fechaRecepcion, $this->tabla, " Fecha Recepción", self::REQUERIDO, 0);
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
     * Set esAceptada
     *
     * Puedes ser SI o NO
     *
     * @parÃ¡metro String $esAceptada
     * 
     * @return EsAceptada
     */
    public function setEsAceptada($esAceptada)
    {
        $this->esAceptada = ValidarDatos::validarAlfa($esAceptada, $this->tabla, " Aceptada SI / NO", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get esAceptada
     *
     * @return null|String
     */
    public function getEsAceptada()
    {
        return $this->esAceptada;
    }

    /**
     * Set fechaVerificada
     *
     * Fecha que es verificada por el laboratorio
     *
     * @parÃ¡metro Date $fechaVerificada
     * 
     * @return FechaVerificada
     */
    public function setFechaVerificada($fechaVerificada)
    {
        $this->fechaVerificada = ValidarDatos::validarFecha($fechaVerificada, $this->tabla, " Fecha a Verificar", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaVerificada
     *
     * @return null|Date
     */
    public function getFechaVerificada()
    {
        return $this->fechaVerificada;
    }

    /**
     * Set esIdonea
     *
     * Puede ser SI o NO
     *
     * @parÃ¡metro String $esIdonea
     * 
     * @return EsIdonea
     */
    public function setEsIdonea($esIdonea)
    {
        $this->esIdonea = ValidarDatos::validarAlfa($esIdonea, $this->tabla, " Muestra idónea SI / NO", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get esIdonea
     *
     * @return null|String
     */
    public function getEsIdonea()
    {
        return $this->esIdonea;
    }

    /**
     * Set observacionVerificacion
     *
     * ObservaciÃ³n en caso de no ser idonea
     *
     * @parÃ¡metro String $observacionVerificacion
     * 
     * @return ObservacionVerificacion
     */
    public function setObservacionVerificacion($observacionVerificacion)
    {
        $this->observacionVerificacion = ValidarDatos::validarAlfaEsp($observacionVerificacion, $this->tabla, " Observación Verificación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionVerificacion
     *
     * @return null|String
     */
    public function getObservacionVerificacion()
    {
        return $this->observacionVerificacion;
    }

    /**
     * Set noIdoneaAnalisis
     *
     * Declarada como no idÃ³nea luego de realizar ciertos anÃ¡lisis
     *
     * @parÃ¡metro String $noIdoneaAnalisis
     * 
     * @return NoIdoneaAnalisis
     */
    public function setNoIdoneaAnalisis($noIdoneaAnalisis)
    {
        $this->noIdoneaAnalisis = ValidarDatos::validarAlfa($noIdoneaAnalisis, $this->tabla, " Muestra no Idónea", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get noIdoneaAnalisis
     *
     * @return null|String
     */
    public function getNoIdoneaAnalisis()
    {
        return $this->noIdoneaAnalisis;
    }

    /**
     * Set noIdoneaAnalisis
     *
     * Declarada como no idÃ³nea luego de realizar ciertos anÃ¡lisis
     *
     * @parÃ¡metro String $noIdoneaAnalisis
     * 
     * @return NoIdoneaAnalisis
     */
    public function setFechaNoIdoneaAnalisis($fechaNoIdoneaAnalisis)
    {
        $this->fechaNoIdoneaAnalisis = ValidarDatos::validarFecha($fechaNoIdoneaAnalisis, $this->tabla, " Fecha no idonea analisis", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get noIdoneaAnalisis
     *
     * @return null|String
     */
    public function getFechaNoIdoneaAnalisis()
    {
        return $this->fechaNoIdoneaAnalisis;
    }

    /**
     * Set conservacionMuestra
     *
     * Indica como llego la muestra a la recepciÃ³n
     *
     * @parÃ¡metro String $conservacionMuestra
     * 
     * @return ConservacionMuestra
     */
    public function setConservacionMuestra($conservacionMuestra)
    {
        $this->conservacionMuestra = ValidarDatos::validarAlfa($conservacionMuestra, $this->tabla, " Conservación Muestra", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get conservacionMuestra
     *
     * @return null|String
     */
    public function getConservacionMuestra()
    {
        return $this->conservacionMuestra;
    }

    /**
     * Set fechaInicioAnalisis
     *
     * Fecha que inicia el anÃ¡lisis
     *
     * @parÃ¡metro Date $fechaInicioAnalisis
     * @return FechaInicioAnalisis
     */
    public function setFechaInicioAnalisis($fechaInicioAnalisis)
    {
        $this->fechaInicioAnalisis = ValidarDatos::validarFecha($fechaInicioAnalisis, $this->tabla, " Fecha Inicio Análisis", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaInicioAnalisis
     *
     * @return null|Date
     */
    public function getFechaInicioAnalisis()
    {
        return $this->fechaInicioAnalisis;
    }

    /**
     * Set fechaFinAnalisis
     *
     * Fecha que finaliza el anÃ¡lisis
     *
     * @parÃ¡metro Date $fechaFinAnalisis
     * @return FechaFinAnalisis
     */
    public function setFechaFinAnalisis($fechaFinAnalisis)
    {
        $this->fechaFinAnalisis = ValidarDatos::validarFecha($fechaFinAnalisis, $this->tabla, " Fecha fin Análisis", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinAnalisis
     *
     * @return null|Date
     */
    public function getFechaFinAnalisis()
    {
        return $this->fechaFinAnalisis;
    }

    /**
     * Set observacionAnalisis
     *
     * Observacion que ingresa el analista al registrar el resultado de analisis
     *
     * @parÃ¡metro String $observacionVerificacion
     * 
     * @return ObservacionAnalisis
     */
    public function setObservacionAnalisis($observacionAnalisis)
    {
        $this->observacionAnalisis = ValidarDatos::validarAlfaEsp($observacionAnalisis, $this->tabla, " Observación Análisis", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionAnalisis
     *
     * @return null|String
     */
    public function getObservacionAnalisis()
    {
        return $this->observacionAnalisis;
    }

    /**
     * Estado actual de la muestra
     * @return type
     */
    public function getEstadoActual()
    {
        return $this->estadoActual;
    }

    /**
     * Estado actual de la muestra
     * @param type $estadoActual
     * @return $this
     */
    public function setEstadoActual($estadoActual)
    {
        $this->estadoActual = ValidarDatos::validarAlfa($estadoActual, $this->tabla, " Estado actual de la muestra", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * El estado es APROBADO cuando el RT aprueba el resultado analizado caso contrario NO APROBADO
     * @return type
     */
    public function getEstadoAprobacion()
    {
        return $this->estadoAprobacion;
    }

    /**
     * El estado es APROBADO cuando el RT aprueba el resultado analizado caso contrario NO APROBADO
     * @param type $estadoAprobacion
     * @return $this
     */
    public function setEstadoAprobacion($estadoAprobacion)
    {
        $this->estadoAprobacion = ValidarDatos::validarAlfa($estadoAprobacion, $this->tabla, " Estado de aprobación", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Set observacionAnalisis
     *
     * Observacion que ingresa el analista al registrar el resultado de analisis
     *
     * @parÃ¡metro String $observacionVerificacion
     * 
     * @return ObservacionAnalisis
     */
    public function setObservacionAprobacion($observacionAprobacion)
    {
        $this->observacionAprobacion = ValidarDatos::validarAlfaEsp($observacionAprobacion, $this->tabla, " Observación Aprobación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionAnalisis
     *
     * @return null|String
     */
    public function getObservacionAprobacion()
    {
        return $this->observacionAprobacion;
    }

    /**
     * Set fechaToma
     *
     * Fecha de toma de reingreso de la meustra
     *
     * @parÃ¡metro Date $fechaToma
     * @return FechaToma
     */
    public function setFechaToma($fechaToma)
    {
        $this->fechaToma = ValidarDatos::validarFecha($fechaToma, $this->tabla, " Fecha toma reingreso", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinAnalisis
     *
     * @return null|Date
     */
    public function getFechaToma()
    {
        return $this->fechaToma;
    }

    /**
     * Set responsableToma
     *
     * Responsable de la toma de reingreso de la muestra
     *
     * @parÃ¡metro String $responsableToma
     * 
     * @return ResponsableToma
     */
    public function setResponsableToma($responsableToma)
    {
        $this->responsableToma = ValidarDatos::validarAlfa($responsableToma, $this->tabla, " Responsable toma", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get conservacionMuestra
     *
     * @return null|String
     */
    public function getResponsableToma()
    {
        return $this->responsableToma;
    }

    /**
     * Set fechaFinAlmacenamiento
     *
     * Fecha de fin de almacenamiento
     *
     * @parÃ¡metro Date $fechaFinAlmacenamiento
     * @return FechaFinAlamacenamiento
     */
    public function setFechaFinAlmacenamiento($fechaFinAlmacenamiento)
    {
        $this->fechaFinAlmacenamiento = ValidarDatos::validarFecha($fechaFinAlmacenamiento, $this->tabla, " Fecha toma reingreso", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinAlmacenamiento
     *
     * @return null|Date
     */
    public function getFechaFinAlmacenamiento()
    {
        return $this->fechaFinAlmacenamiento;
    }

    /**
     * Set fechaDesecho
     *
     * Fecha de registro del desecho
     *
     * @parÃ¡metro Date $FechaDesecho
     * @return FechaDesecho
     */
    public function setFechaDesecho($fechaDesecho)
    {
        $this->fechaDesecho = ValidarDatos::validarFecha($fechaDesecho, $this->tabla, " Fecha toma reingreso", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaFinAlmacenamiento
     *
     * @return null|Date
     */
    public function getFechaDesecho()
    {
        return $this->fechaDesecho;
    }
    
    /**
     * Set contadorNoAprobado
     *
     * Cuenta cada vez que el RT no aprueba.
     *
     * @parÃ¡metro Integer $contadorNoAprobado
     * @return ContadorNoAprobado
     */
    public function setContadorNoAprobado($contadorNoAprobado)
    {
        $this->contadorNoAprobado = (Integer) $contadorNoAprobado;
        return $this;
    }

    /**
     * Get contadorNoAprobado
     *
     * @return null|Integer
     */
    public function getContadorNoAprobado()
    {
        return $this->contadorNoAprobado;
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
     * @return RecepcionMuestrasModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
