<?php

/**
 * Modelo DetalleFormaPagoModelo
 *
 * Este archivo se complementa con el archivo   DetalleFormaPagoLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DetalleFormaPagoModelo
 * @package Financiero
 * @subpackage Modelo
 */

namespace Agrodb\Financiero\Modelos;

use Agrodb\Core\ModeloBase;

class DetalleFormaPagoModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla
     */
    protected $idDetallePago;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla pago
     */
    protected $idPago;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla bancos
     */
    protected $idBanco;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de banco
     */
    protected $institucionBancaria;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la transación
     */
    protected $transaccion;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Valor del depósito
     */
    protected $valorDeposito;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de pago
     */
    protected $fechaOrdenPago;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Nota de crédito
     */
    protected $idNotaCredito;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id de la cuenta bancaria
     */
    protected $idCuentaBancaria;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Número de cuenta
     */
    protected $numeroCuenta;

    /**
     * Campos del formulario 
     * @var array 
     */
    Private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_financiero";

    /**
     * Nombre de la tabla: detalle_forma_pago
     * 
     */
    Private $tabla = "detalle_forma_pago";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_detalle_pago";

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
            throw new \Exception('Clase Modelo: DetalleFormaPagoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DetalleFormaPagoModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_financiero
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idDetallePago
     *
     * Clave primaria de la tabla
     *
     * @parámetro Integer $idDetallePago
     * @return IdDetallePago
     */
    public function setIdDetallePago($idDetallePago)
    {
        $this->idDetallePago = (Integer) $idDetallePago;
        return $this;
    }

    /**
     * Get idDetallePago
     *
     * @return null|Integer
     */
    public function getIdDetallePago()
    {
        return $this->idDetallePago;
    }

    /**
     * Set idPago
     *
     * Clave primaria de la tabla pagos
     *
     * @parámetro Integer $idPago
     * @return IdPago
     */
    public function setIdPago($idPago)
    {
        $this->idPago = (Integer) $idPago;
        return $this;
    }

    /**
     * Get idPago
     *
     * @return null|Integer
     */
    public function getIdPago()
    {
        return $this->idPago;
    }

    /**
     * Set idBanco
     *
     * Clave primaria de la tabla bancos
     *
     * @parámetro Integer $idBanco
     * @return IdBanco
     */
    public function setIdBanco($idBanco)
    {
        $this->idBanco = (Integer) $idBanco;
        return $this;
    }

    /**
     * Get idBanco
     *
     * @return null|Integer
     */
    public function getIdBanco()
    {
        return $this->idBanco;
    }

    /**
     * Set institucionBancaria
     *
     * Nombre del banco
     *
     * @parámetro String $institucionBancaria
     * @return InstitucionBancaria
     */
    public function setInstitucionBancaria($institucionBancaria)
    {
        $this->institucionBancaria = (String) $institucionBancaria;
        return $this;
    }

    /**
     * Get institucionBancaria
     *
     * @return null|String
     */
    public function getInstitucionBancaria()
    {
        return $this->institucionBancaria;
    }

    /**
     * Set transaccion
     *
     * Nombre de la transación
     *
     * @parámetro String $transaccion
     * @return Transaccion
     */
    public function setTransaccion($transaccion)
    {
        $this->transaccion = (String) $transaccion;
        return $this;
    }

    /**
     * Get transaccion
     *
     * @return null|String
     */
    public function getTransaccion()
    {
        return $this->transaccion;
    }

    /**
     * Set valorDeposito
     *
     * Valor del depósito
     *
     * @parámetro Decimal $valorDeposito
     * @return ValorDeposito
     */
    public function setValorDeposito($valorDeposito)
    {
        $this->valorDeposito = (Double) $valorDeposito;
        return $this;
    }

    /**
     * Get valorDeposito
     *
     * @return null|Decimal
     */
    public function getValorDeposito()
    {
        return $this->valorDeposito;
    }

    /**
     * Set fechaOrdenPago
     *
     * Fecha de  pago
     *
     * @parámetro Date $fechaOrdenPago
     * @return FechaOrdenPago
     */
    public function setFechaOrdenPago($fechaOrdenPago)
    {
        $this->fechaOrdenPago = (String) $fechaOrdenPago;
        return $this;
    }

    /**
     * Get fechaOrdenPago
     *
     * @return null|Date
     */
    public function getFechaOrdenPago()
    {
        return $this->fechaOrdenPago;
    }

    /**
     * Set idNotaCredito
     *
     * Nota de crédito
     *
     * @parámetro Integer $idNotaCredito
     * @return IdNotaCredito
     */
    public function setIdNotaCredito($idNotaCredito)
    {
        $this->idNotaCredito = (Integer) $idNotaCredito;
        return $this;
    }

    /**
     * Get idNotaCredito
     *
     * @return null|Integer
     */
    public function getIdNotaCredito()
    {
        return $this->idNotaCredito;
    }

    /**
     * Set idCuentaBancaria
     *
     * Id de la cuenta bancaria
     *
     * @parámetro Integer $idCuentaBancaria
     * @return IdCuentaBancaria
     */
    public function setIdCuentaBancaria($idCuentaBancaria)
    {
        $this->idCuentaBancaria = (Integer) $idCuentaBancaria;
        return $this;
    }

    /**
     * Get idCuentaBancaria
     *
     * @return null|Integer
     */
    public function getIdCuentaBancaria()
    {
        return $this->idCuentaBancaria;
    }

    /**
     * Set numeroCuenta
     *
     * Número de cuenta
     *
     * @parámetro String $numeroCuenta
     * @return NumeroCuenta
     */
    public function setNumeroCuenta($numeroCuenta)
    {
        $this->numeroCuenta = (String) $numeroCuenta;
        return $this;
    }

    /**
     * Get numeroCuenta
     *
     * @return null|String
     */
    public function getNumeroCuenta()
    {
        return $this->numeroCuenta;
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
     * @return DetalleFormaPagoModelo
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
