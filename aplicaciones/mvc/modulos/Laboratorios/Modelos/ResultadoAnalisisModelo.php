<?php

/**
 * Modelo ResultadoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   ResultadoAnalisisLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ResultadoAnalisisModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ResultadoAnalisisModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id
     */
    protected $idResultadoAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id de Campos Resultados Informes
     */
    protected $idCamposResultadosInformes;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Id
     */
    protected $idRecepcionMuestras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Ideficador de usuario
     */
    protected $identificador;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Resultado
     */
    protected $resultadoAnalisis;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Odservacion
     */
    protected $observacionResultado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de informe
     */
    protected $tipoInforme;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nuevo Analisis
     */
    protected $nuevoAnalisis;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Aprobado
     */
    protected $estadoAprobacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Odservacion
     */
    protected $observacionAprobacion;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: resultado_analisis
     * 
     */
    Private $tabla = "resultado_analisis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_resultado_analisis";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."resultado_analisis_id_resultado_analisis_seq';

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
            throw new \Exception('Clase Modelo: ResultadoAnalisisModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ResultadoAnalisisModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idResultadoAnalisis
     *
     * Identificador de la tabla del resultado de analisis
     *
     * @parámetro Integer $idResultadoAnalisis
     * @return IdResultadoAnalisis
     */
    public function setIdResultadoAnalisis($idResultadoAnalisis)
    {
        $this->idResultadoAnalisis = (Integer) $idResultadoAnalisis;
        return $this;
    }

    /**
     * Get idResultadoAnalisis
     *
     * @return null|Integer
     */
    public function getIdResultadoAnalisis()
    {
        return $this->idResultadoAnalisis;
    }

    /**
     * Set idCamposResultadosInformes
     *
     * Identificador de la tabla de campos_resultados_inf
     *
     * @parámetro Integer $idCamposResultadosInformes
     * @return IdCamposResultadosInformes
     */
    public function setIdCamposResultadosInf($idCamposResultadosInf)
    {

        $this->idCamposResultadosInf = (Integer) $idCamposResultadosInf;
        return $this;
    }

    /**
     * Get idCamposResultadosInformes
     *
     * @return null|Integer
     */
    public function getIdCamposResultadosInf()
    {
        return $this->idCamposResultadosInf;
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
     * Set identificador
     *
     * Cedula de identidad o pasaporte.
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = ValidarDatos::validarAlfa($identificador, $this->tabla, " Identificador", self::NO_REQUERIDO, 13);
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
     * Set resultado
     *
     * Valor del campo que ingresa el usuario
     *
     * @parámetro String $resultado
     * @return Resultado
     */
    public function setResultadoAnalisis($resultado)
    {
        $this->resultadoAnalisis = ValidarDatos::validarAlfaEsp($resultado, $this->tabla, "Resultado del Análisis", self::REQUERIDO, 256);
        return $this;
    }

    /**
     * Get resultado
     *
     * @return null|String
     */
    public function getResultadoAnalisis()
    {
        return $this->resultadoAnalisis;
    }

    /**
     * Set observacionResultado
     *
     * Cada respuesta puede tener observaciones
     *
     * @parámetro String $observacionResultado
     * @return ObservacionResultado
     */
    public function setObservacionResultado($observacionResultado)
    {
        $this->observacionResultado = ValidarDatos::validarAlfaEsp($observacionResultado, $this->tabla, " Observaciones del Resultado", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionResultado
     *
     * @return null|String
     */
    public function getObservacionResultado()
    {
        return $this->observacionResultado;
    }

    /**
     * Set tipoInforme
     *
     * Identifica si el resultado es parte de un informe principal o es un alcance o sustituye a otro
     *
     * @parámetro String $tipoInforme
     * @return TipoInforme
     */
    public function setTipoInforme($tipoInforme)
    {
        $this->tipoInforme = ValidarDatos::validarAlfa($tipoInforme, $this->tabla, " Tipo de Informe", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get tipoInforme
     *
     * @return null|String
     */
    public function getTipoInforme()
    {
        return $this->tipoInforme;
    }

    /**
     * Set nuevoAnalisis
     *
     * Indica SI requiere de un nuevo análisis
     *
     * @parámetro String $nuevoAnalisis
     * @return NuevoAnalisis
     */
    public function setNuevoAnalisis($nuevoAnalisis)
    {
        $this->nuevoAnalisis = ValidarDatos::validarAlfa($nuevoAnalisis, $this->tabla, " Nuevo Análisis", self::REQUERIDO, 2);
        return $this;
    }

    /**
     * Get nuevoAnalisis
     *
     * @return null|String
     */
    public function getNuevoAnalisis()
    {
        return $this->nuevoAnalisis;
    }

    /**
     * Set aprobado
     *
     * Indica si es aprobado o no por el responsable del tecnico
     *
     * @parámetro String $aprobado
     * @return Aprobado
     */
    public function setEstadoAprobacion($aprobado)
    {
        $this->estadoAprobacion = ValidarDatos::validarAlfa($aprobado, $this->tabla, " Estado de la Aprobado de resultado", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get aprobado
     *
     * @return null|String
     */
    public function getEstadoAprobacion()
    {
        return $this->estadoAprobacion;
    }

    /**
     * Set observacionAprobacion
     *
     * Esta observación es requerida cuando el resultado no es aceptado
     *
     * @parámetro String $observacionAprobacion
     * @return ObservacionAprobacion
     */
    public function setObservacionAprobacion($observacionAprobacion)
    {
        $this->observacionAprobacion = ValidarDatos::validarAlfaEsp($observacionAprobacion, $this->tabla, "Observación a estado de aprobación", self::NO_REQUERIDO, 1024);
        return $this;
    }

    /**
     * Get observacionAprobacion
     *
     * @return null|String
     */
    public function getObservacionAprobacion()
    {
        return $this->observacionAprobacion;
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
     * @return ResultadoAnalisisModelo
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
