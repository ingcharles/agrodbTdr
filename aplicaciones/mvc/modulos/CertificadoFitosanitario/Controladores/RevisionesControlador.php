<?php
/**
 * Controlador Revisiones
 *
 * Este archivo controla la lógica del negocio para el proceso de revisión y Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-06
 * @uses    RevisionesControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;

use Agrodb\CertificadoFitosanitario\Modelos\ConfirmacionesInspeccionLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ConfirmacionesInspeccionModelo;

use Agrodb\CertificadoFitosanitario\Modelos\InspeccionesLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\InspeccionesModelo;

use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesModelo;

use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesModelo;

use Agrodb\FirmaDocumentos\Modelos\FirmantesLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class RevisionesControlador extends BaseControlador
{
    private $lNegocioCertificadoFitosanitario = null;
    private $modeloCertificadoFitosanitario = null;

    private $lNegocioConfirmacionesInspeccion = null;
    private $modeloConfirmacionesInspeccion = null;
    
    private $lNegocioInspecciones = null;
    private $modeloInspecciones = null;
    
    private $lNegocioRevisionesDocumentales = null;
    private $modeloRevisionesDocumentales = null;
    
    private $lNegocioUsuariosPerfiles = null;
    private $modeloUsuariosPerfiles = null;
    
	private $lNegocioFirmantes = null;								  
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        
        $this->lNegocioConfirmacionesInspeccion = new ConfirmacionesInspeccionLogicaNegocio();
        $this->modeloConfirmacionesInspeccion = new ConfirmacionesInspeccionModelo();
        
        $this->lNegocioInspecciones = new InspeccionesLogicaNegocio();
        $this->modeloInspecciones = new InspeccionesModelo();
        
        $this->lNegocioRevisionesDocumentales = new RevisionesDocumentalesLogicaNegocio();
        $this->modeloRevisionesDocumentales = new RevisionesDocumentalesModelo();
        
        $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
        $this->modeloUsuariosPerfiles = new UsuariosPerfilesModelo();
        
		$this->lNegocioFirmantes = new FirmantesLogicaNegocio();														
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
        $this->cargarPanelRevisiones();
        
        require APP . 'CertificadoFitosanitario/vistas/listaRevisionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo CertificadoFitosanitario";
        require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioVista.php';
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CertificadoFitosanitario
     */
    public function editar()
    {
        $this->accion = "Editar CertificadoFitosanitario";
        $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - CertificadoFitosanitario
     */
    public function tablaHtmlRevisionesInspeccion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array('<tr id="' . $fila['id_certificado_fitosanitario'] . '"
                                                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario/CertificadoFitosanitario"
                                                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                                                		  data-destino="detalleItem">
                                                		  <td>' . ++ $contador . '</td>
                                                <td>' . ucfirst($fila['tipo_certificado']) . '</td>
                                                <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
                                                <td>' . $fila['nombre_pais_destino'] . '</td>
                                                <td>' . $fila['nombre_pais_destino'] . '</td>
                                                <td>' . $fila['estado_certificado'] . '</td>
                                            </tr>'
            );
        }
    }
    
    /**
     * Construye el código HTML para desplegar la lista de - CertificadoFitosanitario
     */
    public function tablaHtmlCertificadoFitosanitarioRevisiones($tabla)
    {
        $contador = 0;
        $controlador = "";
        $opcion = "";
        
        foreach ($tabla as $fila) {
            
            switch($fila['estado_certificado']){
                
                case 'ConfirmarInspeccion' :{
                    $controlador = "ConfirmacionesInspeccion";
                    $opcion = "nuevo";
                    
                    break;
                }
                
                case 'Inspeccion' :{
                    $controlador = "Inspecciones";
                    $opcion = "nuevo";
                    
                    break;
                }
                
                case 'Documental' :{
                    $controlador = "RevisionesDocumentales";
                    $opcion = "nuevo";
                    
                    break;
                }
                
                case 'Aprobado' :{ //Temporal, verificar para incluir la opción de impresión aquí
                    $controlador = "Impresiones";
                    $opcion = "nuevo";
                    
                    break;
                }
                
                default :{
                    $controlador = "";
                    $opcion = "";
                    
                    break;
                }
            }
            
            $this->itemsFiltrados[] = array('<tr id="' . $fila['id_certificado_fitosanitario'] . '"
                                                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario/'.$controlador.'"
                                                		  data-opcion="'.$opcion.'" ondragstart="drag(event)" draggable="true"
                                                		  data-destino="detalleItem">
                                                		  <td>' . ++ $contador . '</td>
                                                <td>' . ucfirst($fila['tipo_certificado']) . '</td>
                                                <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
                                                <td>' . $fila['nombre_pais_destino'] . '</td>
                                                <td>' . $fila['estado_certificado'] . '</td>
                                            </tr>'
            );
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para las revisiones de solicitudes
     */
    public function cargarPanelRevisiones()
    {
        $perfilTecnico = $this->lNegocioUsuariosPerfiles->buscarUsuariosXAplicacionPerfil($_SESSION['usuario'], 'PFL_TEC_CERT_FIT');
        $banderaRevisionDocumental = false;
        $identificadorTecnico = $_SESSION['usuario'];
        $mensajeCaducidad = "";
        
        if(isset($perfilTecnico->current()->identificador)){
            $perfil = 'Revisor';            
            
            $condicion = "identificador = '" . $identificadorTecnico . "' and fecha_caducidad_certificado >= CURRENT_DATE;";
            $verificarFechaCaducidadFirma = $this->lNegocioFirmantes->buscarLista($condicion);
            
            if($verificarFechaCaducidadFirma->count()){
                $banderaRevisionDocumental = true;
            }else{
                $mensajeCaducidad = '<label class="alerta">Firma caducada, no podrá realizar revisión documental</label>';
            }
            
        }
        
        $this->panelBusquedaRevisiones = '<table class="filtro" style="width: 100%; text-align:left;">
                                            <tbody>
                            					<tr>
                            						<td>Revisión: </td>
                            						<td colspan=3>
                                                        <select id="tipoRevisionFiltro" name="tipoRevisionFiltro" style="width: 100%;" required>
                                                            <option value="">Seleccione....</option>' .
                                                            $this->comboFasesRevision($perfil, $banderaRevisionDocumental)                                                          
                                                        . '</select>
                            						</td>
                                                </tr>
                                                <tr>
                            						<td>Solicitante: </td>
                            						<td colspan=3>
                                                        <select id="identificadorSolicitanteFiltro" name="identificadorSolicitanteFiltro" style="width: 100%;">
                                                            <option value="">Seleccione....</option>
                                                        </select>
                            						</td>
                            					</tr>
                                                <tr>
                            						<td>Número de Certificado: </td>
                            						<td colspan=3>
                                                        <input type="text" id="numeroCertificadoFiltro" name="numeroCertificadoFiltro" style="width: 100%;">
                            						</td>
                            					</tr>
                                                <tr>
                            						<td>País de Destino: </td>
                            						<td>
                            							<select id="idPaisDestinoFiltro" name="idPaisDestinoFiltro" style="width: 100%;">
                                                            <option value="">Seleccione....</option>' .
                                                            $this->comboPaises(null) .
                                                        '</select>
                            						</td>
                                                       
                                                    <td>Medio Transporte: </td>
                                                    <td>
                            							<select id="idMedioTransporteFiltro" name="idMedioTransporteFiltro" style="width: 100%;">
                                                            <option value="">Seleccione....</option>' .
                                                            $this->comboMediosTransporte(null) .
                                                        '</select>
                            						</td>
                            					</tr>
                            					<tr>
                                                    <td colspan="3">'
                                                    . $mensajeCaducidad .    
                                                    '</td>
                            						<td style="text-align: end;">
                            							<button type="button" id="btnFiltrar">Buscar</button>
                            						</td>                                                            
                            					</tr>
                            				</tbody>
                            			</table>';
    }
     
    /**
     * Combo de fases de revisión documental
     *
     * @param $respuesta
     * @return string
     */
    public function comboFasesRevision($opcion, $banderaRevisionDocumental)
    {
        
        $combo = "";
        
        if($opcion == "Revisor"){
            
            if($banderaRevisionDocumental){            
                $combo = '<option value="Documental">Revisión Documental</option>
                        <option value="ConfirmarInspeccion">Confirmar Inspección</option>
                        <option value="Inspeccion">Inspección</option>';            
            }else{                
                $combo = '<option value="ConfirmarInspeccion">Confirmar Inspección</option>
                        <option value="Inspeccion">Inspección</option>';                
            }
        
        }

        return $combo;
    }
    
    /**
     * Consulta los nombres de los operadores que tienen solicitudes pendientes por estado
     *  y construye el combo
     */
    public function comboSolicitantesPorFaseRevision()
    {
        $faseRevision = $_POST['faseRevision'];
        $idProvinciaRevision = $_SESSION['idProvincia'];
        
        $usuarios = "";
        $combo = $this->lNegocioCertificadoFitosanitario->buscarSolicitantePorFaseRevision($faseRevision, $idProvinciaRevision);
        
        if($combo != null){
            foreach ($combo as $item)
            {
                $usuarios .= '<option value="' . $item->identificador_solicitante . '">' . $item->razon_social . '</option>';
            }
        }
        
        echo $usuarios;
        exit();
    }
    
    /**
     * Consulta los países donde los operadores/solicitantes que tienen solicitudes pendientes por estado
     *  y construye el combo
     */
    public function comboPaisesSolicitantePorFaseRevision()
    {
        $faseRevision = $_POST['faseRevision'];
        $idProvinciaRevision = $_SESSION['idProvincia'];
        $identificadorSolicitante = $_POST['identificadorSolicitante'];
        
        $usuarios = "";
        $combo = $this->lNegocioCertificadoFitosanitario->buscarPaisesSolicitanteXFaseRevision($faseRevision, $identificadorSolicitante, $idProvinciaRevision);
        
        if($combo != null){
            foreach ($combo as $item)
            {
                $usuarios .= '<option value="' . $item->id_pais_destino . '">' . $item->nombre_pais_destino . '</option>';
            }
        }
        
        echo $usuarios;
        exit();
    }
    
    /**
     * Método para listar las solicitudes registradas
     */
    public function listarSolicitudesRevisionFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $faseRevision = $_POST['faseRevision'];
        $identificadorSolicitante = $_POST["identificadorSolicitante"];
        $numeroCertificado = $_POST['numeroCertificado'];
        $idPaisDestino = $_POST['idPaisDestino'];
        $idMedioTransporte = $_POST['idMedioTransporte'];
        
        $idProvinciaRevision = $_SESSION['idProvincia'];
        $identificadorTecnico = $_SESSION['usuario'];
        
        if($faseRevision == 'ConfirmarInspeccion' || $faseRevision == 'Documental'){
            $arrayParametros = array(
                'faseRevision' => $faseRevision,
                'identificador_solicitante' => $identificadorSolicitante,
                'codigo_certificado' => $numeroCertificado,
                'id_pais_destino' => $idPaisDestino,                
                'id_medio_transporte' => $idMedioTransporte,
                'id_provincia_revision' => $idProvinciaRevision
            );
        }else if($faseRevision == 'Inspeccion'){
            $arrayParametros = array(
                'faseRevision' => $faseRevision,
                'identificador_solicitante' => $identificadorSolicitante,
                'codigo_certificado' => $numeroCertificado,
                'id_pais_destino' => $idPaisDestino,                
                'id_medio_transporte' => $idMedioTransporte,
                'id_provincia_revision' => $idProvinciaRevision,
                'identificador_inspector' => $identificadorTecnico
            );
        }
        
        $solicitudes = $this->lNegocioCertificadoFitosanitario->buscarSolicitudesRevisionFiltradas($arrayParametros);
        
        $this->tablaHtmlCertificadoFitosanitarioRevisiones($solicitudes);
        
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}