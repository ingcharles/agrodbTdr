<?php
 /**
 * Controlador CategoriasToxicologicas
 *
 * Este archivo controla la lógica del negocio del modelo:  CategoriasToxicologicasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    CategoriasToxicologicasControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */
 namespace Agrodb\ModificacionProductoRia\Controladores;
 use Agrodb\ModificacionProductoRia\Modelos\CategoriasToxicologicasLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\CategoriasToxicologicasModelo;
 use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
 
class CategoriasToxicologicasControlador extends BaseControlador 
{

		 private $lNegocioCategoriasToxicologicas = null;
		 private $modeloCategoriasToxicologicas = null;
		 private $accion = null;
		 private $lNegocioProductosInocuidad = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCategoriasToxicologicas = new CategoriasToxicologicasLogicaNegocio();
		 $this->modeloCategoriasToxicologicas = new CategoriasToxicologicasModelo();
		 $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
		 $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCategoriasToxicologicas = $this->lNegocioCategoriasToxicologicas->buscarCategoriasToxicologicas();
		 $this->tablaHtmlCategoriasToxicologicas($modeloCategoriasToxicologicas);
		 require APP . 'ModificacionProductoRia/vistas/listaCategoriasToxicologicasVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CategoriasToxicologicas"; 
		 require APP . 'ModificacionProductoRia/vistas/formularioCategoriasToxicologicasVista.php';
		}	/**
		* Método para registrar en la base de datos -CategoriasToxicologicas
		*/
		public function guardar()
		{
		  $this->lNegocioCategoriasToxicologicas->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CategoriasToxicologicas
		*/
		public function editar()
		{
		 $this->accion = "Editar CategoriasToxicologicas"; 
		 $this->modeloCategoriasToxicologicas = $this->lNegocioCategoriasToxicologicas->buscar($_POST["id"]);
		 require APP . 'ModificacionProductoRia/vistas/formularioCategoriasToxicologicasVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CategoriasToxicologicas
		*/
		public function borrar()
		{
		  $this->lNegocioCategoriasToxicologicas->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CategoriasToxicologicas
		*/
		 public function tablaHtmlCategoriasToxicologicas($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_categoria_toxicologica'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ModificacionProductoRia\categoriastoxicologicas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_categoria_toxicologica'] . '</b></td>
<td>'
		  . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
		  . '</td>
<td>' . $fila['categoria_toxicologica'] . '</td>
</tr>');
		}
		}
	}
	
	public function modificarCategoriaToxicologica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
	{
	    $idArea = $parametros['id_area'];
	    $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
	    $filaCategoriaToxicologica = '';
	    $ingresoDatos = '';
	    $banderaAcciones = false;

	    switch ($estadoSoliciudProducto) {
	        
	        case 'Creado':
	        case 'subsanacion':
	            
	            $qCategoriaToxicologicaActual = $this->lNegocioProductosInocuidad->buscarLista(array('id_producto' => $parametros['id_producto']));
	            $categoriaToxicologicaActual = $qCategoriaToxicologicaActual->current()->id_categoria_toxicologica;
	            
	            $banderaAcciones = true;
	            $ingresoDatos = '<div data-linea="1">
                                    <label>Categoría toxicológica:</label>
                                    <select name="id_categoria_toxicologica" id="id_categoria_toxicologica" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion">
                                        <option value="">Seleccionar....</option>' . $this->comboCategoriaToxicologica($idArea, $categoriaToxicologicaActual) . '</select>
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
                        			<button type="button" class="mas" id="agregarCategoriaToxicologica" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
	            break;
	            
	    }
	    
	    $qDatosCategoriaToxicologica = $this->lNegocioCategoriasToxicologicas->buscarLista(array(
	        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
	    ));
	  	    
	    foreach ($qDatosCategoriaToxicologica as $datosCategoriaToxicologica) {
	        
	        $idDatoCategoriaToxicologica = $datosCategoriaToxicologica['id_categoria_toxicologica'];
	        $categoriaToxicologica = $datosCategoriaToxicologica['categoria_toxicologica'];
	        
	        $filaCategoriaToxicologica .= '
                <tr id="fila' . $idDatoCategoriaToxicologica . '">
                    <td>' . ($categoriaToxicologica != '' ? $categoriaToxicologica : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
	        if ($banderaAcciones) {
	            $filaCategoriaToxicologica .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarCategoriaToxicologica(' . $idDatoCategoriaToxicologica . '); return false;"/>
                        </td>';
	        }
	        $filaCategoriaToxicologica .= '</tr>';
	    }

        $modificarCategoriaToxicologica = '';

        if($rutaDocumentoRespaldo){
            $modificarCategoriaToxicologica .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }
	    
	    $modificarCategoriaToxicologica .= '
        <fieldset  id="fCategoriaToxicologica">
            <legend>Modificar categoría toxicológica</legend>
            ' . $ingresoDatos . '
            <table id="tCategoriaToxicologica" style="width: 100%">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Tiempo de atención</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $filaCategoriaToxicologica . '</tbody>
            </table>
        </fieldset>';
	    
	    return $modificarCategoriaToxicologica;
	}
	
	
	/**
	 * Método para listar categoria toxicologica agregada
	 */
	public function generarFilaCategoriaToxicologica($idCategoriaToxicologica, $datosCategoriaToxicologica, $tiempoAtencion)
	{
	    $categoriaToxicologica = $datosCategoriaToxicologica['categoria_toxicologica'];
	    	    
	    $this->listaDetalles = '
                        <tr id="fila' . $idCategoriaToxicologica . '">
                            <td>' . ($categoriaToxicologica != '' ? $categoriaToxicologica : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarCategoriaToxicologica(' . $idCategoriaToxicologica . '); return false;"/></td>
                        </tr>';
	    
	    return $this->listaDetalles;
	}

}
