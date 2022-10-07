<?php
/**
 * Modelo ValijasModelo
 *
 * Este archivo se complementa con el archivo   ValijasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-13
 * @uses    ValijasModelo
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ValijasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de la valija
     */
    protected $idValija;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único de identificación de valija creado por el sistema
     */
    protected $numeroValija;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha y hora de la creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la ventanilla a la que se envía la valija
     */
    protected $idVentanilla;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Nombre de la ventanilla a la que se envía la valija
     */
    protected $nombreVentanilla;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del usuario que crea el registro
     */
    protected $identificador;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Nombre del usuario que crea el registro
     */
    protected $nombreEmpleado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código único del envío de valija provisto por Correos del Ecuador o el medio de envío del paquete.
     */
    protected $guiaCorreo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la persona a la que está dirigida la valija
     */
    protected $destinatario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Dirección a la que se envía la valija
     */
    protected $direccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número telefónico de contacto del destinatario
     */
    protected $telefono;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del país al que se envía la valija
     */
    protected $idPais;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del país al que se envía la valija
     */
    protected $pais;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia a la que se envía la valija
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia a la que se envía la valija
     */
    protected $provincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del cantón al que se envía la valija
     */
    protected $idCanton;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón al que se envía la valija
     */
    protected $canton;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Información de referencia para entrega de valija
     */
    protected $referencia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Correo electrónico del destinatario de la valija
     */
    protected $email;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Descripción del contenido de la valija
     */
    protected $descripcion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha en que se registra el cambio de estado en la entrega de la valija en el campo Entrega
     */
    protected $fechaEntrega;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro
     */
    protected $estadoEntrega;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Nombre de la persona que recibe el envío
     */
    protected $nombreEntrega;

    /**
     *
     * @var String Campo no requerido
     *      Campo visible en el formulario
     *      Observaciones del proceso
     */
    protected $observaciones;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la unidad de origen de la valija
     */
    protected $idUnidadOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la unidad de origen
     */
    protected $unidadOrigen;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del remitente de la valija
     */
    protected $remitente;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_seguimiento_documental";

    /**
     * Nombre de la tabla: valijas
     */
    private $tabla = "valijas";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_valija";

    /**
     * Secuencia
     */
    private $secuencial = 'g_seguimiento_documental"."valijas_id_valija_seq';

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
            throw new \Exception('Clase Modelo: ValijasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ValijasModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_seguimiento_documental
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idValija
     *
     * Identificador único de la valija
     *
     * @parámetro Integer $idValija
     * @return IdValija
     */
    public function setIdValija($idValija)
    {
        $this->idValija = (integer) $idValija;
        return $this;
    }

    /**
     * Get idValija
     *
     * @return null|Integer
     */
    public function getIdValija()
    {
        return $this->idValija;
    }

    /**
     * Set numeroValija
     *
     * Código único de identificación de valija creado por el sistema
     *
     * @parámetro String $numeroValija
     * @return NumeroValija
     */
    public function setNumeroValija($numeroValija)
    {
        $this->numeroValija = (string) $numeroValija;
        return $this;
    }

    /**
     * Get numeroValija
     *
     * @return null|String
     */
    public function getNumeroValija()
    {
        return $this->numeroValija;
    }

    /**
     * Set fechaCreacion
     *
     * Fecha y hora de la creación del registro
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
     * Set idVentanilla
     *
     * Identificador de la ventanilla a la que se envía la valija
     *
     * @parámetro Integer $idVentanilla
     * @return IdVentanilla
     */
    public function setIdVentanilla($idVentanilla)
    {
        $this->idVentanilla = (integer) $idVentanilla;
        return $this;
    }

    /**
     * Get idVentanilla
     *
     * @return null|Integer
     */
    public function getIdVentanilla()
    {
        return $this->idVentanilla;
    }

    /**
     * Set nombreVentanilla
     *
     * Nombre de la ventanilla a la que se envía la valija
     *
     * @parámetro String $nombreVentanilla
     * @return NombreVentanilla
     */
    public function setNombreVentanilla($nombreVentanilla)
    {
        $this->nombreVentanilla = (string) $nombreVentanilla;
        return $this;
    }

    /**
     * Get nombreVentanilla
     *
     * @return null|string
     */
    public function getNombreVentanilla()
    {
        return $this->nombreVentanilla;
    }

    /**
     * Set identificador
     *
     * Identificador del usuario que crea el registro
     *
     * @parámetro String $identificador
     * @return Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = (string) $identificador;
        return $this;
    }

    /**
     * Get identificador
     *
     * @return null|String
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set nombreEmpleado
     *
     * Nombre del usuario que crea el registro
     *
     * @parámetro String $nombreEmpleado
     * @return Identificador
     */
    public function setNombreEmpleado($nombreEmpleado)
    {
        $this->nombreEmpleado = (string) $nombreEmpleado;
        return $this;
    }

    /**
     * Get nombreEmpleado
     *
     * @return null|String
     */
    public function getNombreEmpleado()
    {
        return $this->nombreEmpleado;
    }

    /**
     * Set guiaCorreo
     *
     * Código único del envío de valija provisto por Correos del Ecuador o el medio de envío del paquete.
     *
     * @parámetro String $guiaCorreo
     * @return GuiaCorreo
     */
    public function setGuiaCorreo($guiaCorreo)
    {
        $this->guiaCorreo = (string) $guiaCorreo;
        return $this;
    }

    /**
     * Get guiaCorreo
     *
     * @return null|String
     */
    public function getGuiaCorreo()
    {
        return $this->guiaCorreo;
    }

    /**
     * Set destinatario
     *
     * Nombre de la persona a la que está dirigida la valija
     *
     * @parámetro String $destinatario
     * @return Destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = (string) $destinatario;
        return $this;
    }

    /**
     * Get destinatario
     *
     * @return null|String
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set direccion
     *
     * Dirección a la que se envía la valija
     *
     * @parámetro String $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = (string) $direccion;
        return $this;
    }

    /**
     * Get direccion
     *
     * @return null|String
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * Número telefónico de contacto del destinatario
     *
     * @parámetro String $telefono
     * @return Telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = (string) $telefono;
        return $this;
    }

    /**
     * Get telefono
     *
     * @return null|String
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set idPais
     *
     * Identificador del país al que se envía la valija
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
     * Set pais
     *
     * Nombre del país al que se envía la valija
     *
     * @parámetro String $pais
     * @return Pais
     */
    public function setPais($pais)
    {
        $this->pais = (string) $pais;
        return $this;
    }

    /**
     * Get pais
     *
     * @return null|String
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set idProvincia
     *
     * Identificador de la provincia a la que se envía la valija
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
     * Nombre de la provincia a la que se envía la valija
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
     * Set idCanton
     *
     * Identificador del cantón al que se envía la valija
     *
     * @parámetro Integer $idCanton
     * @return IdCanton
     */
    public function setIdCanton($idCanton)
    {
        $this->idCanton = (integer) $idCanton;
        return $this;
    }

    /**
     * Get idCanton
     *
     * @return null|Integer
     */
    public function getIdCanton()
    {
        return $this->idCanton;
    }

    /**
     * Set canton
     *
     * Nombre del cantón al que se envía la valija
     *
     * @parámetro String $canton
     * @return Canton
     */
    public function setCanton($canton)
    {
        $this->canton = (string) $canton;
        return $this;
    }

    /**
     * Get canton
     *
     * @return null|String
     */
    public function getCanton()
    {
        return $this->canton;
    }

    /**
     * Set referencia
     *
     * Información de referencia para entrega de valija
     *
     * @parámetro String $referencia
     * @return Referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = (string) $referencia;
        return $this;
    }

    /**
     * Get referencia
     *
     * @return null|String
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set email
     *
     * Correo electrónico del destinatario de la valija
     *
     * @parámetro String $email
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return null|String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set descripcion
     *
     * Descripción del contenido de la valija
     *
     * @parámetro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = (string) $descripcion;
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
     * Set fechaEntrega
     *
     * Fecha en que se registra el cambio de estado en la entrega de la valija en el campo Entrega
     *
     * @parámetro Date $fechaEntrega
     * @return FechaEntrega
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = (string) $fechaEntrega;
        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return null|Date
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set estadoEntrega
     *
     * Estado del registro
     *
     * @parámetro String $estadoEntrega
     * @return Estado
     */
    public function setEstadoEntrega($estadoEntrega)
    {
        $this->estadoEntrega = (string) $estadoEntrega;
        return $this;
    }

    /**
     * Get estadoEntrega
     *
     * @return null|String
     */
    public function getEstadoEntrega()
    {
        return $this->estadoEntrega;
    }

    /**
     * Set nombreEntrega
     *
     * Nombre de la persona que recibe el envío
     *
     * @parámetro String $nombreEntrega
     * @return NombreEntrega
     */
    public function setNombreEntrega($nombreEntrega)
    {
        $this->nombreEntrega = (string) $nombreEntrega;
        return $this;
    }

    /**
     * Get nombreEntrega
     *
     * @return null|String
     */
    public function getNombreEntrega()
    {
        return $this->nombreEntrega;
    }

    /**
     * Set observaciones
     *
     * Observaciones del proceso
     *
     * @parámetro String $observaciones
     * @return Observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = (string) $observaciones;
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set idUnidadOrigen
     *
     * Identificador de la unidad de origen de la valija
     *
     * @parámetro string $idUnidadOrigen
     * @return IdUnidadOrigen
     */
    public function setIdUnidadOrigen($idUnidadOrigen)
    {
        $this->idUnidadOrigen = (string) $idUnidadOrigen;
        return $this;
    }

    /**
     * Get idUnidadOrigen
     *
     * @return null|string
     */
    public function getIdUnidadOrigen()
    {
        return $this->idUnidadOrigen;
    }

    /**
     * Set unidadOrigen
     *
     * Nombre de la unidad de origen
     *
     * @parámetro String $unidadOrigen
     * @return UnidadOrigen
     */
    public function setUnidadOrigen($unidadOrigen)
    {
        $this->unidadOrigen = (string) $unidadOrigen;
        return $this;
    }

    /**
     * Get unidadOrigen
     *
     * @return null|String
     */
    public function getUnidadOrigen()
    {
        return $this->unidadOrigen;
    }

    /**
     * Set remitente
     *
     * Nombre del remitente de la valija
     *
     * @parámetro String $remitente
     * @return Remitente
     */
    public function setRemitente($remitente)
    {
        $this->remitente = (string) $remitente;
        return $this;
    }

    /**
     * Get remitente
     *
     * @return null|String
     */
    public function getRemitente()
    {
        return $this->remitente;
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
     * @return ValijasModelo
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
