<?php

/**
 * Modelo PagosModelo
 *
 * Este archivo se complementa con el archivo   PagosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       PagosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PagosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idPagos;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Cuenta bancaria
     */
    protected $idCuentaBancaria;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Banco
     */
    protected $idBanco;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Solicitud
     */
    protected $idSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Número depósito
     */
    protected $numeroDeposito;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha depósito
     */
    protected $fechaDeposito;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Valor depósito
     */
    protected $valorDepositado;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: pagos
     * 
     */
    Private $tabla = "pagos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_pagos";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."pagos_id_pagos_seq';

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
            throw new \Exception('Clase Modelo: PagosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PagosModelo. Propiedad especificada invalida: get' . $name);
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
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
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
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idPagos
     *
     * Id del la tabla pagos
     *
     * @parámetro Integer $idPagos
     * @return IdPagos
     */
    public function setIdPagos($idPagos)
    {
        if (empty($idPagos))
        {
            $idPagos = "No informa";
        }
        $this->idPagos = (Integer) $idPagos;
        return $this;
    }

    /**
     * Get idPagos
     *
     * @return null|Integer
     */
    public function getIdPagos()
    {
        return $this->idPagos;
    }

    /**
     * Set idCuentaBancaria
     *
     * Cuenta del banco donde se depósita
     *
     * @parámetro Integer $idCuentaBancaria
     * @return IdCuentaBancaria
     */
    public function setIdCuentaBancaria($idCuentaBancaria)
    {
        if (empty($idCuentaBancaria))
        {
            $idCuentaBancaria = "No informa";
        }
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
     * Set idBanco
     *
     * Banco del depósito
     *
     * @parámetro Integer $idBanco
     * @return IdBanco
     */
    public function setIdBanco($idBanco)
    {
        if (empty($idBanco))
        {
            $idBanco = "No informa";
        }
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
     * Set idSolicitud
     *
     * Solicitud que le pertenece el pago
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        if (empty($idSolicitud))
        {
            $idSolicitud = "No informa";
        }
        $this->idSolicitud = (Integer) $idSolicitud;
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
    }

    /**
     * Set numeroDeposito
     *
     * Número de depósto
     *
     * @parámetro String $numeroDeposito
     * @return NumeroDeposito
     */
    public function setNumeroDeposito($numeroDeposito)
    {
        $this->numeroDeposito = ValidarDatos::validarAlfa($numeroDeposito, $this->tabla, " Número de depósito", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get numeroDeposito
     *
     * @return null|String
     */
    public function getNumeroDeposito()
    {
        return $this->numeroDeposito;
    }

    /**
     * Set fechaDeposito
     *
     * Fecha de depósito
     *
     * @parámetro Date $fechaDeposito
     * @return FechaDeposito
     */
    public function setFechaDeposito($fechaDeposito)
    {
        $this->fechaDeposito = ValidarDatos::validarFecha($fechaDeposito, $this->tabla, " Fecha de Depósito", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaDeposito
     *
     * @return null|Date
     */
    public function getFechaDeposito()
    {
        return $this->fechaDeposito;
    }

    /**
     * Set valorDepositado
     *
     * Valor de depósito
     *
     * @parámetro Decimal $valorDepositado
     * @return ValorDepositado
     */
    public function setValorDepositado($valorDepositado)
    {
        
        $this->valorDepositado = ValidarDatos::validarDecimal($valorDepositado, $this->tabla, " Valor Depositado", self::NO_REQUERIDO, 0);
        return $this;
    }

    
    /**
     * Get valorDepositado
     *
     * @return null|Decimal
     */
    public function getValorDepositado()
    {
        return $this->valorDepositado;
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
     * @return PagosModelo
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
