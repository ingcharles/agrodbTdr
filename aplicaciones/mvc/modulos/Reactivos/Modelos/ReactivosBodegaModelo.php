<?php

/**
 * Modelo ReactivosBodegaModelo
 *
 * Este archivo se complementa con el archivo   ReactivosBodegaLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ReactivosBodegaModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use Agrodb\Core\Constantes;

class ReactivosBodegaModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoBodega;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $codigoBodega;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idBodega;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $nombre;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidad;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaActualizacion;

    /**
     * @var String
     * 
     */
    protected $unidad;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $presentacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $observaciones;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $descripcion;

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
     * Nombre de la tabla: reactivos_bodega
     * 
     */
    Private $tabla = "reactivos_bodega";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_reactivo_bodega";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."reactivos_bodega_id_reactivo_bodega_seq';

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
            throw new \Exception('Clase Modelo: ReactivosBodegaModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ReactivosBodegaModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idReactivoBodega
     *
     *
     *
     * @parÃ¡metro Integer $idReactivoBodega
     * @return IdReactivoBodega
     */
    public function setIdReactivoBodega($idReactivoBodega)
    {
        $this->idReactivoBodega = Constantes::ENTERO_VACIO;
        return $this;
    }

    /**
     * Get idReactivoBodega
     *
     * @return null|Integer
     */
    public function getIdReactivoBodega()
    {
        return $this->idReactivoBodega;
    }

    /**
     * Set codigoBodega
     *
     *
     *
     * @parÃ¡metro String $codigoBodega
     * @return CodigoBodega
     */
    public function setCodigoBodega($codigoBodega)
    {
        $this->codigoBodega = ValidarDatos::validarAlfaEsp($codigoBodega, $this->tabla, " Código Bodega", self::REQUERIDO, 32);
        return $this;
    }

    /**
     * Get codigoBodega
     *
     * @return null|String
     */
    public function getCodigoBodega()
    {
        return $this->codigoBodega;
    }

    /**
     * Set idBodega
     *
     *
     *
     * @parÃ¡metro Integer $idBodega
     * @return IdBodega
     */
    public function setIdBodega($idBodega)
    {
        $this->idBodega = (Integer) $idBodega;
        return $this;
    }

    /**
     * Get idBodega
     *
     * @return null|Integer
     */
    public function getIdBodega()
    {
        return $this->idBodega;
    }

    /**
     * Set nombre
     *
     *
     *
     * @parÃ¡metro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, " Nombre", self::REQUERIDO, 256);
        return $this;
    }

    /**
     * Get nombre
     *
     * @return null|String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cantidad
     *
     *
     *
     * @parÃ¡metro Decimal $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = ValidarDatos::validarDecimal($cantidad, $this->tabla, " Cantidad", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|Decimal
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechaActualizacion
     *
     *
     *
     * @parÃ¡metro Date $fechaActualizacion
     * @return FechaActualizacion
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = ValidarDatos::validarFecha($fechaActualizacion, $this->tabla, " Fecha de Actualización", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return null|Date
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set unidad
     *
     *
     *
     * @parÃ¡metro String $unidad
     * @return Unidad
     */
    public function setUnidad($unidad)
    {
        $this->unidad = ValidarDatos::validarAlfa($unidad, $this->tabla, " Unidad de medida", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get unidad
     *
     * @return null|String
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set presentacion
     *
     *
     *
     * @parÃ¡metro String $presentacion
     * @return Presentacion
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = ValidarDatos::validarAlfa($presentacion, $this->tabla, " Unidad", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get presentacion
     *
     * @return null|String
     */
    public function getPresentacion()
    {
        return $this->presentacion;
    }

    /**
     * Set observaciones
     *
     *
     *
     * @parÃ¡metro String $observaciones
     * @return Observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = ValidarDatos::validarAlfaEsp($observaciones, $this->tabla, " Observaciones", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set descripcion
     *
     *
     *
     * @parÃ¡metro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = ValidarDatos::validarAlfaEsp($descripcion, $this->tabla, " Descripción", self::NO_REQUERIDO, 256);
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
     * @return ReactivosBodegaModelo
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
