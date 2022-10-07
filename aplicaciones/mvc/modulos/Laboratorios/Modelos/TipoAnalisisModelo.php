<?php

/**
 * Modelo TipoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   TipoAnalisisLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       TipoAnalisisModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class TipoAnalisisModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla tipo de analisis
     */
    protected $idTipoAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla de detalle_solicitud
     */
    protected $idDetalleSolicitud;

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
     * NÃºmero de muestra
     */
    protected $numeroMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ³digo de laboratorio de la muestra
     */
    protected $codigoLabMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * CÃ³digo de campo de la muestra ingresado por el usuario
     */
    protected $codigoUsuMuestra;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Valor del campo que ingresa el usuario
     */
    protected $valorUsuario;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * CÃ³digo generado de forma automatica al momento de guardar los datos ayuda para identificar el grupo de variables por cada registro
     */
    protected $codigoAgrupa;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Indica el estado de la muestra
     */
    protected $estado;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * NÃºmero de muestra
     */
    protected $totalMarbetes;

    /**
     * @var String
     * Observación interna
     */
    protected $observacionInterna;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: tipo_analisis
     * 
     */
    Private $tabla = "tipo_analisis";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_tipo_analisis";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."tipo_analisis_id_tipo_analisis_seq';

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
            throw new \Exception('Clase Modelo: TipoAnalisisModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: TipoAnalisisModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idTipoAnalisis
     *
     * Clave primaria de la tabla tipo de anÃ¡lisis
     *
     * @parÃ¡metro Integer $idTipoAnalisis
     * @return IdTipoAnalisis
     */
    public function setIdTipoAnalisis($idTipoAnalisis)
    {
        if (empty($idTipoAnalisis))
        {
            $idTipoAnalisis = "No informa";
        }
        $this->idTipoAnalisis = (Integer) $idTipoAnalisis;
        return $this;
    }

    /**
     * Get idTipoAnalisis
     *
     * @return null|Integer
     */
    public function getIdTipoAnalisis()
    {
        return $this->idTipoAnalisis;
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
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
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
     * Set numeroMuestra
     *
     * NÃºmero de muestra
     *
     * @parÃ¡metro Integer $numeroMuestra
     * @return NumeroMuestra
     */
    public function setNumeroMuestra($numeroMuestra)
    {
        $this->numeroMuestra = ValidarDatos::validarEntero($numeroMuestra, $this->tabla, " Número de Muestra", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get numeroMuestra
     *
     * @return null|Integer
     */
    public function getNumeroMuestra()
    {
        return $this->numeroMuestra;
    }

    /**
     * Set codigoLabMuestra
     *
     * CÃ³digo de laboratorio de la muestra
     *
     * @parÃ¡metro String $codigoLabMuestra
     * @return CodigoLabMuestra
     */
    public function setCodigoLabMuestra($codigoLabMuestra)
    {
        $this->codigoLabMuestra = ValidarDatos::validarAlfa($codigoLabMuestra, $this->tabla, " Código Lab. Muestra", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get codigoLabMuestra
     *
     * @return null|String
     */
    public function getCodigoLabMuestra()
    {
        return $this->codigoLabMuestra;
    }

    /**
     * Set codigoUsuMuestra
     *
     * CÃ³digo de campo de la muestra ingresado por el usuario
     *
     * @parÃ¡metro String $codigoUsuMuestra
     * @return CodigoUsuMuestra
     */
    public function setCodigoUsuMuestra($codigoUsuMuestra)
    {
        $this->codigoUsuMuestra = ValidarDatos::validarAlfa($codigoUsuMuestra, $this->tabla, "codigo_usu_muestra", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get codigoUsuMuestra
     *
     * @return null|String
     */
    public function getCodigoUsuMuestra()
    {
        return $this->codigoUsuMuestra;
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
        $this->valorUsuario = ValidarDatos::validarAlfa($valorUsuario, $this->tabla, " Valor", self::REQUERIDO, 256);
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
     * CÃ³digo generado de forma automÃ¡tica al momento de guardar los datos ayuda para identificar el grupo de variables por cada registro
     *
     * @parÃ¡metro String $codigoAgrupa
     * @return CodigoAgrupa
     */
    public function setCodigoAgrupa($codigoAgrupa)
    {
        $this->codigoAgrupa = ValidarDatos::validarAlfa($codigoAgrupa, $this->tabla, "codigo_agrupa", self::NO_REQUERIDO, 64);
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
     * Set estado
     *
     * Indica el estado de la muestra
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 16);
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
     * Set numeroMuestra
     *
     * NÃºmero de muestra
     *
     * @parÃ¡metro Integer $numeroMuestra
     * @return NumeroMuestra
     */
    public function setTotalMarbetes($totalMarbetes)
    {
        $this->totalMarbetes = ValidarDatos::validarEntero($totalMarbetes, $this->tabla, " Total de marbetes", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get numeroMuestra
     *
     * @return null|Integer
     */
    public function getTotalMarbetes()
    {
        return $this->totalMarbetes;
    }

    /**
     * Set estado
     *
     * Observación interna
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setObservacionInterna($observacionInterna)
    {
        $this->observacionInterna = ValidarDatos::validarAlfa($observacionInterna, $this->tabla, " Observacion Interna", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get observacionInterna
     *
     * @return null|String
     */
    public function getObservacionInterna()
    {
        return $this->observacionInterna;
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

    public function borrarPorParametro($param, $value)
    {
        return parent::borrar($param . " = " . $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return TipoAnalisisModelo
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
