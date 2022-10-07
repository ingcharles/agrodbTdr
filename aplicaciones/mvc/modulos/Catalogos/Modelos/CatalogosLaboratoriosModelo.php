<?php

/**
 * Modelo CatalogosModelo
 *
 * Este archivo se complementa con el archivo   CatalogosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       CatalogosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CatalogosLaboratoriosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id
     */
    protected $idCatalogos;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Id padre
     */
    protected $fkIdCatalogos;

    /**
     * @var String
     * Campo requerido
     * Campo no visible en el formulario
     * modulo
     */
    protected $modulo;

    /**
     * @var integer
     * Campo requerido
     * Campo no visible en el formulario
     * Nombre
     */
    protected $codigo;

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
     * Descripción
     */
    protected $descripcion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden
     */
    protected $orden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado
     */
    protected $estado;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: catalogos
     * 
     */
    Private $tabla = "catalogos_laboratorios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_catalogos";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."catalogos_laboratorios_id_catalogos_seq';

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
            throw new \Exception('Clase Modelo: CatalogosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CatalogosModelo. Propiedad especificada invalida: get' . $name);
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
     * Módulo que le corresponde los catálogos LABORATORIOS/REACTIVOS
     * @param \Agrodb\Laboratorios\Modelos\String $modulo
     * @return \Agrodb\Laboratorios\Modelos\CatalogosModelo
     */
    public function setModulo($modulo)
    {
        if (empty($modulo))
        {
            $modulo = "No informa";
        }
        $this->modulo = (String) $modulo;
        return $this;
    }

    /**
     * Retorna el mÓdulo que le corresponde los catálogos LABORATORIOS/REACTIVOS
     * @return type
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /*     * *
     * 
     * 
     */

    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfa($codigo, $this->tabla, "Código", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * 
     * @return type
     * 
     */
    public function getCodigo()
    {
        return $this->codigo;
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
        $this->esquema = (String) $esquema;
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
     * Set idCatalogos
     *
     * Identificador de la tabla
     *
     * @parámetro Integer $idCatalogos
     * @return IdCatalogos
     */
    public function setIdCatalogos($idCatalogos)
    {
        if (empty($idCatalogos))
        {
            $this->idCatalogos = null;
        } else
        {
            $this->idCatalogos = (Integer) $idCatalogos;
        }
        return $this;
    }

    /**
     * Get idCatalogos
     *
     * @return null|Integer
     */
    public function getIdCatalogos()
    {
        return $this->idCatalogos;
    }

    /**
     * Set fkIdCatalogos
     *
     * Id nodo padre
     *
     * @parámetro Integer $fkIdCatalogos
     * @return FkIdCatalogos
     */
    public function setFkIdCatalogos($fkIdCatalogos)
    {

        if (empty($fkIdCatalogos))
        {
            $this->fkIdCatalogos = null;
        } else
        {
            $this->fkIdCatalogos = (Integer) $fkIdCatalogos;
        }
        return $this;
    }

    /**
     * Get fkIdCatalogos
     *
     * @return null|Integer
     */
    public function getFkIdCatalogos()
    {
        return $this->fkIdCatalogos;
    }

    /**
     * Set nombre
     *
     * Nombre del catálogo
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, "nombre", self::REQUERIDO, 128);
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
     * Set descripcion
     *
     * descripción del catálogo
     *
     * @parámetro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = ValidarDatos::validarAlfaEsp($descripcion, $this->tabla, self::REQUERIDO);
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return null|String
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set orden
     *
     * Orden del catálogo
     *
     * @parámetro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = ValidarDatos::validarEntero($orden, $this->tabla, "orden");
        return $this->orden;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set estado
     *
     * Estado del registro
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, "Estado", self::REQUERIDO, 8);

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
     * @return CatalogosModelo
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
