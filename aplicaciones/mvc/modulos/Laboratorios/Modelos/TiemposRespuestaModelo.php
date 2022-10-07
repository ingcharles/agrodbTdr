<?php

/**
 * Modelo TiemposRespuestaModelo
 *
 * Este archivo se complementa con el archivo   TiemposRespuestaLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       TiemposRespuestaModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class TiemposRespuestaModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria para la tabla tiempos_respuesta
     */
    protected $idTiemposRespuesta;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idDireccion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * id_laboratorios_provincia
     */
    protected $idLaboratoriosProvincia;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla servicio
     */
    protected $estadoRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Identifica la dirección de diagnóstico
     */
    protected $condicion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Permite aplicar la condición de acuerdo al número de muestras
     */
    protected $tiempoRespuesta;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * El tiempo de respuesta está determinado en días
     */
    protected $tipoUsuario;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * El tiempo de respuesta puede aplicar a todos los usuarios TODOS, USUARIOS INTERNO O USUARIOS EXTERNOS
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * LDS -diagnóstico rápido
     */
    protected $idServicio;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Detalle del tiempo de respuesta
     */
    protected $descripcion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Indica el estado el registro
     */
    protected $tipoLaboratorio;

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
     * Nombre de la tabla: tiempos_respuesta
     * 
     */
    Private $tabla = "tiempos_respuesta";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_tiempos_respuesta";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."tiempos_respuesta_id_tiempos_respuesta_seq';

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
            throw new \Exception('Clase Modelo: TiemposRespuestaModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: TiemposRespuestaModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idTiemposRespuesta
     *
     * Clave primaria para la tabla tiempos_respuesta
     *
     * @parámetro Integer $idTiemposRespuesta
     * @return IdTiemposRespuesta
     */
    public function setIdTiemposRespuesta($idTiemposRespuesta)
    {
        $this->idTiemposRespuesta = (Integer) $idTiemposRespuesta;
        return $this;
    }

    /**
     * Get idTiemposRespuesta
     *
     * @return null|Integer
     */
    public function getIdTiemposRespuesta()
    {
        return $this->idTiemposRespuesta;
    }

    /**
     * Set idDireccion
     *
     * Secuencial clave primaria de la tabla laboratorios
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
     * Set idLaboratoriosProvincia
     *
     * id_laboratorios_provincia
     *
     * @parámetro Integer $idLaboratoriosProvincia
     * @return IdLaboratoriosProvincia
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia)
    {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
    }

    /**
     * Get idLaboratoriosProvincia
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvincia()
    {
        return $this->idLaboratoriosProvincia;
    }

    /**
     * Set estadoRegistro
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parámetro String $estadoRegistro
     * @return EstadoRegistro
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = (String) $estadoRegistro;
        return $this;
    }

    /**
     * Get estadoRegistro
     *
     * @return null|String
     */
    public function getEstadoRegistro()
    {
        return $this->estadoRegistro;
    }

    /**
     * Set condicion
     *
     * Identifica la dirección de diagnóstico
     *
     * @parámetro String $condicion
     * @return Condicion
     */
    public function setCondicion($condicion)
    {
        $this->condicion = (String) $condicion;
        return $this;
    }

    /**
     * Get condicion
     *
     * @return null|String
     */
    public function getCondicion()
    {
        return $this->condicion;
    }

    /**
     * Set tiempoRespuesta
     *
     * Permite aplicar la condición de acuerdo al número de muestras
     *
     * @parámetro Integer $tiempoRespuesta
     * @return TiempoRespuesta
     */
    public function setTiempoRespuesta($tiempoRespuesta)
    {
        $this->tiempoRespuesta = (Integer) $tiempoRespuesta;
        return $this;
    }

    /**
     * Get tiempoRespuesta
     *
     * @return null|Integer
     */
    public function getTiempoRespuesta()
    {
        return $this->tiempoRespuesta;
    }

    /**
     * Set tipoUsuario
     *
     * El tiempo de respuesta está determinado en días
     *
     * @parámetro String $tipoUsuario
     * @return TipoUsuario
     */
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = (String) $tipoUsuario;
        return $this;
    }

    /**
     * Get tipoUsuario
     *
     * @return null|String
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    /**
     * Set idLaboratorio
     *
     * El tiempo de respuesta puede aplicar a todos los usuarios TODOS, USUARIOS INTERNO O USUARIOS EXTERNOS
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
     * Set idServicio
     *
     * LDS -diagnóstico rápido
     *
     * @parámetro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
        $this->idServicio = ValidarDatos::validarEntero($idServicio, $this->tabla, " Servicio", self::REQUERIDO, 0);
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
     * Set descripcion
     *
     * Detalle del tiempo de respuesta
     *
     * @parámetro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = (String) $descripcion;
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return null|String
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set tipoLaboratorio
     *
     * Indica el estado el registro
     *
     * @parámetro String $tipoLaboratorio
     * @return TipoLaboratorio
     */
    public function setTipoLaboratorio($tipoLaboratorio)
    {
        $this->tipoLaboratorio = (String) $tipoLaboratorio;
        return $this;
    }

    /**
     * Get tipoLaboratorio
     *
     * @return null|String
     */
    public function getTipoLaboratorio()
    {
        return $this->tipoLaboratorio;
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
     * @return TiemposRespuestaModelo
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
