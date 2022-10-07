<?php

/**
 * Modelo FiebreaftosaModelo
 *
 * Este archivo se complementa con el archivo   FiebreaftosaLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       FiebreaftosaModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FiebreaftosaModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * ID
     */
    protected $idFiebreaftosa;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave foranea
     */
    protected $idLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Clave foranea SIFAE
     */
    protected $codigoSifae;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Código generado
     */
    protected $codigoLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la muestra
     */
    protected $nombreMuestra;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de registro
     */
    protected $fecha;

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
     * Nombre de la tabla: fiebre_aftosa
     * 
     */
    Private $tabla = "fiebre_aftosa";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_fiebre_aftosa";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."fiebre_aftosa_id_fiebre_aftosa_seq';

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
            throw new \Exception('Clase Modelo: FiebreaftosaModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FiebreaftosaModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idFiebreaftosa
     *
     * Identificador de la tabla fiebre_aftosa
     *
     * @parámetro Integer $idFiebreaftosa
     * @return IdFiebreaftosa
     */
    public function setIdFiebreaftosa($idFiebreaftosa)
    {
        $this->idFiebreaftosa = (Integer) $idFiebreaftosa;
        return $this;
    }

    /**
     * Get idFiebreaftosa
     *
     * @return null|Integer
     */
    public function getIdFiebreaftosa()
    {
        return $this->idFiebreaftosa;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parámetro Integer $idLaboratorio
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
     * Set codigoSifae
     *
     * Código generado en el sistema SIFAE
     *
     * @parámetro String $codigoSifae
     * @return CodigoSifae
     */
    public function setCodigoSifae($codigoSifae)
    {
        $this->codigoSifae = (String) $codigoSifae;
        return $this;
    }

    /**
     * Get codigoSifae
     *
     * @return null|String
     */
    public function getCodigoSifae()
    {
        return $this->codigoSifae;
    }

    /**
     * Set codigoLaboratorio
     *
     * Código generado por el sistema
     *
     * @parámetro String $codigoLaboratorio
     * @return CodigoLaboratorio
     */
    public function setCodigoLaboratorio($codigoLaboratorio)
    {
        $this->codigoLaboratorio = (String) $codigoLaboratorio;
        return $this;
    }

    /**
     * Get codigoLaboratorio
     *
     * @return null|String
     */
    public function getCodigoLaboratorio()
    {
        return $this->codigoLaboratorio;
    }

    /**
     * Set nombreMuestra
     *
     * Nombre de la muestra
     *
     * @parámetro String $nombreMuestra
     * @return NombreMuestra
     */
    public function setNombreMuestra($nombreMuestra)
    {
        $this->nombreMuestra = (String) $nombreMuestra;
        return $this;
    }

    /**
     * Get nombreMuestra
     *
     * @return null|String
     */
    public function getNombreMuestra()
    {
        return $this->nombreMuestra;
    }

    /**
     * Set fecha
     *
     * Fecha del registro
     *
     * @parámetro Date $fecha
     * @return Fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = (String) $fecha;
        return $this;
    }

    /**
     * Get fecha
     *
     * @return null|Date
     */
    public function getFecha()
    {
        return $this->fecha;
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
     * @return FiebreaftosaModelo
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
