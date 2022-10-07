<?php
/**
 * Modelo ProtocolosAreasModelo
 *
 * Este archivo se complementa con el archivo   ProtocolosAreasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProtocolosAreasModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\Protocolos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProtocolosAreasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProtocoloArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoArea;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreTipoOperacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idTipoOperacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombrePais;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idPais;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_protocolos";

    /**
     * Nombre de la tabla: protocolos_areas
     */
    private $tabla = "protocolos_areas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_protocolo_area";

    /**
     * Secuencia
     */
    private $secuencial = 'g_protocolos"."ProtocolosAreas_id_protocolo_area_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     *
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
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: ProtocolosAreasModelo. Propiedad especificada invalida: set' . $name);
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
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: ProtocolosAreasModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_protocolos
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idProtocoloArea
     *
     *
     *
     * @parámetro Integer $idProtocoloArea
     * @return IdProtocoloArea
     */
    public function setIdProtocoloArea($idProtocoloArea)
    {
        $this->idProtocoloArea = (integer) $idProtocoloArea;
        return $this;
    }

    /**
     * Get idProtocoloArea
     *
     * @return null|Integer
     */
    public function getIdProtocoloArea()
    {
        return $this->idProtocoloArea;
    }

    /**
     * Set codigoArea
     *
     *
     *
     * @parámetro String $codigoArea
     * @return CodigoArea
     */
    public function setCodigoArea($codigoArea)
    {
        $this->codigoArea = (string) $codigoArea;
        return $this;
    }

    /**
     * Get codigoArea
     *
     * @return null|String
     */
    public function getCodigoArea()
    {
        return $this->codigoArea;
    }

    /**
     * Set idArea
     *
     *
     *
     * @parámetro Integer $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (integer) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|Integer
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set nombreProducto
     *
     *
     *
     * @parámetro String $nombreProducto
     * @return NombreProducto
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = (string) $nombreProducto;
        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return null|String
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set idProducto
     *
     *
     *
     * @parámetro Integer $idProducto
     * @return IdProducto
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = (integer) $idProducto;
        return $this;
    }

    /**
     * Get idProducto
     *
     * @return null|Integer
     */
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * Set nombreTipoOperacion
     *
     *
     *
     * @parámetro String $nombreTipoOperacion
     * @return NombreTipoOperacion
     */
    public function setNombreTipoOperacion($nombreTipoOperacion)
    {
        $this->nombreTipoOperacion = (string) $nombreTipoOperacion;
        return $this;
    }

    /**
     * Get nombreTipoOperacion
     *
     * @return null|String
     */
    public function getNombreTipoOperacion()
    {
        return $this->nombreTipoOperacion;
    }

    /**
     * Set idTipoOperacion
     *
     *
     *
     * @parámetro Integer $idTipoOperacion
     * @return IdTipoOperacion
     */
    public function setIdTipoOperacion($idTipoOperacion)
    {
        $this->idTipoOperacion = (integer) $idTipoOperacion;
        return $this;
    }

    /**
     * Get idTipoOperacion
     *
     * @return null|Integer
     */
    public function getIdTipoOperacion()
    {
        return $this->idTipoOperacion;
    }

    /**
     * Set nombrePais
     *
     *
     *
     * @parámetro String $nombrePais
     * @return NombrePais
     */
    public function setNombrePais($nombrePais)
    {
        $this->nombrePais = (string) $nombrePais;
        return $this;
    }

    /**
     * Get nombrePais
     *
     * @return null|String
     */
    public function getNombrePais()
    {
        return $this->nombrePais;
    }

    /**
     * Set idPais
     *
     *
     *
     * @parámetro Integer $idPais
     * @return IdPais
     */
    public function setIdPais($idPais)
    {
        $this->idPais = (integer) $idPais;
        return $this;
    }

    /**
     * Get idPais
     *
     * @return null|Integer
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     *
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
     *
     * @param
     *            string Where|array $where
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
     * @return ProtocolosAreasModelo
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
