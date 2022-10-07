<?php
/**
 * Modelo SitiosAreasProductosModelo
 *
 * Este archivo se complementa con el archivo   SitiosAreasProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    SitiosAreasProductosModelo
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SitiosAreasProductosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro
     */
    protected $idSitioAreaProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud de certificación BPA a la que hace referencia el registro
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
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operador, cédula o RUC del registro
     */
    protected $identificadorOperador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del operador dueño del sitio registrado
     */
    protected $identificadorSitio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del sitio registrado
     */
    protected $idSitio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del sitio registrado
     */
    protected $nombreSitio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del área registrada
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del área registrada
     */
    protected $nombreArea;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del subtipo de producto
     */
    protected $idSubtipoProducto;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del subtipo de producto
     */
    protected $nombreSubtipoProducto;
    
    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto registrado
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto registrado
     */
    protected $nombreProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la operación que mantiene el operador en el sitio, área y para el producto seleccionado
     */
    protected $idOperacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la operación que mantiene el operador para el sitio, área y producto seleccionado
     */
    protected $nombreOperacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tamaño del área designada para la actividad (superficie asignada)
     */
    protected $superficie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Número de animales registrados para las categorías de porcinos, vacas, aves, cuyes
     */
    protected $numAnimales;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro, que refleja el estado de la solicitud:
     *      - Aprobado
     *      - Expirado
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
     * Nombre de la tabla: sitios_areas_productos
     */
    private $tabla = "sitios_areas_productos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_sitio_area_producto";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificacion_bpa"."sitios_areas_productos_id_sitio_area_producto_seq';

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
            throw new \Exception('Clase Modelo: SitiosAreasProductosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SitiosAreasProductosModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idSitioAreaProducto
     *
     * Identificador del registro
     *
     * @parámetro Integer $idSitioAreaProducto
     * @return IdSitioAreaProducto
     */
    public function setIdSitioAreaProducto($idSitioAreaProducto)
    {
        $this->idSitioAreaProducto = (integer) $idSitioAreaProducto;
        return $this;
    }

    /**
     * Get idSitioAreaProducto
     *
     * @return null|Integer
     */
    public function getIdSitioAreaProducto()
    {
        return $this->idSitioAreaProducto;
    }

    /**
     * Set idSolicitud
     *
     * Identificador de la solicitud de certificación BPA a la que hace referencia el registro
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
     * Set identificadorOperador
     *
     * Identificador del operador, cédula o RUC del registro
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
     * Set identificadorSitio
     *
     * Identificador del operador dueño del sitio registrado
     *
     * @parámetro String $identificadorSitio
     * @return IdentificadorSitio
     */
    public function setIdentificadorSitio($identificadorSitio)
    {
        $this->identificadorSitio = (string) $identificadorSitio;
        return $this;
    }

    /**
     * Get identificadorSitio
     *
     * @return null|String
     */
    public function getIdentificadorSitio()
    {
        return $this->identificadorSitio;
    }

    /**
     * Set idSitio
     *
     * Identificador del sitio registrado
     *
     * @parámetro Integer $idSitio
     * @return IdSitio
     */
    public function setIdSitio($idSitio)
    {
        $this->idSitio = (integer) $idSitio;
        return $this;
    }

    /**
     * Get idSitio
     *
     * @return null|Integer
     */
    public function getIdSitio()
    {
        return $this->idSitio;
    }

    /**
     * Set nombreSitio
     *
     * Nombre del sitio registrado
     *
     * @parámetro String $nombreSitio
     * @return NombreSitio
     */
    public function setNombreSitio($nombreSitio)
    {
        $this->nombreSitio = (string) $nombreSitio;
        return $this;
    }

    /**
     * Get nombreSitio
     *
     * @return null|String
     */
    public function getNombreSitio()
    {
        return $this->nombreSitio;
    }

    /**
     * Set idArea
     *
     * Identificador del área registrada
     *
     * @parámetro Integer $idArea
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (integer) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|Integer
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set nombreArea
     *
     * Nombre del área registrada
     *
     * @parámetro String $nombreArea
     * @return NombreArea
     */
    public function setNombreArea($nombreArea)
    {
        $this->nombreArea = (string) $nombreArea;
        return $this;
    }

    /**
     * Get nombreArea
     *
     * @return null|String
     */
    public function getNombreArea()
    {
        return $this->nombreArea;
    }
    
    /**
     * Set idSubtipoProducto
     *
     * Identificador del subtipo de producto
     *
     * @parámetro Integer $idSubtipoProducto
     * @return IdSubtipoProducto
     */
    public function setIdSubtipoProducto($idSubtipoProducto)
    {
        $this->idSubtipoProducto = (integer) $idSubtipoProducto;
        return $this;
    }
    
    /**
     * Get idSubtipoProducto
     *
     * @return null|Integer
     */
    public function getIdSubtipoProducto()
    {
        return $this->idSubtipoProducto;
    }
    
    /**
     * Set nombreSubtipoProducto
     *
     * Nombre del Subtipo producto 
     *
     * @parámetro String $nombreSubtipoProducto
     * @return NombreSubtipoProducto
     */
    public function setNombreSubtipoProducto($nombreSubtipoProducto)
    {
        $this->nombreSubtipoProducto = (string) $nombreSubtipoProducto;
        return $this;
    }
    
    /**
     * Get nombreSubtipoProducto
     *
     * @return null|String
     */
    public function getNombreSubtipoProducto()
    {
        return $this->nombreSubtipoProducto;
    }
    

    /**
     * Set idProducto
     *
     * Identificador del producto registrado
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
     * Set nombreProducto
     *
     * Nombre del producto registrado
     *
     * @parámetro String $nombreProducto
     * @return NombreProducto
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = (string) $nombreProducto;
        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return null|String
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set idOperacion
     *
     * Identificador de la operación que mantiene el operador en el sitio, área y para el producto seleccionado
     *
     * @parámetro Integer $idOperacion
     * @return IdOperacion
     */
    public function setIdOperacion($idOperacion)
    {
        $this->idOperacion = (integer) $idOperacion;
        return $this;
    }

    /**
     * Get idOperacion
     *
     * @return null|Integer
     */
    public function getIdOperacion()
    {
        return $this->idOperacion;
    }

    /**
     * Set nombreOperacion
     *
     * Nombre de la operación que mantiene el operador para el sitio, área y producto seleccionado
     *
     * @parámetro String $nombreOperacion
     * @return NombreOperacion
     */
    public function setNombreOperacion($nombreOperacion)
    {
        $this->nombreOperacion = (string) $nombreOperacion;
        return $this;
    }

    /**
     * Get nombreOperacion
     *
     * @return null|String
     */
    public function getNombreOperacion()
    {
        return $this->nombreOperacion;
    }

    /**
     * Set superficie
     *
     * Tamaño del área designada para la actividad (superficie asignada)
     *
     * @parámetro String $superficie
     * @return Superficie
     */
    public function setSuperficie($superficie)
    {
        $this->superficie = (string) $superficie;
        return $this;
    }

    /**
     * Get superficie
     *
     * @return null|String
     */
    public function getSuperficie()
    {
        return $this->superficie;
    }

    /**
     * Set numAnimales
     *
     * Número de animales registrados para las categorías de porcinos, vacas, aves, cuyes
     *
     * @parámetro Integer $numAnimales
     * @return NumAnimales
     */
    public function setNumAnimales($numAnimales)
    {
        $this->numAnimales = (integer) $numAnimales;
        return $this;
    }

    /**
     * Get numAnimales
     *
     * @return null|Integer
     */
    public function getNumAnimales()
    {
        return $this->numAnimales;
    }

    /**
     * Set estado
     *
     * Estado del registro, que refleja el estado de la solicitud:
     * - Aprobado
     * - Expirado
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
     * @return SitiosAreasProductosModelo
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
