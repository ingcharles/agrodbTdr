<?php
/**
 * Controlador ViasAdministracionesDosis
 *
 * Este archivo controla la lógica del negocio del modelo:  ViasAdministracionesDosisModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    ViasAdministracionesDosisControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\ModificacionProductoRia\Modelos\ViasAdministracionesDosisLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ViasAdministracionesDosisModelo;
use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;

class ViasAdministracionesDosisControlador extends BaseControlador
{

    private $lNegocioViasAdministracionesDosis = null;
    private $modeloViasAdministracionesDosis = null;
    private $accion = null;
    private $lNegocioProductosInocuidad = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioViasAdministracionesDosis = new ViasAdministracionesDosisLogicaNegocio();
        $this->modeloViasAdministracionesDosis = new ViasAdministracionesDosisModelo();
        $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloViasAdministracionesDosis = $this->lNegocioViasAdministracionesDosis->buscarViasAdministracionesDosis();
        $this->tablaHtmlViasAdministracionesDosis($modeloViasAdministracionesDosis);
        require APP . 'ModificacionProductoRia/vistas/listaViasAdministracionesDosisVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo ViasAdministracionesDosis";
        require APP . 'ModificacionProductoRia/vistas/formularioViasAdministracionesDosisVista.php';
    }

    /**
     * Método para registrar en la base de datos -ViasAdministracionesDosis
     */
    public function guardar()
    {
        $this->lNegocioViasAdministracionesDosis->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: ViasAdministracionesDosis
     */
    public function editar()
    {
        $this->accion = "Editar ViasAdministracionesDosis";
        $this->modeloViasAdministracionesDosis = $this->lNegocioViasAdministracionesDosis->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioViasAdministracionesDosisVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ViasAdministracionesDosis
     */
    public function borrar()
    {
        $this->lNegocioViasAdministracionesDosis->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ViasAdministracionesDosis
     */
    public function tablaHtmlViasAdministracionesDosis($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_via_administracion_dosis'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\viasadministracionesdosis"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_via_administracion_dosis'] . '</b></td>
<td>'
                    . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
                    . '</td>
<td>' . $fila['dosis'] . '</td>
</tr>');
            }
        }
    }

    public function modificarViaAdministracionDosis($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaViaAdministracionDosis = '';
        $ingresoDatos = '';
        $banderaAcciones = false;

        switch ($estadoSoliciudProducto) {

            case 'Creado':
            case 'subsanacion':

                $qViaAdministracionDosisActual = $this->lNegocioProductosInocuidad->buscarLista(array('id_producto' => $parametros['id_producto']));
                $dosisActual = $qViaAdministracionDosisActual->current()->dosis;
                $unidadDosisActual = $qViaAdministracionDosisActual->current()->unidad_dosis;

                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Dosis:</label>
                                    <input name="dosis" id="dosis" value="' . $dosisActual . '" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" />
                                </div>
                                <div data-linea="1">
                                    <label>Unidad:</label>
                                    <select name="unidad_dosis" id="unidad_dosis" class="validacion">
                                        <option value="">Seleccionar....</option>' . $this->comboUnidadesMedida($unidadDosisActual) . '</select>
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
                        			<button type="button" class="mas" id="agregarViaAdministracionDosis" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;

        }

        $qViaAdministracionDosis = $this->lNegocioViasAdministracionesDosis->buscarLista(array(
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
        ));

        foreach ($qViaAdministracionDosis as $datosViaAdministracionDosis) {

            $idDatoViaAdministracionDosis = $datosViaAdministracionDosis['id_via_administracion_dosis'];
            $dosis = $datosViaAdministracionDosis['dosis'];
            $unidadDosis = $datosViaAdministracionDosis['unidad_dosis'];

            $filaViaAdministracionDosis .= '
                <tr id="fila' . $idDatoViaAdministracionDosis . '">
                    <td>' . ($dosis != '' ? $dosis : '') . ' ' . ($unidadDosis != '' ? $unidadDosis : '') . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
            if ($banderaAcciones) {
                $filaViaAdministracionDosis .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarViaAdministracionDosis(' . $idDatoViaAdministracionDosis . '); return false;"/>
                        </td>';
            }
            $filaViaAdministracionDosis .= '</tr>';
        }

        $modificarViaAdministracionDosis = '';

        if($rutaDocumentoRespaldo){
            $modificarViaAdministracionDosis .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarViaAdministracionDosis .= '<fieldset  id="fViaAdministracionDosis">
        <legend>Modificar vía administración y/o dosis</legend>
        ' . $ingresoDatos . '
		<table id="tViaAdministracionDosis" style="width: 100%">
			<thead>
				<tr>
					<th>Descripción</th>
					<th>Tiempo de atención</th>
                    <th></th>
				</tr>
			</thead>
			<tbody>' . $filaViaAdministracionDosis . '</tbody>
		</table>
        </fieldset>';

        return $modificarViaAdministracionDosis;
    }

    /**
     * Método para listar via de administracion dosis agregada
     */
    public function generarFilaViaAdministracionDosis($idViaAdministracionDosis, $datosViaAdministracionDosis, $tiempoAtencion)
    {

        $dosis = $datosViaAdministracionDosis['dosis'];
        $unidadDosis = $datosViaAdministracionDosis['unidad_dosis'];

        $this->listaDetalles = '
                        <tr id="fila' . $idViaAdministracionDosis . '">
                            <td>' . ($dosis != '' ? $dosis : '') . ' ' . ($unidadDosis != '' ? $unidadDosis : '') . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarViaAdministracionDosis(' . $idViaAdministracionDosis . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

}
