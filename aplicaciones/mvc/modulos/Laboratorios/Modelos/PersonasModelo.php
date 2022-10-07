<?php

/**
 * Modelo PersonasModelo
 *
 * Este archivo se complementa con el archivo   PersonasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       PersonasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PersonasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * id
     */
    protected $idPersona;

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
     * Provincia
     */
    protected $idLocalizacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Cédula/RUC
     */
    protected $ciRuc;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre
     */
    protected $nombre;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Dirección
     */
    protected $direccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Teléfono
     */
    protected $telefono;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * E-mail
     */
    protected $email;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Contacto
     */
    protected $contactoProforma;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Teléfono
     */
    protected $telefonoProforma;
    
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
     * Nombre de la tabla: personas
     * 
     */
    Private $tabla = "personas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_persona";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."personas_id_persona_seq';

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
            throw new \Exception('Clase Modelo: PersonasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PersonasModelo. Propiedad especificada invalida: get' . $name);
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
     * Nombre del esquema del módulo
     *
     * @parámetro $esquema
     * 
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
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idPersona
     *
     * Identificador de la tabla personas
     *
     * @parámetro Integer $idPersona
     * @return IdPersona
     */
    public function setIdPersona($idPersona)
    {
        if (empty($idPersona))
        {
            $idPersona = "No informa";
        }
        $this->idPersona = (Integer) $idPersona;
        return $this;
    }

    /**
     * Get idPersona
     *
     * @return null|Integer
     */
    public function getIdPersona()
    {
        return $this->idPersona;
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
     * provinica donde recide la persona
     * @return type
     */
    public function getIdLocalizacion()
    {
        return $this->idLocalizacion;
    }

    /**
     * provinica donde recide la persona
     * @param type $idLocalizacion
     * @return \Agrodb\Laboratorios\Modelos\PersonasModelo
     */
    public function setIdLocalizacion($idLocalizacion)
    {
        $this->idLocalizacion = $idLocalizacion;
        return $this;
    }

    /**
     * Set ciRuc
     *
     * Número de cédula o ruc para la factura
     *
     * @parámetro String $ciRuc
     * @return CiRuc
     */
    public function setCiRuc($ciRuc)
    {
        $this->ciRuc = ValidarDatos::validarAlfa($ciRuc, $this->tabla, " CI o Ruc Factura", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get ciRuc
     *
     * @return null|String
     */
    public function getCiRuc()
    {
        return $this->ciRuc;
    }

    /**
     * Set nombre
     *
     * Nombre del cliente para la factura
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, " Nombre", self::NO_REQUERIDO, 128);
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
     * Set direccion
     *
     * Dirección del cliente a facturar
     *
     * @parámetro String $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = ValidarDatos::validarAlfaEsp($direccion, $this->tabla, " Dirección", self::NO_REQUERIDO, 128);
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
     * Teléfono del cliente a facturar
     *
     * @parámetro String $telefono
     * @return Telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = ValidarDatos::validarAlfaEsp($telefono, $this->tabla, " Teléfono", self::NO_REQUERIDO, 16);
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
     * Set email
     *
     * E-mail del cliente a facturar
     *
     * @parámetro String $email
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = (String) $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return null|String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contactoProforma
     *
     * Nombre del contacto de la institución que solicita la proforma
     *
     * @parámetro String $contactoProforma
     * @return ContactoProforma
     */
    public function setContactoProforma($contactoProforma)
    {
        $this->contactoProforma = ValidarDatos::validarAlfa($contactoProforma, $this->tabla, " Contacto Proforma", self::NO_REQUERIDO, 64);
        return $this;
    }

    /**
     * Get contactoProforma
     *
     * @return null|String
     */
    public function getContactoProforma()
    {
        return $this->contactoProforma;
    }

    /**
     * Set telefonoProforma
     *
     * Teléfono/Extensión del contacto de la institución que solicita la proforma
     *
     * @parámetro String $telefonoProforma
     * @return TelefonoProforma
     */
    public function setTelefonoProforma($telefonoProforma)
    {
        $this->telefonoProforma = ValidarDatos::validarAlfaEsp($telefonoProforma, $this->tabla, " Teléfono Proforma", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get telefonoProforma
     *
     * @return null|String
     */
    public function getTelefonoProforma()
    {
        return $this->telefonoProforma;
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
     * @return PersonasModelo
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
