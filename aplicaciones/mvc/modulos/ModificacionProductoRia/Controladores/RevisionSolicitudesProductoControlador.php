<?php
/**
 * Controlador Importaciones
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-08-23
 * @uses RevisionSolicitudesProductoControlador
 * @package RevisionSolicitudesProducto
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;


use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\ModificacionProductoRia\Modelos\DetalleSolicitudesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\SolicitudesProductosLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionCoordinadorLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;

class RevisionSolicitudesProductoControlador extends BaseControlador
{
    private $lNegocioSolicitudesProductos = null;
    private $lNegocioProductos = null;
    private $lNegocioDetalleSolicitudesProductos = null;
    private $lNegocioUsuariosPerfiles = null;
    private $lNegocioAsignacionCoordinador = null;
    private $lNegocioAsignacionInspector = null;

    private $pestania = null;
    private $accion = null;
    private $datosGenerales = null;
    private $comboPerfilRevision = null;
    private $rutaFecha = null;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSolicitudesProductos = new SolicitudesProductosLogicaNegocio();
        $this->lNegocioDetalleSolicitudesProductos = new DetalleSolicitudesProductosLogicaNegocio();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
        $this->lNegocioAsignacionCoordinador = new AsignacionCoordinadorLogicaNegocio();
        $this->lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function asignarSolicitudes()
    {
        $this->cargarPanelBusquedaSolicitud();
        require APP . 'ModificacionProductoRia/vistas/listaRevisionSolicitudesProductoVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function atenderSolicitudes()
    {
        $this->cargarPanelBusquedaSolicitud();
        require APP . 'ModificacionProductoRia/vistas/listaAtenderSolicitudesProductoVista.php';
    }

    /**
     * Desplegar la lista de solicitudes de producto
     */
    public function listarSolicitudesProducto()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $_POST['identificador_revisor'] = '';
        $_POST['estado_solicitud_producto'] = "'inspeccion', 'asignadoInspeccion'";

        $solicitudesModificacion = $this->lNegocioSolicitudesProductos->buscarSolicitudesProductoXFiltro($_POST);

        if ($solicitudesModificacion->count()) {
            $this->tablaHtmlSolicitudesProducto($solicitudesModificacion);
            $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        } else {
            $contenido = \Zend\Json\Json::encode('');
            $mensaje = 'No existen registros';
            $estado = 'FALLO';
        }

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido));
    }

    /**
     * Desplegar la lista de solicitudes de producto
     */
    public function listarSolicitudesProductoPorAtender()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $_POST['identificador_revisor'] = $this->usuarioActivo();
        $_POST['estado_solicitud_producto'] = "'asignadoInspeccion'";

        $solicitudesModificacion = $this->lNegocioSolicitudesProductos->buscarSolicitudesProductoXFiltro($_POST);

        if ($solicitudesModificacion->count()) {
            $this->tablaHtmlSolicitudesProductoPorAtender($solicitudesModificacion);
            $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        } else {
            $contenido = \Zend\Json\Json::encode('');
            $mensaje = 'No existen registros';
            $estado = 'FALLO';
        }

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido));
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes productos
     */
    public function tablaHtmlSolicitudesProducto($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {

                switch ($fila['id_area']) {
                    case 'IAV':
                        $area = 'Pecuaria';
                        break;
                    case 'IAP':
                        $area = 'Agrícola';
                        break;
                    case 'IAF':
                        $area = 'Fertilizante';
                        break;
                }

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_solicitud_producto'] . '"
							  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\RevisionSolicitudesProducto"
							  data-opcion="asignarTecnico" ondragstart="drag(event)" draggable="true"
							  data-destino="detalleItem">
							  <td>' . ++$contador . '</td>
							  <td>' . $fila['numero_solicitud'] . '</td>
                              <td>' . $area . '</td>
                              <td>' . $fila['nombre_comun'] . '</td>
                              <td>' . $fila['razon_social'] . '</td>
                              <td>' . $fila['provincia_operador'] . '</td>
                              <td>' . $fila['nombre_revisor'] . '</td>
					</tr>');
            }
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes productos revision
     */
    public function tablaHtmlSolicitudesProductoPorAtender($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {

                switch ($fila['id_area']) {
                    case 'IAV':
                        $area = 'Pecuaria';
                        break;
                    case 'IAP':
                        $area = 'Agrícola';
                        break;
                    case 'IAF':
                        $area = 'Fertilizante';
                        break;
                }

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_solicitud_producto'] . '"
							  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\RevisionSolicitudesProducto"
							  data-opcion="revisarSolicitudTecnico" ondragstart="drag(event)" draggable="true"
							  data-destino="detalleItem">
							  <td>' . ++$contador . '</td>
							  <td>' . $fila['numero_solicitud'] . '</td>
                              <td>' . $area . '</td>
                              <td>' . $fila['nombre_comun'] . '</td>
                              <td>' . $fila['razon_social'] . '</td>
                              <td>' . $fila['provincia_operador'] . '</td>
					</tr>');
            }
        }
    }

    public function asignarTecnico()
    {
        $idSolicitudProducto = explode(',', ($_POST['id'] === '_asignar' ? $_POST['elementos'] : $_POST['id']));

        foreach ($idSolicitudProducto as $solicitud) {
            $datosSolicitud = $this->lNegocioSolicitudesProductos->buscar($solicitud);

            $idProducto = $datosSolicitud->getIdProducto();
            $identificadorOperador = $datosSolicitud->getIdentificadorOperador();
            $numeroRegistro = $datosSolicitud->getNumeroRegistro();

            $operador = $this->lNegocioSolicitudesProductos->obtenerDatosOperador($identificadorOperador);
            $this->datosGenerales .= $this->datosGeneralesOperador($operador);

            $datosConsultaProducto = [
                'id_producto' => $idProducto
            ];

            $producto = $this->lNegocioProductos->obtenerDatosProducto($datosConsultaProducto);

            $datosProducto = $producto->toArray();
            $datosProducto[0]['numero_registro'] = $numeroRegistro;
            $this->datosGenerales .= $this->datosGeneralesProducto($datosProducto[0]);

            $tiposModificacion = $this->lNegocioDetalleSolicitudesProductos->obtenerDetallesSolicitudesModificacionProducto($solicitud);
            $this->datosGenerales .= $this->datosGeneralesModificacionProducto($tiposModificacion);

            $this->datosGenerales .= '<hr><br>';
        }

        $arrayParametros = array(
            'idSolicitud' => $idSolicitudProducto,
            'tipoSolicitud' => 'modificacionProductoRia',
            'tipoInspector' => 'Técnico');

        $this->desplegarDetalleRevisoresAsignados($arrayParametros);
        $this->cargarTecnicosAsigancionRevisionFormularios();

        require APP . 'ModificacionProductoRia/vistas/formularioRevisionSolicitudesProductosVista.php';

    }

    /**
     * Metodo para cargar el combo de asignacion de tecnicos
     */
    public function cargarTecnicosAsigancionRevisionFormularios()
    {
        $perfil = "('PFL_TEC_MOD_PRO')";

        $arrayParametros = array(
            'codigo_perfil' => $perfil);

        $tecnicosAsignacion = $this->lNegocioUsuariosPerfiles->buscarUsuariosInternosPorPerfil($arrayParametros);

        foreach ($tecnicosAsignacion as $item) {
            $this->comboPerfilRevision .= '<option value="' . $item->identificador . '" >' . $item->nombre . ' ' . $item->apellido . '</option>';
        }

        $this->comboPerfilRevision;
    }

    /**
     * Método para registrar en la base de datos el técnico asignado
     */
    public function guardarAsignacionRevisor()
    {
        $revisorAsignado = $_POST['revisorAsignado'];
        $nombreRevisorAsignado = $_POST['nombreRevisorAsignado'];
        $asignante = $_SESSION['usuario'];
        $idSolicitud = $_POST['idSolicitud'];
        $tipoSolicitud = $_POST['tipoSolicitud'];
        $tipoInspector = $_POST['tipoInspector'];

        $filaRevisorAsignado = "";
        $banderaRegistro = false;

        $validacion = "Fallo";
        $resultado = "La solicitud solo puede ser asignada a un técnico a la vez.";

        $arraySolicitudes = explode(",", $idSolicitud);

        foreach ($arraySolicitudes as $solicitud) {

            $arrayParametros = array(
                'identificador_inspector' => $revisorAsignado,
                'fecha_asignacion' => 'now()',
                'identificador_asignante' => $asignante,
                'id_solicitud' => $solicitud,
                'tipo_solicitud' => $tipoSolicitud,
                'tipo_inspector' => $tipoInspector);

            $procesoValidacion = $this->lNegocioAsignacionCoordinador->guardar($arrayParametros);

            if ($procesoValidacion) {

                $banderaRegistro = true;

                $arrayParametrosAsignacion = array(
                    'id_solicitud_producto' => $solicitud,
                    'estado_solicitud_producto' => 'asignadoInspeccion',
                    'idenitificador_revisor' => $revisorAsignado);

                $this->lNegocioSolicitudesProductos->actualizarEstadoModificacionProducto($arrayParametrosAsignacion);

                $datosSolicitud = $this->lNegocioSolicitudesProductos->buscar($solicitud);
                $numeroSolicitud = $datosSolicitud->getNumeroSolicitud();
                $idArea = $datosSolicitud->getIdArea();
                $provinciaOperador = $datosSolicitud->getProvinciaOperador();

                switch ($idArea) {
                    case 'IAV':
                        $area = 'Pecuaria';
                        break;
                    case 'IAP':
                        $area = 'Agrícola';
                        break;
                    case 'IAF':
                        $area = 'Fertilizante';
                        break;
                }


                $arrayParametrosFila = array(
                    'id_asignacion_coordinador' => $procesoValidacion,
                    'numero_solicitud' => $numeroSolicitud,
                    'provincia_operador' => $provinciaOperador,
                    'nombre_inspector_asignado' => $nombreRevisorAsignado,
                    'id_solicitud_producto' => $solicitud,
                    'nombre_area' => $area,
                    'provincia_operador' => $provinciaOperador
                );

                $filaRevisorAsignado .= $this->generarFilaRevisorAsignado($arrayParametrosFila);
            } else {
                break;
            }
        }

        if ($banderaRegistro) {

            $validacion = "Exito";
            $resultado = "";

            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado,
                'filaRevisorAsignado' => $filaRevisorAsignado));
        } else {

            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado));
        }
    }

    /**
     * Método para agregar una fila del revisor asignado a una solicitud.
     */
    public function generarFilaRevisorAsignado($arrayParametros)
    {
        $idAsignacionCoordinador = $arrayParametros['id_asignacion_coordinador'];
        $numeroSolicitud = $arrayParametros['numero_solicitud'];
        $nombreInspectorAsignado = $arrayParametros['nombre_inspector_asignado'];
        $idSolicitudProducto = $arrayParametros['id_solicitud_producto'];
        $nombreArea = $arrayParametros['nombre_area'];
        $provinciaOperador = $arrayParametros['provincia_operador'];

        $this->listaRevisorAsignado = '
                        <tr id="fila' . $idAsignacionCoordinador . '">
                            <td>' . $numeroSolicitud . '</td>
                            <td>' . $nombreArea . '</td>
                            <td>' . $nombreInspectorAsignado . '</td>
                            <td>' . $provinciaOperador . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetalleRevisorAsignado(' . $idAsignacionCoordinador . ', ' . $idSolicitudProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaRevisorAsignado;
    }

    /**
     * Método para borrar una fila de un revisor asignado
     */
    public function eliminarAsignacionRevisor()
    {
        $idAsignacionCordinador = $_POST['idAsignacionCoordinador'];
        $this->lNegocioAsignacionCoordinador->borrar($idAsignacionCordinador);

        $arrayParametros = array(
            'id_solicitud_producto' => $_POST['idSolicitudProducto'],
            'estado_solicitud_producto' => 'inspeccion',
            'idenificador_revisro' => null
        );

        $this->lNegocioSolicitudesProductos->actualizarEstadoModificacionProducto($arrayParametros);
    }

    /**
     * Método para listar los revisaores asignadasos a una solicitud de proveedor en el exterior
     */
    public function desplegarDetalleRevisoresAsignados($arrayParametros)
    {
        $arraySolicitudes = $arrayParametros['idSolicitud'];
        $tipoSolicitud = $arrayParametros['tipoSolicitud'];
        $tipoInspector = $arrayParametros['tipoInspector'];

        $this->generarFilaRevisorAsignado = "";

        foreach ($arraySolicitudes as $solicitud) {

            $arrayParametros = array(
                'id_solicitud' => $solicitud,
                'tipo_solicitud' => $tipoSolicitud,
                'tipo_inspector' => $tipoInspector);

            $procesoValidacion = $this->lNegocioAsignacionCoordinador->buscarAsignacionCoordinador($arrayParametros);

            if (isset($procesoValidacion->current()->id_asignacion_coordinador)) {

                $datosSolicitud = $this->lNegocioSolicitudesProductos->buscar($solicitud);
                $numeroSolicitud = $datosSolicitud->getNumeroSolicitud();
                $idArea = $datosSolicitud->getIdArea();
                $provinciaOperador = $datosSolicitud->getProvinciaOperador();
                $nombreRevisorAsignado = $procesoValidacion->current()->nombre_revisor;

                switch ($idArea) {
                    case 'IAV':
                        $area = 'Pecuaria';
                        break;
                    case 'IAP':
                        $area = 'Agrícola';
                        break;
                    case 'IAF':
                        $area = 'Fertilizante';
                        break;
                }


                $arrayParametrosFila = array(
                    'id_asignacion_coordinador' => $procesoValidacion->current()->id_asignacion_coordinador,
                    'numero_solicitud' => $numeroSolicitud,
                    'provincia_operador' => $provinciaOperador,
                    'nombre_inspector_asignado' => $nombreRevisorAsignado,
                    'id_solicitud_producto' => $solicitud,
                    'nombre_area' => $area,
                    'provincia_operador' => $provinciaOperador
                );

                $this->generarFilaRevisorAsignado .= $this->generarFilaRevisorAsignado($arrayParametrosFila);
            }
        }

        $this->generarFilaRevisorAsignado;
    }

    public function revisarSolicitudTecnico()
    {
        $modificaciones = [];
        $idSolicitudProducto = $_POST['id'];

        $datosSolicitud = $this->lNegocioSolicitudesProductos->buscar($idSolicitudProducto);

        $idProducto = $datosSolicitud->getIdProducto();
        $identificadorOperador = $datosSolicitud->getIdentificadorOperador();
        $numeroRegistro = $datosSolicitud->getNumeroRegistro();
        $idArea = $datosSolicitud->getIdArea();
        $estadoSolicitudProducto = $datosSolicitud->getEstadoSolicitudProducto();

        $operador = $this->lNegocioSolicitudesProductos->obtenerDatosOperador($identificadorOperador);
        $this->datosGenerales .= $this->datosGeneralesOperador($operador);

        $datosConsultaProducto = [
            'id_producto' => $idProducto
        ];

        $producto = $this->lNegocioProductos->obtenerDatosProducto($datosConsultaProducto);

        $datosProducto = $producto->toArray();
        $datosProducto[0]['numero_registro'] = $numeroRegistro;
        $this->datosGenerales .= $this->datosGeneralesProducto($datosProducto[0]);

        $tiposModificacion = $this->lNegocioDetalleSolicitudesProductos->obtenerDetallesSolicitudesModificacionProducto($idSolicitudProducto);

        foreach ($tiposModificacion as $item) {
            $modificaciones[] = array(
                'modificaciones' => $item->codigo_modificacion,
                'tiempo_atencion' => $item->tiempo_atencion,
                'id_detalle_solicitud_producto' => $item->id_detalle_solicitud_producto,
                'estado_solicitud_producto' => $estadoSolicitudProducto,
                'ruta_documento_respaldo' => $item->ruta_documento_respaldo
            );
        }

        switch ($estadoSolicitudProducto) {

            case 'inspeccion':
            case 'asignadoInspeccion':
                $modificaciones[] = array(
                    'modificaciones' => 'resultadoRevision',
                    'tiempo_atencion' => '',
                    'id_detalle_solicitud_producto' => '',
                    'estado_solicitud_producto' => '',
                    'ruta_documento_respaldo' => ''
                );
                break;

        }

        $parametros = array(
            'id_solicitud_producto' => $idSolicitudProducto,
            'id_area' => $idArea,
            'id_producto' => $idProducto
        );

        $solicitudesProductoControlador = new SolicitudesProductosControlador();
        $this->pestania = $solicitudesProductoControlador->generarPestaniasPorTipoModificacion($modificaciones, $parametros);

        require APP . 'ModificacionProductoRia/vistas/formularioAtenderSolicitudesProductosVista.php';
    }

    public function resultadoRevisionTecnica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto)
    {
        $idSoliciudProducto = $parametros['id_solicitud_producto'];

        $resultadoRevision = ' 
        <form id="resuladoRevisionTecnica" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia" data-opcion="RevisionSolicitudesProducto/guardarProcesoRevisionTecnica" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
            <input type="hidden" id="id_solicitud_producto" name="id_solicitud_producto" value="' . $idSoliciudProducto . '" />
            <fieldset  id="fResuladoRevisionTecnica">
                <legend>Resultado revisión técnica</legend>
                <div data-linea="1">
                    <label>Resultado: </label>
                    <select id="resultado" name="resultado" class="validacion">
                        <option value="">Seleccione....</option>
                        <option value="Aprobado">Aprobar</option>
                        <option value="Rechazado">Rechazar</option>
                        <option value="subsanacion">Subsanar</option>
                    </select>
                </div>
                <label>Observación: </label>
                <div data-linea="2">
                    <textarea name="observacion" rows="3" maxlength="1000" class="validacion"></textarea>
                </div>
                <label>Cargar informe: </label>
                <div data-linea="3">
                    <input type="hidden" class="rutaArchivo" id="informeRevision" name="informeRevision" value="0"/>
                    <input type="file" class="archivo" id="vInforme" accept="application/pdf" />
                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . 'B)</div>
                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                </div>  
            </fieldset>
            
            <div data-linea="4">
                <button id="guardarResultado" type="submit" class="guardar">Enviar resultado</button>
            </div>
        </form> ';

        return $resultadoRevision;
    }

    public function guardarProcesoRevisionTecnica()
    {
        $idSolicitudProducto = $_POST['id_solicitud_producto'];
        $resultado = $_POST['resultado'];
        $observacion = $_POST['observacion'];
        $rutaResultadoRevision = $_POST['informeRevision'];
        $identificadorRevisor = $this->usuarioActivo();
        $rutaCertificado = '';

        if($resultado === 'Aprobado'){
            $rutaCertificado = $this->lNegocioSolicitudesProductos->guardarDatosProductoOrigen($idSolicitudProducto, $rutaResultadoRevision);
        }

        $datos = [
            'id_solicitud_producto' => $idSolicitudProducto,
            'estado_solicitud_producto' => $resultado,
            'idenitificador_revisor' => $identificadorRevisor,
            'observacion_revisor' => $observacion,
            'ruta_revisor' => $rutaResultadoRevision,
            'ruta_certificado' => $rutaCertificado
        ];

        $this->lNegocioSolicitudesProductos->actualizarDatosRevisionTecnica($datos);

        $arrayRevisionSolicitudes = array(
            'identificador_inspector' => $identificadorRevisor,
            'fecha_asignacion' => 'now()',
            'identificador_asignante' => $identificadorRevisor,
            'tipo_solicitud' => 'modificacionProductoRia',
            'tipo_inspector' => 'Técnico',
            'id_operador_tipo_operacion' => 0,
            'id_historial_operacion' => 0,
            'id_solicitud' => $idSolicitudProducto,
            'estado' => 'Técnico',
            'fecha_inspeccion' => 'now()',
            'observacion' => $observacion,
            'estado_siguiente' => $resultado,
            'orden' => 1,
            'ruta_archivo' => $rutaResultadoRevision
        );

        $this->lNegocioAsignacionInspector->guardar($arrayRevisionSolicitudes);
		
		$this->lNegocioSolicitudesProductos->enviarCorreo($idSolicitudProducto);

        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);

    }
}
