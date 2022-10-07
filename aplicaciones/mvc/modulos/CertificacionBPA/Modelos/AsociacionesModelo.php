<?php
/**
 * Modelo AsociacionesModelo
 *
 * Este archivo se complementa con el archivo   AsociacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    AsociacionesModelo
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AsociacionesModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idAsociacion;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operador que registra la asociación
     */
    protected $identificadorOperador;

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
     *      Identificador del operador que será el responsable de la asociación (cédula/RUC)
     */
    protected $identificador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la razón social de la asociación
     */
    protected $razonSocial;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Correo electrónico de la asociación
     */
    protected $correo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número de teléfono de contacto de la asociación
     */
    protected $telefono;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia de la asociación
     */
    protected $provincia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del cantón
     */
    protected $idCanton;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón de la asociación
     */
    protected $canton;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la parroquia
     */
    protected $idParroquia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la parroquia de la asociación
     */
    protected $parroquia;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Dirección de la asociación
     */
    protected $direccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del representante legal de la asociación
     */
    protected $identificadorRepresentanteLegal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del representante legal
     */
    protected $nombreRepresentanteLegal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del representate técnico de la asociación
     */
    protected $identificadorRepresentanteTecnico;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del representante técnico de la asociación
     */
    protected $nombreRepresentanteTecnico;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Correo electrónico del representante técnico
     */
    protected $correoRepresentanteTecnico;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Teléfono de contacto del representante técnico
     */
    protected $telefonoRepresentanteTecnico;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - Activo
     *      - Inactivo
     */
    protected $estadoMovilizacion;

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
     * Nombre de la tabla: asociaciones
     */
    private $tabla = "asociaciones";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_asociacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificacion_bpa"."asociaciones_id_asociacion_seq';

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
            throw new \Exception('Clase Modelo: AsociacionesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: AsociacionesModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idAsociacion
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idAsociacion
     * @return IdAsociacion
     */
    public function setIdAsociacion($idAsociacion)
    {
        $this->idAsociacion = (integer) $idAsociacion;
        return $this;
    }

    /**
     * Get idAsociacion
     *
     * @return null|Integer
     */
    public function getIdAsociacion()
    {
        return $this->idAsociacion;
    }
    
    /**
     * Set identificadorOperador
     *
     * Identificador del operador que registra la asociación
     *
     * @parámetro String $identificadorOperador
     * @return IdentificadorOperador
     */
    public function setIdentificadorOperador($identificadorOperador)
    {
        $this->identificadorOperador = (string) $identificadorOperador;
        return $this;
    }
    
    /**
     * Get identificadorOperador
     *
     * @return null|String
     */
    public function getIdentificadorOperador()
    {
        return $this->identificadorOperador;
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
     * Set identificador
     *
     * Identificador del operador que será el responsable de la asociación (cédula/RUC)
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
     * Set razonSocial
     *
     * Nombre de la razón social de la asociación
     *
     * @parámetro String $razonSocial
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (string) $razonSocial;
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return null|String
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set correo
     *
     * Correo electrónico de la asociación
     *
     * @parámetro String $correo
     * @return Correo
     */
    public function setCorreo($correo)
    {
        $this->correo = (string) $correo;
        return $this;
    }

    /**
     * Get correo
     *
     * @return null|String
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set telefono
     *
     * Número de teléfono de contacto de la asociación
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
     * Set idProvincia
     *
     * Identificador de la provincia
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
     * Nombre de la provincia de la asociación
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
     * Identificador del cantón
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
     * Nombre del cantón de la asociación
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
     * Set idParroquia
     *
     * Identificador de la parroquia
     *
     * @parámetro Integer $idParroquia
     * @return IdParroquia
     */
    public function setIdParroquia($idParroquia)
    {
        $this->idParroquia = (integer) $idParroquia;
        return $this;
    }

    /**
     * Get idParroquia
     *
     * @return null|Integer
     */
    public function getIdParroquia()
    {
        return $this->idParroquia;
    }

    /**
     * Set parroquia
     *
     * Nombre de la parroquia de la asociación
     *
     * @parámetro String $parroquia
     * @return Parroquia
     */
    public function setParroquia($parroquia)
    {
        $this->parroquia = (string) $parroquia;
        return $this;
    }

    /**
     * Get parroquia
     *
     * @return null|String
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }
    
    /**
     * Set direccion
     *
     * Dirección de la asociación
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
     * Set identificadorRepresentanteLegal
     *
     * Identificador del representante legal de la asociación
     *
     * @parámetro String $identificadorRepresentanteLegal
     * @return IdentificadorRepresentanteLegal
     */
    public function setIdentificadorRepresentanteLegal($identificadorRepresentanteLegal)
    {
        $this->identificadorRepresentanteLegal = (string) $identificadorRepresentanteLegal;
        return $this;
    }

    /**
     * Get identificadorRepresentanteLegal
     *
     * @return null|String
     */
    public function getIdentificadorRepresentanteLegal()
    {
        return $this->identificadorRepresentanteLegal;
    }

    /**
     * Set nombreRepresentanteLegal
     *
     * Nombre del representante legal
     *
     * @parámetro String $nombreRepresentanteLegal
     * @return NombreRepresentanteLegal
     */
    public function setNombreRepresentanteLegal($nombreRepresentanteLegal)
    {
        $this->nombreRepresentanteLegal = (string) $nombreRepresentanteLegal;
        return $this;
    }

    /**
     * Get nombreRepresentanteLegal
     *
     * @return null|String
     */
    public function getNombreRepresentanteLegal()
    {
        return $this->nombreRepresentanteLegal;
    }

    /**
     * Set identificadorRepresentanteTecnico
     *
     * Identificador del representate técnico de la asociación
     *
     * @parámetro String $identificadorRepresentanteTecnico
     * @return IdentificadorRepresentanteTecnico
     */
    public function setIdentificadorRepresentanteTecnico($identificadorRepresentanteTecnico)
    {
        $this->identificadorRepresentanteTecnico = (string) $identificadorRepresentanteTecnico;
        return $this;
    }

    /**
     * Get identificadorRepresentanteTecnico
     *
     * @return null|String
     */
    public function getIdentificadorRepresentanteTecnico()
    {
        return $this->identificadorRepresentanteTecnico;
    }

    /**
     * Set nombreRepresentanteTecnico
     *
     * Nombre del representante técnico de la asociación
     *
     * @parámetro String $nombreRepresentanteTecnico
     * @return NombreRepresentanteTecnico
     */
    public function setNombreRepresentanteTecnico($nombreRepresentanteTecnico)
    {
        $this->nombreRepresentanteTecnico = (string) $nombreRepresentanteTecnico;
        return $this;
    }

    /**
     * Get nombreRepresentanteTecnico
     *
     * @return null|String
     */
    public function getNombreRepresentanteTecnico()
    {
        return $this->nombreRepresentanteTecnico;
    }
    
    /**
     * Set correoRepresentanteTecnico
     *
     * Correo electrónico del representante técnico
     *
     * @parámetro String $correoRepresentanteTecnico
     * @return CorreoRepresentanteTecnico
     */
    public function setCorreoRepresentanteTecnico($correoRepresentanteTecnico)
    {
        $this->correoRepresentanteTecnico = (string) $correoRepresentanteTecnico;
        return $this;
    }
    
    /**
     * Get correoRepresentanteTecnico
     *
     * @return null|String
     */
    public function getCorreoRepresentanteTecnico()
    {
        return $this->correoRepresentanteTecnico;
    }
    
    /**
     * Set telefonoRepresentanteTecnico
     *
     * Teléfono de contacto del representante técnico
     *
     * @parámetro String $telefonoRepresentanteTecnico
     * @return TelefonoRepresentanteTecnico
     */
    public function setTelefonoRepresentanteTecnico($telefonoRepresentanteTecnico)
    {
        $this->telefonoRepresentanteTecnico = (string) $telefonoRepresentanteTecnico;
        return $this;
    }
    
    /**
     * Get telefonoRepresentanteTecnico
     *
     * @return null|String
     */
    public function getTelefonoRepresentanteTecnico()
    {
        return $this->telefonoRepresentanteTecnico;
    }
    
    /**
     * Set estado
     *
     * Estado del registro:
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
     * @return AsociacionesModelo
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
