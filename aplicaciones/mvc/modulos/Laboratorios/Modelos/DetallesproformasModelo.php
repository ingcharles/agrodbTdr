<?php

/**
 * Modelo DetallesproformasModelo
 *
 * Este archivo se complementa con el archivo   DetallesproformasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DetallesproformasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetallesproformasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla detalles_proformas
     * ID
     */
    protected $idDetalleProforma;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador primario de la tabla Proformas
     */
    protected $idProforma;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del servicio/analisis
     */
    protected $nomServicio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Cantidad solicitada
     */
    protected $cantidad;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Precio unitario del servicio
     */
    protected $precioUnitario;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Precio total del servicio
     */
    protected $precioTotal;

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
     * Nombre de la tabla: detalles_proformas
     * 
     */
    Private $tabla = "detalles_proformas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_detalle_proforma";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."detalles_proformas_id_detalle_proforma_seq';

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
            throw new \Exception('Clase Modelo: DetallesproformasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DetallesproformasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idDetalleProforma
     *
     * Identificador de la tabla detalles_proformas
     *
     * @parámetro Integer $idDetalleProforma
     * @return IdDetalleProforma
     */
    public function setIdDetalleProforma($idDetalleProforma)
    {
        $this->idDetalleProforma = (Integer) $idDetalleProforma;
        return $this;
    }

    /**
     * Get idDetalleProforma
     *
     * @return null|Integer
     */
    public function getIdDetalleProforma()
    {
        return $this->idDetalleProforma;
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
     * Set nomServicio
     *
     * Nombre del servicio/analisis
     *
     * @parámetro String $nomServicio
     * @return NomServicio
     */
    public function setNomServicio($nomServicio)
    {
        $this->nomServicio = (String) $nomServicio;
        return $this;
    }

    /**
     * Get nomServicio
     *
     * @return null|String
     */
    public function getNomServicio()
    {
        return $this->nomServicio;
    }

    /**
     * Set cantidad
     *
     * Cantidad solicitada
     *
     * @parámetro String $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = (String) $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|String
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precioUnitario
     *
     * Precio unitario del servicio
     *
     * @parámetro Decimal $precioUnitario
     * @return PrecioUnitario
     */
    public function setPrecioUnitario($precioUnitario)
    {
        $this->precioUnitario = (Double) $precioUnitario;
        return $this;
    }

    /**
     * Get precioUnitario
     *
     * @return null|Decimal
     */
    public function getPrecioUnitario()
    {
        return $this->precioUnitario;
    }

    /**
     * Set precioTotal
     *
     * Precio total del servicio
     *
     * @parámetro Decimal $precioTotal
     * @return PrecioTotal
     */
    public function setPrecioTotal($precioTotal)
    {
        $this->precioTotal = (Double) $precioTotal;
        return $this;
    }

    /**
     * Get precioTotal
     *
     * @return null|Decimal
     */
    public function getPrecioTotal()
    {
        return $this->precioTotal;
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

    public function borrarPorParametro($param, $value)
    {
        return parent::borrar($param . " = " . $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return DetallesproformasModelo
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
