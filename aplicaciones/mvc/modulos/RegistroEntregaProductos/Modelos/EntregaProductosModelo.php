<?php
/**
 * Modelo EntregaProductosModelo
 *
 * Este archivo se complementa con el archivo   EntregaProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    EntregaProductosModelo
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EntregaProductosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idEntrega;

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
     *      Identificador del técnico que entrega el producto
     */
    protected $identificador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la institución a la que pertenece el técnico que entrega el producto
     */
    protected $institucion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia en la que se encuentra el técnico
     */
    protected $idProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia en la que se entrega el producto
     */
    protected $provincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del beneficiario del producto (ciudadano)
     */
    protected $identificadorBeneficiario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del producto de distribución
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del producto de distribución
     */
    protected $producto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Cantidad del producto que se entrega al beneficiario
     */
    protected $cantidadEntrega;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la provincia donde se usará el producto
     */
    protected $idProvinciaUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se usará el producto
     */
    protected $provinciaUso;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del cantón donde se usará el producto
     */
    protected $idCantonUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del cantón donde se usará el producto
     */
    protected $cantonUso;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la parroquia donde se usará el producto
     */
    protected $idParroquiaUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la parroquia donde se usará el producto
     */
    protected $parroquiaUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del lugar donde se usará el producto
     */
    protected $lugarUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de uso que se dará al producto:
     *      - Individual
     *      - Asociacion
     */
    protected $tipoUso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado del registro:
     *      - activo
     *      - inactivo
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del certificado PDF con la información del producto entregado al beneficiario
     */
    protected $rutaArchivo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Bandera que indica si se emitió un certificado con el registro
     */
    protected $certificado;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Número del certificado emitido
     */
    protected $numeroCertificado;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ruta del certificado PDF firmado
     */
    protected $rutaCertificadoFirmado;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_registro_entrega_producto";

    /**
     * Nombre de la tabla: entrega_productos
     */
    private $tabla = "entrega_productos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_entrega";

    /**
     * Secuencia
     */
    private $secuencial = 'g_registro_entrega_producto"."entrega_productos_id_entrega_seq';

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
            throw new \Exception('Clase Modelo: EntregaProductosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: EntregaProductosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_registro_entrega_producto
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idEntrega
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idEntrega
     * @return IdEntrega
     */
    public function setIdEntrega($idEntrega)
    {
        $this->idEntrega = (integer) $idEntrega;
        return $this;
    }

    /**
     * Get idEntrega
     *
     * @return null|Integer
     */
    public function getIdEntrega()
    {
        return $this->idEntrega;
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
     * Identificador del técnico que entrega el producto
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
     * Set institucion
     *
     * Nombre de la institución a la que pertenece el técnico que entrega el producto
     *
     * @parámetro String $institucion
     * @return Institucion
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = (string) $institucion;
        return $this;
    }

    /**
     * Get institucion
     *
     * @return null|String
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set idProvincia
     *
     * Identificador de la provincia en la que se encuentra el técnico
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
     * Nombre de la provincia en la que se entrega el producto
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
     * Set identificadorBeneficiario
     *
     * Identificador del beneficiario del producto (ciudadano)
     *
     * @parámetro String $identificadorBeneficiario
     * @return IdentificadorBeneficiario
     */
    public function setIdentificadorBeneficiario($identificadorBeneficiario)
    {
        $this->identificadorBeneficiario = (string) $identificadorBeneficiario;
        return $this;
    }

    /**
     * Get identificadorBeneficiario
     *
     * @return null|String
     */
    public function getIdentificadorBeneficiario()
    {
        return $this->identificadorBeneficiario;
    }

    /**
     * Set idProducto
     *
     * Identificador del producto de distribución
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
     * Set producto
     *
     * Nombre del producto de distribución
     *
     * @parámetro String $producto
     * @return Producto
     */
    public function setProducto($producto)
    {
        $this->producto = (string) $producto;
        return $this;
    }

    /**
     * Get producto
     *
     * @return null|String
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set cantidadEntrega
     *
     * Cantidad del producto que se entrega al beneficiario
     *
     * @parámetro Integer $cantidadEntrega
     * @return CantidadEntrega
     */
    public function setCantidadEntrega($cantidadEntrega)
    {
        $this->cantidadEntrega = (integer) $cantidadEntrega;
        return $this;
    }

    /**
     * Get cantidadEntrega
     *
     * @return null|Integer
     */
    public function getCantidadEntrega()
    {
        return $this->cantidadEntrega;
    }

    /**
     * Set idProvinciaUso
     *
     * Identificador de la provincia donde se usará el producto
     *
     * @parámetro Integer $idProvinciaUso
     * @return IdProvinciaUso
     */
    public function setIdProvinciaUso($idProvinciaUso)
    {
        $this->idProvinciaUso = (integer) $idProvinciaUso;
        return $this;
    }

    /**
     * Get idProvinciaUso
     *
     * @return null|Integer
     */
    public function getIdProvinciaUso()
    {
        return $this->idProvinciaUso;
    }

    /**
     * Set provinciaUso
     *
     * Nombre de la provincia donde se usará el producto
     *
     * @parámetro String $provinciaUso
     * @return ProvinciaUso
     */
    public function setProvinciaUso($provinciaUso)
    {
        $this->provinciaUso = (string) $provinciaUso;
        return $this;
    }

    /**
     * Get provinciaUso
     *
     * @return null|String
     */
    public function getProvinciaUso()
    {
        return $this->provinciaUso;
    }

    /**
     * Set idCantonUso
     *
     * Identificador del cantón donde se usará el producto
     *
     * @parámetro Integer $idCantonUso
     * @return IdCantonUso
     */
    public function setIdCantonUso($idCantonUso)
    {
        $this->idCantonUso = (integer) $idCantonUso;
        return $this;
    }

    /**
     * Get idCantonUso
     *
     * @return null|Integer
     */
    public function getIdCantonUso()
    {
        return $this->idCantonUso;
    }

    /**
     * Set cantonUso
     *
     * Nombre del cantón donde se usará el producto
     *
     * @parámetro String $cantonUso
     * @return CantonUso
     */
    public function setCantonUso($cantonUso)
    {
        $this->cantonUso = (string) $cantonUso;
        return $this;
    }

    /**
     * Get cantonUso
     *
     * @return null|String
     */
    public function getCantonUso()
    {
        return $this->cantonUso;
    }

    /**
     * Set idParroquiaUso
     *
     * Identificador de la parroquia donde se usará el producto
     *
     * @parámetro Integer $idParroquiaUso
     * @return IdParroquiaUso
     */
    public function setIdParroquiaUso($idParroquiaUso)
    {
        $this->idParroquiaUso = (integer) $idParroquiaUso;
        return $this;
    }

    /**
     * Get idParroquiaUso
     *
     * @return null|Integer
     */
    public function getIdParroquiaUso()
    {
        return $this->idParroquiaUso;
    }

    /**
     * Set parroquiaUso
     *
     * Nombre de la parroquia donde se usará el producto
     *
     * @parámetro String $parroquiaUso
     * @return ParroquiaUso
     */
    public function setParroquiaUso($parroquiaUso)
    {
        $this->parroquiaUso = (string) $parroquiaUso;
        return $this;
    }

    /**
     * Get parroquiaUso
     *
     * @return null|String
     */
    public function getParroquiaUso()
    {
        return $this->parroquiaUso;
    }

    /**
     * Set lugarUso
     *
     * Nombre del lugar donde se usará el producto
     *
     * @parámetro String $lugarUso
     * @return LugarUso
     */
    public function setLugarUso($lugarUso)
    {
        $this->lugarUso = (string) $lugarUso;
        return $this;
    }

    /**
     * Get lugarUso
     *
     * @return null|String
     */
    public function getLugarUso()
    {
        return $this->lugarUso;
    }

    /**
     * Set tipoUso
     *
     * Tipo de uso que se dará al producto:
     * - Individual
     * - Asociacion
     *
     * @parámetro String $tipoUso
     * @return TipoUso
     */
    public function setTipoUso($tipoUso)
    {
        $this->tipoUso = (string) $tipoUso;
        return $this;
    }

    /**
     * Get tipoUso
     *
     * @return null|String
     */
    public function getTipoUso()
    {
        return $this->tipoUso;
    }

    /**
     * Set estado
     *
     * Estado del registro:
     * - activo
     * - inactivo
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
     * Set rutaArchivo
     *
     * Ruta del certificado PDF con la información del producto entregado al beneficiario
     *
     * @parámetro String $rutaArchivo
     * @return rutaArchivo
     */
    public function setRutaArchivo($rutaArchivo)
    {
        $this->rutaArchivo = (string) $rutaArchivo;
        return $this;
    }
    
    /**
     * Get rutaArchivo
     *
     * @return null|String
     */
    public function getRutaArchivo()
    {
        return $this->rutaArchivo;
    }
    
    /**
     * Set certificado
     *
     * Bandera que indica si se emitió un certificado con el registro
     *
     * @parámetro String $certificado
     * @return certificado
     */
    public function setCertificado($certificado)
    {
        $this->certificado = (string) $certificado;
        return $this;
    }
    
    /**
     * Get certificado
     *
     * @return null|String
     */
    public function getCertificado()
    {
        return $this->certificado;
    }
    
    /**
     * Set numeroCertificado
     *
     * Número del certificado emitido
     *
     * @parámetro String $numeroCertificado
     * @return numeroCertificado
     */
    public function setNumeroCertificado($numeroCertificado)
    {
        $this->numeroCertificado = (string) $numeroCertificado;
        return $this;
    }
    
    /**
     * Get numeroCertificado
     *
     * @return null|String
     */
    public function getNumeroCertificado()
    {
        return $this->numeroCertificado;
    }
    
    /**
     * Set rutaCertificadoFirmado
     *
     * Ruta del certificado PDF firmado
     *
     * @parámetro String $rutaArchivo
     * @return rutaCertificadoFirmado
     */
    public function setRutaCertificadoFirmado($rutaCertificadoFirmado)
    {
        $this->rutaCertificadoFirmado = (string) $rutaCertificadoFirmado;
        return $this;
    }
    
    /**
     * Get rutaCertificadoFirmado
     *
     * @return null|String
     */
    public function getRutaCertificadoFirmado()
    {
        return $this->rutaCertificadoFirmado;
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
     * @return EntregaProductosModelo
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
