<?php
/**
 * Controlador PeriodosReingresos
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodosReingresosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    PeriodosReingresosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

 namespace Agrodb\ModificacionProductoRia\Controladores;

 use Agrodb\ModificacionProductoRia\Modelos\PeriodosReingresosLogicaNegocio;
 use Agrodb\ModificacionProductoRia\Modelos\PeriodosReingresosModelo;
 use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;

class PeriodosReingresosControlador extends BaseControlador
{

    private $lNegocioPeriodosReingresos = null;
    private $modeloPeriodosReingresos = null;
    private $accion = null;
    private $lNegocioProductosInocuidad = null;
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPeriodosReingresos = new PeriodosReingresosLogicaNegocio();
        $this->modeloPeriodosReingresos = new PeriodosReingresosModelo();
        $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloPeriodosReingresos = $this->lNegocioPeriodosReingresos->buscarPeriodosReingresos();
        $this->tablaHtmlPeriodosReingresos($modeloPeriodosReingresos);
        require APP . 'ModificacionProductoRia/vistas/listaPeriodosReingresosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PeriodosReingresos";
        require APP . 'ModificacionProductoRia/vistas/formularioPeriodosReingresosVista.php';
    }

    /**
     * Método para registrar en la base de datos -PeriodosReingresos
     */
    public function guardar()
    {
        $this->lNegocioPeriodosReingresos->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodosReingresos
     */
    public function editar()
    {
        $this->accion = "Editar PeriodosReingresos";
        $this->modeloPeriodosReingresos = $this->lNegocioPeriodosReingresos->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioPeriodosReingresosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PeriodosReingresos
     */
    public function borrar()
    {
        $this->lNegocioPeriodosReingresos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PeriodosReingresos
     */
    public function tablaHtmlPeriodosReingresos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_periodo_reingreso'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\periodosreingresos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_periodo_reingreso'] . '</b></td>
<td>'
                    . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
                    . '</td>
<td>' . $fila['periodo_reingreso'] . '</td>
</tr>');
            }
        }
    }
    
    public function modificarPeriodoReingreso($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaPeriodoReingreso = '';
        $ingresoDatos = '';
        $banderaAcciones = false;
 
        switch ($estadoSoliciudProducto) {
            
            case 'Creado':
            case 'subsanacion':
                
                $qPeriodoReingresoActual = $this->lNegocioProductosInocuidad->buscarLista(array('id_producto' => $parametros['id_producto']));
                $periodoReingresoActual = $qPeriodoReingresoActual->current()->periodo_reingreso;
                
                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Periódo reingreso:</label>
                                    <input name="periodo_reingreso" id="periodo_reingreso" value="' . $periodoReingresoActual . '" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" />
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
                        			<button type="button" class="mas" id="agregarPeriodoReingreso" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;
                
        }
        
        $qPeriodoReingreso = $this->lNegocioPeriodosReingresos->buscarLista(array(
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
        ));
        
        foreach ($qPeriodoReingreso as $datosPeriodoReingreso) {
            
            $idDatoPeriodoReingreso = $datosPeriodoReingreso['id_periodo_reingreso'];
            $periodoReingreso = $datosPeriodoReingreso['periodo_reingreso'];
            
            $filaPeriodoReingreso .= '
                <tr id="fila' . $idDatoPeriodoReingreso . '">
                    <td>' . ($periodoReingreso != '' ? $periodoReingreso : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
            if ($banderaAcciones) {
                $filaPeriodoReingreso .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarPeriodoReingreso(' . $idDatoPeriodoReingreso . '); return false;"/>
                        </td>';
            }
            $filaPeriodoReingreso .= '</tr>';
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
        
        $modificarPeriodoReingreso .= '<fieldset  id="fPeriodoReingreso">
        <legend>Modificar periódo reingreso</legend>
        ' . $ingresoDatos . '
		<table id="tPeriodoReingreso" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaPeriodoReingreso . '</tbody>
		</table>
        </fieldset>';
        
        return $modificarPeriodoReingreso;
    }
    
    /**
     * Método para listar periodo reingreso agregado
     */
    public function generarFilaPeriodoReingreso($idPeriodoReingreso, $datosPeriodoReingreso, $tiempoAtencion)
    {
        
        $periodoReingreso = $datosPeriodoReingreso['periodo_reingreso'];
        
        $this->listaDetalles = '
                        <tr id="fila' . $idPeriodoReingreso . '">
                            <td>' . ($periodoReingreso != '' ? $periodoReingreso : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarPeriodoReingreso(' . $idPeriodoReingreso . '); return false;"/></td>
                        </tr>';
        
        return $this->listaDetalles;
    }

}
