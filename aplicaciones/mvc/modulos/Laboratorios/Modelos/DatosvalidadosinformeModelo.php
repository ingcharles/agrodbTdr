<?php

/**
 * Modelo DatosvalidadosinformeModelo
 *
 * Este archivo se complementa con el archivo   DatosvalidadosinformeLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DatosvalidadosinformeModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DatosvalidadosinformeModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla datos_validados_informe
     */
    protected $idDatosvalidadosinforme;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria
     */
    protected $idRecepcionMuestras;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla de campos_resultados_informes
     */
    protected $idCamposResultadosInf;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla archivos_informe_analisis
     */
    protected $idInformeAnalisis;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Código del campo
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor del campo
     */
    protected $valor;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Etiqueta del campos del informe
     */
    protected $etiqueta;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Version del informe
     */
    protected $version;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de modificación
     */
    protected $fechaModificacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observación
     */
    protected $observacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del dato para la orden técnica
     */
    protected $estadoOt;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo campo
     */
    protected $tipo;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden a desplegarse en el informe
     */
    protected $orden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del campo para el informe
     */
    protected $estadoInf;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Usuario de la aplicación
     */
    protected $usuarioApl;

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
     * Nombre de la tabla: datos_validados_informe
     * 
     */
    Private $tabla = "datos_validados_informe";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_datos_validados_informe";

    /**
     * Secuencia
     */
    private $secuencial = "";

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
            throw new \Exception('Clase Modelo: DatosvalidadosinformeModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DatosvalidadosinformeModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDatosvalidadosinforme
     *
     * Clave primaria de la tabla datos_validados_informe
     *
     * @parámetro Integer $idDatosvalidadosinforme
     * @return IdDatosvalidadosinforme
     */
    public function setIdDatosvalidadosinforme($idDatosvalidadosinforme)
    {
        $this->idDatosvalidadosinforme = (Integer) $idDatosvalidadosinforme;
        return $this;
    }

    /**
     * Get idDatosvalidadosinforme
     *
     * @return null|Integer
     */
    public function getIdDatosvalidadosinforme()
    {
        return $this->idDatosvalidadosinforme;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (Integer) $idLaboratorio;
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
     * Set idRecepcionMuestras
     *
     * Clave primaria
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
     * Set idCamposResultadosInf
     *
     * Identificador de la tabla de campos_resultados_informes
     *
     * @parámetro Integer $idCamposResultadosInf
     * @return IdCamposResultadosInf
     */
    public function setIdCamposResultadosInf($idCamposResultadosInf)
    {
        $this->idCamposResultadosInf = (Integer) $idCamposResultadosInf;
        return $this;
    }

    /**
     * Get idCamposResultadosInf
     *
     * @return null|Integer
     */
    public function getIdCamposResultadosInf()
    {
        return $this->idCamposResultadosInf;
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
     * Set codigo
     *
     * Código del campo del informe
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = (String) $codigo;
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
     * Set valor
     *
     * Valor del campo del  informe
     *
     * @parámetro String $valor
     * @return Valor
     */
    public function setValor($valor)
    {
        $this->valor = (String) $valor;
        return $this;
    }

    /**
     * Get valor
     *
     * @return null|String
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set etiqueta
     *
     * Etiqueta del campos del informe
     *
     * @parámetro String $etiqueta
     * @return Etiqueta
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = (String) $etiqueta;
        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return null|String
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set version
     *
     * Versión del informe
     *
     * @parámetro Integer $version
     * @return Version
     */
    public function setVersion($version)
    {
        $this->version = (Integer) $version;
        return $this;
    }

    /**
     * Get version
     *
     * @return null|Integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set fechaModificacion
     *
     * Fecha de modificación
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (String) $fechaModificacion;
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
     * Set observacion
     *
     * Observación del cambio
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = (String) $observacion;
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set estadoOt
     *
     * Estado del campo para la orden de trabajo
     *
     * @parámetro String $estadoOt
     * @return EstadoOt
     */
    public function setEstadoOt($estadoOt)
    {
        $this->estadoOt = (String) $estadoOt;
        return $this;
    }

    /**
     * Get estadoOt
     *
     * @return null|String
     */
    public function getEstadoOt()
    {
        return $this->estadoOt;
    }

    /**
     * Set tipo
     *
     * Tipo de campo
     *
     * @parámetro String $tipo
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = (String) $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set orden
     *
     * Orden al desplegarse en el informe
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
     * Set estadoInf
     *
     * Estado del campo para el informe
     *
     * @parámetro String $estadoInf
     * @return EstadoInf
     */
    public function setEstadoInf($estadoInf)
    {
        $this->estadoInf = (String) $estadoInf;
        return $this;
    }

    /**
     * Get estadoInf
     *
     * @return null|String
     */
    public function getEstadoInf()
    {
        return $this->estadoInf;
    }

    /**
     * Set usuarioApl
     *
     * Usuario de la aplicación
     *
     * @parámetro String $usuarioApl
     * @return UsuarioApl
     */
    public function setUsuarioApl($usuarioApl)
    {
        $this->usuarioApl = (String) $usuarioApl;
        return $this;
    }

    /**
     * Get usuarioApl
     *
     * @return null|String
     */
    public function getUsuarioApl()
    {
        return $this->usuarioApl;
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
     * @return DatosvalidadosinformeModelo
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
