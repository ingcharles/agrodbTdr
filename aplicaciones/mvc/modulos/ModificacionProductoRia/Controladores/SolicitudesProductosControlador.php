<?php
/**
 * Controlador SolicitudesProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudesProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    SolicitudesProductosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Financiero\Modelos\OrdenPagoLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DetalleSolicitudesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\SolicitudesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\SolicitudesProductosModelo;
use Agrodb\ModificacionProductoRia\Modelos\CategoriasToxicologicasLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\PeriodosReingresosLogicaNegocio;

class SolicitudesProductosControlador extends BaseControlador
{

    private $lNegocioSolicitudesProductos = null;
    private $lNegocioTipoProducto = null;
    private $lNegocioSubtipoProducto = null;
    private $lNegocioProducto = null;
    private $lNegocioProductoInocuidad = null;
    private $lNegocioDetalleSolicitudesProducto = null;
    private $modeloSolicitudesProductos = null;
    private $lNegocioCategoriaToxicologica = null;
    private $lNegocioPeriodoReingreso = null;
    private $lNegocioProductos = null;
    private $lNegocioFichaEmpleado = null;
    private $lNegocioOrdenPago = null;
    private $accion = null;
    private $pestania = null;
    private $datosGenerales = null;
    private $rutaFecha = null;
    private $datosRevision = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSolicitudesProductos = new SolicitudesProductosLogicaNegocio();
        $this->modeloSolicitudesProductos = new SolicitudesProductosModelo();

        $this->lNegocioTipoProducto = new TipoProductosLogicaNegocio();
        $this->lNegocioSubtipoProducto = new SubtipoProductosLogicaNegocio();
        $this->lNegocioProducto = new ProductosLogicaNegocio();
        $this->lNegocioProductoInocuidad = new ProductosInocuidadLogicaNegocio();
        $this->lNegocioDetalleSolicitudesProducto = new DetalleSolicitudesProductosLogicaNegocio();
        $this->lNegocioCategoriaToxicologica = new CategoriasToxicologicasLogicaNegocio();
        $this->lNegocioPeriodoReingreso = new PeriodosReingresosLogicaNegocio();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
        $this->lNegocioOrdenPago = new OrdenPagoLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloSolicitudesProductos = $this->lNegocioSolicitudesProductos->obtenerSolicitudesProductos();
        $this->tablaHtmlSolicitudesProductos($modeloSolicitudesProductos);
        require APP . 'ModificacionProductoRia/vistas/listaSolicitudesProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo modificación producto";
        $idetificador = $this->identificador;
        $operador = $this->lNegocioSolicitudesProductos->obtenerDatosOperador($idetificador);
        $this->datosGenerales = $this->datosGeneralesOperador($operador);
        require APP . 'ModificacionProductoRia/vistas/formularioSolicitudesProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -SolicitudesProductos
     */
    public function guardar()
    {
        $estado = 'exito';
        $mensaje = '';

        $idetificador = $this->identificador;
        $operador = $this->lNegocioSolicitudesProductos->obtenerDatosOperador($idetificador);

        $_POST['identificador_operador'] = $operador->getIdentificador();
        $_POST['razon_social'] = $operador->getRazonSocial();
        $_POST['representante_legal'] = $operador->getNombreRepresentante() . ' ' . $operador->getApellidoRepresentante();
        $_POST['direccion'] = $operador->getDireccion();
        $_POST['telefono'] = $operador->getTelefonoUno() === '' ? $operador->getTelefonoDos() : $operador->getTelefonoUno();
        $_POST['correo'] = $operador->getCorreo();
        $_POST['representante_tecnico'] = $operador->getNombreTecnico() . ' ' . $operador->getApellidoTecnico();
        $_POST['provincia_operador'] = $operador->getProvincia();

        $idSolicitudModificacionProducto = $this->lNegocioSolicitudesProductos->guardar($_POST);

        echo json_encode(array(
            "estado" => $estado,
            "mensaje" => $mensaje,
            "contenido" => $idSolicitudModificacionProducto
        ));
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: SolicitudesProductos
     */
    public function editar()
    {
        $this->accion = "Modificación de producto";
        $idSolicitudProducto = $_POST['id'];
        $modificaciones = [];

        $datosSolicitud = $this->lNegocioSolicitudesProductos->buscar($idSolicitudProducto);
        $identificadorOperador = $datosSolicitud->getIdentificadorOperador();
        $idArea = $datosSolicitud->getIdArea();
        $idProducto = $datosSolicitud->getIdProducto();
        $estadoSolicitudProducto = $datosSolicitud->getEstadoSolicitudProducto();
        $numeroRegistro = $datosSolicitud->getNumeroRegistro();

        $operador = $this->lNegocioSolicitudesProductos->obtenerDatosOperador($identificadorOperador);
        $this->datosGenerales = $this->datosGeneralesOperador($operador);

        $datosConsultaProducto = [
            'id_producto' => $idProducto
        ];

        $producto = $this->lNegocioProductos->obtenerDatosProducto($datosConsultaProducto);

        $datosProducto = $producto->toArray();
        $datosProducto[0]['numero_registro'] = $numeroRegistro;
        $this->datosGenerales .= $this->datosGeneralesProducto($datosProducto[0]);

        $datosConsultaFinanciero = [
            'id_solicitud' => $idSolicitudProducto,
            'tipo_solicitud' => 'modificacionProductoRia',
            'estado' => 3
        ];

        if ($estadoSolicitudProducto === 'verificacion') {
            $financiero = $this->lNegocioOrdenPago->buscarLista($datosConsultaFinanciero);
            $datosFinanciero = $financiero->toArray();
            $this->datosGenerales .= $this->datosFinancieroModificacionProducto($datosFinanciero[0]);
        }

        $tiposModificacion = $this->lNegocioDetalleSolicitudesProducto->obtenerDetallesSolicitudesModificacionProducto($idSolicitudProducto);

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

            case 'Creado':
                $modificaciones[] = array(
                    'modificaciones' => 'aceptarTerminos',
                    'tiempo_atencion' => '',
                    'id_detalle_solicitud_producto' => '',
                    'estado_solicitud_producto' => '',
                    'ruta_documento_respaldo' => ''
                );
                break;
                break;
            case 'subsanacion':
                $modificaciones[] = array(
                    'modificaciones' => 'aceptarTerminos',
                    'tiempo_atencion' => '',
                    'id_detalle_solicitud_producto' => '',
                    'estado_solicitud_producto' => '',
                    'ruta_documento_respaldo' => ''
                );

                $fichaEmpleado = $this->lNegocioFichaEmpleado->buscar($datosSolicitud->getIdentificadorRevisor());
                $this->datosRevision = $this->datosResultadoRevisionProducto($datosSolicitud, $fichaEmpleado);

                break;
            case 'Aprobado':
            case 'Rechazado':
                $fichaEmpleado = $this->lNegocioFichaEmpleado->buscar($datosSolicitud->getIdentificadorRevisor());
                $this->datosRevision = $this->datosResultadoRevisionProducto($datosSolicitud, $fichaEmpleado);
                break;

        }

        $parametros = array(
            'id_solicitud_producto' => $idSolicitudProducto,
            'id_area' => $idArea,
            'id_producto' => $idProducto
        );

        $this->generarPestaniasPorTipoModificacion($modificaciones, $parametros);
        require APP . 'ModificacionProductoRia/vistas/formularioEditarSolicitudesProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - SolicitudesProductos
     */
    public function borrar()
    {
        $this->lNegocioSolicitudesProductos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - SolicitudesProductos
     */
    public function tablaHtmlSolicitudesProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $estado = '';
                switch ($fila['estado_solicitud_producto']) {
                    case 'Creado':
                        $estado = 'Creado';
                        break;
                    case 'pago':
                        $estado = 'Asiganción de pago';
                        break;
                    case 'verificacion':
                        $estado = 'Verificación de pago';
                        break;
                    case 'inspeccion':
                        $estado = 'Inspección';
                        break;
                    case 'asignadoInspeccion':
                        $estado = 'Asignado inspección';
                        break;
                    case 'subsanacion':
                        $estado = 'Subsanación';
                        break;
                    case 'Aprobado':
                        $estado = 'Aprobado';
                        break;
                    case 'Rechazado':
                        $estado = 'Rechazado';
                        break;
                }

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_solicitud_producto'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\SolicitudesProductos"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td>' . $fila['numero_solicitud'] . '</td>
                        <td>' . $fila['identificador_operador'] . '</td>
						<td>' . $fila['razon_social'] . '</td>
						<td>' . $fila['nombre_producto'] . '</td>
						<td>' . $fila['nombre_area_tematica'] . '</td>
                        <td>' . $estado . '</td>
                    </tr>'
                );
            }
        }
    }

    public function generarPestaniasPorTipoModificacion($modificaciones, $parametros)
    {
        $idArea = $parametros['id_area'];

        foreach ($modificaciones as $modificacionValor) {

            $modificacion = $modificacionValor['modificaciones'];
            $tiempoAtencion = $modificacionValor['tiempo_atencion'];
            $idDetalleSolicitudProducto = $modificacionValor['id_detalle_solicitud_producto'];
            $estadoSolicitudProducto = $modificacionValor['estado_solicitud_producto'];
            $parametros['tipo_modificacion'] = $modificacion;
            $parametros['ruta_documento_respaldo'] = $modificacionValor['ruta_documento_respaldo'];

            $this->pestania .= '
             <div class="pestania">
             <input type="hidden" name="id_detalle_solicitud_producto' . $modificacion . '" id="id_detalle_solicitud_producto' . $modificacion . '" value="' . $idDetalleSolicitudProducto . '">';

            switch ($idArea) {
                case 'IAP':
                    switch ($modificacion) {
                        case 'modificarCategoriaToxicologica':
                            $categoriasToxicologicasControlador = new CategoriasToxicologicasControlador();
                            $this->pestania .= $categoriasToxicologicasControlador->modificarCategoriaToxicologica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarPeriodoReingreso':
                            $periodosReingresosControlador = new PeriodosReingresosControlador();
                            $this->pestania .= $periodosReingresosControlador->modificarPeriodoReingreso($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarVidaUtil':
                            $vidasUtilesControlador = new VidasUtilesControlador();
                            $this->pestania .= $vidasUtilesControlador->modificarVidaUtil($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarEstadoRegistro':
                            $estadosRegistrosControlador = new EstadosRegistrosControlador();
                            $this->pestania .= $estadosRegistrosControlador->modificarEstadoRegistro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarAdicionPresentacionPlaguicida':
                            $adicionesPresentacionesPlaguicidasControlador = new AdicionesPresentacionesPlaguicidasControlador();
                            $this->pestania .= $adicionesPresentacionesPlaguicidasControlador->modificarAdicionPresentacionPlaguicida($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarTitularidadProducto':
                            $titularidadProductoControlador = new TitularesProductosControlador();
                            $this->pestania .= $titularidadProductoControlador->modificarTitularidadProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarFabricanteFormulador':
                            $fabricanteFormuladorControlador = new FabricantesFormuladoresControlador();
                            $this->pestania .= $fabricanteFormuladorControlador->modificarFabricanteFormuladorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarUso':
                            $usosControlador = new UsosControlador();
                            $this->pestania .= $usosControlador->modificarUsoProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarManufacturador':
                            $manufacturadorControlador = new ManufacturadoresControlador();
                            $this->pestania .= $manufacturadorControlador->modificarManufacturadorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'resultadoRevision':
                            $revisionSolicitudesControlador = new RevisionSolicitudesProductoControlador();
                            $this->pestania .= $revisionSolicitudesControlador->resultadoRevisionTecnica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        default:
                            $this->pestania .= $this->aceptarTerminos($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                    }
                    break;
                case 'IAV':
                    switch ($modificacion) {
                        case 'modificarCategoriaToxicologica':
                            $categoriasToxicologicasControlador = new CategoriasToxicologicasControlador();
                            $this->pestania .= $categoriasToxicologicasControlador->modificarCategoriaToxicologica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarViaAdmimistracionDosis':
                            $viasAdministracionesDosisControlador = new ViasAdministracionesDosisControlador();
                            $this->pestania .= $viasAdministracionesDosisControlador->modificarViaAdministracionDosis($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarPeriodoRetiro':
                            $periodosRetirosControlador = new PeriodosRetirosControlador();
                            $this->pestania .= $periodosRetirosControlador->modificarPeriodoRetiro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarVidaUtil':
                            $vidasUtilesControlador = new VidasUtilesControlador();
                            $this->pestania .= $vidasUtilesControlador->modificarVidaUtil($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarNombreComercial':
                            $nombresComercialesControlador = new NombresComercialesControlador();
                            $this->pestania .= $nombresComercialesControlador->modificarNombreComercial($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarEstadoRegistro':
                            $estadosRegistrosControlador = new EstadosRegistrosControlador();
                            $this->pestania .= $estadosRegistrosControlador->modificarEstadoRegistro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarAdicionPresentacion':
                            $adicionesPresentacionesControlador = new AdicionesPresentacionesControlador();
                            $this->pestania .= $adicionesPresentacionesControlador->modificarAdicionPresentacion($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarTitularidadProducto':
                            $titularidadProductoControlador = new TitularesProductosControlador();
                            $this->pestania .= $titularidadProductoControlador->modificarTitularidadProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarFabricanteFormulador':
                            $fabricanteFormuladorControlador = new FabricantesFormuladoresControlador();
                            $this->pestania .= $fabricanteFormuladorControlador->modificarFabricanteFormuladorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarUso':
                            $usosControlador = new UsosControlador();
                            $this->pestania .= $usosControlador->modificarUsoProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarComposicion':
                            $usosControlador = new ComposicionesControlador();
                            $this->pestania .= $usosControlador->modificarComposicionProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarEtiqueta':
                            $this->pestania .= $this->modificarEtiquetaProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarDeclaracionVenta':
                            $denominacionVenta = new DenominacionesVentasControlador();
                            $this->pestania .= $denominacionVenta->modificarDenominacionVentaProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'resultadoRevision':
                            $revisionSolicitudesControlador = new RevisionSolicitudesProductoControlador();
                            $this->pestania .= $revisionSolicitudesControlador->resultadoRevisionTecnica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        default:
                            $this->pestania .= $this->aceptarTerminos($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                    }
                    break;
                case 'IAF':
                    switch ($modificacion) {
                        case 'modificarEstadoRegistro':
                            $estadosRegistrosControlador = new EstadosRegistrosControlador();
                            $this->pestania .= $estadosRegistrosControlador->modificarEstadoRegistro($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarViaAdmimistracionDosis':
                            $viasAdministracionesDosisControlador = new ViasAdministracionesDosisControlador();
                            $this->pestania .= $viasAdministracionesDosisControlador->modificarViaAdministracionDosis($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarAdicionPresentacion':
                            $adicionesPresentacionesControlador = new AdicionesPresentacionesControlador();
                            $this->pestania .= $adicionesPresentacionesControlador->modificarAdicionPresentacion($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarTitularidadProducto':
                            $titularidadProductoControlador = new TitularesProductosControlador();
                            $this->pestania .= $titularidadProductoControlador->modificarTitularidadProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarFabricanteFormulador':
                            $fabricanteFormuladorControlador = new FabricantesFormuladoresControlador();
                            $this->pestania .= $fabricanteFormuladorControlador->modificarFabricanteFormuladorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarUso':
                            $usosControlador = new UsosControlador();
                            $this->pestania .= $usosControlador->modificarUsoProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'modificarEtiqueta':
                            $this->pestania .= $this->modificarEtiquetaProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        case 'resultadoRevision':
                            $revisionSolicitudesControlador = new RevisionSolicitudesProductoControlador();
                            $this->pestania .= $revisionSolicitudesControlador->resultadoRevisionTecnica($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                        default:
                            $this->pestania .= $this->aceptarTerminos($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto);
                            break;
                    }
                    break;
            }
            $this->pestania .= '</div>';
        }

        return $this->pestania;
    }

    public function aceptarTerminos($parametros, $tiempoAtencion, $idDetalleSolicitudProducto)
    {
        $idSoliciudProducto = $parametros['id_solicitud_producto'];

        $aceptarTerminos = ' <form id="finalizarSolicitud" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia" data-opcion="SolicitudesProductos/guardarFinalizarSolicitud" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
        <input type="hidden" id="id_solicitud_producto" name="id_solicitud_producto" value="' . $idSoliciudProducto . '" />
        <fieldset  id="fAceptarTerminos">
        <legend>Finalizar solicitud</legend>
            <div data-linea="1">
                <label>Observación: </label>
                <input type="text" name="">
            </div>
            <hr/>
            <div data-linea="2">
                <label><input type="checkbox" id="terminos" name="terminos" value="Si"> ACEPTO TÉRMINOS Y CONDICIONES GENERALES DE USO</label>
            </div>
            <div data-linea="3">
                <label><input type="checkbox" id="descuento" name="descuento" value="Si"> SOY PERSONA NATURAL DE TERCERA EDAD O ARTESANO</label>
            </div>           
        </fieldset>
        <div data-linea="4">
            <button type="submit" class="guardar">Enviar solicitud</button>
        </div>
        </form> ';
        return $aceptarTerminos;
    }

    /* Metodo para finalizar el envío de solcitud */
    public function guardarFinalizarSolicitud()
    {
        $estado = '';
        $mensaje = '';

        if (!isset($_POST['terminos'])) {

            $estado = 'error';
            $mensaje = 'Por favor acepte los términos y condiciones para poder enviar la solicitud.';

        } else {

            $idSolicitudModificacionProducto = $_POST['id_solicitud_producto'];

            $verificarRegistro = $this->lNegocioSolicitudesProductos->verificarRegistrosSoliciud($idSolicitudModificacionProducto);

            if ($verificarRegistro->current()->f_verificar_registros) {

                $estado = 'exito';
                $mensaje = 'La solicitud fue registrada con éxito';

                $qEstadoActualSolicitudProducto = $this->lNegocioSolicitudesProductos->buscar($idSolicitudModificacionProducto);
                $estadoActualSolicitudProducto = $qEstadoActualSolicitudProducto->getEstadoSolicitudProducto();

                switch ($estadoActualSolicitudProducto) {

                    case 'Creado':
                        $estadoSolicitudProducto = 'pago';
                        break;
                    case 'subsanacion':
                        $estadoSolicitudProducto = 'asignadoInspeccion';
                        break;

                }

                $_POST['estado_solicitud_producto'] = $estadoSolicitudProducto;

                $this->lNegocioSolicitudesProductos->guardarFinalizarSolitud($_POST);

            } else {

                $estado = 'error';
                $mensaje = 'Por favor llene todos los regitros de la solicitud.';

            }

        }

        echo json_encode(array(
            "estado" => $estado,
            "mensaje" => $mensaje
        ));
    }

    public function obtenerTipoProductoPorIdArea()
    {
        $idArea = $_POST['id_area'];

        $comboTipoProducto = '<option value="">Seleccionar....</option>';
        $comboTipoModificacion = '<option value="">Seleccionar....</option>';

        $datos = [
            'id_area' => $idArea
            , 'estado' => 1
        ];
        $tipoProducto = $this->lNegocioTipoProducto->buscarLista($datos, 'nombre');

        foreach ($tipoProducto as $item) {
            $comboTipoProducto .= '<option value="' . $item->id_tipo_producto . '">' . $item->nombre . '</option>';
        }

        $tipoModificacion = $this->lNegocioSolicitudesProductos->obtenerTipoModificacionProducto($idArea);

        foreach ($tipoModificacion as $item) {
            $comboTipoModificacion .= '<option value="' . $item->id_tipo_modificacion_producto . '" data-tiempoatencion="' . $item->dias_atencion . '">' . $item->tipo_modificacion . '</option>';
        }

        echo json_encode(array(
            'estado' => 'EXITO',
            'comboTipoProducto' => $comboTipoProducto,
            'comboTipoModificacion' => $comboTipoModificacion
        ));
    }

    public function obtenerSubtipoProductoPorIdTipoProducto()
    {
        $idTipoProducto = $_POST['id_tipo_producto'];

        $comboSubtipoProducto = '<option value="">Seleccionar....</option>';
        $datos = [
            'id_tipo_producto' => $idTipoProducto
            , 'estado' => 1
        ];
        $subtipoProducto = $this->lNegocioSubtipoProducto->buscarLista($datos, 'nombre');

        foreach ($subtipoProducto as $item) {
            $comboSubtipoProducto .= '<option value="' . $item->id_subtipo_producto . '">' . $item->nombre . '</option>';
        }

        echo json_encode(array(
            'estado' => 'EXITO',
            'comboSubtipoProducto' => $comboSubtipoProducto
        ));
    }

    public function obtenerProductoPorIdSubtipoProducto()
    {
        $idSubtipoProducto = $_POST['id_subtipo_producto'];

        $comboProducto = '<option value="">Seleccionar....</option>';
        $datos = [
            'id_subtipo_producto' => $idSubtipoProducto
            , 'estado' => 1
        ];
        $producto = $this->lNegocioProducto->buscarLista($datos, 'nombre_comun');

        foreach ($producto as $item) {
            $comboProducto .= '<option value="' . $item->id_producto . '">' . $item->nombre_comun . '</option>';
        }

        echo json_encode(array(
            'estado' => 'EXITO',
            'comboProducto' => $comboProducto
        ));
    }

    public function obtenerNumeroRegistroProducto()
    {
        $idProducto = $_POST['id_producto'];

        $numeroRegistro = $this->lNegocioProductoInocuidad->buscar($idProducto);

        echo json_encode(array(
            'estado' => 'EXITO',
            'numeroRegistro' => $numeroRegistro->getNumeroRegistro()
        ));
    }

    public function modificarEtiquetaProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $tipoModificacion = $parametros['tipo_modificacion'];
        $idSolicitudProducto = $parametros['id_solicitud_producto'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaEtiqueta = '';
        $ingresoDatos = '';
        $banderaAcciones = false;

        switch ($estadoSoliciudProducto) {

            case 'Creado':

                $banderaAcciones = true;
                $ingresoDatos = '
                                <div data-linea="1">
                                    <label>Etiquetas:</label>
                                    <input type="hidden" name="id_solicitud_producto" id="id_solicitud_producto" value="' . $idSolicitudProducto . '" data-tiempoatencion="' . $tiempoAtencion . ' días"/>
                                </div>
                                <div data-linea="2">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . 'RutaEtiqueta" name="ruta_etiqueta" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . 'RutaEtiqueta" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="3">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="4">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="5">
                        			<button type="button" class="mas" id="agregarEtiqueta" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;
        }

        $datosEtiqueta = $this->lNegocioSolicitudesProductos->buscar($idSolicitudProducto);
        $rutaEtiqueta = $datosEtiqueta->getRutaEtiquetaProducto();

        if ($rutaEtiqueta) {
            $filaEtiqueta .=
                '<tr id="fila' . $idSolicitudProducto . '">
                    <td><a href="' . $rutaEtiqueta . '" target="_blank">Etiqueta</a></td>';
            if ($banderaAcciones) {
                $filaEtiqueta .=
                    '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarEtiqueta(' . $idSolicitudProducto . '); return false;"/>
                    </td>';
            }
            $filaEtiqueta .= '</tr>';
        }

        $modificarEtiqueta = '';

        if ($rutaDocumentoRespaldo) {
            $modificarEtiqueta .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>' : '<a href=' . $rutaDocumentoRespaldo . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarEtiqueta .= '
            <fieldset  id="fEtiquetaProducto">
                <legend>Etiqueta</legend>
                ' . $ingresoDatos . '
                <table id="tEtiquetaProducto" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Etiqueta</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>' . $filaEtiqueta . '</tbody>
                </table>
            </fieldset>';

        return $modificarEtiqueta;
    }

    /**
     * Método para listar titularidad de producto agregada
     */
    public function generarFilaEtiquetaProducto($idSolicitudProducto, $datosEtiqueta, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $datosEtiqueta['id_solicitud_producto'] . '">
                            <td><a href="' . $datosEtiqueta['ruta_etiqueta_producto'] . '" target="_blank">Etiqueta</a></td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarEtiqueta(' . $idSolicitudProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    public function eliminarEtiqueta()
    {
        $_POST['ruta_etiqueta_producto'] = null;
        $_POST['id_tipo_modificacion_producto'] = [];

        $this->lNegocioSolicitudesProductos->guardar($_POST);

    }

}
