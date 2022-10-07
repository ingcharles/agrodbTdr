<?php
/**
 * Controlador DetalleSolicitudesProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  DetalleSolicitudesProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    DetalleSolicitudesProductosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\ModificacionProductoRia\Modelos\ComposicionesLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DenominacionesVentasLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DetalleSolicitudesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DetalleSolicitudesProductosModelo;
use Agrodb\ModificacionProductoRia\Modelos\CategoriasToxicologicasLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoModificacionProductoLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\FabricantesFormuladoresLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ManufacturadoresLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\PeriodosReingresosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\SolicitudesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\TitularesProductosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\UsosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\VidasUtilesLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\EstadosRegistrosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ViasAdministracionesDosisLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\PeriodosRetirosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\NombresComercialesLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\AdicionesPresentacionesPlaguicidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\PresentacionesPlaguicidasLogicaNegocio;


class DetalleSolicitudesProductosControlador extends BaseControlador
{

    private $lNegocioDetalleSolicitudesProductos = null;
    private $modeloDetalleSolicitudesProductos = null;
    private $lNegocioSolicitudesProducto = null;
    private $lNegocioTipoModificacionProducto = null;
    private $lNegocioCategoriasToxicologicas = null;
    private $lNegocioPeriodoReingreso = null;
    private $lNegocioTitularesProducto = null;
    private $lNegocioFabricantesFormuladores = null;
    private $lNegocioManufacturador = null;
    private $lNegocioUsos = null;
    private $lNegocioComposiciones = null;
    private $lNegocioDenominacionesVentas = null;
	private $lNegocioVidasUtiles = null;
    private $lNegocioEstadosRegistros = null;
    private $lNegocioViasAdministracionesDosis = null;
    private $lNegocioPeriodosRetiros = null;
    private $lNegocioNombresComerciales = null;
    private $lNegocioCodigosInocuidad = null;
    private $lNegocioAdicionesPresentaciones = null;
    private $lNegocioAdicionesPresentacionesPlaguicidas = null;
    private $lNegocioPresentacionesPlaguicidas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDetalleSolicitudesProductos = new DetalleSolicitudesProductosLogicaNegocio();
        $this->lNegocioSolicitudesProducto = new SolicitudesProductosLogicaNegocio();
        $this->modeloDetalleSolicitudesProductos = new DetalleSolicitudesProductosModelo();
        $this->lNegocioTipoModificacionProducto = new TipoModificacionProductoLogicaNegocio();
        $this->lNegocioCategoriasToxicologicas = new CategoriasToxicologicasLogicaNegocio();
        $this->lNegocioPeriodoReingreso = new PeriodosReingresosLogicaNegocio();
        $this->lNegocioTitularesProducto = new TitularesProductosLogicaNegocio();
        $this->lNegocioFabricantesFormuladores = new FabricantesFormuladoresLogicaNegocio();
        $this->lNegocioUsos = new UsosLogicaNegocio();
        $this->lNegocioManufacturador = new ManufacturadoresLogicaNegocio();
        $this->lNegocioComposiciones = new ComposicionesLogicaNegocio();
        $this->lNegocioDenominacionesVentas = new DenominacionesVentasLogicaNegocio();
		$this->lNegocioVidasUtiles = new VidasUtilesLogicaNegocio();
        $this->lNegocioEstadosRegistros = new EstadosRegistrosLogicaNegocio();
        $this->lNegocioViasAdministracionesDosis = new ViasAdministracionesDosisLogicaNegocio();
        $this->lNegocioPeriodosRetiros = new PeriodosRetirosLogicaNegocio();
        $this->lNegocioNombresComerciales = new NombresComercialesLogicaNegocio();
        $this->lNegocioCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
        $this->lNegocioAdicionesPresentaciones = new AdicionesPresentacionesLogicaNegocio();
        $this->lNegocioAdicionesPresentacionesPlaguicidas = new AdicionesPresentacionesPlaguicidasLogicaNegocio();
        $this->lNegocioPresentacionesPlaguicidas = new PresentacionesPlaguicidasLogicaNegocio();

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
        $modeloDetalleSolicitudesProductos = $this->lNegocioDetalleSolicitudesProductos->buscarDetalleSolicitudesProductos();
        $this->tablaHtmlDetalleSolicitudesProductos($modeloDetalleSolicitudesProductos);
        require APP . 'ModificacionProductoRia/vistas/listaDetalleSolicitudesProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DetalleSolicitudesProductos";
        require APP . 'ModificacionProductoRia/vistas/formularioDetalleSolicitudesProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DetalleSolicitudesProductos
     */
    public function guardar()
    {
        $this->lNegocioDetalleSolicitudesProductos->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleSolicitudesProductos
     */
    public function editar()
    {
        $this->accion = "Editar DetalleSolicitudesProductos";
        $this->modeloDetalleSolicitudesProductos = $this->lNegocioDetalleSolicitudesProductos->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioDetalleSolicitudesProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DetalleSolicitudesProductos
     */
    public function borrar()
    {
        $this->lNegocioDetalleSolicitudesProductos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DetalleSolicitudesProductos
     */
    public function tablaHtmlDetalleSolicitudesProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_detalle_solicitud_producto'] . '"
                      class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\detallesolicitudesproductos"
                      data-opcion="editar" ondragstart="drag(event)" draggable="true"
                      data-destino="detalleItem">
                      <td>' . ++$contador . '</td>
                      <td style="white - space:nowrap; "><b>' . $fila['id_detalle_solicitud_producto'] . '</b></td>
                        <td>' . $fila['id_solicitud_producto'] . '</td>
                        <td>' . $fila['tipo_modificacion'] . '</td>
                        <td>' . $fila['tiempo_atencion'] . '</td>
                      </tr>'
                );
            }
        }
    }

    /**
     * Método para registrar en la base de datos el detall de a solicitud de producto
     */
    public function guardarDetalleSolicitud()
    {
        $idDetalleSolicitudProducto = $_POST['id_detalle_solicitud_producto'];
        $tiempoAtencion = $_POST['tiempo_atencion'];

        $validacion = "";
        $resultado = "Datos ingresados con exito";

        $proceso = $this->lNegocioDetalleSolicitudesProductos->guardar($_POST);

        if ($proceso) {

            $tipoModificacion = $_POST['codigo_tipo_modificacion'];

            switch ($tipoModificacion) {

                case "modificarCategoriaToxicologica":

                    $idCategoriaToxicologica = $_POST['id_categoria_toxicologica'];
                    $categoriaToxicologica = $_POST['categoria_toxicologica'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );

                    $verificarCategoriaToxicologica = $this->lNegocioCategoriasToxicologicas->buscarLista($arrayParametros);

                    if (count($verificarCategoriaToxicologica) == 0) {
                        $datosCategoriaToxicologica = array(
                            'id_tabla_origen' => $idCategoriaToxicologica,
                            'categoria_toxicologica' => $categoriaToxicologica,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );

                        $idCategoriaToxicologica = $this->lNegocioCategoriasToxicologicas->guardar($datosCategoriaToxicologica);

						$categoriasToxicologicasControlador = new CategoriasToxicologicasControlador();

                        $filaCategoriaToxicologica = $categoriasToxicologicasControlador->generarFilaCategoriaToxicologica($idCategoriaToxicologica, $datosCategoriaToxicologica, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaCategoriaToxicologica' => $filaCategoriaToxicologica
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar unacategoría toxocológica.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }

                    break;

                case "modificarPeriodoReingreso":

                    $periodoReingreso = $_POST['periodo_reingreso'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );

                    $verificarPeriodoReingreso = $this->lNegocioPeriodoReingreso->buscarLista($arrayParametros);

                    if (count($verificarPeriodoReingreso) == 0) {
                        $datosPeriodoReingreso = array(
                            'periodo_reingreso' => $periodoReingreso,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );

                        $idPeriodoReingreso = $this->lNegocioPeriodoReingreso->guardar($datosPeriodoReingreso);

                        $periodosReingresoControlador = new PeriodosReingresosControlador();
                        
                        $filaPeriodoReingreso = $periodosReingresoControlador->generarFilaPeriodoReingreso($idPeriodoReingreso, $datosPeriodoReingreso, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaPeriodoReingreso' => $filaPeriodoReingreso
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar un periódo de reingreso.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }

                    break;
					
				case "modificarVidaUtil":
                    
                    $vidaUtil = $_POST['vida_util'];
                    
                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );
                    
                    $verificarVidaUtil = $this->lNegocioVidasUtiles->buscarLista($arrayParametros);
                    
                    if (count($verificarVidaUtil) == 0) {
                        $datosVidaUtil = array(
                            'estabilidad' => $vidaUtil,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );
                        
                        $idVidaUtil = $this->lNegocioVidasUtiles->guardar($datosVidaUtil);
                        
                        $vidasUtilesControlador = new VidasUtilesControlador();
                        
                        $filaVidaUtil = $vidasUtilesControlador->generarFilaVidaUtil($idVidaUtil, $datosVidaUtil, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaVidaUtil' => $filaVidaUtil
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar una vida útil.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarEstadoRegistro":
                    
                    $estado = $_POST['estado'];
                    $estadoValor = $_POST['estado_valor'];
                    $validacionCancelaRegistro = $_POST['validacion_cancela_registro'];
                    
                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );
                    
                    $verificarEstadoRegistro = $this->lNegocioEstadosRegistros->buscarLista($arrayParametros);
                    
                    if($validacionCancelaRegistro == 'true'){
                    
                        if (count($verificarEstadoRegistro) == 0) {
                            $datosEstadoRegistro = array(
                                'estado' => $estado,
                                'estado_valor' => $estadoValor,
                                'validacion_cancela_registro' => $validacionCancelaRegistro,
                                'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                            );
                            
                            $idEstadoRegistro = $this->lNegocioEstadosRegistros->guardar($datosEstadoRegistro);
                            
                            $estadosRegistrosControlador = new EstadosRegistrosControlador();
                            
                            $filaEstadoRegistro = $estadosRegistrosControlador->generarFilaEstadoRegistro($idEstadoRegistro, $datosEstadoRegistro, $tiempoAtencion);
                            
                            echo json_encode(array(
                                'validacion' => $validacion,
                                'resultado' => $resultado,
                                'filaEstadoRegistro' => $filaEstadoRegistro
                            ));
                        } else {
                            $validacion = "Fallo";
                            $resultado = "Solo puede agregar un estado de registro.";
                            echo json_encode(array(
                                'validacion' => $validacion,
                                'resultado' => $resultado
                            ));
                        }
                    
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Por favor confirme el cambio de estado de producto";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarViaAdmimistracionDosis":
                    
                    $dosis = $_POST['dosis'];
                    $unidadDdosis = $_POST['unidad_dosis'];
                    
                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );
                    
                    $verificarViaAdministracionDosis= $this->lNegocioViasAdministracionesDosis->buscarLista($arrayParametros);
                    
                    if (count($verificarViaAdministracionDosis) == 0) {
                        $datosViaAdministracionDosis = array(
                            'dosis' => $dosis,
                            'unidad_dosis' => $unidadDdosis,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );
                        
                        $idViaAdministracionDosis = $this->lNegocioViasAdministracionesDosis->guardar($datosViaAdministracionDosis);
                        
                        $viasAdministracionesDosisControlador = new ViasAdministracionesDosisControlador();
                        
                        $filaViaAdministracionDosis = $viasAdministracionesDosisControlador->generarFilaViaAdministracionDosis($idViaAdministracionDosis, $datosViaAdministracionDosis, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaViaAdministracionDosis' => $filaViaAdministracionDosis
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar una dosis.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarPeriodoRetiro":
                    
                    $periodoRetiro = $_POST['periodo_retiro'];
                    
                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );
                    
                    $verificarPeriodoRetiro = $this->lNegocioPeriodosRetiros->buscarLista($arrayParametros);
                    
                    if (count($verificarPeriodoRetiro) == 0) {
                        $datosPeriodoRetiro = array(
                            'periodo_retiro' => $periodoRetiro,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );
                        
                        $idPeriodoRetiro = $this->lNegocioPeriodosRetiros->guardar($datosPeriodoRetiro);
                        
                        $periodosRetirosControlador = new PeriodosRetirosControlador();
                        
                        $filaPeriodoRetiro = $periodosRetirosControlador->generarFilaPeriodoRetiro($idPeriodoRetiro, $datosPeriodoRetiro, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaPeriodoRetiro' => $filaPeriodoRetiro
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar un periódo de retiro.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarNombreComercial":
                    
                    $nombreComercial = $_POST['nombre_comercial'];
                    
                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );
                    
                    $verificarNombreComercial = $this->lNegocioNombresComerciales->buscarLista($arrayParametros);
                    
                    if (count($verificarNombreComercial) == 0) {
                        $datosNombreComercial = array(
                            'nombre_comercial' => $nombreComercial,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );
                        
                        $idNombreComercial = $this->lNegocioNombresComerciales->guardar($datosNombreComercial);
                        
                        $nombresComercialesControlador = new NombresComercialesControlador();
                        
                        $filaNombreComercial = $nombresComercialesControlador->generarFilaNombreComercial($idNombreComercial, $datosNombreComercial, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaNombreComercial' => $filaNombreComercial
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar un nombre comercial.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarAdicionPresentacion":
                    
                    $idProducto = $_POST['id_producto'];
                    $presentacion = $_POST['presentacion'];
                    $unidadMedida = $_POST['unidad_medida'];
                    
                    $arrayVerificarAdicionPresentacion = array('id_producto' => $idProducto
                                                                , 'presentacion' => $presentacion
                                                                , 'unidad_medida' => $unidadMedida
                                                                );
                    
                    $verificarAdicionPresentacion = $this->lNegocioCodigosInocuidad->verificarAdicionPresentacion($arrayVerificarAdicionPresentacion);
                    
                    if (count($verificarAdicionPresentacion) == 0) {
                        
                        $qSubcodigo = $this->lNegocioCodigosInocuidad->obtenerCodigoInocuidad($idProducto);
                        $subcodigo = str_pad($qSubcodigo->current()->codigo, 4, "0", STR_PAD_LEFT);
                                                
                        $datosAdicionPresentacion = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                            'subcodigo' => $subcodigo,
                            'presentacion' => $presentacion,
                            'unidad_medida' => $unidadMedida
                        );
                        
                        $idAdicionPresentacion = $this->lNegocioAdicionesPresentaciones->guardar($datosAdicionPresentacion);
                        
                        $adicionesPresentacionesControlador = new AdicionesPresentacionesControlador();
                        
                        $filaAdicionPresentacion = $adicionesPresentacionesControlador->generarFilaAdicionPresentacion($idAdicionPresentacion, $datosAdicionPresentacion, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaAdicionPresentacion' => $filaAdicionPresentacion
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "La presentación ya ha sido ingresada.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    
                    break;
                    
                case "modificarAdicionPresentacionPlaguicida":

                    $idProducto = $_POST['id_producto'];
                    $idPartidaArancelaria = $_POST['id_partida_arancelaria'];
                    $partidaArancelaria = $_POST['partida_arancelaria'];
                    $codigoProducto = $_POST['codigo_producto'];
                    $idCodigoComplementarioSuplementario = $_POST['id_codigo_complementario_suplementario'];
                    $codigoComplementario = $_POST['codigo_complementario'];
                    $codigoSuplementario = $_POST['codigo_suplementario'];
                    $presentacion = $_POST['presentacion'];
                    $idUnidadMedida = $_POST['id_unidad_medida'];
                    $unidadMedida = $_POST['unidad_medida'];
                   
                    $arrayVerificarAdicionPresentacionPlaguicida = array('id_codigo_complementario_suplementario' => $idCodigoComplementarioSuplementario
                        , 'presentacion' => $presentacion
                        , 'id_unidad_medida' => $idUnidadMedida
                    );
                    
                    $verificarAdicionPresentacionPlaguicida = $this->lNegocioPresentacionesPlaguicidas->verificarAdicionPresentacionPlaguicida($arrayVerificarAdicionPresentacionPlaguicida);
                    
                    if (count($verificarAdicionPresentacionPlaguicida) == 0) {
                        
                        $qSubcodigo = $this->lNegocioPresentacionesPlaguicidas->obtenerCodigoPresentacionPlaguicida($idProducto, $idPartidaArancelaria, $idCodigoComplementarioSuplementario);
                        $subcodigo = str_pad($qSubcodigo->current()->codigo, 4, "0", STR_PAD_LEFT);
                        
                        $datosAdicionPresentacionPlaguicida = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                            , 'id_partida_arancelaria' => $idPartidaArancelaria
                            , 'partida_arancelaria' => $partidaArancelaria
                            , 'codigo_producto' => $codigoProducto
                            , 'id_codigo_comp_supl' => $idCodigoComplementarioSuplementario
                            , 'codigo_complementario' => $codigoComplementario
                            , 'codigo_suplementario' => $codigoSuplementario
                            , 'subcodigo' => $subcodigo
                            , 'presentacion' => $presentacion
                            , 'id_unidad_medida' => $idUnidadMedida
                            , 'unidad_medida' => $unidadMedida
                        );
                        
                        $idAdicionPresentacionPlaguicida = $this->lNegocioAdicionesPresentacionesPlaguicidas->guardar($datosAdicionPresentacionPlaguicida);
                        
                        $adicionesPresentacionesPlaguicidasControlador = new AdicionesPresentacionesPlaguicidasControlador();
                        
                        $filaAdicionPresentacionPlaguicida = $adicionesPresentacionesPlaguicidasControlador->generarFilaAdicionPresentacion($idAdicionPresentacionPlaguicida, $datosAdicionPresentacionPlaguicida, $tiempoAtencion);
                        
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaAdicionPresentacionPlaguicida' => $filaAdicionPresentacionPlaguicida
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "La presentación ya ha sido ingresada.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
				
                case 'modificarTitularidadProducto':

                    $identificadorOperador = $_POST['identificador_operador'];
                    $razonSocial = $_POST['razon_social'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );

                    $verificarTitularidadProducto = $this->lNegocioTitularesProducto->buscarLista($arrayParametros);

                    if (count($verificarTitularidadProducto) == 0) {
                        $datosTitularidadProducto = array(
                            'identificador_operador' => $identificadorOperador,
                            'razon_social' => $razonSocial,
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                        );

                        $idTitularidadProducto = $this->lNegocioTitularesProducto->guardar($datosTitularidadProducto);

                        $titularidadProductoControlador = new TitularesProductosControlador();
                        $filaTitularidadProducto = $titularidadProductoControlador->generarFilaTitularidadProducto($idTitularidadProducto, $datosTitularidadProducto, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaTitularidadProducto' => $filaTitularidadProducto
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Solo puede agregar un registro de titularidad de producto.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
                case 'modificarFabricanteFormulador':

                    $tipo = $_POST['tipo'];
                    $nombre = $_POST['nombre'];
                    $idPaisOrigen = $_POST['id_pais_origen'];
                    $nombrePaisOrigen = $_POST['nombre_pais_origen'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                        'tipo' => $tipo,
                        'nombre' => $nombre,
                        'id_pais_origen' => $idPaisOrigen,
                    );

                    $verificarFabricanteFormuladorProducto = $this->lNegocioFabricantesFormuladores->buscarLista($arrayParametros);

                    if (count($verificarFabricanteFormuladorProducto) === 0) {
                        $datosFabricantesFormuladoresProducto = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                            'tipo' => $tipo,
                            'nombre' => $nombre,
                            'id_pais_origen' => $idPaisOrigen,
                            'nombre_pais_origen' => $nombrePaisOrigen
                        );

                        $idFabricanteFormuladorProducto = $this->lNegocioFabricantesFormuladores->guardar($datosFabricantesFormuladoresProducto);

                        $fabricantesFormuladoresControlador = new FabricantesFormuladoresControlador();
                        $filaFabricanteFormuladorProducto = $fabricantesFormuladoresControlador->generarFilaFabricanteFormuladorProducto($idFabricanteFormuladorProducto, $datosFabricantesFormuladoresProducto, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaFabricanteFormuladorProducto' => $filaFabricanteFormuladorProducto
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "El fabricante/formulador ya ha sido ingresado.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
                case 'modificarUso':

                    $idArea = $_POST['id_area'];

                    switch ($idArea) {
                        case 'IAP':
                            $idCultivo = $_POST['id_cultivo'];
                            $nombreCultivo = $_POST['nombre_cultivo'];
                            $nombreCientifico_cultivo = $_POST['nombre_cientifico_cultivo'];
                            $idPlaga = $_POST['id_plaga'];
                            $nombrePlaga = $_POST['nombre_plaga'];
                            $nombreCientificoPlaga = $_POST['nombre_cientifico_plaga'];
                            $dosis = $_POST['dosis'];
                            $unidadDosis = $_POST['unidad_dosis'];
                            $periodoCarencia = $_POST['periodo_carencia'];
                            $gastoAgua = $_POST['gasto_agua'];
                            $unidadGastoAgua = $_POST['unidad_gasto_agua'];

                            $arrayParametros = array(
                                'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                'id_cultivo' => $idCultivo,
                                'id_plaga' => $idPlaga
                            );

                            $verificarUsoProducto = $this->lNegocioUsos->buscarLista($arrayParametros);

                            if (count($verificarUsoProducto) === 0) {
                                $datosUsoProducto = [
                                    'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                    'id_cultivo' => $idCultivo,
                                    'nombre_cultivo' => $nombreCultivo,
                                    'nombre_cientifico_cultivo' => $nombreCientifico_cultivo,
                                    'id_plaga' => $idPlaga,
                                    'nombre_plaga' => $nombrePlaga,
                                    'nombre_cientifico_plaga' => $nombreCientificoPlaga,
                                    'dosis' => $dosis,
                                    'unidad_dosis' => $unidadDosis,
                                    'periodo_carencia' => $periodoCarencia,
                                    'gasto_agua' => $gastoAgua,
                                    'unidad_gasto_agua' => $unidadGastoAgua
                                ];

                                $idUsoProducto = $this->lNegocioUsos->guardar($datosUsoProducto);

                                $usosControlador = new UsosControlador();
                                $filaUsosProducto = $usosControlador->generarFilaUsoProductoPlaguicida($idUsoProducto, $datosUsoProducto, $tiempoAtencion);

                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado,
                                    'filaUsoProducto' => $filaUsosProducto
                                ));
                            } else {
                                $validacion = "Fallo";
                                $resultado = "El cultivo y plaga ya han sido ingresado.";
                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado
                                ));
                            }
                            break;
                        case 'IAV':
                            $idUsoProducto = $_POST['id_uso_producto'];
                            $nombreUso = $_POST['nombre_uso'];
                            $idEspecie = $_POST['id_especie'];
                            $nombreEspecieTipo = $_POST['nombre_especie_tipo'];
                            $nombreEspecie = $_POST['nombre_especie'];
                            $aplicadoA = $_POST['aplicado_a'];
                            $instalacion = $_POST['instalacion'];

                            $arrayParametros = array(
                                'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                'id_uso_producto' => $idUsoProducto,
                                'aplicado_a' => $aplicadoA
                            );

                            if ($idEspecie !== '') {
                                $arrayParametros += ['id_especie' => $idEspecie];
                            }

                            $verificarUsoProducto = $this->lNegocioUsos->buscarLista($arrayParametros);

                            if (count($verificarUsoProducto) === 0) {
                                $datosUsoProducto = [
                                    'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                    'id_uso_producto' => $idUsoProducto,
                                    'id_especie' => $idEspecie,
                                    'nombre_especie' => $nombreEspecie,
                                    'aplicado_a' => $aplicadoA,
                                    'instalacion' => $instalacion,
                                    'nombre_uso' => $nombreUso,
                                    'nombre_especie_tipo' => $nombreEspecieTipo
                                ];

                                if ($idEspecie === '') {
                                    unset($datosUsoProducto["id_especie"]);
                                }

                                $idUsoProducto = $this->lNegocioUsos->guardar($datosUsoProducto);

                                $usosControlador = new UsosControlador();
                                $filaUsosProducto = $usosControlador->generarFilaUsoProductoVeterinario($idUsoProducto, $datosUsoProducto, $tiempoAtencion);

                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado,
                                    'filaUsoProducto' => $filaUsosProducto
                                ));
                            } else {
                                $validacion = "Fallo";
                                $resultado = "El uso ya ha sido ingresado.";
                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado
                                ));
                            }
                            break;
                        case 'IAF':
                            $idUsoProducto = $_POST['id_uso_producto'];
                            $nombreUso = $_POST['nombre_uso'];
                            $aplicadoA = $_POST['aplicado_a'];
                            $instalacion = $_POST['instalacion'];
                            $instalacionProducto = $_POST['instalacion_producto'];

                            $arrayParametros = array(
                                'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                'id_uso_producto' => $idUsoProducto,
                                'aplicado_a' => $aplicadoA
                            );

                            $verificarUsoProducto = $this->lNegocioUsos->buscarLista($arrayParametros);

                            if (count($verificarUsoProducto) === 0) {
                                $datosUsoProducto = [
                                    'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                                    'id_uso_producto' => $idUsoProducto,
                                    'aplicado_a' => $aplicadoA,
                                    'instalacion' => ($instalacion === '' ? $instalacionProducto : $instalacion),
                                    'nombre_uso' => $nombreUso,
                                ];

                                $idUsoProducto = $this->lNegocioUsos->guardar($datosUsoProducto);

                                $usosControlador = new UsosControlador();
                                $filaUsosProducto = $usosControlador->generarFilaUsoProductoFertilizante($idUsoProducto, $datosUsoProducto, $tiempoAtencion);

                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado,
                                    'filaUsoProducto' => $filaUsosProducto
                                ));
                            } else {
                                $validacion = "Fallo";
                                $resultado = "El uso ya ha sido ingresado.";
                                echo json_encode(array(
                                    'validacion' => $validacion,
                                    'resultado' => $resultado
                                ));
                            }
                            break;
                    }
                    break;
                case 'modificarManufacturador':

                    $idFabricanteFormulador = $_POST['id_fabricante_formulador'];
                    $fabricanteFormulador = $_POST['fabricante_formulador'];
                    $manufacturador = $_POST['manufacturador'];
                    $idPaisOrigen = $_POST['id_pais_origen'];
                    $paisOrigen = $_POST['pais_origen'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                        'id_fabricante_formulador' => $idFabricanteFormulador,
                        'manufacturador' => $manufacturador,
                        'id_pais_origen' => $idPaisOrigen,
                    );

                    $verificarManufacturadorProducto = $this->lNegocioManufacturador->buscarLista($arrayParametros);

                    if (count($verificarManufacturadorProducto) === 0) {
                        $datosManufacturadorProducto = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                            'id_fabricante_formulador' => $idFabricanteFormulador,
                            'manufacturador' => $manufacturador,
                            'id_pais_origen' => $idPaisOrigen,
                            'pais_origen' => $paisOrigen,
                            'fabricante_formulador' => $fabricanteFormulador
                        );

                        $idManufacturadorProducto = $this->lNegocioManufacturador->guardar($datosManufacturadorProducto);

                        $manufacturadorControlador = new ManufacturadoresControlador();
                        $filaManufacturadorProducto = $manufacturadorControlador->generarManufacturadorProducto($idManufacturadorProducto, $datosManufacturadorProducto, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaManufacturadorProducto' => $filaManufacturadorProducto
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "El manufacturador ya ha sido ingresado.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
                case 'modificarComposicion':

                    $idIngredienteActivo = $_POST['id_ingrediente_activo'];
                    $ingredienteActivo = $_POST['ingrediente_activo'];
                    $idTipoComponente = $_POST['id_tipo_componente'];
                    $tipoComponente = $_POST['tipo_componente'];
                    $concentracion = $_POST['concentracion'];
                    $unidadMedida = $_POST['unidad_medida'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                        'id_ingrediente_activo' => $idIngredienteActivo,
                        'id_tipo_componente' => $idTipoComponente,
                        'concentracion' => $concentracion
                    );

                    $verificarComposicionProducto = $this->lNegocioComposiciones->buscarLista($arrayParametros);

                    if (count($verificarComposicionProducto) === 0) {
                        $datosComposicionProducto = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                            'id_ingrediente_activo' => $idIngredienteActivo,
                            'ingrediente_activo' => $ingredienteActivo,
                            'id_tipo_componente' => $idTipoComponente,
                            'tipo_componente' => $tipoComponente,
                            'concentracion' => $concentracion,
                            'unidad_medida' => $unidadMedida
                        );

                        $idComposicionProducto = $this->lNegocioComposiciones->guardar($datosComposicionProducto);

                        $composicionesControlador = new ComposicionesControlador();
                        $filaComposicionProducto = $composicionesControlador->generarFilaComposicionProducto($idComposicionProducto, $datosComposicionProducto, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaComposicionProducto' => $filaComposicionProducto
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "La composición ya ha sido ingresado.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
                case 'modificarEtiqueta':

                    $idSolicitudProducto = $_POST['id_solicitud_producto'];
                    $rutaEtiquetaProducto = $_POST['ruta_etiqueta_producto'];

                    $verificarEtiqueta = $this->lNegocioSolicitudesProducto->buscar($idSolicitudProducto);

                    if (!$verificarEtiqueta->getRutaEtiquetaProducto()) {

                        $datosEtiqueta = array(
                            'id_solicitud_producto' => $idSolicitudProducto,
                            'ruta_etiqueta_producto' => $rutaEtiquetaProducto,
                            'id_tipo_modificacion_producto' => []
                        );

                        $this->lNegocioSolicitudesProducto->guardar($datosEtiqueta);

                        $solicitudesProductoControlador = new SolicitudesProductosControlador();
                        $filaEtiqueta = $solicitudesProductoControlador->generarFilaEtiquetaProducto($idSolicitudProducto, $datosEtiqueta, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaEtiqueta' => $filaEtiqueta
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Etiqueta ingresada previamente.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
                case 'modificarDeclaracionVenta':

                    $idDeclaracionVenta = $_POST['id_declaracion_venta'];
                    $declaracionVenta = $_POST['declaracion_venta'];

                    $arrayParametros = array(
                        'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
                    );

                    $verificarDenominacionVenta = $this->lNegocioDenominacionesVentas->buscarLista($arrayParametros);

                    if (count($verificarDenominacionVenta) === 0) {
                        $datosDenominacionVentaProducto = array(
                            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
                            'id_declaracion_venta' => $idDeclaracionVenta,
                            'declaracion_venta' => $declaracionVenta
                        );

                        $idDenominacionVentaProducto = $this->lNegocioDenominacionesVentas->guardar($datosDenominacionVentaProducto);

                        $denominacionesVentasControlador = new DenominacionesVentasControlador();
                        $filaDenominacionVentaProducto = $denominacionesVentasControlador->generarFilaDenominacionVentaProducto($idDenominacionVentaProducto, $datosDenominacionVentaProducto, $tiempoAtencion);

                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado,
                            'filaDenominacionVentaProducto' => $filaDenominacionVentaProducto
                        ));
                    } else {
                        $validacion = "Fallo";
                        $resultado = "Ya existe un registro de denominación de venta.";
                        echo json_encode(array(
                            'validacion' => $validacion,
                            'resultado' => $resultado
                        ));
                    }
                    break;
            }
        }
    }
}
