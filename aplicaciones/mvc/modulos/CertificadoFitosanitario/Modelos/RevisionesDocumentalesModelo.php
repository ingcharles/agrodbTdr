<?php
/**
 * Modelo RevisionesDocumentalesModelo
 *
 * Este archivo se complementa con el archivo   RevisionesDocumentalesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    RevisionesDocumentalesModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RevisionesDocumentalesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idRevisionDocumental;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del técnico que realiza la inspección
     */
    protected $identificadorInspector;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia de la inspección
     */
    protected $idProvinciaRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia a la que se realiza la revisión documental
     */
    protected $provinciaRevision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de la solicitud que se va a revisar
     */
    protected $idSolicitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de solicitud evaluada:
     *      - ornamentales
     *      - musaceas
     *      - otros
     */
    protected $tipoSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia a la que se realiza la revisión documental
     */
    protected $idProvinciaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia a la que se realiza la revisión documental
     */
    protected $provinciaInspeccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del área a la que se realiza la revisión documental
     */
    protected $idAreaInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del área a la que se realiza la revisión documental
     */
    protected $nombreAreaInspeccion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto al que se realiza la revisión documental
     */
    protected $idProductoInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto al que se realiza la revisión documental
     */
    protected $nombreProductoInspeccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observaciones realizadas por el técnico en su revisión documental
     */
    protected $observacionRevision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de revisión realizada a la solicitud
     */
    protected $numRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Resultado de la revisión documental realizada por el técnico:
     *      - Pago (Aprobado)
     *      - DevueltoTecnico (Para remitir nuevamente a inspección para revisión del técnico
     *      - Subsanacion (Para remitir al usuario)
     */
    protected $estado;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_certificado_fitosanitario";

    /**
     * Nombre de la tabla: revisiones_documentales
     */
    private $tabla = "revisiones_documentales";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_revision_documental";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificado_fitosanitario"."revisiones_documentales_id_revision_documental_seq';

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
            throw new \Exception('Clase Modelo: RevisionesDocumentalesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: RevisionesDocumentalesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificado_fitosanitario
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idRevisionDocumental
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idRevisionDocumental
     * @return IdRevisionDocumental
     */
    public function setIdRevisionDocumental($idRevisionDocumental)
    {
        $this->idRevisionDocumental = (integer) $idRevisionDocumental;
        return $this;
    }

    /**
     * Get idRevisionDocumental
     *
     * @return null|Integer
     */
    public function getIdRevisionDocumental()
    {
        return $this->idRevisionDocumental;
    }

    /**
     * Set fechaCreacion
     *
     * Fecha de creación del registro
     *
     * @parámetro Date $fechaCreacion
     * @return FechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = (string) $fechaCreacion;
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
     * Set identificadorInspector
     *
     * Identificador del técnico que realiza la inspección
     *
     * @parámetro String $identificadorInspector
     * @return IdentificadorInspector
     */
    public function setIdentificadorInspector($identificadorInspector)
    {
        $this->identificadorInspector = (string) $identificadorInspector;
        return $this;
    }

    /**
     * Get identificadorInspector
     *
     * @return null|String
     */
    public function getIdentificadorInspector()
    {
        return $this->identificadorInspector;
    }

    /**
     * Set idProvinciaRevision
     *
     * Identificador de la provincia de la inspección
     *
     * @parámetro Integer $idProvinciaRevision
     * @return IdProvinciaRevision
     */
    public function setIdProvinciaRevision($idProvinciaRevision)
    {
        $this->idProvinciaRevision = (integer) $idProvinciaRevision;
        return $this;
    }

    /**
     * Get idProvinciaRevision
     *
     * @return null|Integer
     */
    public function getIdProvinciaRevision()
    {
        return $this->idProvinciaRevision;
    }

    /**
     * Set provinciaRevision
     *
     * Nombre de la provincia a la que se realiza la revisión documental
     *
     * @parámetro String $provinciaRevision
     * @return ProvinciaRevision
     */
    public function setProvinciaRevision($provinciaRevision)
    {
        $this->provinciaRevision = (string) $provinciaRevision;
        return $this;
    }

    /**
     * Get provinciaRevision
     *
     * @return null|String
     */
    public function getProvinciaRevision()
    {
        return $this->provinciaRevision;
    }

    /**
     * Set idSolicitud
     *
     * Identificador único de la solicitud que se va a revisar
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = (integer) $idSolicitud;
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
     * Set tipoSolicitud
     *
     * Tipo de solicitud evaluada:
     * - ornamentales
     * - musaceas
     * - otros
     *
     * @parámetro String $tipoSolicitud
     * @return TipoSolicitud
     */
    public function setTipoSolicitud($tipoSolicitud)
    {
        $this->tipoSolicitud = (string) $tipoSolicitud;
        return $this;
    }

    /**
     * Get tipoSolicitud
     *
     * @return null|String
     */
    public function getTipoSolicitud()
    {
        return $this->tipoSolicitud;
    }

    /**
     * Set idProvinciaInspeccion
     *
     * Identificador de la provincia a la que se realiza la revisión documental
     *
     * @parámetro Integer $idProvinciaInspeccion
     * @return IdProvinciaInspeccion
     */
    public function setIdProvinciaInspeccion($idProvinciaInspeccion)
    {
        $this->idProvinciaInspeccion = (integer) $idProvinciaInspeccion;
        return $this;
    }

    /**
     * Get idProvinciaInspeccion
     *
     * @return null|Integer
     */
    public function getIdProvinciaInspeccion()
    {
        return $this->idProvinciaInspeccion;
    }

    /**
     * Set provinciaInspeccion
     *
     * Nombre de la provincia a la que se realiza la revisión documental
     *
     * @parámetro String $provinciaInspeccion
     * @return ProvinciaInspeccion
     */
    public function setProvinciaInspeccion($provinciaInspeccion)
    {
        $this->provinciaInspeccion = (string) $provinciaInspeccion;
        return $this;
    }

    /**
     * Get provinciaInspeccion
     *
     * @return null|String
     */
    public function getProvinciaInspeccion()
    {
        return $this->provinciaInspeccion;
    }

    /**
     * Set idAreaInspeccion
     *
     * Identificador del área a la que se realiza la revisión documental
     *
     * @parámetro Integer $idAreaInspeccion
     * @return IdAreaInspeccion
     */
    public function setIdAreaInspeccion($idAreaInspeccion)
    {
        $this->idAreaInspeccion = (integer) $idAreaInspeccion;
        return $this;
    }

    /**
     * Get idAreaInspeccion
     *
     * @return null|Integer
     */
    public function getIdAreaInspeccion()
    {
        return $this->idAreaInspeccion;
    }

    /**
     * Set nombreAreaInspeccion
     *
     * Nombre del área a la que se realiza la revisión documental
     *
     * @parámetro String $nombreAreaInspeccion
     * @return NombreAreaInspeccion
     */
    public function setNombreAreaInspeccion($nombreAreaInspeccion)
    {
        $this->nombreAreaInspeccion = (string) $nombreAreaInspeccion;
        return $this;
    }

    /**
     * Get nombreAreaInspeccion
     *
     * @return null|String
     */
    public function getNombreAreaInspeccion()
    {
        return $this->nombreAreaInspeccion;
    }

    /**
     * Set idProductoInspeccion
     *
     * Identificador del producto al que se realiza la revisión documental
     *
     * @parámetro Integer $idProductoInspeccion
     * @return IdProductoInspeccion
     */
    public function setIdProductoInspeccion($idProductoInspeccion)
    {
        $this->idProductoInspeccion = (integer) $idProductoInspeccion;
        return $this;
    }

    /**
     * Get idProductoInspeccion
     *
     * @return null|Integer
     */
    public function getIdProductoInspeccion()
    {
        return $this->idProductoInspeccion;
    }

    /**
     * Set nombreProductoInspeccion
     *
     * Nombre del producto al que se realiza la revisión documental
     *
     * @parámetro String $nombreProductoInspeccion
     * @return NombreProductoInspeccion
     */
    public function setNombreProductoInspeccion($nombreProductoInspeccion)
    {
        $this->nombreProductoInspeccion = (string) $nombreProductoInspeccion;
        return $this;
    }

    /**
     * Get nombreProductoInspeccion
     *
     * @return null|String
     */
    public function getNombreProductoInspeccion()
    {
        return $this->nombreProductoInspeccion;
    }

    /**
     * Set observacionRevision
     *
     * Observaciones realizadas por el técnico en su revisión documental
     *
     * @parámetro String $observacionRevision
     * @return ObservacionRevision
     */
    public function setObservacionRevision($observacionRevision)
    {
        $this->observacionRevision = (string) $observacionRevision;
        return $this;
    }

    /**
     * Get observacionRevision
     *
     * @return null|String
     */
    public function getObservacionRevision()
    {
        return $this->observacionRevision;
    }

    /**
     * Set numRevision
     *
     * Número de revisión realizada a la solicitud
     *
     * @parámetro Integer $numRevision
     * @return NumRevision
     */
    public function setNumRevision($numRevision)
    {
        $this->numRevision = (integer) $numRevision;
        return $this;
    }

    /**
     * Get numRevision
     *
     * @return null|Integer
     */
    public function getNumRevision()
    {
        return $this->numRevision;
    }

    /**
     * Set estado
     *
     * Resultado de la revisión documental realizada por el técnico:
     * - Pago (Aprobado)
     * - DevueltoTecnico (Para remitir nuevamente a inspección para revisión del técnico
     * - Subsanacion (Para remitir al usuario)
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
     * @return RevisionesDocumentalesModelo
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
