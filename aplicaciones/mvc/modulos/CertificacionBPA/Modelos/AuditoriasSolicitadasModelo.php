<?php
/**
 * Modelo AuditoriasSolicitadasModelo
 *
 * Este archivo se complementa con el archivo   AuditoriasSolicitadasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    AuditoriasSolicitadasModelo
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AuditoriasSolicitadasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idAuditoriaSolicitada;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud de certificación BPA a la que pertenece el registro
     */
    protected $idSolicitud;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del tipo de auditoría
     */
    protected $idTipoAuditoria;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del tipo de auditoría solicitada por el operador
     */
    protected $tipoAuditoria;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del pedido de auditoría:
     *      - Activo
     *      - Inactivo
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
    private $esquema = "g_certificacion_bpa";

    /**
     * Nombre de la tabla: auditorias_solicitadas
     */
    private $tabla = "auditorias_solicitadas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_auditoria_solicitada";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificacion_bpa"."auditorias_solicitadas_id_auditoria_solicitada_seq';

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
            throw new \Exception('Clase Modelo: AuditoriasSolicitadasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: AuditoriasSolicitadasModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificacion_bpa
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idAuditoriaSolicitada
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idAuditoriaSolicitada
     * @return IdAuditoriaSolicitada
     */
    public function setIdAuditoriaSolicitada($idAuditoriaSolicitada)
    {
        $this->idAuditoriaSolicitada = (integer) $idAuditoriaSolicitada;
        return $this;
    }

    /**
     * Get idAuditoriaSolicitada
     *
     * @return null|Integer
     */
    public function getIdAuditoriaSolicitada()
    {
        return $this->idAuditoriaSolicitada;
    }

    /**
     * Set idSolicitud
     *
     * Identificador de la solicitud de certificación BPA a la que pertenece el registro
     *
     * @parámetro Integer $idSolicitudBpa
     * @return IdSolicitudBpa
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
     * Set idTipoAuditoria
     *
     * Identificador del tipo de auditoría
     *
     * @parámetro Integer $idTipoAuditoria
     * @return IdTipoAuditoria
     */
    public function setIdTipoAuditoria($idTipoAuditoria)
    {
        $this->idTipoAuditoria = (integer) $idTipoAuditoria;
        return $this;
    }

    /**
     * Get idTipoAuditoria
     *
     * @return null|Integer
     */
    public function getIdTipoAuditoria()
    {
        return $this->idTipoAuditoria;
    }

    /**
     * Set tipoAuditoria
     *
     * Nombre del tipo de auditoría solicitada por el operador
     *
     * @parámetro String $tipoAuditoria
     * @return TipoAuditoria
     */
    public function setTipoAuditoria($tipoAuditoria)
    {
        $this->tipoAuditoria = (string) $tipoAuditoria;
        return $this;
    }

    /**
     * Get tipoAuditoria
     *
     * @return null|String
     */
    public function getTipoAuditoria()
    {
        return $this->tipoAuditoria;
    }
    
    /**
     * Set estado
     *
     * Estado del pedido de auditoría:
     * - Activo
     * - Inactivo
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
     * @return AuditoriasSolicitadasModelo
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
