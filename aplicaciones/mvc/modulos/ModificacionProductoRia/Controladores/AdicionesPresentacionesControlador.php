<?php
 /**
 * Controlador AdicionesPresentaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  AdicionesPresentacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    AdicionesPresentacionesControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesModelo;
 use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
 
class AdicionesPresentacionesControlador extends BaseControlador 
{

		 private $lNegocioAdicionesPresentaciones = null;
		 private $modeloAdicionesPresentaciones = null;
		 private $lNegocioAdicionesPresentacionesOrigen =null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioAdicionesPresentaciones = new AdicionesPresentacionesLogicaNegocio();
		 $this->modeloAdicionesPresentaciones = new AdicionesPresentacionesModelo();
		 $this->lNegocioAdicionesPresentacionesOrigen = new CodigosInocuidadLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloAdicionesPresentaciones = $this->lNegocioAdicionesPresentaciones->buscarAdicionesPresentaciones();
		 $this->tablaHtmlAdicionesPresentaciones($modeloAdicionesPresentaciones);
		 require APP . 'ModificacionProductoRia/vistas/listaAdicionesPresentacionesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo AdicionesPresentaciones"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioAdicionesPresentacionesVista.php';
		}	/**
		* Método para registrar en la base de datos -AdicionesPresentaciones
		*/
		public function guardar()
		{
		  $this->lNegocioAdicionesPresentaciones->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: AdicionesPresentaciones
		*/
		public function editar()
		{
		 $this->accion = "Editar AdicionesPresentaciones"; 
		 $this->modeloAdicionesPresentaciones = $this->lNegocioAdicionesPresentaciones->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioAdicionesPresentacionesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - AdicionesPresentaciones
		*/
		public function borrar()
		{
		  $this->lNegocioAdicionesPresentaciones->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - AdicionesPresentaciones
		*/
		 public function tablaHtmlAdicionesPresentaciones($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_adicion_presentacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\adicionespresentaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_adicion_presentacion'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['subcodigo']
		  . '</td>
<td>' . $fila['presentacion'] . '</td>
</tr>');
		}
		}
	}
	
	public function modificarAdicionPresentacion($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
	{

	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaAdicionPresentacion = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;
	   	    
	    switch ($estadoSoliciudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $idProducto = $parametros['id_producto'];
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Presentación: </label>
                                    <input type="text" name="presentacion" id="presentacion" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" required/>
                                    <input type="hidden" name="id_producto" id="id_producto" value="' . $idProducto . '" class="validacion" readonly="readonly" />
                                </div>
                                <div data-linea="2">
                                    <label>Unidad: </label>
                                    <select id="unidad_medida" name="unidad_medida" class="validacion" required>
                                        <option value="">Seleccione....</option>
                                        ' . $this->comboUnidadesMedida() . '
                                    </select>
                                </div>
                                <hr/>
                                <div data-linea="4">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="5">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="6">
                        			<button type="button" class="mas" id="agregarAdicionPresentacion" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
               break;
               
	    }	    
	    
	    $arrayConsulta = [
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ];
	    
	    switch ($estadoSoliciudProducto) {
	        case 'Creado':
	            $arrayConsulta += ['id_producto' => $parametros['id_producto']];
	            break;
	    }
	    
	    $qDatosAdicionPresentacion = $this->lNegocioAdicionesPresentaciones->buscarAdicionPresentacionOrigenDestino($arrayConsulta/*, $banderaAcciones*/);
	    
	    foreach ($qDatosAdicionPresentacion as $datosAdicionPresentacion) {
	        
	        $idAdicionPresentacion = $datosAdicionPresentacion['id_adicion_presentacion'];
	        (isset($datosAdicionPresentacion['id_adicion_presentacion_origen'])) ? $idAdicionPresentacionOrigen = $datosAdicionPresentacion['id_adicion_presentacion_origen'] : $idAdicionPresentacionOrigen = "";
	        $subcodigo = $datosAdicionPresentacion['subcodigo'];
	        $presentacion = $datosAdicionPresentacion['presentacion'];
	        $unidadMedida = $datosAdicionPresentacion['unidad_medida'];
	        $estado = $datosAdicionPresentacion['estado'];
	        $idAdicionPresentacionOrigenT = str_replace(".", "A", $idAdicionPresentacionOrigen);
	        
	        $filaAdicionPresentacion .=
	        '<tr id="fila' . ($idAdicionPresentacionOrigenT ? $idAdicionPresentacionOrigenT : $idAdicionPresentacion) . '">
                    <td>' . $subcodigo . '</td>
                    <td>' . $presentacion . ' ' . $unidadMedida . '</td>';                   
	        if ($banderaAcciones) {

	            if (!$idAdicionPresentacionOrigen) {
                    $filaAdicionPresentacion .=
                    '<td class="borrar">
                    <button type="button" name="eliminar" class="icono" onclick="fn_eliminarAdicionPresentacion(' . $idAdicionPresentacion . '); return false;"/>
                </td>';
                } else {
                    $filaAdicionPresentacion .= '<td class="' . $estado . '">
                        <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoAdicionPresentacion(' . $idAdicionPresentacionOrigen . '); return false;"/>
                    </td>';
                }
                
	        }else{
                $filaAdicionPresentacion .= '<td>' . $estado .' </td>';
            }
	        
	        $filaAdicionPresentacion .= '</tr>';
	    }

        $modificarAdicionPresentacion = '';

        if($rutaDocumentoRespaldo){
            $modificarAdicionPresentacion .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarAdicionPresentacion .= '
            <fieldset  id="fAdicionPresentacion">
                <legend>Modificar adicionar presentación</legend>
                ' . $ingresoDatos . '
                <table id="tAdicionPresentacion" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Subcodigo</th>
                            <th>Presentación</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>' . $filaAdicionPresentacion . '</tbody>
                </table>
            </fieldset>';
	    
	    return $modificarAdicionPresentacion;
	}
	
	/**
	 * Método para listar titularidad de producto agregada
	 */
	public function generarFilaAdicionPresentacion($idAdicionPresentacion, $datosAdicionPresentacion, $tiempoAtencion)
	{
	    
	    $subcodigo = $datosAdicionPresentacion['subcodigo'];
	    $presentacion = $datosAdicionPresentacion['presentacion'];
	    $unidadMedida = $datosAdicionPresentacion['unidad_medida'];
	    
	    $this->listaDetalles = '
                        <tr id="fila' . $idAdicionPresentacion . '">
                            <td>' . $subcodigo . '</td>
                            <td>' . $presentacion . ' ' . $unidadMedida . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarAdicionPresentacion(' . $idAdicionPresentacion . '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}
	
	/**
	 * Método para guardar registro de cambio de estado
	 */
	public function guardarEstadoAdicionPresentacion()
	{
	    
	    list($idProducto, $subcodigo) = explode('A', $_POST['id_tabla_origen']);
	    
	    $datos = [
	        'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
	        'id_producto' => $idProducto,
	        'subcodigo' => $subcodigo
	        
	    ];
	    
	    $datosAdicionPresentacion = $this->lNegocioAdicionesPresentaciones->buscarAdicionPresentacionPorEliminar($datos);
	    
	    if (!count($datosAdicionPresentacion)) {

	        $parametrosAdicionPresentacion = [
	            'id_producto' => $idProducto,
	            'subcodigo' => $subcodigo
	        ];
	        
	        $adicionProducto = $this->lNegocioAdicionesPresentacionesOrigen->buscarLista($parametrosAdicionPresentacion);
	        
	        $_POST['subcodigo'] = $adicionProducto->current()->subcodigo;
	        $_POST['presentacion'] = $adicionProducto->current()->presentacion;
	        $_POST['unidad_medida'] = $adicionProducto->current()->unidad_medida;
	        $_POST['estado'] = $adicionProducto->current()->estado === 'activo' ? 'inactivo' : 'activo';
	    
	    } else {
	        
	        $_POST['estado'] = $datosAdicionPresentacion->current()->estado === 'activo' ? 'inactivo' : 'activo';
	        $_POST['id_adicion_presentacion'] = $datosAdicionPresentacion->current()->id_adicion_presentacion;
	    
	    }
	    
	    $this->guardar();
	    
	    echo json_encode(array(
	        'estado' => 'EXITO',
	        'resultado' => 'Datos actualizados con éxito'
	    ));
	}
	

}
