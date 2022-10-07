<?php

/**
 * Modelo MarbetesModelo
 *
 * Este archivo se complementa con el archivo   MarbetesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       MarbetesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MarbetesModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla marbetes
     */
    protected $idMarbete;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla de recepcionmuestra
     */
    protected $idRecepcionMuestras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Numero de lote
     */
    protected $numeroLote;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Cantidad de marbetes del lote
     */
    protected $cantidad;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de impresion
     */
    protected $fechaImpresion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Inicio de la serie
     */
    protected $inicioSerie;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Fin de la serie
     */
    protected $finSerie;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del lote
     */
    protected $estado;

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
     * Nombre de la tabla: marbetes
     * 
     */
    Private $tabla = "marbetes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_marbete";

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
            throw new \Exception('Clase Modelo: MarbetesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: MarbetesModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idMarbete
     *
     * Identificador de la tabla marbetes
     *
     * @parámetro Integer $idMarbete
     * @return IdMarbete
     */
    public function setIdMarbete($idMarbete)
    {
        $this->idMarbete = (Integer) $idMarbete;
        return $this;
    }

    /**
     * Get idMarbete
     *
     * @return null|Integer
     */
    public function getIdMarbete()
    {
        return $this->idMarbete;
    }

    /**
     * Set idRecepcionMuestras
     *
     * Clave primaria de la tabla de recepcionmuestra
     *
     * @parámetro Integer $idRecepcionMuestras
     * @return IdRecepcionMuestras
     */
    public function setIdRecepcionMuestras($idRecepcionMuestras)
    {
        $this->idRecepcionMuestras = (Integer) $idRecepcionMuestras;
        return $this;
    }

    /**
     * Get idRecepcionMuestras
     *
     * @return null|Integer
     */
    public function getIdRecepcionMuestras()
    {
        return $this->idRecepcionMuestras;
    }

    /**
     * Set numeroLote
     *
     * Numero de lote
     *
     * @parámetro String $numeroLote
     * @return NumeroLote
     */
    public function setNumeroLote($numeroLote)
    {
        $this->numeroLote = (String) $numeroLote;
        return $this;
    }

    /**
     * Get numeroLote
     *
     * @return null|String
     */
    public function getNumeroLote()
    {
        return $this->numeroLote;
    }

    /**
     * Set cantidad
     *
     * Cantidad de marbetes del lote
     *
     * @parámetro Integer $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = (Integer) $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|Integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechaImpresion
     *
     * Fecha de impresion
     *
     * @parámetro Date $fechaImpresion
     * @return FechaImpresion
     */
    public function setFechaImpresion($fechaImpresion)
    {
        $this->fechaImpresion = (String) $fechaImpresion;
        return $this;
    }

    /**
     * Get fechaImpresion
     *
     * @return null|Date
     */
    public function getFechaImpresion()
    {
        return $this->fechaImpresion;
    }

    /**
     * Set inicioSerie
     *
     * Inicio de la serie
     *
     * @parámetro Integer $inicioSerie
     * @return InicioSerie
     */
    public function setInicioSerie($inicioSerie)
    {
        $this->inicioSerie = (Integer) $inicioSerie;
        return $this;
    }

    /**
     * Get inicioSerie
     *
     * @return null|Integer
     */
    public function getInicioSerie()
    {
        return $this->inicioSerie;
    }

    /**
     * Set finSerie
     *
     * Fin de la serie
     *
     * @parámetro Integer $finSerie
     * @return FinSerie
     */
    public function setFinSerie($finSerie)
    {
        $this->finSerie = (Integer) $finSerie;
        return $this;
    }

    /**
     * Get finSerie
     *
     * @return null|Integer
     */
    public function getFinSerie()
    {
        return $this->finSerie;
    }

    /**
     * Set estado
     *
     * Estado del lote
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (String) $estado;
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
     * @return MarbetesModelo
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
