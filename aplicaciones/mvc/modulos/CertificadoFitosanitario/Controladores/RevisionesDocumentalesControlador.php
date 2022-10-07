<?php
/**
 * Controlador RevisionesDocumentales
 *
 * Este archivo controla la lógica del negocio del modelo:  RevisionesDocumentalesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    RevisionesDocumentalesControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesModelo;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class RevisionesDocumentalesControlador extends BaseControlador
{

    private $lNegocioRevisionesDocumentales = null;

    private $modeloRevisionesDocumentales = null;

    private $lNegocioCertificadoFitosanitario = null;

    private $modeloCertificadoFitosanitario = null;

    private $lNegocioExportadoresProductos = null;

    private $modeloExportadoresProductos = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->lNegocioRevisionesDocumentales = new RevisionesDocumentalesLogicaNegocio();
        $this->modeloRevisionesDocumentales = new RevisionesDocumentalesModelo();

        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();

        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();

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
        $modeloRevisionesDocumentales = $this->lNegocioRevisionesDocumentales->buscarRevisionesDocumentales();
        $this->tablaHtmlRevisionesDocumentales($modeloRevisionesDocumentales);
        require APP . 'CertificadoFitosanitario/vistas/listaRevisionesDocumentalesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Revisión Documental";
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id"]
        );

        $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($_POST["id"]);
        $nombrePais = $this->modeloCertificadoFitosanitario->getNombrePaisDestino();
        $esReemplazo = $this->modeloCertificadoFitosanitario->getEsReemplazo();
        $estadoCertificado = $this->modeloCertificadoFitosanitario->getEstadoCertificado();
        
        $arrayParametrosAdjuntos = array('id_certificado_fitosanitario' => $_POST["id"]
            , 'estado_certificado' => $estadoCertificado
        );
        
        if ($this->modeloCertificadoFitosanitario->getTipoCertificado() == 'otros') {
            if ($this->modeloCertificadoFitosanitario->getEsReemplazo() != 'Si') {
                $detalleInspecciones = new InspeccionesControlador();
                $this->listaDetallesInspecciones = $detalleInspecciones->construirDetalleInspecciones($_POST["id"]);
            }
        }
        
        if($esReemplazo == "Si"){
            $idCertificadoReemplazo = $this->modeloCertificadoFitosanitario->getIdCertificadoReemplazo();
            $motivoReemplazo = $this->modeloCertificadoFitosanitario->getMotivoReemplazo();
            $arrayParametrosAnulaReemplaza = array('id_certificado_reemplazo' => $idCertificadoReemplazo,
                'motivo_reemplazo' => $motivoReemplazo
            );
            $this->construirDetalleAnulaReemplaza($arrayParametrosAnulaReemplaza);
        }

        $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, false, false);
        $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePais, false);
        $this->construirDetallePaisPuertosTransito($arrayParametros, false);
        $this->construirDetalleExportadoresProductosRevisiones($arrayParametros);
        $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, false);

        require APP . 'CertificadoFitosanitario/vistas/formularioRevisionesDocumentalesVista.php';
    }

    /**
     * Método para registrar en la base de datos -RevisionesDocumentales
     */
    public function guardar()
    {
        $proceso = $this->lNegocioRevisionesDocumentales->guardar($_POST);

        if ($proceso) {
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionesDocumentales
     */
    public function editar()
    {
        $this->accion = "Editar RevisionesDocumentales";

        $this->modeloRevisionesDocumentales = $this->lNegocioRevisionesDocumentales->buscar($_POST["id"]);

        require APP . 'CertificadoFitosanitario/vistas/formularioRevisionesDocumentalesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - RevisionesDocumentales
     */
    public function borrar()
    {
        $this->lNegocioRevisionesDocumentales->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - RevisionesDocumentales
     */
    public function tablaHtmlRevisionesDocumentales($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_revision_documental'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario/RevisionesDocumentales"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
            		  <td>' . ++ $contador . '</td>
            		  <td style="white - space:nowrap; "><b>' . $fila['id_revision_documental'] . '</b></td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['identificador_inspector'] . '</td>
                    <td>' . $fila['id_provincia_revision'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Combo de resultados de revisión documental tipo Otros
     *
     * @param
     *            $respuesta
     * @return string
     */

    // Revisar los estados para el envio al modulo financiero, se tiene que validar
    // por el tipo de solicitud y el tipo de pago que eligio el usuario y si
    // la solicitud se debe procesar manual o automatica
    // verificar si se crea un cron para esa asignación de tasa automatica
    public function comboResultadosDocumentalOtros($opcion = null)
    {
        $combo = "";
        if ($opcion == "DocumentalAprobada") {
            $combo .= '<option value="DocumentalAprobada" selected="selected">Revisión Documental Aprobada</option>';
            $combo .= '<option value="DevueltoTecnico">Devolver al Inspector</option>';
        } else if ($opcion == "DevueltoTecnico") {
            $combo .= '<option value="DocumentalAprobada">Revisión Documental Aprobada</option>';
            $combo .= '<option value="DevueltoTecnico" selected="selected">Devolver al Inspector</option>';
        } else {
            $combo .= '<option value="DocumentalAprobada">Revisión Documental Aprobada</option>';
            $combo .= '<option value="DevueltoTecnico">Devolver al Inspector</option>';
        }
        return $combo;
    }

    /**
     * Combo de resultados de revisión documental tipo Ornamentales/Musáceas
     *
     * @param
     *            $respuesta
     * @return string
     */

    // Revisar los estados para el envio al modulo financiero, se tiene que validar
    // por el tipo de solicitud y el tipo de pago que eligio el usuario y si
    // la solicitud se debe procesar manual o automatica
    // verificar si se crea un cron para esa asignación de tasa automatica
    public function comboResultadosDocumentalOrnamentalesMusaceasRenovacion($opcion = null)
    {
        $combo = "";
        if ($opcion == "DocumentalAprobada") {
            $combo .= '<option value="DocumentalAprobada" selected="selected">Revisión Documental Aprobada</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
        } else if ($opcion == "Subsanación") {
            $combo .= '<option value="DocumentalAprobada">Revisión Documental Aprobada</option>';
            $combo .= '<option value="Subsanacion" selected="selected">Subsanación</option>';
        } else {
            $combo .= '<option value="DocumentalAprobada">Revisión Documental Aprobada</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
        }
        return $combo;
    }

    // Revisar los estados para el envio al modulo financiero, se tiene que validar
    // por el tipo de solicitud y el tipo de pago que eligio el usuario y si
    // la solicitud se debe procesar manual o automatica
    // verificar si se crea un cron para esa asignación de tasa automatica
    public function comboResultadosDocumentalOrnamentalesMusaceasMasiva($opcion = null)
    {
        $combo = "";
        if ($opcion == "DocumentalAprobada") {
            $combo .= '<option value="DocumentalAprobada" selected="selected">Revisión Documental Aprobada</option>';
        } else {
            $combo .= '<option value="DocumentalAprobada">Revisión Documental Aprobada</option>';
        }
        return $combo;
    }

    /**
     * Consulta las provincias donde se realizaron las inspecciones en una solicitud y construye el combo
     */
    public function comboProvinciaXSolicitud($idSolicitud, $estado)
    {
        $provincias = "";

        $combo = $this->lNegocioExportadoresProductos->buscarProvinciaXSolicitud($idSolicitud, $estado);

        if ($combo != null) {
            foreach ($combo as $item) {
                $provincias .= '<option value="' . $item->id_provincia_area . '" >' . $item->nombre_provincia_area . '</option>';
            }
        }

        return $provincias;
    }

    /**
     * Método para obtener los datos del operador de origen
     */
    public function comboCentrosAcopioPorProvincia()
    {
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id_certificado_fitosanitario"],
            'id_provincia_area' => $_POST["id_provincia"],
            'estado_exportador_producto' => "'InspeccionAprobada'"
        );

        $listaCentros = $this->lNegocioExportadoresProductos->buscarCentrosAcopioXProvincia($arrayParametros);

        $centrosAcopio = "";
        $centrosAcopio .= '<option value="">Seleccione....</option>';

        foreach ($listaCentros as $item) {
            $centrosAcopio .= '<option value="' . $item->id_area . '">' . $item->nombre_area . '</option>';
        }

        echo $centrosAcopio;
        exit();
    }

    /**
     * Método para obtener los datos del operador de origen
     */
    public function comboProductosPorCentroAcopioPorProvincia()
    {
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id_certificado_fitosanitario"],
            'id_provincia_area' => $_POST["id_provincia"],
            'id_area' => $_POST["id_area"],
            'estado_exportador_producto' => "'InspeccionAprobada'"
        );

        $listaProductos = $this->lNegocioExportadoresProductos->buscarProductosPorCentroAcopioInspeccion($arrayParametros);

        $productos = "";
        $productos .= '<option value="">Seleccione....</option>';

        foreach ($listaProductos as $item) {
            $productos .= '<option value="' . $item->id_producto . '">' . $item->nombre_producto . '</option>';
        }

        echo $productos;
        exit();
    }

    /**
     * Método para obtener el resultado de una revisión documental para una solicitud, centro de acopio y producto específico
     */
    public function consultarResultadoRevisionDocumental()
    {
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $_POST["id_certificado_fitosanitario"],
            'id_area' => $_POST["id_area"],
            'id_producto' => $_POST["id_producto"],
            'estado_exportador_producto' => "'DevueltoTecnico'"
        );

        $listaProductos = $this->lNegocioRevisionesDocumentales->buscarResultadoRevisionDocumental($arrayParametros);

        $productos = "";

        foreach ($listaProductos as $item) {
            $productos .= $item->observacion_revision;
        }

        echo json_encode(array(
            'resultado' => $productos
        ));
    }
}