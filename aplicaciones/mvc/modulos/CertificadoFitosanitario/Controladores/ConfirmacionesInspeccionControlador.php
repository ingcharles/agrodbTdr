<?php
/**
 * Controlador ConfirmacionesInspeccion
 *
 * Este archivo controla la lógica del negocio del modelo:  ConfirmacionesInspeccionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    ConfirmacionesInspeccionControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\ConfirmacionesInspeccionLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ConfirmacionesInspeccionModelo;

use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;

use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ConfirmacionesInspeccionControlador extends BaseControlador
{

    private $lNegocioConfirmacionesInspeccion = null;
    private $modeloConfirmacionesInspeccion = null;
    
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
        
        $this->lNegocioConfirmacionesInspeccion = new ConfirmacionesInspeccionLogicaNegocio();
        $this->modeloConfirmacionesInspeccion = new ConfirmacionesInspeccionModelo();
        
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
        $modeloConfirmacionesInspeccion = $this->lNegocioConfirmacionesInspeccion->buscarConfirmacionesInspeccion();
        $this->tablaHtmlConfirmacionesInspeccion($modeloConfirmacionesInspeccion);
        
        require APP . 'CertificadoFitosanitario/vistas/listaConfirmacionesInspeccionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Confirmación de Inspección en Centro de Acopio";
        $arrayParametros = array('id_certificado_fitosanitario' => $_POST["id"]);
        
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
        
        require APP . 'CertificadoFitosanitario/vistas/formularioConfirmacionesInspeccionVista.php';
    }

    /**
     * Método para registrar en la base de datos -ConfirmacionesInspeccion
     */
    public function guardar()
    {
        //Guarda registro de la asignación de fecha y hora a un centro de acopio específico        
        $this->lNegocioConfirmacionesInspeccion->guardar($_POST);                       
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ConfirmacionesInspeccion
     */
    public function editar()
    {
        $this->accion = "Editar Confirmaciones de Inspeccion";
        
        $this->modeloConfirmacionesInspeccion = $this->lNegocioConfirmacionesInspeccion->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioConfirmacionesInspeccionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ConfirmacionesInspeccion
     */
    public function borrar()
    {
        $this->lNegocioConfirmacionesInspeccion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ConfirmacionesInspeccion
     */
    public function tablaHtmlConfirmacionesInspeccion($tabla)
    {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_confirmacion_inspeccion'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario/confirmacionesinspeccion"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
            		    <td>' . ++ $contador . '</td>
            		    <td style="white - space:nowrap; "><b>' . $fila['id_confirmacion_inspeccion'] . '</b></td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['identificador_inspector'] . '</td>
                        <td>' . $fila['id_provincia_inspeccion'] . '</td>
                    </tr>'
                );
            }
    }
    
    /**
     * Consulta los centros de acopio por provincia en una solicitud y construye el combo
     */
    public function comboCentrosAcopioPorProvinciaPorSolicitud($idSolicitud, $idProvincia, $estado)
    {
        $centrosAcopio = "";
        
        $combo = $this->lNegocioExportadoresProductos->buscarCentrosAcopioXProvinciaXSolicitud($idSolicitud, $idProvincia, $estado);
        
        if($combo != null){
            foreach ($combo as $item)
            {
                $centrosAcopio .= '<option value="' . $item->id_area . '" data-nombre="' . $item->nombre_area . '" data-fecha="' . $item->fecha_inspeccion . '" data-hora="' . $item->hora_inspeccion . '" >' . $item->codigo_centro_acopio . ' - '. $item->nombre_area . ' - ' . $item->nombre_subtipo_producto . ' - ' . $item->nombre_producto . '  ('. $item->fecha_inspeccion .' '. $item->hora_inspeccion . ')</option>';
            }
        }
        
        return $centrosAcopio;
    }
}