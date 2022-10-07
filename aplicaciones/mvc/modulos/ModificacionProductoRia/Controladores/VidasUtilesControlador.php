<?php
 /**
 * Controlador VidasUtiles
 *
 * Este archivo controla la lógica del negocio del modelo:  VidasUtilesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    VidasUtilesControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\VidasUtilesLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\VidasUtilesModelo;
 use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
 
class VidasUtilesControlador extends BaseControlador 
{

		 private $lNegocioVidasUtiles = null;
		 private $modeloVidasUtiles = null;
		 private $accion = null;
		 private $lNegocioProductosInocuidad = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioVidasUtiles = new VidasUtilesLogicaNegocio();
		 $this->modeloVidasUtiles = new VidasUtilesModelo();
		 $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloVidasUtiles = $this->lNegocioVidasUtiles->buscarVidasUtiles();
		 $this->tablaHtmlVidasUtiles($modeloVidasUtiles);
		 require APP . 'ModificacionProductoRia/vistas/listaVidasUtilesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo VidasUtiles"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioVidasUtilesVista.php';
		}	/**
		* Método para registrar en la base de datos -VidasUtiles
		*/
		public function guardar()
		{
		  $this->lNegocioVidasUtiles->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: VidasUtiles
		*/
		public function editar()
		{
		 $this->accion = "Editar VidasUtiles"; 
		 $this->modeloVidasUtiles = $this->lNegocioVidasUtiles->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioVidasUtilesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - VidasUtiles
		*/
		public function borrar()
		{
		  $this->lNegocioVidasUtiles->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - VidasUtiles
		*/
		 public function tablaHtmlVidasUtiles($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_vida_util'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\vidasutiles"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_vida_util'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
		  . '</td>
<td>' . $fila['estabilidad'] . '</td>
</tr>');
		}
		}
	}
	
	public function modificarVidaUtil($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
	{
	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaVidaUtil = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;
	        
	    switch ($estadoSoliciudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $qVidaUtilActual = $this->lNegocioProductosInocuidad->buscarLista(array('id_producto' => $parametros['id_producto']));
	            $vidaUtilActual = $qVidaUtilActual->current()->estabilidad;
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Vida útil:</label>
                                    <input name="vida_util" id="vida_util" value="' . $vidaUtilActual . '" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" />
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
                        			<button type="button" class="mas" id="agregarVidaUtil" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
	            break;
	            
	    }
	    
	    $qVidaUtil = $this->lNegocioVidasUtiles->buscarLista(array(
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ));

	    foreach ($qVidaUtil as $datosVidaUtil) {
	        
	        $idDatoVidaUtil = $datosVidaUtil['id_vida_util'];
	        $vidaUtil = $datosVidaUtil['estabilidad'];
	        
	        $filaVidaUtil .= '
                <tr id="fila' . $idDatoVidaUtil . '">
                    <td>' . ($vidaUtil != '' ? $vidaUtil : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
	        if ($banderaAcciones) {
	            $filaVidaUtil .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarVidaUtil(' . $idDatoVidaUtil . '); return false;"/>
                        </td>';
	        }
	        $filaVidaUtil .= '</tr>';
	    }

        $modificarVidaUtil = '';

        if($rutaDocumentoRespaldo){
            $modificarVidaUtil .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }
	    
	    $modificarVidaUtil .= '<fieldset  id="fVidaUtil">
        <legend>Modificar vida útil</legend>
        ' . $ingresoDatos . '
		<table id="tVidaUtil" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaVidaUtil . '</tbody>
		</table>
        </fieldset>';
	    
	    return $modificarVidaUtil;
	}
	
	/**
	 * Método para listar vida utila agregada
	 */
	public function generarFilaVidaUtil($idVidaUtil, $datosVidaUtil, $tiempoAtencion)
	{
	    
	    $vidaUtil = $datosVidaUtil['estabilidad'];
	
	    $this->listaDetalles = '
                        <tr id="fila' . $idVidaUtil . '">
                            <td>' . ($vidaUtil != '' ? $vidaUtil : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarVidaUtil(' . $idVidaUtil . '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}

}
