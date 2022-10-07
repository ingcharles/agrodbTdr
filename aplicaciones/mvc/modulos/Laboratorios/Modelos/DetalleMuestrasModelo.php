<?php

/**
 * Modelo DetalleMuestrasModelo
 *
 * Este archivo se complementa con el archivo   DetalleMuestrasLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       DetalleMuestrasModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleMuestrasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave de primaria de la tabla detalle de la muestra
     */
    protected $idDetalleMuestra;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla muestra
     */
    protected $idMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor del campo que ingresa el usuario
     */
    protected $valorUsuario;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Codigo generado de forma automatica al momento de guardar los datos ayuda para identificar el grupo de variables por cada registro
     */
    protected $codigoAgrupa;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: detalle_muestras
     * 
     */
    Private $tabla = "detalle_muestras";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_detalle_muestra";

    /**
     * Secuencia
     */
    private $secuencial = "";

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
            throw new \Exception('Clase Modelo: DetalleMuestrasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: DetalleMuestrasModelo. Propiedad especificada invalida: get' . $name);
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
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
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
     * Set idDetalleMuestra
     *
     * Clave de primaria de la tabla detalle de la muestra
     *
     * @parÃ¡metro Integer $idDetalleMuestra
     * @return IdDetalleMuestra
     */
    public function setIdDetalleMuestra($idDetalleMuestra)
    {
        if (empty($idDetalleMuestra))
        {
            $idDetalleMuestra = "No informa";
        }
        $this->idDetalleMuestra = (Integer) $idDetalleMuestra;
        return $this;
    }

    /**
     * Get idDetalleMuestra
     *
     * @return null|Integer
     */
    public function getIdDetalleMuestra()
    {
        return $this->idDetalleMuestra;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        if (empty($idLaboratorio))
        {
            $idLaboratorio = "No informa";
        }
        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }

    /**
     * Set idMuestra
     *
     * Clave primaria de la tabla muestra
     *
     * @parÃ¡metro Integer $idMuestra
     * @return IdMuestra
     */
    public function setIdMuestra($idMuestra)
    {
        if (empty($idMuestra))
        {
            $idMuestra = "No informa";
        }
        $this->idMuestra = (Integer) $idMuestra;
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
     * Set valorUsuario
     *
     * Valor del campo que ingresa el usuario
     *
     * @parÃ¡metro String $valorUsuario
     * @return ValorUsuario
     */
    public function setValorUsuario($valorUsuario)
    {
        $this->valorUsuario = ValidarDatos::validarAlfa($valorUsuario, $this->tabla," Valor Ingresado", self::NO_REQUERIDO,256);
        return $this;
    }

    /**
     * Get valorUsuario
     *
     * @return null|String
     */
    public function getValorUsuario()
    {
        return $this->valorUsuario;
    }

    /**
     * Set codigoAgrupa
     *
     * Codigo generado de forma automatica al momento de guardar los datos ayuda para identificar el grupo de variables por cada registro
     *
     * @parÃ¡metro String $codigoAgrupa
     * @return CodigoAgrupa
     */
    public function setCodigoAgrupa($codigoAgrupa)
    {
        $this->codigoAgrupa = ValidarDatos::validarAlfa($codigoAgrupa, $this->tabla," Código Generado", self::NO_REQUERIDO,64);
        return $this;
    }

    /**
     * Get codigoAgrupa
     *
     * @return null|String
     */
    public function getCodigoAgrupa()
    {
        return $this->codigoAgrupa;
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
     * @return DetalleMuestrasModelo
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
