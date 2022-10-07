<?php

/**
 * Modelo MuestrasModelo
 *
 * Este archivo se complementa con el archivo   MuestrasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       MuestrasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MuestrasModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Id de la tabla
     */
    protected $idMuestra;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Provincia
     */
    protected $idLocalizacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      CantÃ³n
     */
    protected $fkIdLocalizacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Solicitud
     */
    protected $idSolicitud;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Laboratorio
     */
    protected $idLaboratorio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Parroquia
     */
    protected $fkIdLocalizacion2;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Propietario
     */
    protected $idPersona;

    /**
     *
     * @var $PersonasModelo Tabla relacionada
     *      Propietario
     */
    protected $persona;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Referencia
     */
    protected $referenciaUbicacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de toma de la muestra
     */
    protected $fechaToma;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de envÃ­o
     */
    protected $fechaEnvio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Responsable de la toma
     */
    protected $responsableToma;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Coordenada X
     */
    protected $longitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Coordenada Y
     */
    protected $latitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Altura en msnm
     */
    protected $altura;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      ConservaciÃ³n de la muestra
     */
    protected $conservacion;

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: muestras
     */
    private $tabla = "muestras";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_muestra";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."muestras_id_muestra_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos))
        {
            $this->setOptions($datos);
            $this->setPersona($datos);
        }
        $features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
        parent::__construct($this->esquema, $this->tabla, $features);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @parÃ¡metro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: MuestrasModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parÃ¡metro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: MuestrasModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parÃ¡metro array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Set $esquema
     *
     * Nombre del esquema del mÃ³dulo
     *
     * @parÃ¡metro $esquema
     *
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idMuestra
     *
     * Clave primaria de la tabla muestra
     *
     * @parÃ¡metro Integer $idMuestra
     *
     * @return IdMuestra
     */
    public function setIdMuestra($idMuestra)
    {
        $this->idMuestra = (integer) $idMuestra;
        return $this;
    }

    /**
     * Get idMuestra
     *
     * @return null|Integer
     */
    public function getIdMuestra()
    {
        return $this->idMuestra;
    }

    /**
     * Set idLocalizacion
     *
     * Provincia
     *
     * @parÃ¡metro Integer $idLocalizacion
     *
     * @return IdLocalizacion
     */
    public function setIdLocalizacion($idLocalizacion)
    {
        if ((integer) $idLocalizacion == 0)
        {
            $this->idLocalizacion = null;
        } else
        {
            $this->idLocalizacion = (integer) $idLocalizacion;
        }
        return $this;
    }

    /**
     * Get idLocalizacion
     *
     * @return null|Integer
     */
    public function getIdLocalizacion()
    {
        return $this->idLocalizacion;
    }

    /**
     * Set fkIdLocalizacion
     *
     * CantÃ³n
     *
     * @parÃ¡metro Integer $fkIdLocalizacion
     *
     * @return FkIdLocalizacion
     */
    public function setFkIdLocalizacion($fkIdLocalizacion)
    {
        $this->fkIdLocalizacion = (integer) $fkIdLocalizacion;
        return $this;
    }

    /**
     * Get fkIdLocalizacion
     *
     * @return null|Integer
     */
    public function getFkIdLocalizacion()
    {
        return $this->fkIdLocalizacion;
    }

    /**
     * Set idSolicitud
     *
     * Solicitud
     *
     * @parÃ¡metro Integer $idSolicitud
     *
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

    function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }

    function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (integer) $idLaboratorio;
        return $this;
    }

    /**
     * Set fkIdLocalizacion2
     *
     * Parroquia
     *
     * @parÃ¡metro Integer $fkIdLocalizacion2
     *
     * @return FkIdLocalizacion2
     */
    public function setFkIdLocalizacion2($fkIdLocalizacion2)
    {
        $this->fkIdLocalizacion2 = (integer) $fkIdLocalizacion2;
        return $this;
    }

    /**
     * Get fkIdLocalizacion2
     *
     * @return null|Integer
     */
    public function getFkIdLocalizacion2()
    {
        return $this->fkIdLocalizacion2;
    }

    /**
     * Set idPersona
     *
     * Propietario
     *
     * @parÃ¡metro Integer $idPersona
     *
     * @return IdPersona
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;
    }

    /**
     * Get idPersona
     *
     * @return null|Integer
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set Persona
     *
     * Propietario de la muestra
     *
     * @parÃ¡metro $array | $PersonasModelo
     *
     * 
     */
    public function setPersona($datos)
    {
        if (is_array($datos))
        {
            $this->persona = new PersonasModelo($datos);
        } else
        {
            $this->persona = $datos;
        }
    }

    /**
     * Get Persona
     *
     * @return $PersonasModelo
     */
    public function getPersona()
    {
        if (null === $this->persona)
        {
            $this->persona = new PersonasModelo();
        }
        return $this->persona;
    }

    /**
     * Set referenciaUbicacion
     *
     * Referencia para llegar al sitio donde se tomo la muestra
     *
     * @parÃ¡metro String $referenciaUbicacion
     *
     * @return ReferenciaUbicacion
     */
    public function setReferenciaUbicacion($referenciaUbicacion)
    {
        $this->referenciaUbicacion = ValidarDatos::validarAlfa($referenciaUbicacion, $this->tabla, " Ub. Referencia de la Muestra", self::NO_REQUERIDO, 512);
        return $this;
    }

    /**
     * Get referenciaUbicacion
     *
     * @return null|String
     */
    public function getReferenciaUbicacion()
    {
        return $this->referenciaUbicacion;
    }

    /**
     * Set fechaToma
     *
     * Fecha en que se tomÃ³ la muestra
     *
     * @parÃ¡metro Date $fechaToma
     *
     * @return FechaToma
     */
    public function setFechaToma($fechaToma)
    {
        $this->fechaToma = ValidarDatos::validarFecha($fechaToma, $this->tabla, " Fecha de Toma", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaToma
     *
     * @return null|Date
     */
    public function getFechaToma()
    {
        return $this->fechaToma;
    }

    /**
     * Set fechaEnvio
     *
     * Fecha que se envÃ­a la muestra
     *
     * @parÃ¡metro Date $fechaEnvio
     *
     * @return FechaEnvio
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = ValidarDatos::validarFecha($fechaEnvio, $this->tabla, " Fecha de Envío", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return null|Date
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set responsableToma
     *
     * Responsable que toma la muestra
     *
     * @parÃ¡metro String $responsableToma
     *
     * @return ResponsableToma
     */
    public function setResponsableToma($responsableToma)
    {
        $this->responsableToma = ValidarDatos::validarAlfaEsp($responsableToma, $this->tabla, " Responsable de la Toma", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get responsableToma
     *
     * @return null|String
     */
    public function getResponsableToma()
    {
        return $this->responsableToma;
    }

    /**
     * Set longitud
     *
     * Coordenada X - Longitud
     *
     * @parÃ¡metro String $longitud
     *
     * @return Longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
        return $this;
    }

    /**
     * Get longitud
     *
     * @return null|String
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set latitud
     *
     * Coordenada Y - Latitud
     *
     * @parÃ¡metro String $latitud
     *
     * @return Latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
        return $this;
    }

    /**
     * Get latitud
     *
     * @return null|String
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set altura
     *
     * Altura (msnm)
     *
     * @parÃ¡metro String $altura
     *
     * @return Altura
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;
        return $this;
    }

    /**
     * Get altura
     *
     * @return null|String
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Set conservacion
     *
     * ConservaciÃ³n de la muestra
     *
     * @parÃ¡metro String $conservacion
     *
     * @return Conservacion
     */
    public function setConservacion($conservacion)
    {
        $this->conservacion = ValidarDatos::validarAlfa($conservacion, $this->tabla, " Conservación", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get conservacion
     *
     * @return null|String
     */
    public function getConservacion()
    {
        return $this->conservacion;
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

    public function borrarPorParametro($param, $value)
    {
        return parent::borrar($param . " = " . $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MuestrasModelo
     */
    public function buscar($id)
    {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
        return $this;
    }

    public function buscarPorParametro($param, $value)
    {
        return $this->setOptions(parent::buscar($param . " = " . $value));
    }

    public function buscarMuestraLaboratorio($idSolicitud, $idLaboratorio)
    {
        return $this->setOptions(parent::buscar("id_solicitud = $idSolicitud and id_laboratorio = $idLaboratorio"));
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
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
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
