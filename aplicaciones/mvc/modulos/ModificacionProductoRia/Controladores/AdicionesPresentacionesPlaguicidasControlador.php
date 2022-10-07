<?php
/**
 * Controlador AdicionesPresentacionesPlaguicidas
 *
 * Este archivo controla la lógica del negocio del modelo:  AdicionesPresentacionesPlaguicidasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    AdicionesPresentacionesPlaguicidasControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesPlaguicidasLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesPlaguicidasModelo;
use Agrodb\Catalogos\Modelos\PresentacionesPlaguicidasLogicaNegocio;

class AdicionesPresentacionesPlaguicidasControlador extends BaseControlador
{

    private $lNegocioAdicionesPresentacionesPlaguicidas = null;
    private $modeloAdicionesPresentacionesPlaguicidas = null;
    private $lNegocioPresentacionesPlaguicidas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAdicionesPresentacionesPlaguicidas = new AdicionesPresentacionesPlaguicidasLogicaNegocio();
        $this->modeloAdicionesPresentacionesPlaguicidas = new AdicionesPresentacionesPlaguicidasModelo();
        $this->lNegocioPresentacionesPlaguicidas = new PresentacionesPlaguicidasLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloAdicionesPresentacionesPlaguicidas = $this->lNegocioAdicionesPresentacionesPlaguicidas->buscarAdicionesPresentacionesPlaguicidas();
        $this->tablaHtmlAdicionesPresentacionesPlaguicidas($modeloAdicionesPresentacionesPlaguicidas);
        require APP . 'ModificacionProductoRia/vistas/listaAdicionesPresentacionesPlaguicidasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo AdicionesPresentacionesPlaguicidas";
        require APP . 'ModificacionProductoRia/vistas/formularioAdicionesPresentacionesPlaguicidasVista.php';
    }

    /**
     * Método para registrar en la base de datos -AdicionesPresentacionesPlaguicidas
     */
    public function guardar()
    {
        $this->lNegocioAdicionesPresentacionesPlaguicidas->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: AdicionesPresentacionesPlaguicidas
     */
    public function editar()
    {
        $this->accion = "Editar AdicionesPresentacionesPlaguicidas";
        $this->modeloAdicionesPresentacionesPlaguicidas = $this->lNegocioAdicionesPresentacionesPlaguicidas->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioAdicionesPresentacionesPlaguicidasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - AdicionesPresentacionesPlaguicidas
     */
    public function borrar()
    {
        $this->lNegocioAdicionesPresentacionesPlaguicidas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - AdicionesPresentacionesPlaguicidas
     */
    public function tablaHtmlAdicionesPresentacionesPlaguicidas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_adicion_presentacion_plaguicida'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\adicionespresentacionesplaguicidas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_adicion_presentacion_plaguicida'] . '</b></td>
<td>'
                    . $fila['id_detalle_solicitud_producto'] . '</td>
<td>' . $fila['id_tabla_origen']
                    . '</td>
<td>' . $fila['subcodigo'] . '</td>
</tr>');
            }
        }
    }

    public function modificarAdicionPresentacionPlaguicida($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
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
                            <label>Partida arancelaria: </label>
                            <select id="id_partida_arancelaria_plaguicida" name="id_partida_arancelaria_plaguicida" class="validacion" required>
                                <option value="">Seleccione....</option>
                                ' . $this->comboPartidasArancelariasPorProducto($parametros['id_producto']) . '
                            </select>
                            </div>
                        <div data-linea="2">
                            <label>Código complementario - suplementario: </label>
                            <select id="id_codigo_complementario_suplementario" name="id_codigo_complementario_suplementario" class="validacion" required>
                                <option value="">Seleccione....</option>
                            </select>
                        </div>
                        <div data-linea="3">
                            <label>Presentación: </label>
                            <input type="text" name="presentacion_plaguicida" id="presentacion_plaguicida" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" required/>
                            <input type="hidden" name="id_producto_plaguicida" id="id_producto_plaguicida" value="' . $idProducto . '" class="validacion" readonly="readonly" />
                        </div>
                        <div data-linea="4">
                            <label>Unidad: </label>
                            <select id="unidad_medida_plaguicida" name="unidad_medida_plaguicida" class="validacion" required>
                                <option value="">Seleccione....</option>
                                ' . $this->comboUnidadesMedida() . '
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
                			<button type="button" class="mas" id="agregarAdicionPresentacionPlaguicida" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
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

        $qDatosAdicionPresentacionPlagucida = $this->lNegocioAdicionesPresentacionesPlaguicidas->buscarAdicionPresentacionOrigenDestino($arrayConsulta);

        foreach ($qDatosAdicionPresentacionPlagucida as $datosAdicionPresentacion) {

            $idAdicionPresentacion = $datosAdicionPresentacion['id_adicion_presentacion_plaguicida'];
            (isset($datosAdicionPresentacion['id_adicion_presentacion_origen'])) ? $idAdicionPresentacionOrigen = $datosAdicionPresentacion['id_adicion_presentacion_origen'] : $idAdicionPresentacionOrigen = "";
            $subcodigo = $datosAdicionPresentacion['subcodigo'];
            $presentacion = $datosAdicionPresentacion['presentacion'];
            $unidadMedida = $datosAdicionPresentacion['unidad_medida'];
            $estado = $datosAdicionPresentacion['estado'];
            $partidaArancelaria = $datosAdicionPresentacion['partida_arancelaria'];
            $codigoProducto = $datosAdicionPresentacion['codigo_producto'];
            $codigoComplementario = $datosAdicionPresentacion['codigo_complementario'];
            $codigoSuplementario = $datosAdicionPresentacion['codigo_suplementario'];

            $filaAdicionPresentacion .=
                '<tr id="fila' . ($idAdicionPresentacionOrigen ? $idAdicionPresentacionOrigen : $idAdicionPresentacion) . '">
                    <td><label>Partida: </label>' . $partidaArancelaria . '<br/><label>Código: </label>' . $codigoProducto . '<br/><label>Complementario: </label>' . $codigoComplementario . '<br/><label>Suplememntario: </label>' . $codigoSuplementario . '</td>
                    <td>' . $subcodigo . '</td>
                    <td>' . $presentacion . ' ' . $unidadMedida . '</td>';
            if ($banderaAcciones) {

                if (!$idAdicionPresentacionOrigen) {
                    $filaAdicionPresentacion .=
                        '<td class="borrar">
                            <button type="button" name="eliminar" class="icono" onclick="fn_eliminarAdicionPresentacionPlaguicida(' . $idAdicionPresentacion . '); return false;"/>
                        </td>';
                } else {
                    $filaAdicionPresentacion .= '<td class="' . $estado . '">
                        <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoAdicionPresentacionPlaguicida(' . $idAdicionPresentacionOrigen . '); return false;"/>
                    </td>';
                }

            }else{
                $filaAdicionPresentacion .='<td>' . $estado . '</td>';
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
                <fieldset  id="fAdicionPresentacionPlaguicida">
                    <legend>Modificar adicionar presentación</legend>
                    ' . $ingresoDatos . '
                    <table id="tAdicionPresentacionPlaguicida" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Datos del producto</th>
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
     * Método para listar adicion de presentacion agregada
     */
    public function generarFilaAdicionPresentacion($idAdicionPresentacion, $datosAdicionPresentacion, /*$filas,*/ $tiempoAtencion)
    {
        $partidaArancelaria = $datosAdicionPresentacion['partida_arancelaria'];
        $codigoProducto = $datosAdicionPresentacion['codigo_producto'];
        $codigoComplementario = $datosAdicionPresentacion['codigo_complementario'];
        $codigoSuplementario = $datosAdicionPresentacion['codigo_suplementario'];
        $subcodigo = $datosAdicionPresentacion['subcodigo'];
        $presentacion = $datosAdicionPresentacion['presentacion'];
        $unidadMedida = $datosAdicionPresentacion['unidad_medida'];

        $this->listaDetalles = '
                        <tr id="fila' . $idAdicionPresentacion . '">
                            <td><label>Partida: </label>' . $partidaArancelaria . '<br/><label>Código: </label>' . $codigoProducto . '<br/><label>Complementario: </label>' . $codigoComplementario . '<br/><label>Suplememntario: </label>' . $codigoSuplementario . '</td>
                            <td>' . $subcodigo . '</td>
                            <td>' . $presentacion . ' ' . $unidadMedida . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarAdicionPresentacionPlaguicida(' . $idAdicionPresentacion . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para guardar registro de cambio de estado
     */
    public function guardarEstadoAdicionPresentacion()
    {

        $datos = [
            'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
            'id_tabla_origen' => $_POST['id_tabla_origen']
        ];

        $datosAdicionPresentacion = $this->lNegocioAdicionesPresentacionesPlaguicidas->buscarAdicionPresentacionPorEliminar($datos);

        if (!count($datosAdicionPresentacion)) {

            $adicionProducto = $this->lNegocioPresentacionesPlaguicidas->buscar($_POST['id_tabla_origen']);

            $idCodigoComplementarioSuplementario = $adicionProducto->getIdCodigoCompSupl();

            $qCodigoComplementarioSuplementario = $this->lNegocioPresentacionesPlaguicidas->obtenerDatosPartidaArencelariaPorIdCodigoComplementarioSuplementario($idCodigoComplementarioSuplementario);

            $_POST['id_partida_arancelaria'] = $qCodigoComplementarioSuplementario->current()->id_partida_arancelaria;
            $_POST['subcodigo'] = $qCodigoComplementarioSuplementario->current()->codigo_producto;
            $_POST['id_codigo_comp_supl'] = $idCodigoComplementarioSuplementario;
            $_POST['presentacion'] = $adicionProducto->getPresentacion();
            $_POST['id_unidad_medida'] = $adicionProducto->getIdUnidad();
            $_POST['unidad_medida'] = $adicionProducto->getUnidad();
            $_POST['estado'] = $adicionProducto->getEstado() === 'activo' ? 'inactivo' : 'activo';

        } else {

            $_POST['estado'] = $datosAdicionPresentacion->current()->estado === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_adicion_presentacion_plaguicida'] = $datosAdicionPresentacion->current()->id_adicion_presentacion_plaguicida;

        }

        $this->guardar();

        echo json_encode(array(
            'estado' => 'EXITO',
            'resultado' => 'Datos actualizados con éxito'
        ));
    }

}
