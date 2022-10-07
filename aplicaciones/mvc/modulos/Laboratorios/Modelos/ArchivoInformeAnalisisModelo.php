<?php

/**
 * Modelo ArchivoInformeAnalisisModelo
 *
 * Este archivo se complementa con el archivo   ArchivoInformeAnalisisLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ArchivoInformeAnalisisModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;

class ArchivoInformeAnalisisModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria
     */
    protected $idArchivoInformeAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria tabla recepción de muestras
     */
    protected $idRecepcionMuestras;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id de la tabla informe
     */
    protected $idInforme;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Secuencial (PK) de la tabla archivos_informe_analisis
     */
    protected $idInformeAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Secuencial (PK) de la tabla firma_electronica
     */
    protected $idFirmaElectronica;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Clave primaria
     */
    protected $fkIdArchivoInformeAnalisis;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Este nombre es generado de forma automática por el sistema concatenando nombre cliente orden de trabajo + código formato del informe
     */
    protected $nombreInforme;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha que el cliente envía la solicitud, esta fecha inicia el proceso.
     */
    protected $fechaEnvio;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha de aprobación
     */
    protected $fechaAprobado;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha de la firma
     */
    protected $fechaFirma;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Dato booleano que identifica si el archivo ha sido firmado o no
     */
    protected $firmado;

    /**
     * @var String
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Dato booleano que identifica si el archivo ha sido descargado o no
     */
    protected $descargado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * id_nforme principal
     */
    protected $alcance;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * id del informe principal
     */
    protected $sustituto;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observación general del informe
     */
    protected $observacionGeneral;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado puede ser ACTIVO o INACTIVO
     */
    protected $estadoInforme;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observación del estado. En caso de ser anulado esta observación es obligatoria
     */
    protected $observacionEstado;

    /**
     * @var String
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Contiene la ruta del archivo a descargar
     */
    protected $rutaArchivo;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Nivel del nodo en el árbol
     */
    protected $nivel;

    /**
     * @var String
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * 
     */
    protected $rama;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden del nodo en arbol
     */
    protected $orden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Orden del nodo en arbol
     */
    protected $agruparPor;

    /**
     * Campos del formulario 
     * @var array 
     */
    Private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: archivo_informe_analisis
     * 
     */
    Private $tabla = "archivo_informe_analisis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_archivo_informe_analisis";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."archivo_informe_analisis_id_archivo_informe_analisis_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro  array|null $datos
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
     * @parámetro  string $name 
     * @parámetro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: ArchivoInformeAnalisisModelo. Propiedad especificada invalida: set' . $name);
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
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: ArchivoInformeAnalisisModelo. Propiedad especificada invalida: get' . $name);
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
        foreach ($datos as $key => $value)
        {
            $key_original = $key;
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string)
                {
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
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idArchivoInformeAnalisis
     *
     * Clave primaria
     *
     * @parámetro Integer $idArchivoInformeAnalisis
     * @return IdArchivoInformeAnalisis
     */
    public function setIdArchivoInformeAnalisis($idArchivoInformeAnalisis)
    {
        $this->idArchivoInformeAnalisis = (Integer) $idArchivoInformeAnalisis;
        return $this;
    }

    /**
     * Get idArchivoInformeAnalisis
     *
     * @return null|Integer
     */
    public function getIdArchivoInformeAnalisis()
    {
        return $this->idArchivoInformeAnalisis;
    }

    /**
     * Set idRecepcionMuestras
     *
     * Clave primaria tabla de recepción de muestras
     *
     * @parámetro Integer $idRecepcionMuestras
     * @return IdRecepcionMuestras
     */
    public function setIdRecepcionMuestras($idRecepcionMuestras)
    {
        $this->idRecepcionMuestras = (Integer) $idRecepcionMuestras;
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
     * Set idInforme
     *
     * Id de la tabla informe
     *
     * @parámetro Integer $idInforme
     * @return IdInforme
     */
    public function setIdInforme($idInforme)
    {
        $this->idInforme = (Integer) $idInforme;
        return $this;
    }

    /**
     * Get idInforme
     *
     * @return null|Integer
     */
    public function getIdInforme()
    {
        return $this->idInforme;
    }

    /**
     * Set idInformeAnalisis
     *
     * Secuencial (PK) de la tabla archivos_informe_analisis
     *
     * @parámetro Integer $idInformeAnalisis
     * @return IdInformeAnalisis
     */
    public function setIdInformeAnalisis($idInformeAnalisis)
    {
        $this->idInformeAnalisis = (Integer) $idInformeAnalisis;
        return $this;
    }

    /**
     * Get idInformeAnalisis
     *
     * @return null|Integer
     */
    public function getIdInformeAnalisis()
    {
        return $this->idInformeAnalisis;
    }

    /**
     * Set idFirmaElectronica
     *
     * Secuencial (PK) de la tabla firma_electronica
     *
     * @parámetro Integer $idFirmaElectronica
     * @return IdFirmaElectronica
     */
    public function setIdFirmaElectronica($idFirmaElectronica)
    {
        $this->idFirmaElectronica = (Integer) $idFirmaElectronica;
        return $this;
    }

    /**
     * Get idFirmaElectronica
     *
     * @return null|Integer
     */
    public function getIdFirmaElectronica()
    {
        return $this->idFirmaElectronica;
    }

    /**
     * Set fkIdArchivoInformeAnalisis
     *
     * Clave primaria
     *
     * @parámetro Integer $fkIdArchivoInformeAnalisis
     * @return FkIdArchivoInformeAnalisis
     */
    public function setFkIdArchivoInformeAnalisis($fkIdArchivoInformeAnalisis)
    {
        $this->fkIdArchivoInformeAnalisis = (Integer) $fkIdArchivoInformeAnalisis;
        return $this;
    }

    /**
     * Get fkIdArchivoInformeAnalisis
     *
     * @return null|Integer
     */
    public function getFkIdArchivoInformeAnalisis()
    {
        return $this->fkIdArchivoInformeAnalisis;
    }

    /**
     * Set nombreInforme
     *
     * Este nombre es generado de forma automática por el sistema concatenando nombre cliente orden de trabajo + código formato del informe
     *
     * @parámetro String $nombreInforme
     * @return NombreInforme
     */
    public function setNombreInforme($nombreInforme)
    {
        $this->nombreInforme = (String) $nombreInforme;
        return $this;
    }

    /**
     * Get nombreInforme
     *
     * @return null|String
     */
    public function getNombreInforme()
    {
        return $this->nombreInforme;
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
        $this->fechaCreacion = (String) $fechaCreacion;
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
     * Set fechaEnvio
     *
     * Fecha que el cliente envía la solicitud, esta fecha inicia el proceso.
     *
     * @parámetro Date $fechaEnvio
     * @return FechaEnvio
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = (String) $fechaEnvio;
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
     * Set fechaAprobado
     *
     * Fecha de aprobación
     *
     * @parámetro Date $fechaAprobado
     * @return FechaAprobado
     */
    public function setFechaAprobado($fechaAprobado)
    {
        $this->fechaAprobado = (String) $fechaAprobado;
        return $this;
    }

    /**
     * Get fechaAprobado
     *
     * @return null|Date
     */
    public function getFechaAprobado()
    {
        return $this->fechaAprobado;
    }

    /**
     * Set fechaFirma
     *
     * Fecha de la firma
     *
     * @parámetro Date $fechaFirma
     * @return FechaFirma
     */
    public function setFechaFirma($fechaFirma)
    {
        $this->fechaFirma = (String) $fechaFirma;
        return $this;
    }

    /**
     * Get fechaFirma
     *
     * @return null|Date
     */
    public function getFechaFirma()
    {
        return $this->fechaFirma;
    }

    /**
     * Set firmado
     *
     * Dato booleano que identifica si el archivo ha sido firmado o no
     *
     * @parámetro String $firmado
     * @return Firmado
     */
    public function setFirmado($firmado)
    {
        $this->firmado = (String) $firmado;
        return $this;
    }

    /**
     * Get firmado
     *
     * @return null|String
     */
    public function getFirmado()
    {
        return $this->firmado;
    }

    /**
     * Set descargado
     *
     * Dato booleano que identifica si el archivo ha sido descargado o no
     *
     * @parámetro String $descargado
     * @return Descargado
     */
    public function setDescargado($descargado)
    {
        $this->descargado = (String) $descargado;
        return $this;
    }

    /**
     * Get descargado
     *
     * @return null|String
     */
    public function getDescargado()
    {
        return $this->descargado;
    }

    /**
     * Set alcance
     *
     * id_nforme principal
     *
     * @parámetro String $alcance
     * @return Alcance
     */
    public function setAlcance($alcance)
    {
        $this->alcance = (String) $alcance;
        return $this;
    }

    /**
     * Get alcance
     *
     * @return null|String
     */
    public function getAlcance()
    {
        return $this->alcance;
    }

    /**
     * Set sustituto
     *
     * id del informe principal
     *
     * @parámetro String $sustituto
     * @return Sustituto
     */
    public function setSustituto($sustituto)
    {
        $this->sustituto = (String) $sustituto;
        return $this;
    }

    /**
     * Get sustituto
     *
     * @return null|String
     */
    public function getSustituto()
    {
        return $this->sustituto;
    }

    /**
     * Set observacionGeneral
     *
     * Observación general del informe
     *
     * @parámetro String $observacionGeneral
     * @return ObservacionGeneral
     */
    public function setObservacionGeneral($observacionGeneral)
    {
        $this->observacionGeneral = (String) $observacionGeneral;
        return $this;
    }

    /**
     * Get observacionGeneral
     *
     * @return null|String
     */
    public function getObservacionGeneral()
    {
        return $this->observacionGeneral;
    }

    /**
     * Set estadoInforme
     *
     * Estado puede ser ACTIVO o INACTIVO
     *
     * @parámetro String $estadoInforme
     * @return EstadoInforme
     */
    public function setEstadoInforme($estadoInforme)
    {
        $this->estadoInforme = (String) $estadoInforme;
        return $this;
    }

    /**
     * Get estadoInforme
     *
     * @return null|String
     */
    public function getEstadoInforme()
    {
        return $this->estadoInforme;
    }

    /**
     * Set observacionEstado
     *
     * Observación del estado. En caso de ser anulado esta observación es obligatoria
     *
     * @parámetro String $observacionEstado
     * @return ObservacionEstado
     */
    public function setObservacionEstado($observacionEstado)
    {
        $this->observacionEstado = (String) $observacionEstado;
        return $this;
    }

    /**
     * Get observacionEstado
     *
     * @return null|String
     */
    public function getObservacionEstado()
    {
        return $this->observacionEstado;
    }

    /**
     * Set rutaArchivo
     *
     * Contiene la ruta del archivo a descargar
     *
     * @parámetro String $rutaArchivo
     * @return RutaArchivo
     */
    public function setRutaArchivo($rutaArchivo)
    {
        $this->rutaArchivo = (String) $rutaArchivo;
        return $this;
    }

    /**
     * Get rutaArchivo
     *
     * @return null|String
     */
    public function getRutaArchivo()
    {
        return $this->rutaArchivo;
    }

    /**
     * Set nivel
     *
     * Nivel del nodo en el árbol
     *
     * @parámetro Integer $nivel
     * @return Nivel
     */
    public function setNivel($nivel)
    {
        $this->nivel = (Integer) $nivel;
        return $this;
    }

    /**
     * Get nivel
     *
     * @return null|Integer
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set rama
     *
     * Contiene los id padres separados por un carácter especial
     *
     * @parámetro String $rama
     * @return Rama
     */
    public function setRama($rama)
    {
        $this->rama = (String) $rama;
        return $this;
    }

    /**
     * Get rama
     *
     * @return null|String
     */
    public function getRama()
    {
        return $this->rama;
    }

    /**
     * Set orden
     *
     * Orden del nodo en arbol
     *
     * @parámetro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = (Integer) $orden;
        return $this;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Indica si se debe agrupar por ANALISIS O MUESTRA
     * @return type
     */
    public function getAgruparPor()
    {
        return $this->agruparPor;
    }

    public function setAgruparPor($agruparPor)
    {
        $this->agruparPor = $agruparPor;
        return $this;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
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
     * @param string Where|array $where
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
     * @param  int $id
     * @return ArchivoInformeAnalisisModelo
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
