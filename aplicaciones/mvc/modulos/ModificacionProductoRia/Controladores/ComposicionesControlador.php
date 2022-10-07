<?php
/**
 * Controlador Composiciones
 *
 * Este archivo controla la lógica del negocio del modelo:  ComposicionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    ComposicionesControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\ComposicionInocuidadLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ComposicionesLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ComposicionesModelo;

class ComposicionesControlador extends BaseControlador
{

    private $lNegocioComposiciones = null;
    private $modeloComposiciones = null;
    private $accion = null;

    private $lNegocioComposicionInocuidadActual = null;


    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioComposiciones = new ComposicionesLogicaNegocio();
        $this->modeloComposiciones = new ComposicionesModelo();

        $this->lNegocioComposicionInocuidadActual = new ComposicionInocuidadLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloComposiciones = $this->lNegocioComposiciones->buscarComposiciones();
        $this->tablaHtmlComposiciones($modeloComposiciones);
        require APP . 'ModificacionProductoRia/vistas/listaComposicionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Composiciones";
        require APP . 'ModificacionProductoRia/vistas/formularioComposicionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Composiciones
     */
    public function guardar()
    {
        $this->lNegocioComposiciones->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: Composiciones
     */
    public function editar()
    {
        $this->accion = "Editar Composiciones";
        $this->modeloComposiciones = $this->lNegocioComposiciones->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioComposicionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Composiciones
     */
    public function borrar()
    {
        $this->lNegocioComposiciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Composiciones
     */
    public function tablaHtmlComposiciones($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_composicion'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\composiciones"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_composicion'] . '</b></td>
                        <td>' . $fila['id_detalle_solicitud_producto'] . '</td>
                        <td>' . $fila['id_ingrediente_activo'] . '</td>
                        <td>' . $fila['ingrediente_activo'] . '</td>
                    </tr>');
            }
        }
    }

    public function modificarComposicionProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $idArea = $parametros['id_area'];
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaComposicion = '';
        $ingresoDatos = '';
        $banderaAcciones = false;

        switch ($estadoSoliciudProducto) {

            case 'Creado':
            case 'subsanacion':
                
                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Tipo: </label>									
                                    <select id="id_tipo_componente" name="id_tipo_componente" class="validacion" required>
                                        <option value="">Seleccione....</option>' . $this->comboTipoComponente($idArea) . '
                                    </select>
                                    <input type="hidden" name="tipo_componente" id="tipo_componente" />
                                </div>
                                <div data-linea="2">
                                    <label>Nombre: </label>
                                        <select id="id_ingrediente_activo" name="id_ingrediente_activo" class="validacion" required style="width: 419px;">
                                        <option value="">Seleccione....</option>' . $this->comboIngredienteActivo($idArea) . '                                        
                                    </select>
                                    <input type="hidden" name="ingrediente_activo" id="ingrediente_activo" />
                                </div>
                                <div data-linea="3">
                                    <label>Concentración: </label>
                                    <input type="text" name="concentracion" id="concentracion" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" required/>
                                </div>
                                <div data-linea="4">
                                    <label>Unidad: </label>
                                    <select id="unidad_medida" name="unidad_medida" required class="validacion">
                                        <option value="">Seleccione....</option>' . $this->comboUnidadesMedida() . '
                                    </select>
                                </div>
                                <hr/>
                                <div data-linea="5">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="6">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="7">
                        			<button type="button" class="mas" id="agregarComposicion" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
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

        $qDatosComposicion = $this->lNegocioComposiciones->buscarComposicionesOrigenDestino($arrayConsulta);

        foreach ($qDatosComposicion as $datosComposicion) {

            $idComposicionOrigen = $datosComposicion['id_composicion_origen'];
            $idComposicion = $datosComposicion['id_composicion'];
            $ingredienteActivo = $datosComposicion['ingrediente_activo'];
            $tipoComponente = $datosComposicion['tipo_componente'];
            $concentracion = $datosComposicion['concentracion'];
            $unidadMedida = $datosComposicion['unidad_medida'];
            $estado = ($datosComposicion['estado'] ? $datosComposicion['estado'] : 'activo');

            $filaComposicion .=
                '<tr id="fila' . ($idComposicionOrigen ? $idComposicionOrigen : $idComposicion) . '">
                    <td>' . $tipoComponente . '</td>
                    <td>' . $ingredienteActivo . '</td>
                    <td>' . $concentracion . ' ' . $unidadMedida . '</td>';
            if ($banderaAcciones) {
                if (!$idComposicionOrigen) {
                    $filaComposicion .=
                        '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarComposicion(' . $idComposicion . '); return false;"/>
                    </td>';
                } else {
                    $filaComposicion .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoComposicion(' . $idComposicionOrigen . '); return false;"/>
                        </td>';
                }

            }else{
                $filaComposicion .= '<td>' . $estado .' </td>';
            }
            $filaComposicion .= '</tr>';
        }

        $modificarComposicion = '';

        if($rutaDocumentoRespaldo){
            $modificarComposicion .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarComposicion .= '
            <fieldset  id="fComposicionProducto">
                <legend>Modificar composición</legend>
                ' . $ingresoDatos . '
                <table id="tComposicionProducto" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Concentración</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>' . $filaComposicion . '</tbody>
                </table>
            </fieldset>';

        return $modificarComposicion;
    }

    /**
     * Método generar fila de composiciones
     */
    public function generarFilaComposicionProducto($idComposicionProducto, $datosComposicionProducto, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idComposicionProducto . '">
                            <td>' . $datosComposicionProducto['tipo_componente'] . '</td>
                            <td>' . $datosComposicionProducto['ingrediente_activo'] . '</td>
                            <td>' . $datosComposicionProducto['concentracion'] . ' ' . $datosComposicionProducto['unidad_medida'] . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarComposicion(' . $idComposicionProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para guardar registro de cambio de estado
     */
    public function guardarEstadoComposicion()
    {

        $datos = [
            'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
            'id_tabla_origen' => $_POST['id_tabla_origen']
        ];

        $datosComposicion = $this->lNegocioComposiciones->buscarLista($datos);

        if (!count($datosComposicion)) {

            $composicion = $this->lNegocioComposicionInocuidadActual->buscar($_POST['id_tabla_origen']);

            $_POST['id_ingrediente_activo'] = $composicion->getIdIngredienteActivo();
            $_POST['ingrediente_activo'] = $composicion->getIngredienteActivo();
            $_POST['id_tipo_componente'] = $composicion->getIdTipoComponente();
            $_POST['tipo_componente'] = $composicion->getTipoComponente();
            $_POST['concentracion'] = $composicion->getConcentracion();
            $_POST['unidad_medida'] = $composicion->getUnidadMedida();
            $_POST['estado'] = 'inactivo';
        } else {
            $_POST['estado'] = $datosComposicion->current()->estado === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_composicion'] = $datosComposicion->current()->id_composicion;
        }

        $this->guardar();

        echo json_encode(array(
            'estado' => 'EXITO',
            'resultado' => 'Datos actualizados con éxito'
        ));
    }
}
