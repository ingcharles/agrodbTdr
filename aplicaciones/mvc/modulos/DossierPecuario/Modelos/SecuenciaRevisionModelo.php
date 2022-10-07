<?php
/**
 * Modelo SecuenciaRevisionModelo
 *
 * Este archivo se complementa con el archivo   SecuenciaRevisionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    SecuenciaRevisionModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SecuenciaRevisionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idSecuenciaRevision;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud de registro de producto
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
     *      Número que indica el orden de los registros
     */
    protected $orden;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que realiza una acción
     */
    protected $identificadorEjecutor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del operador o técnico que realiza una acción sobre la solicitud
     */
    protected $nombreEjecutor;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del perfil del usuario/operador que realiza una acción:
     *      - Operador
     *      - Financiero
     *      - Administrador
     *      - Tecnico
     */
    protected $perfil;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia donde se realizó la acción
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se realiza la acción
     */
    protected $provincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado de la revisión de la solicitud:
     *      - pago
     *      - Recibida
     *      - EnTramite
     *      - Subsanacion
     *      - Aprobado
     *      - Rechazado
     */
    protected $estadoRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Mostrará la observación emitida por el revisor
     */
    protected $comentarioRevision;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del técnico asignado para la revisión. Se llena solamente en el proceso del administrador
     */
    protected $identificadorTecnicoAsignado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del técnico asignado para la revisión.
     */
    protected $nombreTecnicoAsignado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Mostrará mensajes genéricos para denotar la acción realizada por los ejecutores:
     *      - pago -> Usuario remite solicitud a pago
     *      - Recibida -> Financiero remite solicitud a Administrador
     *      - EnTramite -> Administrador asigna solicitud a Técnico (Admin), Usuario remite respuesta (usuario)
     *      - Subsanacion, Aprobado, Rechazado -> Técnico revisa solicitud
     */
    protected $accion;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_dossier_pecuario_mvc";

    /**
     * Nombre de la tabla: secuencia_revision
     */
    private $tabla = "secuencia_revision";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_secuencia_revision";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."secuencia_revision_id_secuencia_revision_seq';

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
            throw new \Exception('Clase Modelo: SecuenciaRevisionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SecuenciaRevisionModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_dossier_pecuario_mvc
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSecuenciaRevision
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idSecuenciaRevision
     * @return IdSecuenciaRevision
     */
    public function setIdSecuenciaRevision($idSecuenciaRevision)
    {
        $this->idSecuenciaRevision = (integer) $idSecuenciaRevision;
        return $this;
    }

    /**
     * Get idSecuenciaRevision
     *
     * @return null|Integer
     */
    public function getIdSecuenciaRevision()
    {
        return $this->idSecuenciaRevision;
    }

    /**
     * Set idSolicitud
     *
     * Identificador de la solicitud de registro de producto
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
     * Set orden
     *
     * Número que indica el orden de los registros
     *
     * @parámetro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = (integer) $orden;
        return $this;
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
     * Set identificadorEjecutor
     *
     * Identificador del usuario que realiza una acción
     *
     * @parámetro String $identificadorEjecutor
     * @return IdentificadorEjecutor
     */
    public function setIdentificadorEjecutor($identificadorEjecutor)
    {
        $this->identificadorEjecutor = (string) $identificadorEjecutor;
        return $this;
    }

    /**
     * Get identificadorEjecutor
     *
     * @return null|String
     */
    public function getIdentificadorEjecutor()
    {
        return $this->identificadorEjecutor;
    }

    /**
     * Set nombreEjecutor
     *
     * Nombre del operador o técnico que realiza una acción sobre la solicitud
     *
     * @parámetro String $nombreEjecutor
     * @return NombreEjecutor
     */
    public function setNombreEjecutor($nombreEjecutor)
    {
        $this->nombreEjecutor = (string) $nombreEjecutor;
        return $this;
    }

    /**
     * Get nombreEjecutor
     *
     * @return null|String
     */
    public function getNombreEjecutor()
    {
        return $this->nombreEjecutor;
    }

    /**
     * Set perfil
     *
     * Nombre del perfil del usuario/operador que realiza una acción:
     * - Operador
     * - Financiero
     * - Administrador
     * - Tecnico
     *
     * @parámetro String $perfil
     * @return Perfil
     */
    public function setPerfil($perfil)
    {
        $this->perfil = (string) $perfil;
        return $this;
    }

    /**
     * Get perfil
     *
     * @return null|String
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set idProvincia
     *
     * Identificador de la provincia donde se realizó la acción
     *
     * @parámetro Integer $idProvincia
     * @return IdProvincia
     */
    public function setIdProvincia($idProvincia)
    {
        $this->idProvincia = (integer) $idProvincia;
        return $this;
    }

    /**
     * Get idProvincia
     *
     * @return null|Integer
     */
    public function getIdProvincia()
    {
        return $this->idProvincia;
    }

    /**
     * Set provincia
     *
     * Nombre de la provincia donde se realiza la acción
     *
     * @parámetro String $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
        return $this;
    }

    /**
     * Get provincia
     *
     * @return null|String
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set estadoRevision
     *
     * Estado de la revisión de la solicitud:
     * - pago
     * - Recibida
     * - EnTramite
     * - Subsanacion
     * - Aprobado
     * - Rechazado
     *
     * @parámetro String $estadoRevision
     * @return EstadoRevision
     */
    public function setEstadoRevision($estadoRevision)
    {
        $this->estadoRevision = (string) $estadoRevision;
        return $this;
    }

    /**
     * Get estadoRevision
     *
     * @return null|String
     */
    public function getEstadoRevision()
    {
        return $this->estadoRevision;
    }

    /**
     * Set comentarioRevision
     *
     * Mostrará la observación emitida por el revisor
     *
     * @parámetro String $comentarioRevision
     * @return ComentarioRevision
     */
    public function setComentarioRevision($comentarioRevision)
    {
        $this->comentarioRevision = (string) $comentarioRevision;
        return $this;
    }

    /**
     * Get comentarioRevision
     *
     * @return null|String
     */
    public function getComentarioRevision()
    {
        return $this->comentarioRevision;
    }

    /**
     * Set identificadorTecnicoAsignado
     *
     * Identificador del técnico asignado para la revisión. Se llena solamente en el proceso del administrador
     *
     * @parámetro String $identificadorTecnicoAsignado
     * @return IdentificadorTecnicoAsignado
     */
    public function setIdentificadorTecnicoAsignado($identificadorTecnicoAsignado)
    {
        $this->identificadorTecnicoAsignado = (string) $identificadorTecnicoAsignado;
        return $this;
    }

    /**
     * Get identificadorTecnicoAsignado
     *
     * @return null|String
     */
    public function getIdentificadorTecnicoAsignado()
    {
        return $this->identificadorTecnicoAsignado;
    }

    /**
     * Set nombreTecnicoAsignado
     *
     * Nombre del técnico asignado para la revisión.
     *
     * @parámetro String $nombreTecnicoAsignado
     * @return NombreTecnicoAsignado
     */
    public function setNombreTecnicoAsignado($nombreTecnicoAsignado)
    {
        $this->nombreTecnicoAsignado = (string) $nombreTecnicoAsignado;
        return $this;
    }

    /**
     * Get nombreTecnicoAsignado
     *
     * @return null|String
     */
    public function getNombreTecnicoAsignado()
    {
        return $this->nombreTecnicoAsignado;
    }

    /**
     * Set accion
     *
     * Mostrará mensajes genéricos para denotar la acción realizada por los ejecutores:
     * - pago -> Usuario remite solicitud a pago
     * - Recibida -> Financiero remite solicitud a Administrador
     * - EnTramite -> Administrador asigna solicitud a Técnico (Admin), Usuario remite respuesta (usuario)
     * - Subsanacion, Aprobado, Rechazado -> Técnico revisa solicitud
     *
     * @parámetro String $accion
     * @return Accion
     */
    public function setAccion($accion)
    {
        $this->accion = (string) $accion;
        return $this;
    }

    /**
     * Get accion
     *
     * @return null|String
     */
    public function getAccion()
    {
        return $this->accion;
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
     * @return SecuenciaRevisionModelo
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
