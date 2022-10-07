<?php

/**
 * Modelo ActaBajaModelo
 *
 * Este archivo se complementa con el archivo   ActabajaLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ActaBajaModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ActabajaModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla acta_baja
     */
    protected $idActaBaja;

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
     * Clave primaria de la tabla saldos_laboratorio
     */
    protected $idSaldoLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla direccion
     */
    protected $idDireccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del archivo de acta generada
     */
    protected $nombreActa;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Contenido del acta
     */
    protected $contenido;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de registro
     */
    protected $fechaRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Responsable de creacion  la acta
     */
    protected $responsableCrea;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Responsable de aprobar la acta
     */
    protected $responsableAprueba;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado de la acta
     */
    protected $estadoActa;
    
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observacion
     */
    protected $observacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado de la acta
     */
    protected $estado;

    /**
     * Campos del formulario 
     * @var array 
     */
    Private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_reactivos";

    /**
     * Nombre de la tabla: acta_baja
     * 
     */
    Private $tabla = "acta_baja";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_acta_baja";

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
            throw new \Exception('Clase Modelo: ActaBajaModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ActaBajaModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idActaBaja
     *
     * Clave primaria de la tabla acta_baja
     *
     * @parámetro Integer $idActaBaja
     * @return IdActaBaja
     */
    public function setIdActaBaja($idActaBaja)
    {
        $this->idActaBaja = (Integer) $idActaBaja;
        return $this;
    }

    /**
     * Get idActaBaja
     *
     * @return null|Integer
     */
    public function getIdActaBaja()
    {
        return $this->idActaBaja;
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
     * Set idSaldoLaboratorio
     *
     * Clave primaria de la tabla saldos_laboratorio
     *
     * @parámetro Integer $idSaldoLaboratorio
     * @return IdSaldoLaboratorio
     */
    public function setIdSaldoLaboratorio($idSaldoLaboratorio)
    {
        $this->idSaldoLaboratorio = (Integer) $idSaldoLaboratorio;
        return $this;
    }

    /**
     * Get idSaldoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdSaldoLaboratorio()
    {
        return $this->idSaldoLaboratorio;
    }

    /**
     * Set idDireccion
     *
     * Clave primaria de la tabla direccion
     *
     * @parámetro Integer $idDireccion
     * @return IdDireccion
     */
    public function setIdDireccion($idDireccion)
    {
        $this->idDireccion = (Integer) $idDireccion;
        return $this;
    }

    /**
     * Get idDireccion
     *
     * @return null|Integer
     */
    public function getIdDireccion()
    {
        return $this->idDireccion;
    }

    /**
     * Set nombreActa
     *
     * Nombre del archivo de acta generada
     *
     * @parámetro String $nombreActa
     * @return NombreActa
     */
    public function setNombreActa($nombreActa)
    {
        $this->nombreActa = (String) $nombreActa;
        return $this;
    }

    /**
     * Get nombreActa
     *
     * @return null|String
     */
    public function getNombreActa()
    {
        return $this->nombreActa;
    }

    /**
     * Set contenido
     *
     * Contenido del acta
     *
     * @parámetro String $contenido
     * @return Contenido
     */
    public function setContenido($contenido)
    {
        $this->contenido = (String) $contenido;
        return $this;
    }

    /**
     * Get contenido
     *
     * @return null|String
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set fechaRegistro
     *
     * Fecha de registro
     *
     * @parámetro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = (String) $fechaRegistro;
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
     * Set responsableCrea
     *
     * Responsable de creacion  la acta
     *
     * @parámetro String $responsableCrea
     * @return ResponsableCrea
     */
    public function setResponsableCrea($responsableCrea)
    {
        $this->responsableCrea = (String) $responsableCrea;
        return $this;
    }

    /**
     * Get responsableCrea
     *
     * @return null|String
     */
    public function getResponsableCrea()
    {
        return $this->responsableCrea;
    }

    /**
     * Set responsableAprueba
     *
     * Responsable de aprobar la acta
     *
     * @parámetro String $responsableAprueba
     * @return ResponsableAprueba
     */
    public function setResponsableAprueba($responsableAprueba)
    {
        $this->responsableAprueba = (String) $responsableAprueba;
        return $this;
    }

    /**
     * Get responsableAprueba
     *
     * @return null|String
     */
    public function getResponsableAprueba()
    {
        return $this->responsableAprueba;
    }

    /**
     * Set estadoActa
     *
     * Estado de la acta
     *
     * @parámetro String $estadoActa
     * @return EstadoActa
     */
    public function setEstadoActa($estadoActa)
    {
        $this->estadoActa = (String) $estadoActa;
        return $this;
    }

    /**
     * Get estadoActa
     *
     * @return null|String
     */
    public function getEstadoActa()
    {
        return $this->estadoActa;
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
     * Set estado
     *
     * Estado de la acta
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (String) $estado;
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
     * @return ActaBajaModelo
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
