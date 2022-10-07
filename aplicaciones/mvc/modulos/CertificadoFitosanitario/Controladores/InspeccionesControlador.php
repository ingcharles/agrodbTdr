<?php
/**
 * Controlador Inspecciones
 *
 * Este archivo controla la lógica del negocio del modelo:  InspeccionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    InspeccionesControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\InspeccionesLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\InspeccionesModelo;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\FormulariosInspeccion\Modelos\Certificacionf11LogicaNegocio;

class InspeccionesControlador extends BaseControlador
{

    private $lNegocioInspecciones = null;

    private $modeloInspecciones = null;

    private $lNegocioCertificadoFitosanitario = null;

    private $modeloCertificadoFitosanitario = null;

    private $lNegocioExportadoresProductos = null;

    private $modeloExportadoresProductos = null;
    
    private $lNegocioCertificacionf11 = null;

    private $accion = null;

    private $formulario = null;

    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->lNegocioInspecciones = new InspeccionesLogicaNegocio();
        $this->modeloInspecciones = new InspeccionesModelo();

        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();

        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();

        $this->lNegocioCertificacionf11 = new Certificacionf11LogicaNegocio();
        
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
        $modeloInspecciones = $this->lNegocioInspecciones->buscarInspecciones();
        $this->tablaHtmlInspecciones($modeloInspecciones);
        require APP . 'CertificadoFitosanitario/vistas/listaInspeccionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Resultado de Inspección";
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id"]
        );

        $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($_POST["id"]);
        $nombrePais = $this->modeloCertificadoFitosanitario->getNombrePaisDestino();
        $estadoCertificado = $this->modeloCertificadoFitosanitario->getEstadoCertificado();
        
        $arrayParametrosAdjuntos = array('id_certificado_fitosanitario' => $_POST["id"]
            , 'estado_certificado' => $estadoCertificado
        );
        
        $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, false, false);
        $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePais, false);
        $this->construirDetallePaisPuertosTransito($arrayParametros, false);
        $this->construirDetalleExportadoresProductosRevisiones($arrayParametros);
        $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, false);

        require APP . 'CertificadoFitosanitario/vistas/formularioInspeccionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Inspecciones
     */
    public function guardar()
    {
        /*echo '<pre>';
        print_r($_POST);
        echo '<pre>';*/
        $proceso = $this->lNegocioInspecciones->guardar($_POST);

        if ($proceso) {
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Inspecciones
     */
    public function editar()
    {
        $this->accion = "Editar Inspecciones";
        $this->formulario = 'abrir';

        $this->modeloInspecciones = $this->lNegocioInspecciones->buscar($_POST["id"]);

        require APP . 'CertificadoFitosanitario/vistas/formularioInspeccionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Inspecciones
     */
    public function borrar()
    {
        $this->lNegocioInspecciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Inspecciones
     */
    public function tablaHtmlInspecciones($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_inspeccion'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario/Inspecciones"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                        <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_inspeccion'] . '</b></td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['identificador_inspector'] . '</td>
                        <td>' . $fila['id_provincia_inspeccion'] . '</td>
                    </tr>'
                );
            }
        }
    }

    /**
     * Combo de resultados de inspección
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboResultadosInspeccion($opcion = null)
    {
        $combo = "";
        if ($opcion == "InspeccionAprobada") {
            $combo .= '<option value="InspeccionAprobada" selected="selected">Inspección Aprobada</option>';
            $combo .= '<option value="Subanacion">Subsanación</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
        } else if ($opcion == "Subsanacion") {
            $combo .= '<option value="InspeccionAprobada">Inspección Aprobada</option>';
            $combo .= '<option value="Subsanacion" selected="selected">Subsanación</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
        } else if ($opcion == "Rechazado") {
            $combo .= '<option value="InspeccionAprobada">Inspección Aprobada</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
            $combo .= '<option value="Rechazado" selected="selected">Rechazado</option>';
        } else {
            $combo .= '<option value="InspeccionAprobada">Inspección Aprobada</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
        }
        return $combo;
    }

    /**
     * Consulta los centros de acopio por provincia en una solicitud y construye el combo
     */
    public function comboCentrosAcopioPorProvinciaPorSolicitud($idSolicitud, $idProvincia, $estado)
    {
        $centrosAcopio = "";

        $combo = $this->lNegocioExportadoresProductos->buscarCentrosAcopioXProvinciaXSolicitud($idSolicitud, $idProvincia, $estado);

        if ($combo != null) {
            foreach ($combo as $item) {
                $centrosAcopio .= '<option value="' . $item->id_area . '" data-nombre="' . $item->nombre_area . '" data-fecha="' . $item->fecha_inspeccion . '" data-hora="' . $item->hora_inspeccion . '" data-estado="' . $item->estado_exportador_producto . '" data-idAreaProducto = "' . $item->id_area . $item->id_producto . '" >' . $item->codigo_centro_acopio . ' - ' . $item->nombre_area . ' - ' . $item->nombre_subtipo_producto . ' - ' . $item->nombre_producto . '  (' . $item->fecha_inspeccion . ' ' . $item->hora_inspeccion . ')</option>';
            }
        }

        return $centrosAcopio;
    }

    /**
     * Método para obtener los datos del operador de origen
     */
    public function comboProductosPorCentroAcopioInspeccion()
    {
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id_certificado_fitosanitario"],
            'id_provincia_area' => $_SESSION["idProvincia"],
            'id_area' => $_POST["id_area"],
            'estado_exportador_producto' => "'FechaConfirmada', 'Subsanado', 'DevueltoTecnico'"
        );

        $listaProductos = $this->lNegocioExportadoresProductos->buscarProductosPorCentroAcopioInspeccion($arrayParametros);

        $productos = "";
        $productos .= '<option value="">Seleccione....</option>';

        foreach ($listaProductos as $item) {
            $productos .= '<option value="' . $item->id_producto . '" data-identificadorExportador="' . $item->identificador_exportador . '" data-idPaisDestino="' . $item->id_pais_destino . '" data-estado="' . $item->estado_exportador_producto . '">' . $item->nombre_producto . '</option>';
        }

        echo $productos;
        exit();
    }

    /**
     * Método para listar las inspecciones registradas
     */
    public function construirDetalleInspecciones($idSolicitud)
    {
        $listaDestalles = $this->lNegocioInspecciones->buscarInspeccionXSolicitud($idSolicitud);

        $this->listaDetalles = '<fieldset>
		                              <legend>Detalle de Inspecciones realizadas</legend>
                                      <table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Provincia</th>
                                                <th>Centro de Acopio</th>
                                                <th>Producto</th>
                                                <th>Fecha Inspección Solicitada</th>
                                                <th>Reporte Inspección</th>
                                                <th>Fecha Revisión Técnico</th>
                                                <th>Resultado</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>';

        $i = 1;

        foreach ($listaDestalles as $fila) {

            $this->listaDetalles .= '<tr>
                        <td>' . $i ++ . '</td>
                        <td>' . ($fila['provincia_inspeccion'] != '' ? $fila['provincia_inspeccion'] : '') . '</td>
                        <td>' . ($fila['nombre_area_inspeccion'] != '' ? $fila['nombre_area_inspeccion'] : '') . '</td>
                        <td>' . ($fila['nombre_producto_inspeccion'] != '' ? $fila['nombre_producto_inspeccion'] : '') . '</td>
                        <td>' . ($fila['fecha_confirmacion_inspeccion'] != '' ? date('Y-m-d', strtotime($fila['fecha_confirmacion_inspeccion'])) . ' ' . date('H:i', strtotime($fila['hora_confirmacion_inspeccion'])) : '') . '</td>
                        <td>' . ($fila['formulario_inspeccion_tablet'] != '' ? $fila['formulario_inspeccion_tablet'] . ' </br> ' . ($fila['ruta_archivo_inspeccion'] != '' ? ($fila['ruta_archivo_inspeccion'] != '0' ? '<a href="' . $fila['ruta_archivo_inspeccion'] . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de Inspección</a>' : '') : '') : ($fila['ruta_archivo_inspeccion'] != '' ? ($fila['ruta_archivo_inspeccion'] != '0' ? '<a href="' . $fila['ruta_archivo_inspeccion'] . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de Inspección</a>' : '') : 'No se ha cargado información')) . '</td>
                        <td>' . ($fila['fecha_creacion'] != '' ? date('Y-m-d', strtotime($fila['fecha_creacion'])) : '') . '</td>
                        <td>' . ($fila['estado'] != '' ? $fila['estado'] : '') . '</td>
                        <td>' . ($fila['observacion_inspeccion'] != '' ? $fila['observacion_inspeccion'] : '') . '</td>
                    </tr>';
        }

        $this->listaDetalles .= '</table></fieldset>';

        return $this->listaDetalles;
    }

    /**
     * Método para verificar si el formulario de inspeccion tablet es valido
     */
    public function verificarFormularioInspeccion()
    {
        
        if(isset($_POST["identificadorExportador"]) && isset($_POST["idProducto"])){
        
                $identificadorExportador = $_POST["identificadorExportador"];
                $numeroFormularioInspeccion = $_POST["numeroFormularioInspeccion"];
                $idProducto = $_POST["idProducto"];
                $idPaisDestino = $_POST["idPaisDestino"];
        
                $arrayParametros = array(
                    'identificadorExportador' => $identificadorExportador,
                    'numeroFormularioInspeccion' => $numeroFormularioInspeccion,
                    'idProducto' => $idProducto,
                    'idPaisDestino' => $idPaisDestino
                );
        
                $validacion = "Fallo";
                $resultado = "El formulario ingresado no existe o no corresponde al exportador.";
                       
                $formularioInspeccion = $this->lNegocioCertificacionf11->verificarFormularioInspeccionCfe($arrayParametros);
        
                if(isset($formularioInspeccion->current()->numero_reporte)){
                    $resultado = "";
                    $validacion = "Exito";
                    echo json_encode(array('resultado' => $resultado,'validacion' => $validacion));
                }else{
                    echo json_encode(array('resultado' => $resultado, 'validacion' => $validacion,));
                }
            
            }else{
                
                $validacion = "Fallo";
                $resultado = "Por favor seleccione parámetros para la búsqueda.";
                
                echo json_encode(array('resultado' => $resultado, 'validacion' => $validacion,));
                
            }

    }
    
}