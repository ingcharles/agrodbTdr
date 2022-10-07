<?php
/**
 * Modelo InventariosModelo
 *
 * Este archivo se complementa con el archivo   InventariosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @uses   InventariosModelo
 * @package Inventarios
 * @subpackage Modelo
 */
namespace Agrodb\Inventarios\Modelos;

use Agrodb\Core\ModeloBase;

class InventariosModeloMouse extends ModeloBase
{
    
//     /**
//      *
//      * @var Integer Campo requerido
//      *      Campo visible en el formulario
//      *      Clave primaria de items
//      */
//     protected $idItem;
    
//     /**
//      *
//      * @var Date Campo requerido
//      *      Campo visible en el formulario
//      *      Fecha creacion
//      */
//     protected $fechaCreacion;
    
//     /**
//      *
//      * @var String creacion Campo requerido
//      *      Campo visible en el formulario
//      *      Estado
//      */
//     protected $estado;
    
//     /**
//      *
//      * @var String Campo requerido
//      *      Campo visible en el formulario
//      *      Nombre
//      */
//     protected $observacion;
    
//     /**
//      *
//      * @var String Campo requerido
//      *      Campo visible en el formulario
//      *      Tipo item
//      */
//     protected $tipoItem;
    
//     /**
//      *
//      * @var String Campo requerido
//      *      Campo visible en el formulario
//      *      Codigo institucional
//      */
//     protected $codigoInstitucional;
    
//     /**
//      *
//      * @var String Campo requerido
//      *      Campo visible en el formulario
//      *      Serial
//      */
    
//     protected $serial;
    
    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Clave primaria de items
     */
    protected $idRaton;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Modelo
     */
    protected $modelo;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Marca
     */
    protected $marca;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Conector
     */
    protected $conector;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo
     */
    protected $tipo; 
    
    /**
     * Nombre del esquema
     */
    private $esquema = "g_inventarios";
    
    /**
     * Nombre de la tabla: servicios
     */
    private $tabla = "ratones";
    
    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_raton";
    
    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos)) {
            $this->setOptions($datos);
        }
        parent::__construct($this->esquema, $this->tabla);
    }
    
    /**
     * Permitir el acceso a la propiedad
     *
     * @parámetro string $name
     * @parámetro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: InventariosModeloMouse. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }
    
    /**
     * Permitir el acceso a la propiedad
     *
     * @parámetro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: InventariosModeloMouse. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }
    
    /**
     * Llena el modelo con datos
     *
     * @parámetro array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                    $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
//     /**
//     *Get $this->idItem
//     *
//      * @return null|number
//      */
//     public function getIdItem()
//     {
//         return $this->idItem;
//     }

//      /**
//          * Set $this->idItem
//          *
//          * Identificador unico de la tabla item
//          *
//          * @parámetro number $this->idItem
//          *
//          * @return $this->idItem
//          */
//     public function setIdItem($idItem)
//     {
//         $this->idItem = $idItem;
//     }

//     /**
//     *Get $this->fechaCreacion
//     *
//      * @return null|Date
//      */
//     public function getFechaCreacion()
//     {
//         return $this->fechaCreacion;
//     }

//      /**
//          * Set $this->fechaCreacion
//          *
//          * Fecha de creacion del registro en tabla
//          *
//          * @parámetro Date $this->fechaCreacion
//          *
//          * @return $this->fechaCreacion
//          */
//     public function setFechaCreacion($fechaCreacion)
//     {
//         $this->fechaCreacion = $fechaCreacion;
//     }

//     /**
//     *Get $this->estado
//     *
//      * @return null|string
//      */
//     public function getEstado()
//     {
//         return $this->estado;
//     }

//      /**
//          * Set $this->estado
//          *
//          * Estado del item
//          *
//          * @parámetro string $this->estado
//          *
//          * @return $this->estado
//          */
//     public function setEstado($estado)
//     {
//         $this->estado = $estado;
//     }

//     /**
//     *Get $this->observacion
//     *
//      * @return null|string
//      */
//     public function getObservacion()
//     {
//         return $this->observacion;
//     }

//      /**
//          * Set $this->observacion
//          *
//          * Observación del item
//          *
//          * @parámetro string $this->observacion
//          *
//          * @return $this->observacion
//          */
//     public function setObservacion($observacion)
//     {
//         $this->observacion = $observacion;
//     }

//     /**
//     *Get $this->tipoItem
//     *
//      * @return null|string
//      */
//     public function getTipoItem()
//     {
//         return $this->tipoItem;
//     }

//      /**
//          * Set $this->tipoItem
//          *
//          * Tipo de item registrado
//          *
//          * @parámetro string $this->tipoItem
//          *
//          * @return $this->tipoItem
//          */
//     public function setTipoItem($tipoItem)
//     {
//         $this->tipoItem = $tipoItem;
//     }

//     /**
//     *Get $this->codigoInstitucional
//     *
//      * @return null|string
//      */
//     public function getCodigoInstitucional()
//     {
//         return $this->codigoInstitucional;
//     }

//      /**
//          * Set $this->codigoInstitucional
//          *
//          * Codigo instirucional del item ingresado
//          *
//          * @parámetro string $this->codigoInstitucional
//          *
//          * @return $this->codigoInstitucional
//          */
//     public function setCodigoInstitucional($codigoInstitucional)
//     {
//         $this->codigoInstitucional = $codigoInstitucional;
//     }

//     /**
//     *Get $this->serial
//     *
//      * @return null|string
//      */
//     public function getSerial()
//     {
//         return $this->serial;
//     }

//      /**
//          * Set $this->serial
//          *
//          * Serial del item ingresado
//          *
//          * @parámetro string $this->serial
//          *
//          * @return $this->serial
//          */
//     public function setSerial($serial)
//     {
//         $this->serial = $serial;
//     }

    /**
    *Get $this->idRaton
    *
     * @return null|number
     */
    public function getIdRaton()
    {
        return $this->idRaton;
    }

     /**
         * Set $this->idRaton
         *
         * Clave primaria de la tabla ratones
         *
         * @parámetro number $this->idRaton
         *
         * @return $this->idRaton
         */
    public function setIdRaton($idRaton)
    {
        $this->idRaton = $idRaton;
    }

    /**
    *Get $this->modelo
    *
     * @return null|string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

     /**
         * Set $this->modelo
         *
         * Modelo del item registrado
         *
         * @parámetro string $this->modelo
         *
         * @return $this->modelo
         */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    /**
    *Get $this->marca
    *
     * @return null|string
     */
    public function getMarca()
    {
        return $this->marca;
    }

     /**
         * Set $this->marca
         *
         * Marca del item registrado.
         *
         * @parámetro string $this->marca
         *
         * @return $this->marca
         */
    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    /**
    *Get $this->conector
    *
     * @return null|string
     */
    public function getConector()
    {
        return $this->conector;
    }

     /**
         * Set $this->conector
         *
         * Conector del item registrado
         *
         * @parámetro string $this->conector
         *
         * @return $this->conector
         */
    public function setConector($conector)
    {
        $this->conector = $conector;
    }

    /**
    *Get $this->tipo
    *
     * @return null|string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

     /**
         * Set $this->tipo
         *
         * Tipo del itm registrado.
         *
         * @parámetro string $this->tipo
         *
         * @return $this->tipo
         */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    
    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        // TODO Auto-generated method stub
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
     * @return MuestrasModelo
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

?>