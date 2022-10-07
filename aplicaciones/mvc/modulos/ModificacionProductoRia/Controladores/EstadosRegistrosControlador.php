<?php
 /**
 * Controlador EstadosRegistros
 *
 * Este archivo controla la lógica del negocio del modelo:  EstadosRegistrosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    EstadosRegistrosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\EstadosRegistrosLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\EstadosRegistrosModelo;
 use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
 
class EstadosRegistrosControlador extends BaseControlador 
{

		 private $lNegocioEstadosRegistros = null;
		 private $modeloEstadosRegistros = null;
		 private $accion = null;
		 private $lNegocioProductos = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioEstadosRegistros = new EstadosRegistrosLogicaNegocio();
		 $this->modeloEstadosRegistros = new EstadosRegistrosModelo();
		 $this->lNegocioProductos = new ProductosLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloEstadosRegistros = $this->lNegocioEstadosRegistros->buscarEstadosRegistros();
		 $this->tablaHtmlEstadosRegistros($modeloEstadosRegistros);
		 require APP . 'ModificacionProductoRia/vistas/listaEstadosRegistrosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo EstadosRegistros"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioEstadosRegistrosVista.php';
		}	/**
		* Método para registrar en la base de datos -EstadosRegistros
		*/
		public function guardar()
		{
		  $this->lNegocioEstadosRegistros->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: EstadosRegistros
		*/
		public function editar()
		{
		 $this->accion = "Editar EstadosRegistros"; 
		 $this->modeloEstadosRegistros = $this->lNegocioEstadosRegistros->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioEstadosRegistrosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - EstadosRegistros
		*/
		public function borrar()
		{
		  $this->lNegocioEstadosRegistros->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - EstadosRegistros
		*/
		 public function tablaHtmlEstadosRegistros($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_estado_registro'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\estadosregistros"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_estado_registro'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
		  . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
		}
		}
	}
		
	public function modificarEstadoRegistro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
	{
	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaEstadoRegistro = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;
	      
	    switch ($estadoSoliciudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $qEstadoRegistroActual = $this->lNegocioProductos->buscarLista(array('id_producto' => $parametros['id_producto']));
	            $estadoProductoActual = $qEstadoRegistroActual->current()->estado;
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Estado registro: </label>
                                    <select name="estado_producto" id="estado_producto" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion">
                                        <option value="">Seleccionar....</option>' . $this->comboEstadoProducto($estadoProductoActual) . '</select>
                                </div>
                                <hr/>
                                <div data-linea="2">
                                    <input type="checkbox" id="validacion_cancela_registro" value="true"> Seguro que desea cancelar el registro de este producto
                        	    </div>
                                <div data-linea="3">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="4">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . '</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="5">
                                    <button type="button" class="mas" id="agregarEstadoRegistro" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
	            break;
	            
	    }
	    
	    $qDatosEstadoRegistro = $this->lNegocioEstadosRegistros->buscarLista(array(
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ));
	    
	    $fila = 0;
	    
	    foreach ($qDatosEstadoRegistro as $datosEstadoRegistro) {
	        
	        $fila = $fila + 1;
	        
	        $idDatoEstadoRegistro = $datosEstadoRegistro['id_estado_registro'];
	        $estado = $datosEstadoRegistro['estado'];
	        
	        switch ($estado) {
	            case '1':
	                $estado = 'Vigente';
	            break;	            
	            case '2':
	                $estado = 'Suspendido';
	            break;
	            case '3':
	                $estado = 'Caducado';
	            break;
	            case '4':
	                $estado = 'Cancelado';
	            break;
	        }
	        
	        $filaEstadoRegistro .= '
                <tr id="fila' . $idDatoEstadoRegistro . '">
                    <td>' . ($estado != '' ? $estado : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
	        if ($banderaAcciones) {
	            $filaEstadoRegistro .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarEstadoRegistro(' . $idDatoEstadoRegistro . '); return false;"/>
                        </td>';
	        }
	        $filaEstadoRegistro .= '</tr>';
	    }

        $modificarEstadoRegistro = '';

        if($rutaDocumentoRespaldo){
            $modificarEstadoRegistro .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }
	    
	    $modificarEstadoRegistro .= '<fieldset  id="fEstadoRegistro">
        <legend>Modificar estado de registro</legend>
        ' . $ingresoDatos . '
		<table id="tEstadoRegistro" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaEstadoRegistro . '</tbody>
		</table>
        </fieldset>';
	    
	    return $modificarEstadoRegistro;
	}
	
	/**
	 * Método para listar estado registro agregado
	 */
	public function generarFilaEstadoRegistro($idEstadoRegistro, $datosEstadoRegistro, $tiempoAtencion)
	{

	    $estadoValor = $datosEstadoRegistro['estado_valor'];
	    
	    $this->listaDetalles = '
                        <tr id="fila' . $idEstadoRegistro . '">
                            <td>' . ($estadoValor != '' ? $estadoValor : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarEstadoRegistro(' . $idEstadoRegistro. '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}

}
