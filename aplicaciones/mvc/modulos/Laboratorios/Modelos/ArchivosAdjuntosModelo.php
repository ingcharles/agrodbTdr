<?php

/**
 * Modelo ArchivosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo   ArchivosAdjuntosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ArchivosAdjuntosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ArchivosAdjuntosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de archivos de adjuntos
     */
    protected $idArchivosAdjuntos;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de parametros de servicio
     */
    protected $idParametrosServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de detalle de solicitud
     */
    protected $idDetalleSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre
     */
    protected $nombreArchivo;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha de subido
     */
    protected $fechaSubido;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: archivos_adjuntos
     * 
     */
    Private $tabla = "archivos_adjuntos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_archivos_adjuntos";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."archivos_adjuntos_id_archivos_adjuntos_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
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
     * @parÃ¡metro  string $name 
     * @parÃ¡metro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: ArchivosAdjuntosModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: ArchivosAdjuntosModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parÃ¡metro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string)
                {
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
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idArchivosAdjuntos
     *
     * Secuencial (PK) de la tabla archivos_adjuntos
     *
     * @parÃ¡metro Integer $idArchivosAdjuntos
     * @return IdArchivosAdjuntos
     */
    public function setIdArchivosAdjuntos($idArchivosAdjuntos)
    {
        if (empty($idArchivosAdjuntos))
        {
            $idArchivosAdjuntos = "No informa";
        }
        $this->idArchivosAdjuntos = (Integer) $idArchivosAdjuntos;
        return $this;
    }

    /**
     * Get idArchivosAdjuntos
     *
     * @return null|Integer
     */
    public function getIdArchivosAdjuntos()
    {
        return $this->idArchivosAdjuntos;
    }

    /**
     * Set idParametrosServicio
     *
     * Secuencial (PK) de la tabla parametros_servicio
     *
     * @parÃ¡metro Integer $idParametrosServicio
     * @return IdParametrosServicio
     */
    public function setIdParametrosServicio($idParametrosServicio)
    {
        if (empty($idParametrosServicio))
        {
            $idParametrosServicio = "No informa";
        }
        $this->idParametrosServicio = (Integer) $idParametrosServicio;
        return $this;
    }

    /**
     * Get idParametrosServicio
     *
     * @return null|Integer
     */
    public function getIdParametrosServicio()
    {
        return $this->idParametrosServicio;
    }

    /**
     * Set idDetalleSolicitud
     *
     * Secuencial (PK) de la tabla de detalle_solicitud
     *
     * @parÃ¡metro Integer $idDetalleSolicitud
     * @return IdDetalleSolicitud
     */
    public function setIdDetalleSolicitud($idDetalleSolicitud)
    {
        if (empty($idDetalleSolicitud))
        {
            $idDetalleSolicitud = "No informa";
        }
        $this->idDetalleSolicitud = (Integer) $idDetalleSolicitud;
        return $this;
    }

    /**
     * Get idDetalleSolicitud
     *
     * @return null|Integer
     */
    public function getIdDetalleSolicitud()
    {
        return $this->idDetalleSolicitud;
    }

    /**
     * Set nombreArchivo
     *
     * Nombre del archivo adjunto con lo cual el usuario lo puede identificar
     *
     * @parÃ¡metro String $nombreArchivo
     * @return NombreArchivo
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = ValidarDatos::validarAlfaEsp($nombreArchivo, $this->tabla, " Nombre del Archivo", self::REQUERIDO, 256);
        return $this;
    }

    /**
     * Get nombreArchivo
     *
     * @return null|String
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }

    /**
     * Set fechaSubido
     *
     * Fecha que fue subido el archivo adjunto
     *
     * @parÃ¡metro Date $fechaSubido
     * @return FechaSubido
     */
    public function setFechaSubido($fechaSubido)
    {
        $this->fechaSubido = ValidarDatos::validarFecha($fechaSubido, $this->tabla, " Fecha de subida", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaSubido
     *
     * @return null|Date
     */
    public function getFechaSubido()
    {
        return $this->fechaSubido;
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
     * @return ArchivosAdjuntosModelo
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
