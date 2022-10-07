<?php
/**
 * Modelo AnexosPecuariosModelo
 *
 * Este archivo se complementa con el archivo   AnexosPecuariosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    AnexosPecuariosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AnexosPecuariosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idAnexoPecuario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del documento anexo
     */
    protected $anexoPecuario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del grupo de producto
     */
    protected $idGrupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código del grupo de producto pecuario
     */
    protected $grupoProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Proceso de revisión en el que se usará el documento para Dossier Pecuario
     */
    protected $procesoRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoAnexoPecuario;

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
     * Nombre de la tabla: anexos_pecuarios
     */
    private $tabla = "anexos_pecuarios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_anexo_pecuario";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."anexos_pecuarios_id_anexo_pecuario_seq';

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
            throw new \Exception('Clase Modelo: AnexosPecuariosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: AnexosPecuariosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idAnexoPecuario
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idAnexoPecuario
     * @return IdAnexoPecuario
     */
    public function setIdAnexoPecuario($idAnexoPecuario)
    {
        $this->idAnexoPecuario = (integer) $idAnexoPecuario;
        return $this;
    }

    /**
     * Get idAnexoPecuario
     *
     * @return null|Integer
     */
    public function getIdAnexoPecuario()
    {
        return $this->idAnexoPecuario;
    }

    /**
     * Set anexoPecuario
     *
     * Nombre del documento anexo
     *
     * @parámetro String $anexoPecuario
     * @return AnexoPecuario
     */
    public function setAnexoPecuario($anexoPecuario)
    {
        $this->anexoPecuario = (string) $anexoPecuario;
        return $this;
    }

    /**
     * Get anexoPecuario
     *
     * @return null|String
     */
    public function getAnexoPecuario()
    {
        return $this->anexoPecuario;
    }

    /**
     * Set idGrupoProducto
     *
     * Identificador del grupo de producto
     *
     * @parámetro Integer $idGrupoProducto
     * @return IdGrupoProducto
     */
    public function setIdGrupoProducto($idGrupoProducto)
    {
        $this->idGrupoProducto = (integer) $idGrupoProducto;
        return $this;
    }

    /**
     * Get idGrupoProducto
     *
     * @return null|Integer
     */
    public function getIdGrupoProducto()
    {
        return $this->idGrupoProducto;
    }

    /**
     * Set grupoProducto
     *
     * Código del grupo de producto pecuario
     *
     * @parámetro String $grupoProducto
     * @return GrupoProducto
     */
    public function setGrupoProducto($grupoProducto)
    {
        $this->grupoProducto = (string) $grupoProducto;
        return $this;
    }

    /**
     * Get grupoProducto
     *
     * @return null|String
     */
    public function getGrupoProducto()
    {
        return $this->grupoProducto;
    }

    /**
     * Set procesoRevision
     *
     * Proceso de revisión en el que se usará el documento para Dossier Pecuario
     *
     * @parámetro String $procesoRevision
     * @return ProcesoRevision
     */
    public function setProcesoRevision($procesoRevision)
    {
        $this->procesoRevision = (string) $procesoRevision;
        return $this;
    }

    /**
     * Get procesoRevision
     *
     * @return null|String
     */
    public function getProcesoRevision()
    {
        return $this->procesoRevision;
    }

    /**
     * Set estadoAnexoPecuario
     *
     * Estado del registro:
     * - Activo
     * - Inactivo
     *
     * @parámetro String $estadoAnexoPecuario
     * @return EstadoAnexoPecuario
     */
    public function setEstadoAnexoPecuario($estadoAnexoPecuario)
    {
        $this->estadoAnexoPecuario = (string) $estadoAnexoPecuario;
        return $this;
    }

    /**
     * Get estadoAnexoPecuario
     *
     * @return null|String
     */
    public function getEstadoAnexoPecuario()
    {
        return $this->estadoAnexoPecuario;
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
     * @return AnexosPecuariosModelo
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
