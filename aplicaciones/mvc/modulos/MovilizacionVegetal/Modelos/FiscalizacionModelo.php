<?php
/**
 * Modelo FiscalizacionModelo
 *
 * Este archivo se complementa con el archivo   FiscalizacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    FiscalizacionModelo
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionVegetal\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FiscalizacionModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro.
     */
    protected $idFiscalizacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del registro de movilización
     */
    protected $idMovilizacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;
    
    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de fiscalizacion del registro
     */
    protected $fechaFiscalizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Identificador del técnico AGR fiscalizador del registro
     */
    protected $identificadorFiscalizador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del técnico AGR fiscalizador del registro
     */
    protected $nombreFiscalizador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la provincia donde se realiza la fiscalización, provincia del técnico.
     */
    protected $provinciaFiscalizacion;
    
    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Resultado de la fiscalización de la movilización:
     *      -Positivo
     *      -Negativo
     */
    protected $resultadoFiscalizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Acción correctiva a la movilización dad apor el técnico fiscalizador:
     *      -Fiscalización correcta
     *      -Modificar permiso
     *      -Aplicación de medidas fitosanitarias de emergencia
     */
    protected $accionCorrectiva;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Observación de la fiscalización
     */
    protected $observacionFiscalizacion;

    /**
    * @var String
    * Campo requerido
    * Campo visible en el formulario
    * Causa de la anulación
    */
    protected $causaAnulacion;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_movilizacion_vegetal";

    /**
     * Nombre de la tabla: fiscalizacion
     */
    private $tabla = "fiscalizacion";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_fiscalizacion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_movilizacion_vegetal"."fiscalizacion_id_fiscalizacion_seq';

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
            throw new \Exception('Clase Modelo: FiscalizacionModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FiscalizacionModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_movilizacion_vegetal
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idFiscalizacion
     *
     * Identificador del registro.
     *
     * @parámetro Integer $idFiscalizacion
     * @return IdFiscalizacion
     */
    public function setIdFiscalizacion($idFiscalizacion)
    {
        $this->idFiscalizacion = (integer) $idFiscalizacion;
        return $this;
    }

    /**
     * Get idFiscalizacion
     *
     * @return null|Integer
     */
    public function getIdFiscalizacion()
    {
        return $this->idFiscalizacion;
    }

    /**
     * Set idMovilizacion
     *
     * Identificador del registro de movilización
     *
     * @parámetro Integer $idMovilizacion
     * @return IdMovilizacion
     */
    public function setIdMovilizacion($idMovilizacion)
    {
        $this->idMovilizacion = (integer) $idMovilizacion;
        return $this;
    }

    /**
     * Get idMovilizacion
     *
     * @return null|Integer
     */
    public function getIdMovilizacion()
    {
        return $this->idMovilizacion;
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
     * Set fechaCreacion
     *
     * Fecha de fiscalizacion del registro
     *
     * @parámetro Date $fechaCreacion
     * @return FechaFiscalizacion
     */
    public function setFechaFiscalizacion($fechaFiscalizacion)
    {
        $this->fechaFiscalizacion = (string) $fechaFiscalizacion;
        return $this;
    }
    
    /**
     * Get fechaFiscalizacion
     *
     * @return null|Date
     */
    public function getFechaFiscalizacion()
    {
        return $this->fechaFiscalizacion;
    }

    /**
     * Set identificadorFiscalizador
     *
     * Identificador del técnico AGR fiscalizador del registro
     *
     * @parámetro String $identificadorFiscalizador
     * @return IdentificadorFiscalizador
     */
    public function setIdentificadorFiscalizador($identificadorFiscalizador)
    {
        $this->identificadorFiscalizador = (string) $identificadorFiscalizador;
        return $this;
    }

    /**
     * Get identificadorFiscalizador
     *
     * @return null|String
     */
    public function getIdentificadorFiscalizador()
    {
        return $this->identificadorFiscalizador;
    }

    /**
     * Set nombreFiscalizador
     *
     * Nombre del técnico AGR fiscalizador del registro
     *
     * @parámetro String $nombreFiscalizador
     * @return NombreFiscalizador
     */
    public function setNombreFiscalizador($nombreFiscalizador)
    {
        $this->nombreFiscalizador = (string) $nombreFiscalizador;
        return $this;
    }

    /**
     * Get nombreFiscalizador
     *
     * @return null|String
     */
    public function getNombreFiscalizador()
    {
        return $this->nombreFiscalizador;
    }
    
    /**
     * Set provinciaFiscalizacion
     *
     * Nombre de la provincia de fiscalización, provincia del técnico
     *
     * @parámetro String provinciaFiscalizacion
     * @return ProvinciaFiscalizacion
     */
    public function setProvinciaFiscalizacion($provinciaFiscalizacion)
    {
        $this->provinciaFiscalizacion = (string) $provinciaFiscalizacion;
        return $this;
    }
    
    /**
     * Get provinciaFiscalizacion
     *
     * @return null|String
     */
    public function getProvinciaFiscalizacion()
    {
        return $this->provinciaFiscalizacion;
    }

    /**
     * Set resultadoFiscalizacion
     *
     * Resultado de la fiscalización de la movilización:
     * -Positivo
     * -Negativo
     *
     * @parámetro String $resultadoFiscalizacion
     * @return ResultadoFiscalizacion
     */
    public function setResultadoFiscalizacion($resultadoFiscalizacion)
    {
        $this->resultadoFiscalizacion = (string) $resultadoFiscalizacion;
        return $this;
    }

    /**
     * Get resultadoFiscalizacion
     *
     * @return null|String
     */
    public function getResultadoFiscalizacion()
    {
        return $this->resultadoFiscalizacion;
    }

    /**
     * Set accionCorrectiva
     *
     * Acción correctiva a la movilización dad apor el técnico fiscalizador:
     * -Fiscalización correcta
     * -Modificar permiso
     * -Aplicación de medidas fitosanitarias de emergencia
     *
     * @parámetro String $accionCorrectiva
     * @return AccionCorrectiva
     */
    public function setAccionCorrectiva($accionCorrectiva)
    {
        $this->accionCorrectiva = (string) $accionCorrectiva;
        return $this;
    }

    /**
     * Get accionCorrectiva
     *
     * @return null|String
     */
    public function getAccionCorrectiva()
    {
        return $this->accionCorrectiva;
    }

    /**
     * Set observacionFiscalizacion
     *
     * Observación de la fiscalización
     *
     * @parámetro String $observacionFiscalizacion
     * @return ObservacionFiscalizacion
     */
    public function setObservacionFiscalizacion($observacionFiscalizacion)
    {
        $this->observacionFiscalizacion = (string) $observacionFiscalizacion;
        return $this;
    }

    /**
     * Get observacionFiscalizacion
     *
     * @return null|String
     */
    public function getObservacionFiscalizacion()
    {
        return $this->observacionFiscalizacion;
    }

    /**
	* Set causaAnulacion
	*
	*Causa de la anulación
	*
	* @parámetro String $causaAnulacion
	* @return CausaAnulacion
	*/
	public function setCausaAnulacion($causaAnulacion)
	{
	  $this->causaAnulacion = (String) $causaAnulacion;
	    return $this;
	}

	/**
	* Get causaAnulacion
	*
	* @return null|String
	*/
	public function getCausaAnulacion()
	{
		return $this->causaAnulacion;
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
     * @return FiscalizacionModelo
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
