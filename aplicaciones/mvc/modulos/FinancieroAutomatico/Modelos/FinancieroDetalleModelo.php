<?php

/**
 * Modelo FinancieroDetalleModelo
 *
 * Este archivo se complementa con el archivo   FinancieroDetalleLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       FinancieroDetalleModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\FinancieroAutomatico\Modelos;

use Agrodb\Core\ModeloBase;

class FinancieroDetalleModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Identificador único de la tabla
     */
    protected $idFinancieroDetalle;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Llave  foránea de la tabla g_financiero_automatico.financiero_cabecera
     */
    protected $idFinancieroCabecera;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Llave  foránea de la tabla g_financiero.servicios
     */
    protected $idServicio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Detalle del servicio.
     */
    protected $conceptoOrden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Cantidad del servicio
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
     * Descuento del servicio
     */
    protected $descuento;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Iva del servicio
     */
    protected $iva;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Total del servicio
     */
    protected $total;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_financiero_automatico";

    /**
     * Nombre de la tabla: financiero_detalle
     * 
     */
    Private $tabla = "financiero_detalle";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_financiero_detalle";

    /**
     * Secuencia
     */
    private $secuencial = 'g_financiero_automatico"."financiero_detalle_id_financiero_detalle_seq';

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
            throw new \Exception('Clase Modelo: FinancieroDetalleModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FinancieroDetalleModelo. Propiedad especificada invalida: get' . $name);
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
            }
        }
        return $this;
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
     * Get g_financiero_automatico
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idFinancieroDetalle
     *
     * Identificador único de la tabla
     *
     * @parámetro Integer $idFinancieroDetalle
     * @return IdFinancieroDetalle
     */
    public function setIdFinancieroDetalle($idFinancieroDetalle)
    {
        if (empty($idFinancieroDetalle))
        {
            $idFinancieroDetalle = "No informa";
        }
        $this->idFinancieroDetalle = (Integer) $idFinancieroDetalle;
        return $this;
    }

    /**
     * Get idFinancieroDetalle
     *
     * @return null|Integer
     */
    public function getIdFinancieroDetalle()
    {
        return $this->idFinancieroDetalle;
    }

    /**
     * Set idFinancieroCabecera
     *
     * Llave  foránea de la tabla g_financiero_automatico.financiero_cabecera
     *
     * @parámetro Integer $idFinancieroCabecera
     * @return IdFinancieroCabecera
     */
    public function setIdFinancieroCabecera($idFinancieroCabecera)
    {
        if (empty($idFinancieroCabecera))
        {
            $idFinancieroCabecera = "No informa";
        }
        $this->idFinancieroCabecera = (Integer) $idFinancieroCabecera;
        return $this;
    }

    /**
     * Get idFinancieroCabecera
     *
     * @return null|Integer
     */
    public function getIdFinancieroCabecera()
    {
        return $this->idFinancieroCabecera;
    }

    /**
     * Set idServicio
     *
     * Llave  foránea de la tabla g_financiero.servicios
     *
     * @parámetro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
        if (empty($idServicio))
        {
            $idServicio = "No informa";
        }
        $this->idServicio = (Integer) $idServicio;
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
     * Set conceptoOrden
     *
     * Detalle del servicio.
     *
     * @parámetro String $conceptoOrden
     * @return ConceptoOrden
     */
    public function setConceptoOrden($conceptoOrden)
    {
        if (empty($conceptoOrden))
        {
            $conceptoOrden = "No informa";
        }
        $this->conceptoOrden = (String) $conceptoOrden;
        return $this;
    }

    /**
     * Get conceptoOrden
     *
     * @return null|String
     */
    public function getConceptoOrden()
    {
        return $this->conceptoOrden;
    }

    /**
     * Set cantidad
     *
     * Cantidad del servicio
     *
     * @parámetro String $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        if (empty($cantidad))
        {
            $cantidad = "No informa";
        }
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
        if (empty($precioUnitario))
        {
            $precioUnitario = "No informa";
        }
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
     * Set descuento
     *
     * Descuento del servicio
     *
     * @parámetro Decimal $descuento
     * @return Descuento
     */
    public function setDescuento($descuento)
    {
        if (empty($descuento))
        {
            $descuento = "No informa";
        }
        $this->descuento = (Double) $descuento;
        return $this;
    }

    /**
     * Get descuento
     *
     * @return null|Decimal
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set iva
     *
     * Iva del servicio
     *
     * @parámetro Decimal $iva
     * @return Iva
     */
    public function setIva($iva)
    {
        if (empty($iva))
        {
            $iva = "No informa";
        }
        $this->iva = (Double) $iva;
        return $this;
    }

    /**
     * Get iva
     *
     * @return null|Decimal
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set total
     *
     * Total del servicio
     *
     * @parámetro Decimal $total
     * @return Total
     */
    public function setTotal($total)
    {
        if (empty($total))
        {
            $total = "No informa";
        }
        $this->total = (Double) $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return null|Decimal
     */
    public function getTotal()
    {
        return $this->total;
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
     * @return FinancieroDetalleModelo
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
