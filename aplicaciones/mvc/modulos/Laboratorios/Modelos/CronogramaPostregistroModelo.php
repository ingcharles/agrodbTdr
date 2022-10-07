<?php

/**
 * Modelo CronogramaPostregistroModelo
 *
 * Este archivo se complementa con el archivo   CronogramaPostregistroLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       CronogramaPostregistroModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CronogramaPostregistroModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Identificador de la tabla
     */
    protected $idCronogramaPostregistro;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * AÃ±o que le corresponde el cronograma
     */
    protected $anio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * $fechaInicio que le corresponde el cronograma
     */
    protected $fechaInicio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * $fechaFin que le corresponde el cronograma
     */
    protected $fechaFin;

  
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del registro
     */
    protected $estadoRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * ObservaciÃ³n
     */
    protected $observacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Ingrediente activo
     */
    protected $ingredienteActivo;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * DirecciÃ³n de diagnÃ³stico
     */
    protected $idDireccion;

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
     * Nombre de la tabla: cronograma_postregistro
     * 
     */
    Private $tabla = "cronograma_postregistro";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_cronograma_postregistro";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."cronograma_postregistro_id_cronograma_postregistro_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
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
     * @parÃ¡metro  string $name 
     * @parÃ¡metro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: CronogramaPostregistroModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: CronogramaPostregistroModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parÃ¡metro  array $datos 
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
     * Nombre del esquema del mÃ³dulo 
     *
     * @parÃ¡metro $esquema
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
     * Set idCronogramaPostregistro
     *
     * Identificador de la tabla
     *
     * @parÃ¡metro Integer $idCronogramaPostregistro
     * @return IdCronogramaPostregistro
     */
    public function setIdCronogramaPostregistro($idCronogramaPostregistro)
    {
        if (empty($idCronogramaPostregistro))
        {
            $idCronogramaPostregistro = "No informa";
        }
        $this->idCronogramaPostregistro = (Integer) $idCronogramaPostregistro;
        return $this;
    }

    /**
     * Get idCronogramaPostregistro
     *
     * @return null|Integer
     */
    public function getIdCronogramaPostregistro()
    {
        return $this->idCronogramaPostregistro;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
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
     * Set anio
     *
     * AÃ±o que le corresponde el cronograma
     *
     * @parÃ¡metro String $anio
     * @return Anio
     */
    public function setAnio($anio)
    {
        $this->anio = ValidarDatos::validarAlfa($anio, $this->tabla, "Año", self::REQUERIDO, 4);
        return $this;
    }

    /**
     * Get anio
     *
     * @return null|String
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Fecha inicio de cronograma
     * @return type
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Fecha fin del cronograma
     * @return type
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Fecha fin del cronograma
     * @param type $fechaFin
     * @return \Agrodb\Laboratorios\Modelos\CronogramaPostregistroModelo
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
        return $this;
    }

    /**
     * Fecha inicio de cronograma
     * @param type $fechaInicio
     * @return \Agrodb\Laboratorios\Modelos\CronogramaPostregistroModelo
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
        return $this;
    }

    

    /**
     * Set estado
     *
     * Estado del registro
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, "estado", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstadoRegistro()
    {
        return $this->estadoRegistro;
    }

    /**
     * Set observacion
     *
     * ObservaciÃ³n
     *
     * @parÃ¡metro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = ValidarDatos::validarAlfa($observacion, $this->tabla, "observacion", self::NO_REQUERIDO, 0);
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
     * Set ingredienteActivo
     *
     * Ingrediente activo
     *
     * @parÃ¡metro String $ingredienteActivo
     * @return IngredienteActivo
     */
    public function setIngredienteActivo($ingredienteActivo)
    {
        $this->ingredienteActivo = ValidarDatos::validarAlfaEsp($ingredienteActivo, $this->tabla, "Ingrediente Activo", self::REQUERIDO, 1024);
        return $this;
    }

    /**
     * Get ingredienteActivo
     *
     * @return null|String
     */
    public function getIngredienteActivo()
    {
        return $this->ingredienteActivo;
    }

    /**
     * Set direccion
     *
     * DirecciÃ³n de diagnÃ³stico
     *
     * @parÃ¡metro Integer $direccion
     * @return Direccion
     */
    public function setIdDireccion($idDireccion)
    {
        $this->idDireccion = (Integer) $idDireccion;
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|Integer
     */
    public function getIdDireccion()
    {
        return $this->idDireccion;
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
     * @return CronogramaPostregistroModelo
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
