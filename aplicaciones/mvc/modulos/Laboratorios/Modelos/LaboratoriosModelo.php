<?php

/**
 * Modelo LaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   LaboratoriosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       LaboratoriosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\Constantes;
use Agrodb\Core\ValidarDatos;

class LaboratoriosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Clave primaria
     */
    protected $idLaboratorio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      ID DirecciÃ³n
     */
    protected $fkIdLaboratorio;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      ID Provincia
     */
    protected $idLocalizacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ³digo
     */
    protected $codigo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre
     */
    protected $nombre;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      DescripciÃ³n
     */
    protected $descripcion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Tipo de campo
     */
    protected $tipoCampo;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Nivel
     */
    protected $nivel;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Ãšltimo nivel
     */
    protected $ultimoNivel;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Obligatorio
     */
    protected $obligatorio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nivel de acceso
     */
    protected $nivelAcceso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Donde es visible
     */
    protected $visibleEn;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      CÃ³digo codigo_campo
     */
    protected $codigoCampo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Atributos HTML
     */
    protected $atributos;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Estado
     */
    protected $estadoRegistro;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Orden
     */
    protected $orden;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Orden
     */
    protected $ordenOt;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      data_linea
     */
    protected $dataLinea;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      CÃ³digo Sistema GuÃ­a
     */
    protected $idSistemaGuia;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Indica el usuario que aprobo el cambio
     */
    protected $aprobadoPor;

    /**
     *
     * @var String 
     *      Campo visible en el formulario
     *     Observación de aprobación
     */
    protected $observacionAprobacion;

    /**
     *
     * @var String 
     *      Campo visible en el formulario
     *     Estado de aprobación POR APROBAR / APROBADO
     */
    protected $estadoAprobado;

    /**
     *
     * @var String 
     *      Campo visible en el formulario
     *     En caso de las secciones nivel=2 indica como  se debe desplegar en la orden de trabajo
     */
    protected $orientacion;

    /**
     *
     * @var String 
     *      Campo visible en el formulario
     *     Configuracion de la orden de trabajo
     */
    protected $confOrdenTrabajo;

    /**
     *
     * @var String 
     *     Campo visible en el formulario en ambiente de desarrollo
     *     Código html o jacascript
     */
    protected $codigoEjecutable;

    /**
     * @var String
     * Campo codigoEspecial
     * CodigoEspecial
     */
    protected $codigoEspecial;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: laboratorios
     */
    private $tabla = "laboratorios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_laboratorio";

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
        }
        parent::__construct($this->esquema, $this->tabla);
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
            throw new \Exception('Clase Modelo: LaboratoriosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: LaboratoriosModelo. Propiedad especificada invalida: get' . $name);
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
        foreach ($datos as $key => $value)
        {
            $key_original = $key;
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
     * 
     * @return type
     */
    function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * 
     * @param type $esquema
     */
    function setEsquema($esquema)
    {
        $this->esquema = $esquema;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $idLaboratorio
     *
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (integer) $idLaboratorio;
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
     * Set fkIdLaboratorio
     *
     * Clave forÃ¡nea de la direcciÃ³n de diagnÃ³stico (Id padre tabla recursiva de laboratorios)
     *
     * @parÃ¡metro Integer $fkIdLaboratorio
     *
     * @return FkIdLaboratorio
     */
    public function setFkIdLaboratorio($fkIdLaboratorio)
    {
        $this->fkIdLaboratorio = (integer) $fkIdLaboratorio;
        return $this;
    }

    /**
     * Get fkIdLaboratorio
     *
     * @return null|Integer
     */
    public function getFkIdLaboratorio()
    {
        return $this->fkIdLaboratorio;
    }

    /**
     * Set idLocalizacion
     *
     * Id de la provincia
     *
     * @parÃ¡metro Integer $idLocalizacion
     *
     * @return IdLocalizacion
     */
    public function setIdLocalizacion($idLocalizacion)
    {
        $this->idLocalizacion = (integer) $idLocalizacion;
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

//
    /**
     * Set codigo
     *
     * CÃ³digo de la direcciÃ³n, laboratorio o su variable de configuraciÃ³n
     *
     * @parÃ¡metro String $codigo
     *
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfaEsp($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set codigo
     *
     * CÃ³digo de la direcciÃ³n, laboratorio o su variable de configuraciÃ³n
     *
     * @parÃ¡metro String $codigo
     *
     * @return Codigo
     */
    public function setCodigoCampo($codigoCampo)
    {
        $this->codigoCampo = ValidarDatos::validarAlfaEsp($codigoCampo, $this->tabla, "Código de campo", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigoCampo()
    {
        return $this->codigoCampo;
    }

    /**
     * Set nombre
     *
     * Nombre de la direcciÃ³n, laboratorio o su variable de configuraciÃ³n
     *
     * @parÃ¡metro String $nombre
     *
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, "Nombre", self::REQUERIDO, 128);
        return $this;
    }

    /**
     * Get nombre
     *
     * @return null|String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * InformaciÃ³n complementaria de cada variable, que puede servir como ayuda en las pantallas de usuario
     *
     * @parÃ¡metro String $descripcion
     *
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = ValidarDatos::validarAlfa($descripcion, $this->tabla, " Descripción", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return null|String
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set tipoCampo
     *
     * El tipo de campo que se debe presentar en el formulario, por ejemplo puede ser Combo, check, caja texto, etc
     *
     * @parÃ¡metro String $tipoCampo
     *
     * @return TipoCampo
     */
    public function setTipoCampo($tipoCampo)
    {
        $this->tipoCampo = ValidarDatos::validarAlfa($tipoCampo, $this->tabla, " Tipo de Campo", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get tipoCampo
     *
     * @return null|String
     */
    public function getTipoCampo()
    {
        return $this->tipoCampo;
    }

    /**
     * Set nivel
     *
     * 0 - > DirecciÃ³n de diagnÃ³stico. 1 -> Laboratorios. 2 -> Datos especÃ­ficos de la muestra o tipo de anÃ¡lisis. -> 2 para campos y etiquetas
     *
     * @parÃ¡metro Integer $nivel
     *
     * @return Nivel
     */
    public function setNivel($nivel)
    {
        $this->nivel = ValidarDatos::validarEntero($nivel, $this->tabla, " Nivel", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get nivel
     *
     * @return null|Integer
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set ultimoNivel
     *
     * Ayuda en la programaciÃ³n al momento de realizar las bÃºsquedas para construir los formularios.
     *
     * @parÃ¡metro String $ultimoNivel
     *
     * @return UltimoNivel
     */
    public function setUltimoNivel($ultimoNivel)
    {
        $this->ultimoNivel = $ultimoNivel;
        $this->ultimoNivel = ValidarDatos::validarAlfa($ultimoNivel, $this->tabla, " Último Nivel", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get ultimoNivel
     *
     * @return null|String
     */
    public function getUltimoNivel()
    {
        return $this->ultimoNivel;
    }

    /**
     * Set obligatorio
     *
     * Poner el valor true. Cuando el campo debe ser controlado como obligatorio
     *
     * @parÃ¡metro String $obligatorio
     *
     * @return Obligatorio
     */
    public function setObligatorio($obligatorio)
    {
        $this->obligatorio = $obligatorio;
        return $this;
    }

    /**
     * Get obligatorio
     *
     * @return null|String
     */
    public function getObligatorio()
    {
        return $this->obligatorio;
    }

    /**
     * Set nivelAcceso
     *
     * Quien tiene acceso a este campo/dato?: 0->Todos 1->Todos los usuarios internos. Caso contrario ingresar el perfil de usuario que se requiere, se puede ingresar varios separados con una coma.
     *
     * @parÃ¡metro String $nivelAcceso
     *
     * @return NivelAcceso
     */
    public function setNivelAcceso($nivelAcceso)
    {
        $this->nivelAcceso = ValidarDatos::validarAlfa($nivelAcceso, $this->tabla, " Nivel de Acceso", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get nivelAcceso
     *
     * @return null|String
     */
    public function getNivelAcceso()
    {
        return $this->nivelAcceso;
    }

    /**
     * Set visibleEn
     *
     * Indica si el campo es visible en: OT - Solo en el formulario de la solicitud para generar la orden de trabajo. RA - En el formulario para generar el resultado del anÃ¡lisis OTRA- En ambos formularios anteriores. IMPRI- Solo en el informe. Dejar vacÃ­o se presenta en todos
     *
     * @parÃ¡metro String $visibleEn
     *
     * @return VisibleEn
     */
    public function setVisibleEn($visibleEn)
    {
        $this->visibleEn = ValidarDatos::validarAlfa($visibleEn, $this->tabla, " Visible en", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get visibleEn
     *
     * @return null|String
     */
    public function getVisibleEn()
    {
        return $this->visibleEn;
    }

    /**
     * Set atributos
     *
     * Contiene atributos tipo html para configurar un campo en el formulario. Por ejemplo maxlength=\"4\"
     *
     * @parÃ¡metro String $atributos
     *
     * @return Atributos
     */
    public function setAtributos($atributos)
    {
        $this->atributos = (string) $atributos;
        return $this;
    }

    /**
     * Get atributos
     *
     * @return null|String
     */
    public function getAtributos()
    {
        return $this->atributos;
    }

    /**
     * Set estado
     *
     * Estado del laboratorio o variable y pueden ser: Activo. - Estado por defecto. Suspendido. - Cuando el servicio no estÃ© disponible temporalmente. Se debe indicar la red de laboratorios autorizados para prestar este servicio. Inactivo. - SuspensiÃ³n definitiva Borrado. - Borrado lÃ³gico
     *
     * @parÃ¡metro String $estado
     *
     * @return Estado
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, " Estado", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstadoRegistro()
    {
        return $this->estadoRegistro;
    }

    /**
     * Set orden
     *
     * Orden que se debe presentar en la pantalla
     *
     * @parÃ¡metro Integer $orden
     *
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = ValidarDatos::validarEntero($orden, $this->tabla, " Orden", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Orden de campos en la Orden de trabajo 
     * @return type
     */
    public function getOrdenOt()
    {
        return $this->ordenOt;
    }

    /**
     * Orden de campos en la Orden de trabajo
     * @param \Agrodb\Laboratorios\Modelos\Integer $ordenOt
     * @return $this
     */
    public function setOrdenOt($ordenOt)
    {
        $this->ordenOt = ValidarDatos::validarEntero($ordenOt, $this->tabla, " Orden OT", self::NO_REQUERIDO, 0);
         return $this;
    }

    /**
     * Set dataLinea
     *
     * Indica como agrupar los elementos en el formulario, parte del core del sistema GUIA
     *
     * @parÃ¡metro String $dataLinea
     *
     * @return DataLinea
     */
    public function setDataLinea($dataLinea)
    {
        $this->dataLinea = ValidarDatos::validarAlfaEsp($dataLinea, $this->tabla, " Forma de Agrupar", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get dataLinea
     *
     * @return null|String
     */
    public function getDataLinea()
    {
        return $this->dataLinea;
    }

    /**
     * Set idSistemaGuia
     *
     * En caso de Direcciones y laboratorios se debe relacionar con la tabla de servicios del esquema financiero
     *
     * @parÃ¡metro Integer $idSistemaGuia
     *
     * @return IdSistemaGuia
     */
    public function setIdSistemaGuia($idSistemaGuia)
    {
        $this->idSistemaGuia = ValidarDatos::validarEntero($idSistemaGuia, $this->tabla, "id_sistema_guia", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get idSistemaGuia
     *
     * @return null|Integer
     */
    public function getIdSistemaGuia()
    {
        return $this->idSistemaGuia;
    }

    /**
     * Indica el usuario que aprobo el cambio
     * @return type
     */
    public function getAprobadoPor()
    {
        return $this->aprobadoPor;
    }

    /**
     * Indica el usuario que aprobo el cambio
     * @param type $aprobadoPor
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setAprobadoPor($aprobadoPor)
    {
        $this->aprobadoPor = $aprobadoPor;
        return $this;
    }

    /**
     * Observación de aprobación
     * @return type
     */
    public function getObservacionAprobacion()
    {
        return $this->observacionAprobacion;
    }

    /**
     * Observación de aprobación
     * @param type $observacionAprobacion
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setObservacionAprobacion($observacionAprobacion)
    {
        $this->observacionAprobacion = $observacionAprobacion;
        return $this;
    }

    /**
     * Estado de aprobación POR APROBAR / APROBADO
     * @return type
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Estado de aprobación POR APROBAR / APROBADO
     * @param type $estadoAprobado
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;
        return $this;
    }

    /**
     * En caso de las secciones nivel=2 indica como  se debe desplegar en la orden de trabajo
     * @return type
     */
    public function getOrientacion()
    {
        return $this->orientacion;
    }

    /**
     * En caso de las secciones nivel=2 indica como  se debe desplegar en la orden de trabajo
     * @param type $orientacion
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setOrientacion($orientacion)
    {
        $this->orientacion = $orientacion;
        return $this;
    }

    /**
     * Configuracion de la orden de trabajo
     * @return type
     */
    public function getConfOrdenTrabajo()
    {
        return $this->confOrdenTrabajo;
    }

    /**
     * Configuracion de la orden de trabajo
     * @param type $confOrdenTrabajo
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setConfOrdenTrabajo($confOrdenTrabajo)
    {
        $this->confOrdenTrabajo = $confOrdenTrabajo;
        return $this;
    }

    /**
     * Código html o jacascript
     * @return type
     */
    public function getCodigoEjecutable()
    {
        return $this->codigoEjecutable;
    }

    /**
     * Código html o jacascript
     * @param type $codigoEjecutable
     * @return \Agrodb\Laboratorios\Modelos\LaboratoriosModelo
     */
    public function setCodigoEjecutable($codigoEjecutable)
    {
        $this->codigoEjecutable = $codigoEjecutable;
        return $this;
    }

    /**
     * Set codigoEspecial
     *
     * CÃ³digo que ayuda a identificar el tipo de anÃ¡lisis
     *
     * @parÃ¡metro String $codigoEspecial
     * @return CodigoEspecial
     */
    public function setCodigoEspecial($codigoEspecial)
    {
        $this->codigoEspecial = ValidarDatos::validarAlfa($codigoEspecial, $this->tabla, " Código Especial", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get codigoEspecial
     *
     * @return null|String
     */
    public function getCodigoEspecial()
    {
        return $this->codigoEspecial;
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
     * @return LaboratoriosModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
