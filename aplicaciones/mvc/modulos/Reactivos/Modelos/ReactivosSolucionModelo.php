<?php

/**
 * Modelo ReactivosSolucionModelo
 *
 * Este archivo se complementa con el archivo   ReactivosSolucionLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ReactivosSolucionModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use Agrodb\Core\Constantes;

class ReactivosSolucionModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoSolucion;
    
    protected $idSolucion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidadRequerida;
    
    protected $estadoRegistroRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $observacion;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_reactivos";

    /**
     * Nombre de la tabla: solicitud_requerimiento
     * 
     */
    Private $tabla = "reactivos_solucion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_reactivo_solucion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."reactivos_solucion_id_reactivo_solucion_seq';

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
            throw new \Exception('Clase Modelo: ReactivosSolucionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ReactivosSolucionModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idReactivoSolucion
     *
     *
     *
     * @parámetro Integer $idReactivoSolucion
     * @return idReactivoSolucion
     */
    public function setIdReactivoSolucion($idReactivoSolucion)
    {
        $this->idReactivoSolucion = (Integer) $idReactivoSolucion;
        return $this;
    }

    /**
     * Get idReactivoSolucion
     *
     * @return null|Integer
     */
    public function getIdReactivoSolucion()
    {
        return $this->idReactivoSolucion;
    }

    /**
     * Set idReactivoLaboratorio
     *
     *
     *
     * @parámetro Integer $idReactivoLaboratorio
     * @return IdReactivoLaboratorio
     */
    public function setIdReactivoLaboratorio($idReactivoLaboratorio)
    {
        $this->idReactivoLaboratorio = (Integer) $idReactivoLaboratorio;
        return $this;
    }

    /**
     * Get idReactivoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdReactivoLaboratorio()
    {
        return $this->idReactivoLaboratorio;
    }
    
    /**
     * Set idReactivoLaboratorio
     *
     *
     *
     * @parámetro Integer $idReactivoLaboratorio
     * @return IdReactivoLaboratorio
     */
    public function setIdSolucion($idSolucion)
    {
        $this->idSolucion = (Integer) $idSolucion;
        return $this;
    }

    /**
     * Get idReactivoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdSolucion()
    {
        return $this->idSolucion;
    }

    /**
     * Set cantidadRequerida
     *
     *
     *
     * @parámetro Decimal $cantidadRequerida
     * @return CantidadRequerida
     */
    public function setCantidadRequerida($cantidadRequerida)
    {
        $this->cantidadRequerida = ValidarDatos::validarDecimal($cantidadRequerida, $this->tabla, " Cantidad Solicitada", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidadRequerida
     *
     * @return null|Decimal
     */
    public function getCantidadRequerida()
    {
        return $this->cantidadRequerida;
    }
    
    /**
     * Set observacion
     *
     * observacion
     *
     * @parámetro String $observacion
     * @return observacion
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
     * Set estadoRegistro
     *
     *
     *
     * @parÃ¡metro String $estadoRegistro
     * @return EstadoRegistro
     */
    public function setEstadoRegistro($estadoRegistro) {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, "EstadoRegistro", self::REQUERIDO, 8);
        return $this;
    }

    /**
     * Get estadoRegistro
     *
     * @return null|String
     */
    public function getEstadoRegistro() {
        return $this->estadoRegistro;
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
     * @return ReactivosSolucionModelo
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
