<?php
/**
 * Modelo FabricanteFormuladorModelo
 *
 * Este archivo se complementa con el archivo   FabricanteFormuladorLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    FabricanteFormuladorModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FabricanteFormuladorModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idFabricanteFormulador;

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
    protected $nombre;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $tipo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $observacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idPaisOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $paisOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del modificador del registro
     */
    protected $identificadorModificacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de modificación del registro
     */
    protected $fechaModificacion;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: fabricante_formulador
     */
    private $tabla = "fabricante_formulador";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_fabricante_formulador";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."fabricante_formulador_id_fabricante_formulador_seq';

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
            throw new \Exception('Clase Modelo: FabricanteFormuladorModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FabricanteFormuladorModelo. Propiedad especificada invalida: get' . $name);
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
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idFabricanteFormulador
     *
     *
     *
     * @parámetro Integer $idFabricanteFormulador
     * @return IdFabricanteFormulador
     */
    public function setIdFabricanteFormulador($idFabricanteFormulador)
    {
        $this->idFabricanteFormulador = (integer) $idFabricanteFormulador;
        return $this;
    }

    /**
     * Get idFabricanteFormulador
     *
     * @return null|Integer
     */
    public function getIdFabricanteFormulador()
    {
        return $this->idFabricanteFormulador;
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
     * Set nombre
     *
     *
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = (string) $nombre;
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
     * Set tipo
     *
     *
     *
     * @parámetro String $tipo
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = (string) $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set observacion
     *
     *
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = (string) $observacion;
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set idPaisOrigen
     *
     *
     *
     * @parámetro Integer $idPaisOrigen
     * @return IdPaisOrigen
     */
    public function setIdPaisOrigen($idPaisOrigen)
    {
        $this->idPaisOrigen = (integer) $idPaisOrigen;
        return $this;
    }

    /**
     * Get idPaisOrigen
     *
     * @return null|Integer
     */
    public function getIdPaisOrigen()
    {
        return $this->idPaisOrigen;
    }

    /**
     * Set paisOrigen
     *
     *
     *
     * @parámetro String $paisOrigen
     * @return PaisOrigen
     */
    public function setPaisOrigen($paisOrigen)
    {
        $this->paisOrigen = (string) $paisOrigen;
        return $this;
    }

    /**
     * Get paisOrigen
     *
     * @return null|String
     */
    public function getPaisOrigen()
    {
        return $this->paisOrigen;
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
        $this->estado = (string) $estado;
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
     * Set identificadorModificacion
     *
     * Identificador del modificador del registro
     *
     * @parámetro String $identificadorModificacion
     * @return IdentificadorModificacion
     */
    public function setIdentificadorModificacion($identificadorModificacion)
    {
        $this->identificadorModificacion = (string) $identificadorModificacion;
        return $this;
    }

    /**
     * Get identificadorModificacion
     *
     * @return null|String
     */
    public function getIdentificadorModificacion()
    {
        return $this->identificadorModificacion;
    }

    /**
     * Set fechaModificacion
     *
     * Fecha de modificación del registro
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string) $fechaModificacion;
        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return null|Date
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
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
     * @return FabricanteFormuladorModelo
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
