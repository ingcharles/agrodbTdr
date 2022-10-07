<?php
 /**
 * Controlador PeriodosRetiros
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodosRetirosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    PeriodosRetirosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\PeriodosRetirosLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\PeriodosRetirosModelo;
use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
 
class PeriodosRetirosControlador extends BaseControlador 
{

		 private $lNegocioPeriodosRetiros = null;
		 private $modeloPeriodosRetiros = null;
		 private $accion = null;
		 private $lNegocioProductosInocuidad = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioPeriodosRetiros = new PeriodosRetirosLogicaNegocio();
		 $this->modeloPeriodosRetiros = new PeriodosRetirosModelo();
		 $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloPeriodosRetiros = $this->lNegocioPeriodosRetiros->buscarPeriodosRetiros();
		 $this->tablaHtmlPeriodosRetiros($modeloPeriodosRetiros);
		 require APP . 'ModificacionProductoRia/vistas/listaPeriodosRetirosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo PeriodosRetiros"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioPeriodosRetirosVista.php';
		}	/**
		* Método para registrar en la base de datos -PeriodosRetiros
		*/
		public function guardar()
		{
		  $this->lNegocioPeriodosRetiros->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodosRetiros
		*/
		public function editar()
		{
		 $this->accion = "Editar PeriodosRetiros"; 
		 $this->modeloPeriodosRetiros = $this->lNegocioPeriodosRetiros->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioPeriodosRetirosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - PeriodosRetiros
		*/
		public function borrar()
		{
		  $this->lNegocioPeriodosRetiros->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - PeriodosRetiros
		*/
		 public function tablaHtmlPeriodosRetiros($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_periodo_retiro'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\periodosretiros"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_periodo_retiro'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['periodo_retiro']
		  . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
		}
		}
	}
		
	public function modificarPeriodoRetiro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto)
	{
	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaPeriodoRetiro = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;
	    
	    switch ($estadoSolicitudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $qPeriodoRetiroActual = $this->lNegocioProductosInocuidad->buscarLista(array('id_producto' => $parametros['id_producto']));
	            $periodoRetiroActual = $qPeriodoRetiroActual->current()->periodo_carencia_retiro;
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Periódo retiro:</label>
                                    <input name="periodo_retiro" id="periodo_retiro" value="' . $periodoRetiroActual . '" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" />
                                </div>
                                <hr/>
                                <div data-linea="2">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="3">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . '</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="4">
                        			<button type="button" class="mas" id="agregarPeriodoRetiro" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
	            break;
	            
	    }
	    
	    $qPeriodoRetiro = $this->lNegocioPeriodosRetiros->buscarLista(array(
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ));
	    
	    foreach ($qPeriodoRetiro as $datosPeriodoRetiro) {

	        $idDatoPeriodoRetiro = $datosPeriodoRetiro['id_periodo_retiro'];
	        $periodoRetiro = $datosPeriodoRetiro['periodo_retiro'];
	        
	        $filaPeriodoRetiro .= '
                <tr id="fila' . $idDatoPeriodoRetiro . '">
                    <td>' . ($periodoRetiro != '' ? $periodoRetiro : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
	        if ($banderaAcciones) {
	            $filaPeriodoRetiro .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarPeriodoRetiro(' . $idDatoPeriodoRetiro . '); return false;"/>
                        </td>';
	        }
	        $filaPeriodoRetiro .= '</tr>';
	    }

        $modificarPeriodoRetiro = '';

        if($rutaDocumentoRespaldo){
            $modificarPeriodoRetiro .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }
	    
	    $modificarPeriodoRetiro .= '<fieldset  id="fPeriodoRetiro">
        <legend>Modificar periódo retiro</legend>
        ' . $ingresoDatos . '
		<table id="tPeriodoRetiro" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaPeriodoRetiro . '</tbody>
		</table>
        </fieldset>';
	    
	    return $modificarPeriodoRetiro;
	}
	
	/**
	 * Método para listar periodo retiro agregado
	 */
	public function generarFilaPeriodoRetiro($idPeriodoRetiro, $datosPeriodoRetiro, $tiempoAtencion)
	{
	    
	    $periodoRetiro = $datosPeriodoRetiro['periodo_retiro'];
	    
	    $this->listaDetalles = '
                        <tr id="fila' . $idPeriodoRetiro . '">
                            <td>' . ($periodoRetiro != '' ? $periodoRetiro : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarPeriodoRetiro(' . $idPeriodoRetiro . '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}
	

}
