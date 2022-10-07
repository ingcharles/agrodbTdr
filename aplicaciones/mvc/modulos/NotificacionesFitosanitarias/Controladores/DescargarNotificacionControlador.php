<?php

/**
 * Controlador Notificaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  NotificacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-09
 * @uses    NotificacionesControlador
 * @package NotificacionesFitosanitarias
 * @subpackage Controladores
 */

namespace Agrodb\NotificacionesFitosanitarias\Controladores;

use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesModelo;

class DescargarNotificacionControlador extends BaseControlador {

    private $lNegocioNotificaciones = null;
    private $modeloNotificaciones = null;
    
    private $accion = null;
    
    private $formulario = null;
    private $ruta = null;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->lNegocioNotificaciones = new NotificacionesLogicaNegocio();
        $this->modeloNotificaciones = new NotificacionesModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

/**
     * Método de inicio del controlador
     */

    public function index() {
        $this->cargarPanelReportes();
        require APP . 'NotificacionesFitosanitarias/vistas/listaNotificacionesReporteAdministracionVista.php';
    }

/**
     * Método para desplegar el formulario vacio
     */

    public function nuevo() {
        $this->accion = "Nuevo Notificaciones";
        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionesVista.php';
    }

/**
     * Método para registrar en la base de datos -Notificaciones
     */

    public function guardar() {
        $this->lNegocioNotificaciones->guardar($_POST);
    }

/**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Notificaciones
     */

    public function editar() {
        $this->accion = "Editar Notificaciones";
        $this->modeloNotificaciones = $this->lNegocioNotificaciones->buscar($_POST["id"]);
        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionesVista.php';
    }

/**
     * Método para borrar un registro en la base de datos - Notificaciones
     */

    public function borrar() {
        $this->lNegocioNotificaciones->borrar($_POST['elementos']);
    }

/**
     * Construye el código HTML para desplegar la lista de - Notificaciones
     */

    public function tablaHtmlNotificaciones($tabla) { {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_notificacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\notificaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_notificacion'] . '</b></td>
                <td>'
                                    . $fila['id_lista_notificacion'] . '</td>
                <td>' . $fila['id_pais_notifica']
                                    . '</td>
                <td>' . $fila['nombre_pais_notifica'] . '</td>
                </tr>');
                            }
                        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para los reportes
     */
    public function cargarPanelReportes() {
        $this->panelBusquedaNotificacionesReporteAdmin = '<table class="filtro" style="width: 400px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <th colspan="2">Filtro para descargar notificaciones:</th>
                                                                    </tr>
                                                                    <tr  style="width: 100%;">
                                                                        <td >Fecha Notificación inicio: </td>
                                                                        <td>
                                                                            <input id="fechaNotificacionInicioFiltro" type="text" name="fechaNotificacionInicioFiltro" style="width: 100%" readonly="readonly" required="required">
                                                                        </td>
                                                                   
                                                                        <td >Fecha Notificación fin: </td>
                                                                        <td>
                                                                            <input id="fechaNotificacionFinFiltro" type="text" name="fechaNotificacionFinFiltro" style="width: 100%" readonly="readonly" required="required">
                                                                        </td>
                                                                    </tr>			
                                                                    <tr  style="width: 100%;">
                                                                        <td >Fecha cierre: </td>
                                                                        <td colspan="3">
                                                                                <input id="fechaCierreFiltro" type="text" name="fechaCierreFiltro" style="width: 100%" readonly="readonly" >
                                                                        </td>
                                                                    </tr>
                                                                   
                                                                    <tr  style="width: 100%;">
                                                                        <td >País que notifica: </td>
                                                                        <td colspan="3">
                                                                            <select id="idPaisFiltro" name="idPaisFiltro" required style="width: 100%;>
                                                                                <option value="">Seleccione...</option>
                                                                                ' . $this->comboVariosPaises($idLocalizacion = null) . '
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr  style="width: 100%;">
                                                                        <td >Tipo de documento: </td>
                                                                        <td colspan="3">
                                                                            <select id="tipoDocumentoFiltro" name="tipoDocumentoFiltro" style="width: 100%;" required>' . $this->comboTipoDocumento() . '</select>
                                                                        </td>
        
                                                                    </tr>
                                                                    <tr  style="width: 100%;">
                                                                        <td>Producto: </td>
                                                                        <td colspan="3">
                                                                            <input type="text" id="productoNotificacionFiltro" name="productoNotificacionFiltro" style="width: 100%">
                                                                       </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Área temática: </td>
                                                                        <td colspan="3">
                                                                         <select id="areaTematicaFiltro" name="areaTematicaFiltro" style="width: 100%;" >' . $this->comboTipoDocumento('Área temática') . '</select> 
                                                                         </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td >Estado: </td>
                                                                        <td colspan="3">
                                                                             <select id="estadoReporteFiltro" name="estadoReporteFiltro" style="width: 100%;" >' . $this->comboEstados() . '</select> 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                                <button type="button" id="btnFiltrar" data-toggle="modal">Generar Reporte</button>
                                                                        </td>        
                                                                    </tr>
                                                                </tbody>
                                                            </table>';
    }
    
    /**
     * Método para desplegar el reporte de Notificaciones
     */
    public function mostrarReporteNotificaciones()
    {
        $this->formulario = $this->listarReporteNotificacionesAdministradorFiltrados();
    }
    
    /**
     * Método para listar las notificaciones registradas
     */
    public function listarReporteNotificacionesAdministradorFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $fechaNotificacionInicio = $_POST["fechaNotificacionInicio"];
        $fechaNotificacionFin = $_POST["fechaNotificacionFin"];
        $fechaCierre = $_POST["fechaCierre"];
       // $fechaRevision = $_POST["fechaRevision"];
        $idPais = $_POST["idPais"];
        $tipoDocumento = $_POST["tipoDocumento"];
        $producto = $_POST["productoNotificacion"];
        $areaTematica = $_POST["areaTematica"];
        $estadoReporte = $_POST["estadoReporte"];
        
        $arrayParametros = array(
            'fecha_notificacion_inicio' => $fechaNotificacionInicio,
            'fecha_notificacion_fin' => $fechaNotificacionFin,
            'fecha_cierre' => $fechaCierre,
           // 'fecha_revision' => $fechaRevision,
            'id_pais_notifica' => $idPais,
            'tipo_documento' => $tipoDocumento,
            'producto' => $producto,
            'area_tematica' => $areaTematica,
            'estado_respuesta' => $estadoReporte
        );
        
        $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroReporte($arrayParametros);
        
        $this->tablaHtmlNotificacionesReporteAdministrador($notificaciones);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    //Construye el código HTML para desplegar la lista de Notificaciones en pantalla
    public function tablaHtmlNotificacionesReporteAdministrador($tabla)
    {
        foreach ($tabla as $fila) {
        	$estado = ($fila['estado_respuesta'] == true ) ? "Respondido":"No respondido";
            $this->itemsFiltrados[] = array(
                '<tr>
                	<td>' . $fila['codigo_documento'] . '</td>
                    <td>' . $fila['nombre_pais_notifica'] . '</td>
                    <td>' . $fila['tipo_documento'] . '</td>
                    <td>' . ($fila['fecha_notificacion'] != null ? date('Y-m-d', strtotime($fila['fecha_notificacion'])) : '') . '</td>
                    <td>' . $fila['producto'] . '</td>
                    <td>' . $fila['palabra_clave'] . '</td>
                    <td>' . $fila['descripcion'] . '</td>
                    <td>' . $fila['enlace'] . '</td>
					<td>' . $fila['area_tematica'] . '</td>
                    <td>' . $estado .'</td>
                </tr>'
            );
        }
    }

    public function exportarListaExcel() {
        $fechaNotificacionInicio = $_POST["fechaNotificacionInicio"];
        $fechaNotificacionFin = $_POST["fechaNotificacionFin"];
        $fechaCierre = $_POST["fechaCierre"];
        $idPais = $_POST["idPais"];
        $tipoDocumento = $_POST["tipoDocumento"];
        $producto = $_POST["productoNotificacion"];
        $areaTematica = $_POST["areaTematica"];
        $estadoReporte = $_POST["estadoReporte"];

        $arrayParametros = array(
            'fecha_notificacion_inicio' => $fechaNotificacionInicio,
            'fecha_notificacion_fin' => $fechaNotificacionFin,
            'fecha_cierre' => $fechaCierre,
            'id_pais_notifica' => $idPais,
            'tipo_documento' => $tipoDocumento,
            'producto' => $producto,
        	'area_tematica' => $areaTematica,
        	'estado_respuesta' => $estadoReporte
        );

        $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroReporte($arrayParametros);
        
        $this->lNegocioNotificaciones->exportarArchivoExcel($notificaciones);
    }

}
