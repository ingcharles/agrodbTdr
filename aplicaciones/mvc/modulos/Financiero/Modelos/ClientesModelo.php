<?php

/**
 * Modelo ClientesModelo
 *
 * Este archivo se complementa con el archivo   ClientesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ClientesModelo
 * @package Financiero
 * @subpackage Modelo
 */

namespace Agrodb\Financiero\Modelos;

use Agrodb\Core\ModeloBase;

class ClientesModelo extends ModeloBase
{

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Cédula o RUC
     */
    protected $identificador;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de identificación
     */
    protected $tipoIdentificacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del cliente
     */
    protected $razonSocial;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Dirección del cliente
     */
    protected $direccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Teléfono del cliente
     */
    protected $telefono;

    /**
     * @var Email
     * Campo requerido
     * Campo visible en el formulario
     * Correo del cliente
     */
    protected $correo;

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
     * Nombre de la tabla: clientes
     * 
     */
    Private $tabla = "clientes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "identificador";

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
            throw new \Exception('Clase Modelo: ClientesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ClientesModelo. Propiedad especificada invalida: get' . $name);
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
     * Set identificador
     *
     * Cédula o RUC
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (String) $identificador;
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set tipoIdentificacion
     *
     * Tipo de identificación
     *
     * @parámetro String $tipoIdentificacion
     * @return TipoIdentificacion
     */
    public function setTipoIdentificacion($tipoIdentificacion)
    {
        $this->tipoIdentificacion = (String) $tipoIdentificacion;
        return $this;
    }

    /**
     * Get tipoIdentificacion
     *
     * @return null|String
     */
    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    /**
     * Set razonSocial
     *
     * Nombre del Cliente
     *
     * @parámetro String $razonSocial
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (String) $razonSocial;
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return null|String
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set direccion
     *
     * Dirección del cliente
     *
     * @parámetro String $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = (String) $direccion;
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|String
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * Teléfono del cliente
     *
     * @parámetro String $telefono
     * @return Telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = (String) $telefono;
        return $this;
    }

    /**
     * Get telefono
     *
     * @return null|String
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * Correo del cliente
     *
     * @parámetro Email $correo
     * @return Correo
     */
    public function setCorreo($correo)
    {
        $this->correo = (String) $correo;
        return $this;
    }

    /**
     * Get correo
     *
     * @return null|Email
     */
    public function getCorreo()
    {
        return $this->correo;
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
     * @return ClientesModelo
     */
    public function buscar($id)
    {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . "'$id'"));
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
