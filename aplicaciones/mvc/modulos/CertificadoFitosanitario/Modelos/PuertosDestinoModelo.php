<?php
/**
 * Modelo PuertosDestinoModelo
 *
 * Este archivo se complementa con el archivo   PuertosDestinoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    PuertosDestinoModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PuertosDestinoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único de la tabla
     */
    protected $idPuertoDestino;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la tabla certificado_fitosanitario (llave foránea)
     */
    protected $idCertificadoFitosanitario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la tabla puertos (puerto destino)
     */
    protected $idPuertoPaisDestino;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Campo que almacena el nombre del puerto de destino
     */
    protected $nombrePuertoPaisDestino;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_certificado_fitosanitario";

    /**
     * Nombre de la tabla: puertos_destino
     */
    private $tabla = "puertos_destino";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_puerto_destino";

    /**
     * Secuencia
     */
    private $secuencial = 'g_certificado_fitosanitario"."puertos_destino_id_puerto_destino_seq';

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
            throw new \Exception('Clase Modelo: PuertosDestinoModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PuertosDestinoModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_certificado_fitosanitario
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idPuertoDestino
     *
     * Identificador único de la tabla
     *
     * @parámetro Integer $idPuertoDestino
     * @return IdPuertoDestino
     */
    public function setIdPuertoDestino($idPuertoDestino)
    {
        $this->idPuertoDestino = (integer) $idPuertoDestino;
        return $this;
    }

    /**
     * Get idPuertoDestino
     *
     * @return null|Integer
     */
    public function getIdPuertoDestino()
    {
        return $this->idPuertoDestino;
    }

    /**
     * Set idCertificadoFitosanitario
     *
     * Identificador de la tabla certificado_fitosanitario (llave foránea)
     *
     * @parámetro Integer $idCertificadoFitosanitario
     * @return IdCertificadoFitosanitario
     */
    public function setIdCertificadoFitosanitario($idCertificadoFitosanitario)
    {
        $this->idCertificadoFitosanitario = (integer) $idCertificadoFitosanitario;
        return $this;
    }

    /**
     * Get idCertificadoFitosanitario
     *
     * @return null|Integer
     */
    public function getIdCertificadoFitosanitario()
    {
        return $this->idCertificadoFitosanitario;
    }

    /**
     * Set idPuertoPaisDestino
     *
     * Identificador de la tabla puertos (puerto destino)
     *
     * @parámetro Integer $idPuertoPaisDestino
     * @return IdPuertoPaisDestino
     */
    public function setIdPuertoPaisDestino($idPuertoPaisDestino)
    {
        $this->idPuertoPaisDestino = (integer) $idPuertoPaisDestino;
        return $this;
    }

    /**
     * Get idPuertoPaisDestino
     *
     * @return null|Integer
     */
    public function getIdPuertoPaisDestino()
    {
        return $this->idPuertoPaisDestino;
    }

    /**
     * Set nombrePuertoPaisDestino
     *
     * Campo que almacena el nombre del puerto de destino
     *
     * @parámetro String $nombrePuertoPaisDestino
     * @return NombrePuertoPaisDestino
     */
    public function setNombrePuertoPaisDestino($nombrePuertoPaisDestino)
    {
        $this->nombrePuertoPaisDestino = (string) $nombrePuertoPaisDestino;
        return $this;
    }

    /**
     * Get nombrePuertoPaisDestino
     *
     * @return null|String
     */
    public function getNombrePuertoPaisDestino()
    {
        return $this->nombrePuertoPaisDestino;
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
     * @return PuertosDestinoModelo
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
