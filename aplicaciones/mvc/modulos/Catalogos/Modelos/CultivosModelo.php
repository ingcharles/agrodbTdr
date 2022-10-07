<?php
/**
 * Modelo CultivosModelo
 *
 * Este archivo se complementa con el archivo   CultivosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    CultivosModelo
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CultivosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador único del registro
     */
    protected $idCultivo;
    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de creación del registro
     */
    protected $fechaCreacion;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre común del cultivo
     */
    protected $nombreComunCultivo;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre científico del cultivo
     */
    protected $nombreCientificoCultivo;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Código del área
     */
    protected $idArea;

    /**
     * Campos del formulario
     * @var array
     */
    private $campos = array();

    /**
     * Nombre del esquema
     *
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: cultivos
     *
     */
    private $tabla = "cultivos";

    /**
     *Clave primaria
     */
    private $clavePrimaria = "id_cultivo";


    /**
     *Secuencia
     */
    private $secuencial = 'g_catalogos"."Cultivos_id_cultivo_seq';


    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos)) {
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
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: CultivosModelo. Propiedad especificada invalida: set' . $name);
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
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: CultivosModelo. Propiedad especificada invalida: get' . $name);
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
        foreach ($datos as $key => $value) {
            $key_original = $key;
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
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
        foreach ($this->campos as $key => $value) {
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
     * Get g_catalogos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idCultivo
     *
     *Identificador único del registro
     *
     * @parámetro Integer $idCultivo
     * @return IdCultivo
     */
    public function setIdCultivo($idCultivo)
    {
        $this->idCultivo = (integer)$idCultivo;
        return $this;
    }

    /**
     * Get idCultivo
     *
     * @return null|Integer
     */
    public function getIdCultivo()
    {
        return $this->idCultivo;
    }

    /**
     * Set fechaCreacion
     *
     *Fecha de creación del registro
     *
     * @parámetro Date $fechaCreacion
     * @return FechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = (string)$fechaCreacion;
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return null|Date
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set nombreComunCultivo
     *
     *Nombre común del cultivo
     *
     * @parámetro String $nombreComunCultivo
     * @return NombreComunCultivo
     */
    public function setNombreComunCultivo($nombreComunCultivo)
    {
        $this->nombreComunCultivo = (string)$nombreComunCultivo;
        return $this;
    }

    /**
     * Get nombreComunCultivo
     *
     * @return null|String
     */
    public function getNombreComunCultivo()
    {
        return $this->nombreComunCultivo;
    }

    /**
     * Set nombreCientificoCultivo
     *
     *Nombre científico del cultivo
     *
     * @parámetro String $nombreCientificoCultivo
     * @return NombreCientificoCultivo
     */
    public function setNombreCientificoCultivo($nombreCientificoCultivo)
    {
        $this->nombreCientificoCultivo = (string)$nombreCientificoCultivo;
        return $this;
    }

    /**
     * Get nombreCientificoCultivo
     *
     * @return null|String
     */
    public function getNombreCientificoCultivo()
    {
        return $this->nombreCientificoCultivo;
    }

    /**
     * Set idArea
     *
     *Código del área
     *
     * @parámetro String $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (string)$idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|String
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(array $datos, $id)
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
     * @param int $id
     * @return CultivosModelo
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
        return parent::buscarLista($where, $order);
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
