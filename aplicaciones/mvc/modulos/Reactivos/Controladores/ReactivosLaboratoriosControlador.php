<?php

/**
 * Controlador ReactivosLaboratorios
 *
 * Este archivo controla la lógica del negocio del modelo:  ReactivosLaboratoriosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ReactivosLaboratoriosControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosModelo;
use Agrodb\Reactivos\Modelos\SolicitudCabeceraLogicaNegocio;
use Agrodb\Reactivos\Modelos\SolicitudRequerimientoLogicaNegocio;
use Agrodb\Reactivos\Modelos\SaldosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosSolucionLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ReactivosLaboratoriosControlador extends BaseControlador
{

    private $lNegocioReactivosLaboratorios = null;
    private $modeloReactivosLaboratorios = null;
    private $lNegocioSolicitudCabecera = null;
    private $lNegocioSaldosLaboratorios = null;
    private $accion = null;
    private $itemsRequeridos = array();
    private $lNegocioSolicitudRequerimiento = null;
    private $codEstadoReactivo = null;
    private $tipo = null;
    private $listaReactivosSolucion;    //tabla html que contiene la lista de reactivos de la solucion

    /**
     * 
     * 
     * Constructor
     */

    function __construct()
    {
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSaldosLaboratorios = new SaldosLaboratoriosLogicaNegocio();
        $this->modeloReactivosLaboratorios = new ReactivosLaboratoriosModelo();
        $this->lNegocioSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->lNegocioSolicitudRequerimiento = new SolicitudRequerimientoLogicaNegocio();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
        $this->codEstadoReactivo = Constantes::catalogos_rea()->COD_ESTADO;
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Reactivos/vistas/listaReactivosLaboratoriosVista.php';
    }

    /**
     * Mustras la lista de reactivos del laboratorio
     */
    public function listarDatos()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = array('REACTIVO', 'SOLUCION');
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlReactivosLaboratorios($buscarSaldosPorLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivosLaboratorios
     */
    public function tablaHtmlReactivosLaboratorios($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $cantidadMinima = !empty($fila->cantidad_minima) ? $fila->cantidad_minima : 'ND';
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/ReactivosLaboratorios"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                      <td style="text-align: center">' . $fila->tipo . '</td>
                          <td style="text-align: center">' . $fila->origen . '</td>
                  <td style="text-align: center">' . $fila->unidad_medida . '</td>
                      <td style="text-align: right">' . $fila->total_ingreso . '</td>
                          <td style="text-align: right">' . $fila->total_egreso . '</td>' .
                    "<td style='text-align: right' class='{$this->calcularStockMinimo($fila->cantidad_minima, $fila->saldo)}'>" . $fila->saldo . '</td>
                                  <td style="text-align: right">' . $cantidadMinima . '</td>
                          <td style="text-align: center">' . $fila->estado_registro . '</td>
                 </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Reactivo del Laboratorio";
        require APP . 'Reactivos/vistas/formularioReactivosLaboratoriosVista.php';
    }

    /**
     * Método para registrar en la base de datos -ReactivosLaboratorios
     */
    public function guardar()
    {
        $_POST['id_laboratorio'] = parent::laboratorioUsuario();
        $_POST['origen'] = "MANUAL";
        $this->lNegocioReactivosLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ReactivosLaboratorios
     */
    public function editar()
    {
        $this->accion = "Editar Reactivo Laboratorio";
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioReactivosLaboratoriosVista.php';
    }

    /**
     * Registra información adicional
     */
    public function informacionAdicional()
    {
        $this->accion = "Información Adicional del Reactivo";
        $id = isset($_POST["elementos"]) ? $_POST["elementos"] : isset($_POST["id"]);
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($id);
        require APP . 'Reactivos/vistas/formularioInformacionAdicionalVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - SolicitudRequerimiento
     */
    public function tablaHtmlSolicitudRequerimiento($idSolicitudCabecera)
    {
        $tabla = $this->lNegocioSolicitudRequerimiento->buscarSolicitudRequerimiento(parent::laboratorioUsuario(), $idSolicitudCabecera);
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsRequeridos[] = array(
                '<tr>
		  <td>' . ++$contador . '</td>
		  <td>' . $fila->codigo_bodega . '</td>
                  <td>' . $fila->nombre . '</td>
                      <td>' . $fila->cantidad_solicitada . '</td>
                  <td><input name="cantidad[' . $fila->id_solicitud_requerimiento . ']" type="text" value="' . $fila->cantidad_solicitada . '" size="10"/></td>
                      
                  <td>' . $fila->unidad . '</td>
                    
                 </tr>');
        }
    }

    /*     * ******************************* */
    /*     * ******** SOLUCIONES *********** */
    /*     * ******************************* */

    /**
     * SOLUCIONES
     * Método de inicio para soluciones
     */
    public function soluciones()
    {
        require APP . 'Reactivos/vistas/listaReactivosLaboratoriosSolucionesVista.php';
    }

    /**
     * SOLUCIONES
     * Muestra la lista de soluciones
     */
    public function listarDatosSoluciones()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = 'SOLUCION';
        $modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlReactivosLaboratoriosSoluciones($modeloReactivosLaboratorios);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * SOLUCIONES
     * Método para desplegar el formulario vacio para soluciones
     */
    public function nuevas()
    {
        $this->accion = "Nueva Soluci&oacute;n";
        $array[] = array("id" => 0, "text" => '');
        $this->listaReactivosLaboratorios = json_encode($array);    //inicializar vacio
        require APP . 'Reactivos/vistas/formularioSolucionesLaboratorioVista.php';
    }

    /**
     * SOLUCIONES
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ReactivosLaboratorios
     */
    public function editarSolucion()
    {
        $this->accion = "Editar Solución";
        $buscaReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarReactivos(parent::laboratorioUsuario());
        $array = array();
        foreach ($buscaReactivosLaboratorios as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_laboratorio, "text" => $fila->nombre);
        }
        $this->listaReactivosLaboratorios = json_encode($array);
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($_POST["id"]);

        $this->tablaHtmlReactivosSolucion($_POST["id"]);

        require APP . 'Reactivos/vistas/formularioSolucionesLaboratorioVista.php';
    }

    /**
     * SOLUCIONES
     * Método para registrar en la base de datos -ReactivosLaboratorios
     */
    public function guardarSolucion()
    {
        $_POST["id_laboratorio"] = parent::laboratorioUsuario();    //ojo
        $_POST["tipo"] = 'SOLUCION';
        $_POST["origen"] = 'MANUAL';
        $this->lNegocioReactivosLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Método para borrar un registro en la base de datos - ReactivosLaboratorios
     */
    public function borrar()
    {
        $this->lNegocioReactivosLaboratorios->borrar($_POST['elementos']);
    }

    /**
     * SOLUCIONES
     * Construye el código HTML para desplegar la lista de Reactivos tipo Solucion del Laboratorio
     */
    public function tablaHtmlReactivosLaboratoriosSoluciones($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/ReactivosLaboratorios"
		  data-opcion="editarSolucion" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                  <td style="text-align: center">' . $fila->unidad_medida . '</td>
                  <td style="text-align: right">' . $fila->volumen_final . '</td>
                  <td style="text-align: center">' . $fila->estado_registro . '</td>
                  <td style="text-align: center">' . $fila->total_reactivos_solucion . '</td>
                 </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - ActividadUso
     */
    public function tablaHtmlReactivosSolucion($idSolucion)
    {
        $lNReactivosSolucion = new ReactivosSolucionLogicaNegocio();
        $buscaReactivosSolucion = $lNReactivosSolucion->buscarReactivosSolucion($idSolucion);
        $html = "";
        $contador = 0;
        if (count($buscaReactivosSolucion) > 0)
        {
            foreach ($buscaReactivosSolucion as $fila)
            {
                $html.="<tr id = $idSolucion-$fila->id_reactivo_solucion class='item'"
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/ReactivosSolucion'"
                        . 'data-opcion="editar"'
                        . 'data-destino="detalleItem">'
                        . "<td>" . ++$contador . "</td>"
                        . "<td>" . $fila->nombre . "</td>"
                        . "<td style='text-align: center'>" . $fila->unidad_medida . "</td>"
                        . "<td style='text-align: right'>" . $fila->cantidad_requerida . "</td>"
                        . "<td style='text-align: center'>" . $fila->estado_registro . "</td>"
                        . "<td>" . $fila->observacion . "</td>"
                        . "</tr>";
            }
        } else
        {
            $html = "<tr colspan='5'><td colspan='5'>No tiene asignado los reactivos</td></tr>";
        }
        $this->listaReactivosSolucion = $html;
    }

    /*     * ******************************* */
    /*     * ******** INGRESO DE REACTIVO LABORATORIO A SALDOS *********** */
    /*     * ******************************* */

    public function ingresoReactivo()
    {
        require APP . 'Reactivos/vistas/listaReactivosLaboratoriosManualVista.php';
    }

    /**
     * Mustras la lista de reactivos del laboratorio
     */
    public function listarDatosReactivosManual()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = array('REACTIVO','SOLUCION');
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlSaldosReactivosLaboratorios($buscarSaldosPorLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivosLaboratorios
     */
    public function tablaHtmlSaldosReactivosLaboratorios($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/ReactivosLaboratorios"
		  data-opcion="ingresoReactivoManual" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                      <td>' . $fila->tipo . '</td>
                          <td>' . $fila->origen . '</td>
                  <td>' . $fila->unidad_medida . '</td>
                      <td>' . $fila->total_ingreso . '</td>
                          <td>' . $fila->total_egreso . '</td>
                              <td>' . $fila->saldo . '</td>
                          <td>' . $fila->estado_registro . '</td>
                 </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    public function ingresoReactivoManual()
    {
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioIngresoReactivoManualVista.php';
    }

    public function guardarReactivoManual()
    {
        $_POST["tipo_ingreso"] = 'INGRESO';
        $_POST["motivo"] = 'REACTIVO LABORATORIO';
        $this->lNegocioSaldosLaboratorios->guardarReactivoManual($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /*     * ******************************* */
    /*     * ******** INGRESO DE REACTIVO DESDE OTRO LABORATORIO A SALDOS *********** */
    /*     * ******************************* */

    public function ingresoReaOtroLab()
    {
        $this->modeloLaboratoriosProvincia = new \Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaModelo();
        $this->modeloSolicitudCabecera = new \Agrodb\Reactivos\Modelos\SolicitudCabeceraModelo();
        require APP . 'Reactivos/vistas/listaReactivosLaboratoriosOtrosVista.php';
    }
    
}
