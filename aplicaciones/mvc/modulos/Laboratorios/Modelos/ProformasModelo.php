<?php

/**
 * Modelo ProformasModelo
 *
 * Este archivo se complementa con el archivo   ProformasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ProformasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProformasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador primario de la tabla Proformas
     * ID
     */
    protected $idProforma;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id de la persona
     */
    protected $idPersona;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Codigo de la proforma
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del Laboratorio
     */
    protected $nomLaboratorio;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de emision de la proforma
     */
    protected $fechaEmision;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Codigo auxiliar para control de proformas
     */
    protected $codigoAuxiliar;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Número de muestras, ingresado por el usuario
     */
    protected $numeroMuestras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tiempo estimado de acuerdo a los servicios seleccionados es el mayo de todos
     */
    protected $tiempoEstimado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Parametros necesarios que aparescan el la proforma
     */
    protected $parametrosImprimir;

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
     * Nombre de la tabla: proformas
     * 
     */
    Private $tabla = "proformas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_proforma";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."proformas_id_proforma_seq';

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
            throw new \Exception('Clase Modelo: ProformasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ProformasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idProforma
     *
     * Identificador primario de la tabla Proformas
     *
     * @parámetro Integer $idProforma
     * @return IdProforma
     */
    public function setIdProforma($idProforma)
    {
        $this->idProforma = (Integer) $idProforma;
        return $this;
    }

    /**
     * Get idProforma
     *
     * @return null|Integer
     */
    public function getIdProforma()
    {
        return $this->idProforma;
    }

    /**
     * Set idPersona
     *
     * Id de la persona
     *
     * @parámetro Integer $idPersona
     * @return IdPersona
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = (Integer) $idPersona;
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return null|Integer
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set codigo
     *
     * Codigo de la proforma
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
     * Set nomLaboratorio
     *
     * Nombre del Laboratorio
     *
     * @parámetro String $nomLaboratorio
     * @return NomLaboratorio
     */
    public function setNomLaboratorio($nomLaboratorio)
    {
        $this->nomLaboratorio = (String) $nomLaboratorio;
        return $this;
    }

    /**
     * Get nomLaboratorio
     *
     * @return null|String
     */
    public function getNomLaboratorio()
    {
        return $this->nomLaboratorio;
    }

    /**
     * Set fechaEmision
     *
     * Fecha de emision de la proforma
     *
     * @parámetro Date $fechaEmision
     * @return FechaEmision
     */
    public function setFechaEmision($fechaEmision)
    {
        $this->fechaEmision = (String) $fechaEmision;
        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return null|Date
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set codigoAuxiliar
     *
     * Codigo para el control de proformas
     *
     * @parámetro String $codigoAuxiliar
     * @return CodigoAuxiliar
     */
    public function setCodigoAuxiliar($codigoAuxiliar)
    {
        $this->codigoAuxiliar = (String) $codigoAuxiliar;
        return $this;
    }

    /**
     * Get codigoAuxiliar
     *
     * @return null|String
     */
    public function getCodigoAuxiliar()
    {
        return $this->codigoAuxiliar;
    }

    /**
     * Tiempo estimado de acuerdo a los servicios será tomado el mayor
     * @return type
     */
    public function getTiempoEstimado()
    {
        return $this->tiempoEstimado;
    }

    /**
     * Tiempo estimado de acuerdo a los servicios será tomado el mayor
     * @param type $tiempoEstimado
     * @return \Agrodb\Laboratorios\Modelos\ProformasModelo
     */
    public function setTiempoEstimado($tiempoEstimado)
    {
        $this->tiempoEstimado = $tiempoEstimado;
        return $this;
    }

    /**
     * Número de muestras ingresadas por el usuario
     * Número de muestras
     * @return type
     */
    public function getNumeroMuestras()
    {
        return $this->numeroMuestras;
    }

    /**
     * Número de muestras ingresadas por el usuario
     * @param type $numeroMuestras
     * @return \Agrodb\Laboratorios\Modelos\ProformasModelo
     */
    public function setNumeroMuestras($numeroMuestras)
    {
        $this->numeroMuestras = $numeroMuestras;
        return $this;
    }

    /**
     * Parametros necesarios que se requieren que aparescan en la proforma
     * @return type
     */
    public function getParametrosImprimir()
    {
        return $this->parametrosImprimir;
    }

    /**
     * 
     * @param type $parametrosImprimir
     * @return \Agrodb\Laboratorios\Modelos\ProformasModelo
     */
    public function setParametrosImprimir($parametrosImprimir)
    {
        $this->parametrosImprimir = $parametrosImprimir;
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
     * @return ProformasModelo
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
