<?php

/**
 * Modelo DocumentosReactivosModelo
 *
 * Este archivo se complementa con el archivo   DocumentosReactivosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DocumentosReactivosModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DocumentosReactivosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idDocumentosReactivos;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoBodega;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $nombreArchivo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $estado;

    /**
     * Descripción del formualrio
     * @var String 
     * Campo opcional
     * Campo visible en el formulario
     */
    protected $descripcion;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_reactivos";

    /**
     * Nombre de la tabla: documentos_reactivos
     * 
     */
    Private $tabla = "documentos_reactivos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_documentos_reactivos";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."documentos_reactivos_id_documentos_reactivos_seq';

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
            throw new \Exception('Clase Modelo: DocumentosReactivosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: BodegasModelo. Propiedad especificada invalida: get' . $name);
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
            $key_original = $key;
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
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
        foreach ($this->campos as $key => $value)
        {
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idDocumentosReactivos
     *
     *
     *
     * @parámetro Integer $idDocumentosReactivos
     * @return IdDocumentosReactivos
     */
    public function setIdDocumentosReactivos($idDocumentosReactivos)
    {
        $this->idDocumentosReactivos = (Integer) $idDocumentosReactivos;
        return $this;
    }

    /**
     * Get idDocumentosReactivos
     *
     * @return null|Integer
     */
    public function getIdDocumentosReactivos()
    {
        return $this->idDocumentosReactivos;
    }

    /**
     * Set idSolicitudRequerimiento
     *
     *
     *
     * @parÃ¡metro Integer $idSolicitudRequerimiento
     * @return IdSolicitudRequerimiento
     */
    public function setIdSolicitudRequerimiento($idSolicitudRequerimiento)
    {
        $this->idSolicitudRequerimiento = (Integer) $idSolicitudRequerimiento;
        return $this;
    }

    /**
     * Set idReactivoBodega
     *
     *
     *
     * @parÃ¡metro Integer $idReactivoBodega
     * @return IdReactivoBodega
     */
    public function setIdReactivoBodega($idReactivoBodega)
    {
        $this->idReactivoBodega = (Integer) $idReactivoBodega;
        return $this;
    }

    /**
     * Set nombreArchivo
     *
     * @parÃ¡metro String $nombreArchivo
     * @return NombreArchivo
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = ValidarDatos::validarAlfaEsp($nombreArchivo, $this->tabla, " Nombre del Archivo", self::NO_REQUERIDO, 128);
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
     * Set estado
     *
     *
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::NO_REQUERIDO, 8);
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
     * Configura la despcripcion del certificado del reactivo
     * @param type $descripcion
     * @return \Agrodb\Reactivos\Modelos\DocumentosReactivosModelo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Obtienen la descripción del certificado del reactivo
     * @return type
     */
    public function getDescripcion()
    {
        return $this->descripcion;
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
     * @return DocumentosReactivosModelo
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
