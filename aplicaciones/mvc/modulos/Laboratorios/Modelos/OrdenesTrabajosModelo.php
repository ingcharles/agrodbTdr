<?php

/**
 * Modelo OrdenesTrabajosModelo
 *
 * Este archivo se complementa con el archivo   OrdenesTrabajosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       OrdenesTrabajosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class OrdenesTrabajosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idOrdenTrabajo;

    /**
     * @var String
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Identifcador del usuario
     */
    protected $identificador;

    /**
     * @var String
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Identificador del usuario
     */
    protected $fkIdentificador;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de la solicitud
     */
    protected $idSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ³digo de la orden detrabajo
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de orden
     */
    protected $tipoOrden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado de la orden de trabajo
     */
    protected $estado;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de activacion de la orden de trabajo
     */
    protected $fechaActivacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * ObservaciÃ³n
     */
    protected $observacionInterna;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id laboratorio provincia
     */
    protected $idLaboratoriosProvincia;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Temperatura de la muestra cuando llega al laboratorio
     */
    protected $temperatura;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observación realizada por el cliente
     */
    protected $observacionCliente;

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
     * Nombre de la tabla: ordenes_trabajos
     * 
     */
    Private $tabla = "ordenes_trabajos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_orden_trabajo";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."ordenes_trabajos_id_orden_trabajo_seq';

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
            throw new \Exception('Clase Modelo: OrdenesTrabajosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: OrdenesTrabajosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idOrdenTrabajo
     *
     * Secuencial (PK) de la tabla ordenes_trabajos
     *
     * @parÃ¡metro Integer $idOrdenTrabajo
     * @return IdOrdenTrabajo
     */
    public function setIdOrdenTrabajo($idOrdenTrabajo)
    {
        $this->idOrdenTrabajo = (Integer) $idOrdenTrabajo;
        return $this;
    }

    /**
     * Get idOrdenTrabajo
     *
     * @return null|Integer
     */
    public function getIdOrdenTrabajo()
    {
        return $this->idOrdenTrabajo;
    }

    /**
     * Set identificador
     *
     * CÃ©dula de identidad o pasaporte.
     *
     * @parÃ¡metro String $identificador
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
     * Set fkIdentificador
     *
     * Cedula de identidad o pasaporte.
     *
     * @parÃ¡metro String $fkIdentificador
     * @return FkIdentificador
     */
    public function setFkIdentificador($fkIdentificador)
    {
        $this->fkIdentificador = (String) $fkIdentificador;
        return $this;
    }

    /**
     * Get fkIdentificador
     *
     * @return null|String
     */
    public function getFkIdentificador()
    {
        return $this->fkIdentificador;
    }

    /**
     * Set idSolicitud
     *
     * Secuencial (PK) de la tabla de solicitud
     *
     * @parÃ¡metro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
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
     * Set codigo
     *
     * CÃ³digo generado por el sistema
     *
     * @parÃ¡metro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfaEsp($codigo, $this->tabla, " Código", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set tipoOrden
     *
     * Tipo de orden de trabajo
     *
     * @parÃ¡metro String $tipoOrden
     * @return TipoOrden
     */
    public function setTipoOrden($tipoOrden)
    {
        $this->tipoOrden = ValidarDatos::validarAlfa($tipoOrden, $this->tabla, " Tipo de Orden", self::REQUERIDO, 32);
        return $this;
    }

    /**
     * Get tipoOrden
     *
     * @return null|String
     */
    public function getTipoOrden()
    {
        return $this->tipoOrden;
    }

    /**
     * Set estado
     *
     * Estado de la orden ACTIVA/CERRADA
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 16);
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
     * Set fechaActivacion
     *
     * Fecha de activaciÃ³n es cuando se inicia el trÃ¡mite; no es necesariamente el iniicion del anÃ¡lisis
     *
     * @parÃ¡metro Date $fechaActivacion
     * @return FechaActivacion
     */
    public function setFechaActivacion($fechaActivacion)
    {
        $this->fechaActivacion = ValidarDatos::validarFecha($fechaActivacion, $this->tabla, " Fecha de Activación", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaActivacion
     *
     * @return null|Date
     */
    public function getFechaActivacion()
    {
        return $this->fechaActivacion;
    }

    /**
     * Set observacionInterna
     *
     * ObservaciÃ³n
     *
     * @parÃ¡metro String $observacionInterna
     * @return ObservacionInterna
     */
    public function setObservacionInterna($observacionInterna)
    {
        $this->observacionInterna = ValidarDatos::validarAlfa($observacionInterna, $this->tabla, " Observación Interna", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observacionInterna
     *
     * @return null|String
     */
    public function getObservacionInterna()
    {
        return $this->observacionInterna;
    }

    /**
     * Set idLaboratoriosProvincia
     *
     * Id laboratorio provincia
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
     * Set temperatura
     *
     * Temperatura de la muestra cuando llega al laboratorio
     *
     * @parámetro String $temperatura
     * @return Temperatura
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = (String) $temperatura;
        return $this;
    }

    /**
     * Get temperatura
     *
     * @return null|String
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    /**
     * Set observacionCliente
     *
     * Observación realizada por el cliente
     *
     * @parámetro String $observacionCliente
     * @return ObservacionCliente
     */
    public function setObservacionCliente($observacionCliente)
    {
        $this->observacionCliente = (String) $observacionCliente;
        return $this;
    }

    /**
     * Get observacionCliente
     *
     * @return null|String
     */
    public function getObservacionCliente()
    {
        return $this->observacionCliente;
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
     * @return OrdenesTrabajosModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
