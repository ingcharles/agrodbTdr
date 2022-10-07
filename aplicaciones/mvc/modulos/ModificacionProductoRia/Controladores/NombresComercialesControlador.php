<?php
 /**
 * Controlador NombresComerciales
 *
 * Este archivo controla la lógica del negocio del modelo:  NombresComercialesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    NombresComercialesControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\NombresComercialesLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\NombresComercialesModelo;
 use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
 
class NombresComercialesControlador extends BaseControlador 
{

		 private $lNegocioNombresComerciales = null;
		 private $modeloNombresComerciales = null;
		 private $accion = null;
		 private $lNegocioProductos = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioNombresComerciales = new NombresComercialesLogicaNegocio();
		 $this->modeloNombresComerciales = new NombresComercialesModelo();
		 $this->lNegocioProductos = new ProductosLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloNombresComerciales = $this->lNegocioNombresComerciales->buscarNombresComerciales();
		 $this->tablaHtmlNombresComerciales($modeloNombresComerciales);
		 require APP . 'ModificacionProductoRia/vistas/listaNombresComercialesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo NombresComerciales"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioNombresComercialesVista.php';
		}	/**
		* Método para registrar en la base de datos -NombresComerciales
		*/
		public function guardar()
		{
		  $this->lNegocioNombresComerciales->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: NombresComerciales
		*/
		public function editar()
		{
		 $this->accion = "Editar NombresComerciales"; 
		 $this->modeloNombresComerciales = $this->lNegocioNombresComerciales->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioNombresComercialesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - NombresComerciales
		*/
		public function borrar()
		{
		  $this->lNegocioNombresComerciales->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - NombresComerciales
		*/
		 public function tablaHtmlNombresComerciales($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_nombre_comercial'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\nombrescomerciales"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_nombre_comercial'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['nombre_comercial']
		  . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
		}
		}
	}
	
	public function modificarNombreComercial($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
	{
	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaNombreComercial = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;
	    
	    switch ($estadoSoliciudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $qNombreComercialActual = $this->lNegocioProductos->buscarLista(array('id_producto' => $parametros['id_producto']));
	            $nombreComercialActual = $qNombreComercialActual->current()->nombre_comun;
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Nombre comercial:</label>
                                    <input name="nombre_comercial" id="nombre_comercial" value="' . $nombreComercialActual . '" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" />
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
                        			<button type="button" class="mas" id="agregarNombreComercial" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
	            break;
	            
	    }
	    
	    $qNombreComercial = $this->lNegocioNombresComerciales->buscarLista(array(
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ));

	    foreach ($qNombreComercial as $datosNombreComercial) {

	        $idNombreComercial = $datosNombreComercial['id_nombre_comercial'];
	        $nombreComercial = $datosNombreComercial['nombre_comercial'];
	        
	        $filaNombreComercial .= '
                <tr id="fila' . $idNombreComercial . '">
                    <td>' . ($nombreComercial != '' ? $nombreComercial : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
	        if ($banderaAcciones) {
	            $filaNombreComercial .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarNombreComercial(' . $idNombreComercial . '); return false;"/>
                        </td>';
	        }
	        $filaNombreComercial .= '</tr>';
	    }

        $modificarPeriodoReingreso = '';

        if($rutaDocumentoRespaldo){
            $modificarPeriodoReingreso .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }
	    
	    $modificarPeriodoReingreso .= '<fieldset  id="fNombreComercial">
        <legend>Modificar periódo reingreso</legend>
        ' . $ingresoDatos . '
		<table id="tNombreComercial" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaNombreComercial . '</tbody>
		</table>
        </fieldset>';
	    
	    return $modificarPeriodoReingreso;
	}
	
	/**
	 * Método para listar el nombre comercial
	 */
	public function generarFilaNombreComercial($idNombreComercial, $datosNombreComercial, $tiempoAtencion)
	{
	    
	    $nombreComercial = $datosNombreComercial['nombre_comercial'];
	    
	    $this->listaDetalles = '
                        <tr id="fila' . $idNombreComercial . '">
                            <td>' . ($nombreComercial != '' ? $nombreComercial : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarNombreComercial(' . $idNombreComercial . '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}

}
