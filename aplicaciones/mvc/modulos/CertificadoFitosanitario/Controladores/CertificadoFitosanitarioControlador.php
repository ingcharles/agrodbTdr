<?php
/**
 * Controlador CertificadoFitosanitario
 *
 * Este archivo controla la lógica del negocio del modelo:  CertificadoFitosanitarioModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    CertificadoFitosanitarioControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoModelo;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoModelo;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosModelo;
use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\RevisionesDocumentalesModelo;
use Agrodb\RegistroOperador\Modelos\CodigosPoaLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\CodigosPoaModelo;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosModelo;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoModelo;
use Agrodb\FirmaDocumentos\Modelos\DocumentosLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\RevisionFormularios\Modelos\RevisionDocumentalLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\RevisionDocumentalModelo;

class CertificadoFitosanitarioControlador extends BaseControlador
{
    
    private $lNegocioCertificadoFitosanitario = null;
    private $modeloCertificadoFitosanitario = null;
    private $lNegocioPuertosDestino = null;
    private $modeloPuertosDestino = null;
    private $lNegocioPaisesPuertosTransito = null;
    private $modeloPaisesPuertosTransito = null;
    private $lNegocioExportadoresProductos = null;
    private $modeloExportadoresProductos = null;
    private $lNegocioDocumentosAdjuntos = null;
    private $modeloDocumentosAdjuntos = null;
    private $lNegocioRevisionesDocumentales = null;
    private $modeloRevisionesDocumentales = null;
    private $lNegocioCodigosPoa = null;
    private $modeloCodigosPoa = null;
    private $lNegocioSubtipoProductos = null;
    private $modeloSubtipoProductos = null;
    private $lNegocioFichaEmpleado = null;
    private $modeloFichaEmpleado = null;
    private $rutaFecha = null;
    private $comboFormaPago = null;
    private $panelBusquedaCertificadosFitosanitarios = null;
    private $ingresarExportadoresProductos = null;
    private $paisPuertosDestinoReimpresion = null;
    private $paisesPuertosTransitoReimpresion = null;
    private $exportadoresProductosReimpresion = null;
    private $listaDetalles = null;
    private $accion = null;
    private $formulario = null;
    private $procesoSolicitud = null;
    private $detalleDesestimiento = null;
    private $lNegocioDocumentos = null;
    public $procesoImpresion = null;

    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        $this->lNegocioPuertosDestino = new PuertosDestinoLogicaNegocio();
        $this->modeloPuertosDestino = new PuertosDestinoModelo();
        $this->lNegocioPaisesPuertosTransito = new PaisesPuertosTransitoLogicaNegocio();
        $this->modeloPaisesPuertosTransito = new PaisesPuertosTransitoModelo();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();
        $this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
        $this->modeloDocumentosAdjuntos = new DocumentosAdjuntosModelo();
        $this->lNegocioRevisionesDocumentales = new RevisionesDocumentalesLogicaNegocio();
        $this->modeloRevisionesDocumentales = new RevisionesDocumentalesModelo();
        $this->lNegocioCodigosPoa = new CodigosPoaLogicaNegocio();
        $this->modeloCodigosPoa = new CodigosPoaModelo();
        $this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
        $this->modeloSubtipoProductos = new SubtipoProductosModelo();
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
        $this->modeloFichaEmpleado = new FichaEmpleadoModelo();        
        $this->lNegocioDocumentos = new DocumentosLogicaNegocio();
        $this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }
    
    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarPanelCertificadosFitosanitarios();
        
        require APP . 'CertificadoFitosanitario/vistas/listaCertificadoFitosanitarioVista.php';
    }
    
    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {

        $this->cargarFormaPago();
        $this->procesoSolicitud = "nueva";
        $this->accion = "Nuevo Certificado Fitosanitario";

        require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioVista.php';
    }
    
    /**
     * Método para registrar en la base de datos CertificadoFitosanitario
     */
    public function guardar()
    {
        
        $identificadorSolicitante = $_SESSION['usuario'];
        $anio = date('y');
        $numeroDigitos = 5;
        $codigoGenerado = "";
        $verificarCodigo = null;
        
        if(isset($_POST['proceso_solicitud'])){
            if($_POST['proceso_solicitud'] == "reimpresion"){
                $_POST['es_reemplazo'] = 'Si';
                $_POST['id_certificado_reemplazo'] = $_POST['id_certificado_fitosanitario_reemplazo'];
            }
        }
        
        do{
            
            $codigoGenerado = $this->generarCodigoCertificado($numeroDigitos);
            $codigoGenerado = str_pad($identificadorSolicitante, 13, 999) . $anio . $codigoGenerado;
            
            $arrayParametros = array('identificadorSolicitante' =>  $identificadorSolicitante,
                'codigoGenerado' => $codigoGenerado);
            
            $verificarCodigo = $this->lNegocioCertificadoFitosanitario->verificarCodigoCertificadoFitosanitario($arrayParametros);
            
        }while(isset($verificarCodigo->current()->codigo_certificado));
        
        $_POST['identificador_solicitante'] = $identificadorSolicitante;
        $_POST['codigo_certificado'] = $codigoGenerado;        
        $_POST['array_pais_puertos_destino'] = json_decode($_POST['array_pais_puertos_destino'], true);
        $_POST['array_pais_puertos_transito'] = json_decode($_POST['array_pais_puertos_transito'], true);
        $_POST['array_exportadores_productos'] = json_decode($_POST['array_exportadores_productos'], true);
        
        /*echo "<pre>";
        print_r($_POST);
        echo "<pre>";*/
        
        /*echo "<pre>";
        print_r($_POST['array_exportadores_productos']);
        echo "<pre>";*/
        
        $this->lNegocioCertificadoFitosanitario->guardar($_POST);
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        
    }
    
    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: CertificadoFitosanitario
     */
    public function editar()
    {
        $banderaUsuarioInterno = false;
        $resultadoUsuarioInterno = $this->lNegocioFichaEmpleado->buscarDatosUsuarioContrato($_SESSION['usuario']);
        $arrayParametros = array('id_certificado_fitosanitario' => $_POST["id"]);
        
        $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($arrayParametros['id_certificado_fitosanitario']);
        
        $nombrePaisDestino = $this->modeloCertificadoFitosanitario->getNombrePaisDestino();
        $estadoCertificado = $this->modeloCertificadoFitosanitario->getEstadoCertificado();
        $tipoSolicitud = $this->modeloCertificadoFitosanitario->getTipoCertificado();
        $idIdioma = $this->modeloCertificadoFitosanitario->getIdIdioma();
        $esReemplazo = $this->modeloCertificadoFitosanitario->getEsReemplazo();
        
        $arrayParametrosAdjuntos = array('id_certificado_fitosanitario' => $_POST["id"]
                                          , 'estado_certificado' => $estadoCertificado
                                        );
        
        if($esReemplazo == "Si"){            
            $idCertificadoReemplazo = $this->modeloCertificadoFitosanitario->getIdCertificadoReemplazo();
            $motivoReemplazo = $this->modeloCertificadoFitosanitario->getMotivoReemplazo();
            $arrayParametrosAnulaReemplaza = array('id_certificado_reemplazo' => $idCertificadoReemplazo,
                                                    'motivo_reemplazo' => $motivoReemplazo                                                    
                                                    );
            $this->construirDetalleAnulaReemplaza($arrayParametrosAnulaReemplaza);
        }
        
        if($estadoCertificado == "Anulado"){
            $motivoDesestimiento = $this->modeloCertificadoFitosanitario->getMotivoDesestimiento();
            $arrayParametrosDesestimiento = array('motivo_desestimiento' => $motivoDesestimiento
                                                    );
            $this->construirDetalleDesestimiento($arrayParametrosDesestimiento);
        }
        
        if(isset($resultadoUsuarioInterno->current()->identificador)){
            $banderaUsuarioInterno = true;
        }

        if($banderaUsuarioInterno){

            $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, false, false);
            $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePaisDestino, false);
            $this->construirDetallePaisPuertosTransito($arrayParametros, false);
            $this->construirDetalleExportadoresProductos($arrayParametros, false);
            $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, false);
            $this->accion = "Certificado Fitosanitario";
            require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioAbrirCertificado.php';
        
        }else{           
            
            if($estadoCertificado == "Subsanacion"){
                $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, true, false);
                $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePaisDestino, true);
                $this->construirDetallePaisPuertosTransito($arrayParametros, true);
                $this->construirDetalleExportadoresProductos($arrayParametros, true);
                $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, true);
                
                if(($tipoSolicitud == "ornamentales" || $tipoSolicitud == "musaceas")){
                    if($esReemplazo != "Si"){
                        $codigoIdioma = $this->lNegocioCertificadoFitosanitario->obtenerDatosIdioma($idIdioma);
                        $this->construirIngresoExportadoresProductos('Kg', $codigoIdioma->current()->codigo_idioma);
                    }
                }
                
                $this->accion = "Editar Certificado Fitosanitario";
                require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioAbrirSubsanacion.php';
            }else{
                $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, false, false);
                $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePaisDestino, false);
                $this->construirDetallePaisPuertosTransito($arrayParametros, false);
                $this->construirDetalleExportadoresProductos($arrayParametros, false);
                $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, false);
                $this->accion = "Certificado Fitosanitario";
                require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioAbrirCertificado.php';
            }
            
        }
        
    }
    
    /**
     * Método para borrar un registro en la base de datos - CertificadoFitosanitario
     */
    public function borrar()
    {
        $this->lNegocioCertificadoFitosanitario->borrar($_POST['elementos']);
    }
    
    /**
     * Construye el código HTML para desplegar la lista de - CertificadoFitosanitario
     */
    public function tablaHtmlCertificadoFitosanitario($tabla) {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                
                $estadoCertificado = "";
                
                switch($fila['estado_certificado']){
                    
                    case 'ConfirmarInspeccion':
                        $estadoCertificado = "Confirmar inspección";
                    break;
                    case 'documental':
                        $estadoCertificado = "Revisión documental";
                    break;
                    case 'inspeccion':
                    $estadoCertificado = "Inspección";
                        break;
                    case 'verificacion':
                        $estadoCertificado = "Verificación";
                    break;
                    case 'pago':
                        $estadoCertificado = "Pago";
                    break;
                    case 'generarOrden':
                        $estadoCertificado = "Por generar orden";
                    break;
                    case 'PorReemplazar':
                        $estadoCertificado = "Por reemplazar";
                    break;
                    default:
                        $estadoCertificado = $fila['estado_certificado'];
                }
                
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_certificado_fitosanitario'] . '"
                                    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'CertificadoFitosanitario/CertificadoFitosanitario"
                                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                                    data-destino="detalleItem">
                                    <td>' . ++$contador . '</td>
                                    <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
                                    <td>' . $fila['nombre_pais_destino'] . '</td>
                                    <td>' . date('Y-m-d', strtotime($fila['fecha_creacion_certificado'])) . '</td>
                                    <td>' . $estadoCertificado . '</td>
                                    </tr>');
            }
        }
    }
    
    /**
     * Método para reimprimir un certificado fitosanitario
     */
    public function reimpresion()
    {
        echo "XXXX";
        $solicitudesSeleccionadas = $_POST['elementos'];
        
        if(!empty($solicitudesSeleccionadas)){
            
            $this->procesoSolicitud = "reimpresion";
            $this->accion = "Reimpresión Certificado Fitosanitario";
            
            $arrayParametros = array('id_certificado_fitosanitario' => $_POST["elementos"]);
            
            $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($arrayParametros['id_certificado_fitosanitario']);
            $estadoCertificado = $this->modeloCertificadoFitosanitario->getEstadoCertificado();
            
            if($estadoCertificado == "Aprobado"){
            
                $idPaisDestino = $this->modeloCertificadoFitosanitario->getIdPaisDestino();
                $nombrePaisDestino = $this->modeloCertificadoFitosanitario->getNombrePaisDestino();
                
                $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, true, true);
                $this->construirDetallePuertoPaisDestinoReimpresion($arrayParametros, $idPaisDestino, $nombrePaisDestino);
                $this->construirDetallePaisPuertosTransitoReimpresion($arrayParametros);
                $this->construirDetalleExportadoresProductosReimpresion($arrayParametros, false);
            
            }
            
        }
        
        require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioVistaReimpresion.php';
        
    }
    
    /**
     * Método para anular un certificado fitosanitario
     */
    public function desestimiento()
    {
        
        $solicitudesSeleccionadas = $_POST['elementos'];
        
        if(!empty($solicitudesSeleccionadas)){
            
            $this->accion = "Desestimiento Certificado Fitosanitario";
            
            $arrayParametros = array('id_certificado_fitosanitario' => $_POST["elementos"]);
            
            $this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($arrayParametros['id_certificado_fitosanitario']);
            $estadoCertificado = $this->modeloCertificadoFitosanitario->getEstadoCertificado();
            $esReemplazo = $this->modeloCertificadoFitosanitario->getEsReemplazo();
            
            $arrayParametrosAdjuntos = array('id_certificado_fitosanitario' => $_POST["elementos"]
                                              , 'estado_certificado' => $estadoCertificado
                                            );
            
            if($estadoCertificado != "Anulado"){
            
                $nombrePaisDestino = $this->modeloCertificadoFitosanitario->getNombrePaisDestino();
                
                if($esReemplazo == "Si"){
                    $idCertificadoReemplazo = $this->modeloCertificadoFitosanitario->getIdCertificadoReemplazo();
                    $motivoReemplazo = $this->modeloCertificadoFitosanitario->getMotivoReemplazo();
                    $arrayParametrosAnulaReemplaza = array('id_certificado_reemplazo' => $idCertificadoReemplazo,
                        'motivo_reemplazo' => $motivoReemplazo
                    );
                    $this->construirDetalleAnulaReemplaza($arrayParametrosAnulaReemplaza);
                }
                
                $this->construirDatosGeneralesCertificadoFitosanitario($arrayParametros, false, false);
                $this->construirDetallePuertoPaisDestino($arrayParametros, $nombrePaisDestino, false);
                $this->construirDetallePaisPuertosTransito($arrayParametros, false);
                $this->construirDetalleExportadoresProductos($arrayParametros, false);
                $this->construirDetalleDocumentosAdjuntos($arrayParametrosAdjuntos, false);
            
            }
        }
        
        require APP . 'CertificadoFitosanitario/vistas/formularioCertificadoFitosanitarioDesestimiento.php';
        
    }
    
    /**
     * Método para guardar desestimiento cerificado fitosanitario
     */
    public function guardarDesestimiento()
    {          
        
        $proceso = $this->lNegocioCertificadoFitosanitario->guardarEstadoCertificadoFitosanitarioDesestimiento($_POST);
        
        if ($proceso) {
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
        
    }
    
    /**
     * Método para subsanr cerificado fitosanitario
     */
    public function guardarSubsanacion()
    {
        
        $proceso = $this->lNegocioCertificadoFitosanitario->guardarSubsanacion($_POST);       
        
        if ($proceso) {
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
        
    }
    
    /**
     * Método para obtener los puertos de acuerdo un país seleccionado
     * */
    public function buscarPuertosPorIdPais(){
        
        $idLocalizacion = $_POST["idLocalizacion"];
        $tipoValor = $_POST["tipoValor"];
        
        $arrayParametros = array(
            'idLocalizacion' => $idLocalizacion,
            'tipoValor' => $tipoValor
        );
        //print_r($arrayParametros);
        $puertos = $this->lNegocioCertificadoFitosanitario->obtenerPuertosPorIdPaisPorIdProvincia($arrayParametros);
        
        $comboPuertos = "";
        $comboPuertos .= '<option value="">Seleccionar....</option>';
        
        foreach ($puertos as $item)
        {
            $comboPuertos .= '<option value="' . $item->id_puerto . '" data-nombrepuerto="' . $item->nombre_puerto . '">' . $item->nombre_puerto . ' - ' . $item->codigo_puerto . '</option>';
        }
        
        echo $comboPuertos;
        exit();
    }
	
	/**
     * Método para obtener los puertos de acuerdo un medio de trasnporte seleccionado
     * */
    public function buscarPuertosPorNombreMedioTransporte(){
        
        $idLocalizacion = $_POST["idLocalizacion"];
        $nombreMedioTrasporte = $_POST["nombreMedioTrasporte"];
        
        switch($nombreMedioTrasporte) {
            case 'Aéreo':
            case 'Aerial':
                $nombreMedioTrasporte = "Aéreo";
            break;
            case 'Marítimo':
            case 'Maritime':
                $nombreMedioTrasporte = "Marítimo";
            break;
            case 'Terrestre':
            case 'Land':
                $nombreMedioTrasporte = "Terrestre";
            break;
        }       
        
        $arrayParametros = array(
            'idLocalizacion' => $idLocalizacion,
            'nombreMedioTrasporte' => $nombreMedioTrasporte
        );
        //print_r($arrayParametros);
        $puertos = $this->lNegocioCertificadoFitosanitario->obtenerPuertosPorNombreMedioTrasporte($arrayParametros);
        
        $comboPuertos = "";
        $comboPuertos .= '<option value="">Seleccionar....</option>';
        
        foreach ($puertos as $item)
        {
            $comboPuertos .= '<option value="' . $item->id_puerto . '" data-nombrepuerto="' . $item->nombre_puerto . '" >' . $item->nombre_puerto . ' - ' . $item->codigo_puerto . '</option>';
        }
        
        echo $comboPuertos;
        exit();
    }
	    
    /**
     * Combo de Forma de pago
     *
     * @param
     * $respuesta
     * @return string
     */
    public function cargarFormaPago(){
        
        $this->comboFormaPago = '<option value="">Seleccionar....</option>
    	<option value="efectivo">Comprobante de depósito</option>
    	<option value="saldo">Saldo</option>';        
        
    }
    
    /**
     * Método para obtener
     * localización de Ecuador
     * */
    public function buscarDatosLocalizacionEcuador(){
        
        $datosEcuador = $this->lNegocioCertificadoFitosanitario->obtenerLocalizacionEcuador();
        
        if(isset($datosEcuador->current()->id_localizacion)){
            
            echo json_encode(array('idLocalizacion' => $datosEcuador->current()->id_localizacion,
                'codigoLocalizacion' => $datosEcuador->current()->codigo,
                'nombreLocalizacion' => $datosEcuador->current()->nombre,
                'codigoNombreLocalizacion' => $datosEcuador->current()->codigo_vue,
                'nombreLocalizacionIngles' =>  $datosEcuador->current()->nombre_ingles
            ));
            
        }
        
    }
    
    /**
     * Método para obtener los puertos de acuerdo un país seleccionado
     * */
    public function buscarDatosOperadorPorIdentificador(){
        
        $identificadorExportador = $_POST["identificadorExportador"];
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorExportador
        );
        
        $validacion = "";
        $mensaje = "";
        
        $datosOperador = $this->lNegocioExportadoresProductos->obtenerDatosOperadorPorIdentificador($arrayParametros);
        
        if(isset($datosOperador->current()->identificador_operador)){
            $validacion = 'Exito';            
            echo json_encode(array('mensaje' => $mensaje,
                'identificadorOperador' => $datosOperador->current()->identificador_operador,
                'nombreOperador' => $datosOperador->current()->nombre_operador,
                'direccionOperador' => $datosOperador->current()->direccion_operador,
                'validacion' => $validacion));
        }else{
            $validacion = 'Fallo';
            $mensaje = 'El operador no se encuentra registrado en Agrocalidad.';
            echo json_encode(array('mensaje' => $mensaje,'validacion' => $validacion));
        }
        
    }
    
    /**
     * Método para obtener los tipos de productos
     * que posee registrado el operador por identificador y tipo solicitud
     * */
    public function buscarTipoProductoPorOperadorPorTipoSolicitud(){
        
        $identificadorExportador = $_POST["identificadorExportador"];
        $tipoSolicitud = $_POST["tipoSolicitud"];
        $bandera = 0;
        $validacion = "";
        $mensaje = "";
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorExportador,
            'tipoSolicitud' => $tipoSolicitud
        );
        
        $comboTipoProducto = "";
        $comboTipoProducto .= '<option value="">Seleccionar....</option>';

        $tiposProducto = $this->lNegocioExportadoresProductos->obtenerTipoProductoPorOperadorPorTipoSolicitud($arrayParametros);

        foreach ($tiposProducto as $item)
        {
            $bandera = 1;
            $comboTipoProducto .= '<option value="' . $item->id_tipo_producto . '" >' . $item->nombre_tipo_producto . '</option>';
        }        
        
        if($bandera){
            $validacion = 'Exito';
        }else{
            $validacion = 'Fallo';
            $mensaje = 'El operador no posee productos habilitados para el tipo de solicitud seleccionada.';
        }
        
        echo json_encode(array('validacion' => $validacion, 'mensaje' => $mensaje,'resultado' => $comboTipoProducto));
               
    }
    
    /**
     * Método para obtener los subtipos de productos
     * por idTipoProducto por tipo solicitud
     * */
    public function buscarSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud(){
        
        $identificadorExportador = $_POST["identificadorExportador"];
        $idTipoProducto = $_POST["idTipoProducto"];
        $tipoSolicitud = $_POST["tipoSolicitud"];
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorExportador,
            'idTipoProducto' => $idTipoProducto,
            'tipoSolicitud' => $tipoSolicitud
        );

        $subtiposProducto = $this->lNegocioExportadoresProductos->obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud($arrayParametros);
        
        $comboSubtipoProducto = "";
        $comboSubtipoProducto .= '<option value="">Seleccionar....</option>';
        
        foreach ($subtiposProducto as $item)
        {
            $comboSubtipoProducto .= '<option value="' . $item->id_subtipo_producto . '" >' . $item->nombre_subtipo_producto . '</option>';
        }
        
        echo $comboSubtipoProducto;
        exit();
        
    }
    
    /**
     * Método para obtener los  productos
     * por idSubtipoProducto por tipo solicitud
     * */
    public function buscarProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais(){
        
        $identificadorExportador = $_POST["identificadorExportador"];
        $idSubtipoProducto = $_POST["idSubtipoProducto"];
        $tipoSolicitud = $_POST["tipoSolicitud"];
        $idLocalizacion = $_POST["idLocalizacion"];
        $bandera = 0;
        $validacion = "";
        $mensaje = "";
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorExportador,
            'idSubtipoProducto' => $idSubtipoProducto,
            'tipoSolicitud' => $tipoSolicitud,
            'idLocalizacion' => $idLocalizacion
        );
        
        //Verifica que el producto pertenezca a programa y posea requisitos asignados para el pais de destino
        $productosRequisitos = $this->lNegocioExportadoresProductos->obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais($arrayParametros);
        
        $comboProducto = "";
        $comboProducto .= '<option value="">Seleccionar....</option>';
        
        foreach ($productosRequisitos as $item)
        {
            $bandera = 1;  
            $comboProducto .= '<option value="' . $item->id_producto . '" data-nombreProducto="' . $item->nombre_comun . '" data-partidaArancelaria="' . $item->partida_arancelaria . '" data-clasificacion="' . $item->clasificacion . '">' . $item->nombre_comun . '</option>';
        }        
        
        if($bandera){
            $validacion = 'Exito';
        }else{
            $validacion = 'Fallo';
            $mensaje = 'El operador no posee productos con requisitos de comercialización asignados.';            
        }
        
        echo json_encode(array('validacion' => $validacion, 'mensaje' => $mensaje, 'resultado' => $comboProducto));
        
    }
    
    /**
     * Método para obtener los  centros de acopio por exportador
     * con validacion de requisitos y protocolos
     * */
    public function buscarCentroAcopioExportadorProducto(){
        
        $banderaProveedor = true;
        $tipoCentroAcopio = $_POST["tipoCentroAcopio"];
        $identificadorExportador = $_POST["identificadorExportador"];
        $identificadorProveedor = $_POST["identificadorProveedor"];
        $idProducto = $_POST["idProducto"];
        $idLocalizacion = $_POST["idLocalizacion"];
        $tipoSolicitud = $_POST["tipoSolicitud"];
        $validacion = "";
        $mensaje = "";
        
        $arrayParametros = array(
            'tipoCentroAcopio' => $tipoCentroAcopio,
            'identificadorExportador' => $identificadorExportador,
            'identificadorOperador' => $identificadorProveedor,
            'idProducto' => $idProducto,
            'idLocalizacion' => $idLocalizacion,
            'tipoSolicitud' => $tipoSolicitud
        );
                
        $comboCentrosAcopio = "";
        $comboCentrosAcopio .= '<option value="">Seleccionar....</option>';
        
        if($tipoCentroAcopio == "proveedor"){
            
            $proveedores = $this->lNegocioExportadoresProductos->obtenerProveedoresPorExportador($arrayParametros);
            
            if(!isset($proveedores->current()->id_proveedor)){
                $banderaProveedor = false;
            }
            
        }
        
        if($banderaProveedor){
        
            $protocolos = $this->lNegocioExportadoresProductos->validarProtocoloPorProductoPorPais($arrayParametros);
            
            if(isset($protocolos->current()->protocolo_producto_pais)){
                
                $arrayParametros += ['protocoloProductoPais' => $protocolos->current()->protocolo_producto_pais];
                
                $areasProducto = $this->lNegocioExportadoresProductos->obtenerAreasProductosPorOperadorPorProducto($arrayParametros);
                
                foreach ($areasProducto as $itemAreasProducto) {
                    
                    $protocoloProductoPais = explode(",", $arrayParametros['protocoloProductoPais']);
                    
                    $protocoloAreasAsignados = $this->lNegocioExportadoresProductos->obtenerProtocolosAreasAsignados($itemAreasProducto->id_area, $itemAreasProducto->id_tipo_operacion);
                    
                    if(isset($protocoloAreasAsignados->current()->protocolo_area)){
                        
                        $protocoloAreaAsignado = explode(",", $protocoloAreasAsignados->current()->protocolo_area);
                        
                        if (count($protocoloProductoPais) < count($protocoloAreaAsignado)) {
                             //echo "menor";
                            $bandera = false;
                            foreach ($protocoloProductoPais as $value) {
                                
                                if (in_array($value, $protocoloAreaAsignado)) { //
                                    $bandera = true;
                                }
                            }
                            
                            if ($bandera) {
                                $comboCentrosAcopio .= '<option value="' . $itemAreasProducto['codigo_area'] . '" data-idArea="' . $itemAreasProducto['id_area'] . '" data-nombreArea="' . $itemAreasProducto['nombre_area'] . '" data-idProvinciaArea="' .$itemAreasProducto['id_localizacion'] . '" data-nombreProvinciaArea="' . $itemAreasProducto['nombre'] . '">' . $itemAreasProducto['codigo_area'] . ' - ' . $itemAreasProducto['nombre_lugar'] . ' (' . $itemAreasProducto['nombre'] .') - ' . $itemAreasProducto['nombre_area'] . '</option>';
                            }
                            
                        } else if (count($protocoloProductoPais) >= count($protocoloAreaAsignado)) {
                             //echo "mayor o igual";
                            if (! array_diff($protocoloProductoPais, $protocoloAreaAsignado)) {                                
                                $comboCentrosAcopio .= '<option value="' . $itemAreasProducto['codigo_area'] . '" data-idArea="' . $itemAreasProducto['id_area'] . '" data-nombreArea="' . $itemAreasProducto['nombre_area'] . '" data-idProvinciaArea="' .$itemAreasProducto['id_localizacion'] . '" data-nombreProvinciaArea="' . $itemAreasProducto['nombre'] . '">' . $itemAreasProducto['codigo_area'] . ' - ' . $itemAreasProducto['nombre_lugar'] . ' (' . $itemAreasProducto['nombre'] .') - ' . $itemAreasProducto['nombre_area'] . '</option>';
                            }
                        }
                        
                    }
                    
                }
                
            }else{        
                //Muestra todas las áreas donde el operador tenga registrado el producto a exportar
                $qAreasProducto = $this->lNegocioExportadoresProductos->obtenerAreasProductosPorOperadorPorProducto($arrayParametros);
                
                foreach ($qAreasProducto as $itemAreasProducto) {
                    $comboCentrosAcopio .= '<option value="' . $itemAreasProducto['codigo_area'] . '" data-idArea="' . $itemAreasProducto['id_area'] . '" data-nombreArea="' . $itemAreasProducto['nombre_area'] . '" data-idProvinciaArea="' .$itemAreasProducto['id_localizacion'] . '" data-nombreProvinciaArea="' . $itemAreasProducto['nombre'] . '">' . $itemAreasProducto['codigo_area'] . ' - ' . $itemAreasProducto['nombre_lugar'] . ' (' . $itemAreasProducto['nombre'] .') - ' . $itemAreasProducto['nombre_area'] . '</option>';
                }
            }
        
            $validacion = 'Exito';
            
        }else{
            $validacion = 'Fallo';
            $mensaje = 'El proveedor ingresado no se encuentra registrado para el exportador.';
        }
        
        echo json_encode(array('validacion' => $validacion, 'mensaje' => $mensaje, 'resultado' => $comboCentrosAcopio));
        //echo $comboCentrosAcopio;
        //exit();
        
    }     
    
    /**
     * Método para obtener los  tipos de productos
     * por de SAnidad Vegetal
     * */
    public function buscarTiposProductosSVPorTipoSolicitud(){
        
        $tipoSolicitud = $_POST["tipoSolicitud"];
        
        $arrayParametros = array(
            'tipoSolicitud' => $tipoSolicitud
        );
        
        //print_r($arrayParametros);
        $busquedaTiposProductos = $this->lNegocioExportadoresProductos->obtenerTiposProductosSVPorTipoSolicitud($arrayParametros);
        
        $comboBusquedaTipoProducto = "";
        $comboBusquedaTipoProducto .= '<option value="">Seleccionar....</option>';
        
        foreach ($busquedaTiposProductos as $item)
        {
            $comboBusquedaTipoProducto .= '<option value="' . $item->id_tipo_producto . '">' . $item->nombre_tipo_producto . '</option>';
        }
        
        echo $comboBusquedaTipoProducto;
        exit();
        
    }
    
    /**
     * Método para obtener los  subtipos de productos
     * por de IdTipoProducto
     * */
    public function buscarSubtiposProductoPorIdTipoProducto(){
        
        $idTipoProducto = $_POST["idTipoProducto"];
        
        $arrayParametros = array(
            'idTipoProducto' => $idTipoProducto
        );
        
        //print_r($arrayParametros);
        $busquedaSubtiposProductos = $this->lNegocioExportadoresProductos->obtenerSubtiposProductoPorIdTipoProducto($arrayParametros);
        
        $comboBusquedaSubtipoProducto = "";
        $comboBusquedaSubtipoProducto .= '<option value="">Seleccionar....</option>';
        
        foreach ($busquedaSubtiposProductos as $item)
        {
            $comboBusquedaSubtipoProducto .= '<option value="' . $item->id_subtipo_producto . '">' . $item->nombre_subtipo_producto . '</option>';
        }
        
        echo $comboBusquedaSubtipoProducto;
        exit();
        
    }
    
    /**
     * Método para obtener los   productos
     * por de IdSubtipoProducto
     * */
    public function buscarProductoPorIdSubipoProducto(){
        
        $idSubtipoProducto = $_POST["idSubtipoProducto"];
        
        $arrayParametros = array(
            'idTipoProducto' => $idSubtipoProducto
        );
        
        //print_r($arrayParametros);
        $busquedaProductos = $this->lNegocioExportadoresProductos->obtenerProductoPorIdSubipoProducto($arrayParametros);
        
        $comboBusquedaProducto = "";
        $comboBusquedaProducto .= '<option value="">Seleccionar....</option>';
        
        foreach ($busquedaProductos as $item)
        {
            $comboBusquedaProducto .= '<option value="' . $item->id_producto . '">' . $item->nombre_producto . '</option>';
        }
        
        echo $comboBusquedaProducto;
        exit();
        
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para certificados fitosanitarios
     */
    public function cargarPanelCertificadosFitosanitarios()
    {        
        
    	$resultadoUsuarioInterno = $this->lNegocioFichaEmpleado->buscarDatosUsuarioContrato($_SESSION['usuario']);

        if(isset($resultadoUsuarioInterno->current()->identificador)){
            $identificadorUsuario = "";
        }else{
            $identificadorUsuario = $_SESSION['usuario'];
        }
        
        $this->panelBusquedaCertificadosFitosanitarios = '<table class="filtro" style="width: 100%; text-align:left;">
                                                            <input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="' . $identificadorUsuario . '" readonly="readonly" >
                                                                
                                                <tbody>
                                                    <tr>
                                                        <th colspan="4">Consulta de Certificados Fitosanitarios:</th>
                                                    </tr>
                                                                
                                					<tr>
                                						<td>Tipo de Solicitud: </td>
                                						<td style="width: 50%;">
                                							<select id="bTipoSolicitud" name="bTipoSolicitud" style="width: 100%;">
                                                				<option value="">Seleccionar....</option>
                                                                <option value="musaceas">Musaceas</option>
                                                                <option value="ornamentales">Ornamentales</option>
                                                                <option value="otros">Otros</option>
                                                            </select>
                                                        </td>
                                                        <td >Estado: </td>
                                						<td style="width: 50%;">
                                							<select id="bEstado" name="bEstado" style="width: 100%;">
                                                				<option value="">Seleccionar....</option>
                                                                <option value="Aprobado">Aprobado</option>
                                                                <option value="Rechazado">Rechazado</option>
                                                                <option value="Subsanacion">Subsanacion</option>
                                                                <option value="ConfirmarInspeccion">Confirmar Inspección</option>
                                                                <option value="pago">Pago</option>
                                                                <option value="Documental">Revisión Documental</option>
                                                                <option value="Inspeccion">Inspeccion</option>
																<option value="Anulado">Desistido</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                						<td>País de Destino: </td>
                                						<td>
                                							<select id="bPaisDestino" name="bPaisDestino" style="width: 100%;">
                                                			     <option value="">Seleccionar....</option>' .
                                                			     $this->comboPaises() .
                                                			     '</select>
                                                        </td>
                                						<td>Tipo de Producto: </td>
                                						<td>
                                    						<select id="bTipoProducto" name="bTipoProducto" style="width: 100%;" disabled>
                                                				<option value="">Seleccionar....</option>
                                                            </select>
                                                        </td>
                                					</tr>
                                                    <tr>
                                						<td>Subtipo de Producto: </td>
                                						<td>
                                							<select id="bSubtipoProducto" name="bSubtipoProducto" style="width: 100%;"disabled>
                                                				<option value="">Seleccionar....</option>
                                                            </select>
                                                        </td>
                                                        <td>Producto: </td>
                                						<td>
                                							<select id="bProducto" name="bProducto" style="width: 100%;"disabled>
                                                				<option value="">Seleccionar....</option>
                                                            </select>
                                                        </td>
                                					</tr>
													<tr>
                                						<td >Número de certificado: </td>
                                						<td colspan="3">
                                							<input id="bNumeroCertificado" type="text" name="bNumeroCertificado" value="" style="width: 100%" maxlength="128">
                                						</td>
                                                    </tr>
                                                    <tr>
                                						<td >F. Inicio: </td>
                                						<td>
                                							<input id="bFechaInicio" type="text" name="bFechaInicio" value="" style="width: 100%" maxlength="128" readonly="readonly">
                                						</td>
                                                        <td >F. Fin: </td>
                                						<td>
                                							<input id="bFechaFin" type="text" name="bFechaFin" value="" style="width: 100%" maxlength="128" readonly="readonly">
                                						</td>
                                					</tr>
                                					<tr>
                                						<td colspan="4" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Método para listar los certificados fitosanitarios
     * por tipo operador e identificador
     */
    public function listarCertificadosFitosanitariosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $identificadorOperador = $_POST["identificadorOperador"];
        $tipoSolicitud = $_POST["tipoSolicitud"];
        $paisDestino = $_POST["paisDestino"];
        $idTipoProducto = $_POST["idTipoProducto"];
        $idSubtipoProducto = $_POST["idSubtipoProducto"];
        $idProducto = $_POST["idProducto"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoCertificado = $_POST["estadoCertificado"];
        $numeroCertificado = $_POST["numeroCertificado"];
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorOperador,
            'tipoSolicitud' => $tipoSolicitud,
            'paisDestino' => $paisDestino,
            'idTipoProducto' => $idTipoProducto,
            'idSubtipoProducto' => $idSubtipoProducto,
            'idProducto' => $idProducto,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'estadoCertificado' => $estadoCertificado,
            'numeroCertificado' => $numeroCertificado
        );
        
        $certificadosFitosanitarios = $this->lNegocioCertificadoFitosanitario->buscarCertificadosFitosanitariosPorFiltro($arrayParametros);
        
        $this->tablaHtmlCertificadoFitosanitario($certificadosFitosanitarios);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Funcion para generar el numero de certificado por operador
     */
    function generarCodigoCertificado($numeroDigitos){
        
        $codigo = str_pad(mt_rand(0, pow(10, $numeroDigitos)-1), $numeroDigitos, '0', STR_PAD_LEFT);
        
        return $codigo . 'P';
        
    }    
    
    /////////////////////////////////////////////////////////////////////////////
    /////////FUNCIONES PARA MOSTRAR INFORMACION DE ACUERDO A LOS ESTADOS/////////
    /////////////////////////////////////////////////////////////////////////////
    

    /**
     * Método para listar los exportadores y productos del certificado fitosanitario
     */
    public function construirIngresoExportadoresProductos($codigoUnidadMedida, $codigoIdioma = null)
    {       
        
        $this->ingresarExportadoresProductos ='<input type="hidden" id="id_certificado_fitosanitario_exportadores_productos" name="id_certificado_fitosanitario_exportadores_productos" value="' . $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario() . '" readonly="readonly" />
                                    	<div data-linea="1">
                                    	<label for="identificador_exportador">Identificador Exportador: </label>
                                    	<input type="text" id="identificador_exportador" name="identificador_exportador" value="' . $this->modeloExportadoresProductos->getIdentificadorExportador() . '"
                                    	placeholder="Ejm: 1715897481" maxlength="13" />
                                    	</div>
                                    	    
                                    	<div data-linea="2">
                                    	<label for="razon_social_exportador">Nombre / Razón Social: </label>
                                    	<input type="text" id="razon_social_exportador" name="razon_social_exportador" value="' . $this->modeloExportadoresProductos->getRazonSocialExportador() . '" readonly="readonly" />
                                    	</div>
                                    	    
                                    	<div data-linea="3">
                                    	<label for="direccion_exportador">Dirección: </label>
                                    	<input type="text" id="direccion_exportador" name="direccion_exportador" value="' . $this->modeloExportadoresProductos->getDireccionExportador() . '" readonly="readonly" />
                                    	</div>
                                    	    
                                    	<hr/>
                                    	    
                                    	<div data-linea="4">
                                    	<label for="id_tipo_producto">Tipo de Producto: </label>
                                    	<select id="id_tipo_producto" name="id_tipo_producto" class="validacion" disabled="disabled">
                                    	<option value="">Seleccionar....</option>
                                    	</select>
                                    	</div>
                                    	    
                                    	<div data-linea="4">
                                    	<label for="id_subtipo_producto">Subtipo de Producto: </label>
                                    	<select id="id_subtipo_producto" name="id_subtipo_producto" class="validacion" disabled="disabled">
                                    	<option value="">Seleccionar....</option>
                                    	</select>
                                    	</div>
                                    	    
                                    	<div data-linea="5">
                                    	<label for="id_producto">Producto: </label>
                                    	<select id="id_producto" name="id_producto" class="validacion" disabled="disabled">
                                    	<option value="">Seleccionar....</option>
                                    	</select>
                                    	</div>
                                    	    
                                    	<div data-linea="5">
                                    	<label for="partida_arancelaria_producto">Partida Arancelaria: </label>
                                    	<input type="text" id="partida_arancelaria_producto" name="partida_arancelaria_producto" value="' . $this->modeloExportadoresProductos->getPartidaArancelariaProducto() . '" readonly="readonly" />
                                    	</div>

                                        <div data-linea="6" id="iCentificacionOrganica">
                                			<label for="certificacion_organica">Certificación orgánica: </label>
                                			<input type="text" id="certificacion_organica" name="certificacion_organica" value="" maxlength="6" />
                                		</div>	
                                    	    
                                    	<div data-linea="7">
                                    	<label for="cantidad_comercial">Cantidad Comercial: </label>
                                    	<input type="text" id="cantidad_comercial" name="cantidad_comercial" value="' . $this->modeloExportadoresProductos->getCantidadComercial() . '"
                                    	placeholder="Ejm: 200" maxlength="8" />
                                    	</div>
                                    	    
                                    	<div data-linea="7">
                                        	<select id="id_unidad_cantidad_comercial" name="id_unidad_cantidad_comercial" class="validacion">
                                            <option value="">Seleccionar....</option>
                                        	' .
                                        	$this->comboUnidadesMedidaCfePorIdioma($codigoIdioma)
                                        	. '
                                            </select>
                                		</div>
                                	    
                                		<div data-linea="8" id="pesoBruto">
                                			<label for="peso_bruto">Peso Bruto: </label>
                                			<input type="text" id="peso_bruto" name="peso_bruto" value="' . $this->modeloExportadoresProductos->getPesoBruto() . '"
                                			placeholder="Ejm: 284" maxlength="8" />
                                		</div>
                                			    
                                		<div data-linea="8" id="idUnidadPesoBruto">
                                			<select id="id_unidad_peso_bruto" name="id_unidad_peso_bruto" class="validacion">
                            				<option value="">Seleccionar....</option>
                                            ' .
                                            $this->comboUnidadesMedidaCfePorCodigoPorIdioma($codigoUnidadMedida, $codigoIdioma)
                                            . '
                                        </select>
                                		</div>
                                			    
                                		<div data-linea="9">
                                			<label for="peso_neto">Peso Neto: </label>
                                			<input type="text" id="peso_neto" name="peso_neto" value="' . $this->modeloExportadoresProductos->getPesoNeto() . '"
                                			placeholder="Ejm: 240" maxlength="8" />
                                		</div>
                                			    
                                		<div data-linea="9">
                                			<select id="id_unidad_peso_neto" name="id_unidad_peso_neto" class="validacion">
                                				<option value="">Seleccionar....</option>
                                            ' .
                                            $this->comboUnidadesMedidaCfePorCodigoPorIdioma($codigoUnidadMedida, $codigoIdioma)
                                            . '
                                            </select>
                                		</div>
                                			    
                                		<hr/>
                                			    
                                		<div data-linea="10" id="cTipoCentroAcopio">
                                			<label>Tipo centro acopio</label>
                                			<select id="tipo_centro_acopio" name="tipo_centro_acopio" class="validacion">
                                				<option value="">Seleccionar....</option>
                                				<option value="propio">Propio</option>
                                				<option value="proveedor">Proveedor</option>
                                        	</select>
                                		</div>
                                			    
                                		<div data-linea="10" id="buscarCentroAcopio">
                                			<input type="text" id="identificador_centro_acopio" name="identificador_centro_acopio" value="">
                                		</div>
                                			    
                                		<div data-linea="11" id="centroAcopio">
                                			<label for="codigo_centro_acopio">Centro de Acopio: </label>
                                			<select id="codigo_centro_acopio" name="codigo_centro_acopio" class="validacion">
                                				<option value="">Seleccionar....</option>
                                        	</select>
                                		</div>
                                			    
                                		<div data-linea="13" id="fechaInspeccion">
                                			<label for="fecha_inspeccion">Fecha de Inspección: </label>
                                			<input type="text" id="fecha_inspeccion" name="fecha_inspeccion" value="' . $this->modeloExportadoresProductos->getFechaInspeccion() .'" readonly="readonly" />
                                		</div>
                                			    
                                		<div data-linea="13" id="horaInspeccion">
                                		<label for="hora_inspeccion">Hora de Inspección: </label>
                                			<input type="time" id="hora_inspeccion" name="hora_inspeccion" value="' . $this->modeloExportadoresProductos->getHoraInspeccion() . '" />
                                		</div>
                                			    
                                		<hr/>
                                			    
                                		<div data-linea="14">
                                			<label for="id_tipo_tratamiento">Tipo de Tratamiento: </label>
                                			<select id="id_tipo_tratamiento" name="id_tipo_tratamiento" class="validacion">
                                            <option value="">Seleccionar....</option>
                                            ' .
                                        	$this->comboTiposTratamientoPorIdioma($codigoIdioma)
                                        	. '
                                            </select>
                                        </div>
                                                    
                                		<div data-linea="14">
                                			<label for="id_tratamiento">Tratamiento: </label>
                                			<select id="id_tratamiento" name="id_tratamiento" class="validacion">
                                            <option value="">Seleccionar....</option>
                                            ' .
                                        	$this->comboTratamientosPorIdioma($codigoIdioma)
                                        	. '
                                            </select>
                                		</div>
                                            	    
                                		<div data-linea="15">
                                			<label for="duracion_tratamiento">Duración: </label>
                                			<input type="text" id="duracion_tratamiento" name="duracion_tratamiento" value="' . $this->modeloExportadoresProductos->getDuracionTratamiento() . '" min="1" />
                                		</div>
                                			    
                                		<div data-linea="15">
                                			<select id="id_unidad_duracion" name="id_unidad_duracion" class="validacion">
                                            <option value="">Seleccionar....</option>
                                            ' .
                                        	$this->comboUnidadesDuracionPorIdioma($codigoIdioma)
                                        	. '
                                            </select>
                                		</div>
                                        		    
                                		<div data-linea="16">
                                			<label for="temperatura_tratamiento">Temperatura: </label>
                                			<input type="text" id="temperatura_tratamiento" name="temperatura_tratamiento" value="' . $this->modeloExportadoresProductos->getTemperaturaTratamiento() .'"
                                			placeholder="Ejm: 34" maxlength="8" />
                                		</div>
                                			    
                                		<div data-linea="16">
                                			<select id="id_unidad_temperatura" name="id_unidad_temperatura" class="validacion">
                                            <option value="">Seleccionar....</option>
                                            ' .
                                        	$this->comboUnidadesTemperaturaPorIdioma($codigoIdioma)
                                        	. '
                                            </select>
                                		</div>
                                			    
                                		<div data-linea="17">
                                			<label for="fecha_tratamiento">Fecha de tratamiento: </label>
                                			<input type="text" id="fecha_tratamiento" name="fecha_tratamiento" value="' . $this->modeloExportadoresProductos->getFechaTratamiento() .'"
                                			placeholder="Ejm: 2021-01-01" readonly="readonly" />
                                		</div>
                                			    
                                		<div data-linea="17">
                                			<label for="producto_quimico">Producto químico: </label>
                                			<input type="text" id="producto_quimico" name="producto_quimico" value="' . $this->modeloExportadoresProductos->getProductoQuimico() . '"
                                			placeholder="Ingrese el nombre del producto químico" maxlength="64" />
                                		</div>

                                        <div data-linea="18">
                                			<label for="nuevo">Concentración: </label>
                                			<input type="text" id="concentracion_tratamiento" name="concentracion_tratamiento" value=""
                                			placeholder="Ejm: 23" maxlength="64" />
                                		</div>
                                			    
                                		<div data-linea="18">
                                			<select id="id_unidad_concentracion" name="id_unidad_concentracion" class="validacion">
                                            <option value="">Seleccionar....</option>
                                            ' .
                                        	$this->comboConcentracionesTratamientoPorIdioma($codigoIdioma)
                                        	. '
                                            </select>	
                                		</div>
                                			    
                                		<hr/>
                                			    
                                		<div data-linea="19">
                                			<button type="submit" class="mas" id="agregarExportadoresProductos">Agregar</button>
                                		</div>';
                                    	
                                    	$this->ingresarExportadoresProductos;
                                    	
    }
    
    
    //////////////////////////////////////////////////////////////////
    /////////FUNCIONES PARA REIMPRESION REEMPLAZO CERTIFICADO/////////
    //////////////////////////////////////////////////////////////////
    
    /**
     * Método para listar puertos de destino
     * del certificado fitosanitario en reimpresion
     */
    public function construirDetallePuertoPaisDestinoReimpresion($arrayParametros, $idPaisDestino, $nombrePaisDestino)
    {
        
        $paisPuertosDestinoReimpresion = $this->lNegocioPuertosDestino->buscarLista($arrayParametros);
                
        $this->paisPuertosDestinoReimpresion = '<table id="detallePuertoPaisDestino" style="width: 100%">
    			<thead>
    				<tr>
    					<th>#</th>
    					<th>País</th>
    					<th>Puerto</th>
	                    <th>Opción</th>
	               </tr>
    			</thead>
    			<tbody>';
        
        $contador = 1;
        
        foreach ($paisPuertosDestinoReimpresion as $item) {
            
            $codigoPuertoPaisDestino = $idPaisDestino . $item['id_puerto_pais_destino'];
            
            $this->paisPuertosDestinoReimpresion .= "<tr id='r_" . $codigoPuertoPaisDestino . "'>
                <td>" . $contador++ .
                "</td>
                <td>" . $nombrePaisDestino .
                "<input name='nPaisDestino[]' value='". $nombrePaisDestino . "' type='hidden'>
                </td>
                <td>" . $item['nombre_puerto_pais_destino'] .
                "<input name='iPuertoPaisDestino[]' value='" . $item['id_puerto_pais_destino'] . "' type='hidden'>
                <input name='nPuertoPaisDestino[]' value='" . $item['nombre_puerto_pais_destino'] . "' type='hidden'>
                </td>
                <td class='borrar'>
                <button type='button' onclick='quitarPuertoPaisDestino(r_" . $codigoPuertoPaisDestino . ")' class='icono'></button>
                </td>
                </tr>";
            
        }
        
        $this->paisPuertosDestinoReimpresion .= '</tbody>
    	              </table>';
        
        $this->paisPuertosDestinoReimpresion;
        
    }
    
    /**
     * Método para listar paises y puertos de trasnsito
     * del certificado fitosanitario
     */
    public function construirDetallePaisPuertosTransitoReimpresion($arrayParametros)
    {
        
        $paisesPuertosTransitoReimpresion = $this->lNegocioPaisesPuertosTransito->buscarLista($arrayParametros);
       
        $this->paisesPuertosTransitoReimpresion = '<table id="detallePaisPuertoTransito" style="width: 100%">
    			<thead>
    				<tr>
    					<th>#</th>
    					<th>País</th>
    					<th>Puerto</th>
                        <th>Medio de Transporte</th>
	                    <th>Opción</th>
	               </tr>
    			</thead>
    			<tbody>';
        
        $contador = 1;
        
        foreach ($paisesPuertosTransitoReimpresion as $item) {
            
            $codigoPaisPuertoTransito = $item['id_pais_transito'] . $item['id_pais_puerto_transito'];
            
            $this->paisesPuertosTransitoReimpresion .= "<tr id='r_" . $codigoPaisPuertoTransito . "'>
	        <td>" . $contador++ .
	        "</td>
	        <td>" . $item['nombre_pais_transito'] .
	        "<input name='iPaisTransito[]' value='" . $item['id_pais_transito'] . "' type='hidden'>
	        <input name='nPaisTransito[]' value='" . $item['nombre_pais_transito'] . "' type='hidden'>
	        </td>
	        <td>" . $item['nombre_puerto_transito'] .
	        "<input name='iPuertoTransito[]' value='" . $item['id_puerto_transito'] . "' type='hidden'>
	        <input name='nPuertoTransito[]' value='" . $item['nombre_puerto_transito'] . "' type='hidden'>
	        </td>
            <td>" . $item['nombre_medio_transporte_transito'] .
            "<input name='iMedioTransporteTransito[]' value='" . $item['id_medio_transporte_transito'] ."' type='hidden'>
	        <input name='nMedioTransporteTransito[]' value='" . $item['nombre_medio_transporte_transito'] ."' type='hidden'>
	        </td>
	        <td class='borrar'>
	        <button type='button' onclick='quitarPaisPuertoTransito(r_" . $codigoPaisPuertoTransito . ")' class='icono'></button>
	        </td>
	        </tr>";
            
        }
        
        $this->paisesPuertosTransitoReimpresion .= '</tbody>
    	              </table>';
        
        $this->paisesPuertosTransitoReimpresion;
    }
    
    /**
     * Método para listar los exportadores y productos
     * del certificado fitosanitario
     */
    public function construirDetalleExportadoresProductosReimpresion($arrayParametros)
    {
        
        $exportadoresProductosReimpresion = $this->lNegocioExportadoresProductos->buscarLista($arrayParametros);
               
        $this->exportadoresProductosReimpresion = '<table id="detalleExportadoresProductos" style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Identificador</th>
					<th>Razón Social</th>
					<th>Producto</th>
                    <th>Código Orgánico</th>
					<th>Cantidad Comercial</th>
					<th>Peso Bruto</th>
					<th>Peso Neto</th>
					<th>Inspección</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>';
        
        $contador = 1;
        
        foreach ($exportadoresProductosReimpresion as $item) {
            
            $codigoExportadorProducto = $item['identificador_exportador'] . $item['id_producto'] . str_replace ( ".", '', $item['codigo_centro_acopio']);
            
            $this->exportadoresProductosReimpresion .= "<tr id='r_" . $codigoExportadorProducto . "'>
	        <td>" . $contador++ .
	        "</td>
			<td>" . $item['identificador_exportador'] .
			"<input name='iIdentificadorExportador[]' value='" . $item['identificador_exportador'] . "' type='hidden'>
			</td>
			<td>" . $item['razon_social_exportador'] .
			"<input name='iRazonSocialExportador[]' value='" . $item['razon_social_exportador'] . "' type='hidden'>
			</td>
			<td>" . $item['nombre_producto'] .
			"<input name='iProducto[]' value='" . $item['id_producto'] . "' type='hidden'>
			<input name='nProducto[]' value='" . $item['nombre_producto'] . "' type='hidden'>
			</td>
            <td>";
            if($item['certificacion_organica'] != ""){
                $this->exportadoresProductosReimpresion .= "<input name='iCertificacionOrganica[]' value='" . $item['certificacion_organica'] . "' type='hidden'>";
            }else{
                $this->exportadoresProductosReimpresion .= "N/A";
            }
            $this->exportadoresProductosReimpresion .= "</td>
            <td>
            <input name='iCantidadComercial[]' value='" . $item['cantidad_comercial'] . "' type='text' size='2' id='iCantidadComercial" . $item['id_exportador_producto'] . "' onchange='verificarCantidades(" . $item['id_exportador_producto'] . ", this.value, this.id, this.name)'>
			<input name='iUnidadCantidadComercial[]' value='" . $item['id_unidad_cantidad_comercial'] . "' type='hidden'>
            <input name='nUnidadCantidadComercial[]' value='" . $item['nombre_unidad_cantidad_comercial'] . "' type='hidden'>
			</td>
			<td>";
            if($item['peso_bruto'] != ""){
                $this->exportadoresProductosReimpresion .= "<input name='iPesoBruto[]' value='" . $item['peso_bruto'] . "' type='text' size='2' id='iPesoBruto" . $item['id_exportador_producto'] . "' onchange='verificarCantidades(" . $item['id_exportador_producto'] . ", this.value, this.id, this.name)'>
                                                    <input name='iUnidadPesoBruto[]' value='" . $item['id_unidad_peso_bruto'] . "' type='hidden'>
                                                    <input name='nUnidadPesoBruto[]' value='" . $item['nombre_unidad_peso_bruto'] . "' type='hidden'>";
            }else{
                $this->exportadoresProductosReimpresion .= "N/A";
            }
            $this->exportadoresProductosReimpresion .= "</td>
			<td>                
            <input name='iPesoNeto[]' value='" . $item['peso_neto'] . "' type='text' size='2' id='iPesoNeto" . $item['id_exportador_producto'] . "' onchange='verificarCantidades(" . $item['id_exportador_producto'] . ", this.value, this.id, this.name)'>
			<input name='iUnidadPesoNeto[]' value='". $item['id_unidad_peso_neto'] . "' type='hidden'>
            <input name='nUnidadPesoNeto[]' value='". $item['nombre_unidad_peso_neto'] . "' type='hidden'>
			</td>
            <td>" . $item['fecha_inspeccion'] .
            "</td>
			<td class='borrar'>
			<button type='button' onclick='quitarExportadoresProductos(r_" . $codigoExportadorProducto. ")' class='icono'></button>
            <input name='iDireccionExportador[]' value='" . $item['direccion_exportador'] . "' type='hidden'>
			<input name='iTipoProducto[]' value='" . $item['id_tipo_producto'] . "' type='hidden'>
			<input name='nTipoProducto[]' value='" . $item['nombre_tipo_producto'] . "' type='hidden'>
			<input name='iSubtipoProducto[]' value='" . $item['id_subtipo_producto'] . "' type='hidden'>
            <input name='nSubtipoProducto[]' value='" . $item['nombre_subtipo_producto'] . "' type='hidden'>
            <input name='iPartidaArancelariaProducto[]' value='" . $item['partida_arancelaria_producto'] . "' type='hidden'>
            <input name='iCodigoCentroAcopio[]' value='" . $item['codigo_centro_acopio'] . "' type='hidden'>
            <input name='iFechaInspeccion[]' value='" . $item['fecha_inspeccion'] . "' type='hidden'>
            <input name='iHoraInspeccion[]' value='" . $item['hora_inspeccion'] . "' type='hidden'>
            <input name='iTipoTratamiento[]' value='" . $item['id_tipo_tratamiento'] . "' type='hidden'>
            <input name='nTipoTratamiento[]' value='" . $item['nombre_tipo_tratamiento'] . "' type='hidden'>
            <input name='iTratamiento[]' value='" . $item['id_tratamiento'] . "' type='hidden'>
            <input name='nTratamiento[]' value='" . $item['nombre_tratamiento'] . "' type='hidden'>
            <input name='iDuracionTratamiento[]' value='" . $item['duracion_tratamiento'] . "' type='hidden'>
            <input name='iUnidadDuracion[]' value='" . $item['id_unidad_duracion'] . "' type='hidden'>
            <input name='nUnidadDuracion[]' value='" . $item['nombre_unidad_duracion'] . "' type='hidden'>
            <input name='iTemperaturaTratamiento[]' value='" . $item['temperatura_tratamiento'] . "' type='hidden'>
            <input name='iUnidadTemperatura[]' value='" . $item['id_unidad_temperatura'] . "' type='hidden'>
            <input name='nUnidadTemperatura[]' value='" . $item['nombre_unidad_temperatura'] . "' type='hidden'>
            <input name='iFechaTratamiento[]' value='" . $item['fecha_tratamiento'] . "' type='hidden'>
            <input name='iProductoQuimico[]' value='" . $item['producto_quimico'] . "' type='hidden'>
            <input name='iConcentracionTratamiento[]' value='" . $item['concentracion_tratamiento'] . "' type='hidden'>
            <input name='iUnidadConcentracion[]' value='" . $item['id_unidad_concentracion'] . "' type='hidden'>
            <input name='nUnidadConcentracion[]' value='" . $item['nombre_unidad_concentracion'] . "' type='hidden'>
            <input name='iArea[]' value='" . $item['id_area'] . "' type='hidden'>
            <input name='nArea[]' value='" . $item['nombre_area'] . "' type='hidden'>
            <input name='iProvinciaArea[]' value='" . $item['id_provincia_area'] . "' type='hidden'>
            <input name='nProvinciaArea[]' value='" . $item['nombre_provincia_area'] . "' type='hidden'>
            </td>
		    </tr>";
            
        }
        
        $this->exportadoresProductosReimpresion .= '</tbody>
    	              </table>';
        
        $this->exportadoresProductosReimpresion;
    }
     
    /**
     * Método para listar las inspecciones registradas
     */
    public function construirDetalleSolicitudes()
    {
        $idSolicitud = $_POST['id_solicitud'];
        $estado = '';
        $tipo = '';
        $columna = '';
        $imprimirCertificado = false;
        
        switch ($_POST['proceso']){
            case 'MasivoDocumental':
                $estado = "'Documental'";
                $tipo = "'ornamentales'";
                break;
                
            case 'MasivoImpresion':
                $estado = "'Aprobado'";
                $tipo = "'ornamentales', 'musaceas', 'otros'";
                $columna = '<th>Certificado generado</th>';
                $imprimirCertificado = true;
                break;
                
            default:
                $estado = "'Aprobado'";
                $tipo = "'ornamentales', 'musaceas', 'otros'";
                break;
        }
        
        $arrayParametros = array(
            'id_solicitud' => $idSolicitud,
            'estado_certificado' => $estado,
            'tipo_certificado' => $tipo
        );
        
        $this->listaDetalles = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo</th>
                                                <th>CFE</th>
                                                <th>Solicitante</th>
                                                <th>País Destino</th>
												'.$columna.'
                                            </tr>
                                        </thead>';
        
        $listaDestalles = $this->lNegocioCertificadoFitosanitario->buscarSolicitudesXTipoXFaseRevision($arrayParametros);
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '<tbody>
                    <tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['tipo_certificado'] != '' ? $fila['tipo_certificado'] : '') . '</td>
                        <td>' . ($fila['codigo_certificado'] != '' ? $fila['codigo_certificado'] : ''). '</td>
                        <td>' . ($fila['identificador_solicitante'] != '' ? $fila['identificador_solicitante'].' - '.$fila['razon_social'] : '') . '</td>
                        <td>' . ($fila['nombre_pais_destino'] != '' ? $fila['nombre_pais_destino'] : ''). '</td>';
            if($imprimirCertificado){
            	$this->listaDetalles .= '<td>' .$fila['certificado']. '</td>';
            }
            
            $this->listaDetalles .= '</tr>
                					</tbody>';
        }
        
        $this->listaDetalles .= '</table>';
       
        echo $this->listaDetalles;
    }
    
    /**
     * Método para listar las inspecciones registradas
     */
    public function construirDetalleSolicitudesMasivo()
    {
        $idSolicitud = $_POST['id_solicitud'];
        $estado = "'Aprobado'";
        $tipo = "'ornamentales', 'musaceas', 'otros'";
        
        $arrayParametros = array(
            'id_solicitud' => $idSolicitud,
            'estado_certificado' => $estado,
            'tipo_certificado' => $tipo
        );
        
        $this->listaDetalles = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo</th>
                                                <th>CFE</th>
                                                <th>Solicitante</th>
                                                <th>País Destino</th>
                                                <th>Certificado</th>
                                            </tr>
                                        </thead>';
        
        $listaDestalles = $this->lNegocioCertificadoFitosanitario->buscarSolicitudesXTipoXFaseRevisionConCertificado($arrayParametros);
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '<tbody>
                    <tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['tipo_certificado'] != '' ? $fila['tipo_certificado'] : '') . '</td>
                        <td>' . ($fila['codigo_certificado'] != '' ? $fila['codigo_certificado'] : ''). '</td>
                        <td>' . ($fila['identificador_solicitante'] != '' ? $fila['identificador_solicitante'].' - '.$fila['razon_social'] : '') . '</td>
                        <td>' . ($fila['nombre_pais_destino'] != '' ? $fila['nombre_pais_destino'] : ''). '</td>
                        <td>' . ($fila['certificado'] != '' ? '<a href="'.$fila['certificado'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado Anexo Fitosanitario</a>' : '<span class="alerta">No se dispone de un documento generado</span>') . '</td>                        
                    </tr>
                </tbody>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
    
    /**
     * Método para listar las inspecciones registradas
     */
    /*public function construirDetalleSolicitudesEditable()
    {
        $idSolicitud = $_POST['id_solicitud'];
        $estado = '';
        $tipo = '';
        $imprimirCertificado = false;
        $banderaImprimir = true;
        
        switch ($_POST['proceso']){
            case 'MasivoDocumental':
                $estado = "'Documental'";
                $tipo = "'ornamentales'";
                break;
                
            case 'MasivoImpresion':
                $estado = "'Aprobado'";
                $tipo = "'ornamentales', 'musaceas', 'otros'";
                $imprimirCertificado = true;
                break;
                
            default:
                $estado = "'Aprobado'";
                $tipo = "'ornamentales', 'musaceas', 'otros'";
                break;
        }
        
        $arrayParametros = array(
            'id_solicitud' => $idSolicitud,
            'estado_certificado' => $estado,
            'tipo_certificado' => $tipo
        );
        
        $this->listaDetalles = '<table table id="tbItems" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo</th>
                                                <th>CFE</th>
                                                <th>Solicitante</th>
                                                <th>País Destino</th>
                                                <th></th>
                                            </tr>
                                        </thead>';
        
        $listaDestalles = $this->lNegocioCertificadoFitosanitario->buscarSolicitudesXTipoXFaseRevision($arrayParametros);
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '<tbody>
                    <tr id="S_'.$fila['id_certificado_fitosanitario'].'">
                        <td>' . $i++. '
                            <input id="iSolicitud" name="iSolicitud[]" value="'.$fila['id_certificado_fitosanitario'].'" type="hidden">
                        </td>
                        <td>' . ($fila['tipo_certificado'] != '' ? $fila['tipo_certificado'] : '') . '
                            <input id="iTipoSolicitud" name="iTipoSolicitud[]" value="'.$fila['tipo_certificado'].'" type="hidden">
                        </td>
                        <td>' . ($fila['codigo_certificado'] != '' ? $fila['codigo_certificado'] : ''). '
                            <input id="iCodigoCertificado" name="iCodigoCertificado[]" value="'.$fila['codigo_certificado'].'" type="hidden">
                            <input id="iEsReemplazo" name="iEsReemplazo[]" value="'.$fila['es_reemplazo'].'" type="hidden">
                            <input id="iIdCertificadoReemplazo" name="iIdCertificadoReemplazo[]" value="'.$fila['id_certificado_reemplazo'].'" type="hidden">
                        </td>
                        <td>' . ($fila['identificador_solicitante'] != '' ? $fila['identificador_solicitante'].' - '.$fila['razon_social'] : '') . '
                            <input id="iFormaPago" name="iFormaPago[]" value="'.$fila['forma_pago'].'" type="hidden">
                        </td>
                        <td>' . ($fila['nombre_pais_destino'] != '' ? $fila['nombre_pais_destino'] : ''). '</td>
                        <td> <button type="button" onclick="quitarSolicitud(S_'.$fila['id_certificado_fitosanitario'].')" class="menos">Quitar</button> </td>
                    </tr>
                </tbody>';
            
            if($imprimirCertificado && $fila['certificado'] == 'No'){
            		$banderaImprimir = false;
            }
        }
        
        $this->listaDetalles .= '</table>';
        
        if($banderaImprimir){
        	$this->procesoImpresion = '<button type="submit" class="certificado">Imprimir Certificado</button>';
        }else{
        	$this->procesoImpresion = '<div class="nota">Por favor verificar que todos los certificados se encuentren generados para proceder con la impresión masiva.</div>';
        }
        
        echo json_encode(array('estado' => 'EXITO', 'mensaje' => $this->listaDetalles, 'boton'=> $this->procesoImpresion));
    }*/
    
    /**
     * Método para carga masiva de certificados
     * */
    public function cargaMasiva(){
        $this->accion = "Carga masiva de Certificados Fitosanitarios";
        require APP . 'CertificadoFitosanitario/vistas/formularioCargaMasivaCertificado.php';
    }
    
    /**
     * Método para obtener ruta de archivo excel
     * */
    public function cargarDocumentoMasivo(){
        //$this->lNegocioCertificadoFitosanitario->leerArchivoExcelCertificados($_POST);
        $this->lNegocioCertificadoFitosanitario->leerArchivoExcelCertificadoMasivo($_POST);
    }
        
    /**
     * Método para listar los países de acuerdo al idioma
     * */
    public function buscarPaisesPorIdioma(){
        echo $this->comboPaisesPorIdioma($_POST['idioma']);
    } 
    
    /**
     * Método para listar medios de transporte de acuerdo al idioma
     * */
    public function buscarMediosTransportePorIdioma(){
        echo $this->comboMediosTransportePorIdioma($_POST['idioma']);
    }
    
    /**
     * Método para listar unidades de medida de acuerdo al idioma
     * */
    public function buscarUnidadesMedidaPorIdioma(){
        echo $this->comboUnidadesMedidaCfePorIdioma($_POST['idioma']);
    }
    
    /**
     * Método para listar unidades de medida de acuerdo al código e idioma
     * */
    public function buscarUnidadesMedidaPorCodigoPorIdioma(){
        echo $this->comboUnidadesMedidaCfePorCodigoPorIdioma($_POST['codigoUnidadMedida'], $_POST['idioma']);
    }
    
    /**
     * Método para listar tipos de tratamiento de acuerdo al idioma
     * */
    public function buscarTiposTratamientoPorIdioma(){
        echo $this->comboTiposTratamientoPorIdioma($_POST['idioma']);
    }    
    
    /**
     * Método para listar tratamientos de acuerdo al idioma
     * */
    public function buscarTratamientosPorIdioma(){
        echo $this->comboTratamientosPorIdioma($_POST['idioma']);
    }  
    
    /**
     * Método para unidades de duracion de acuerdo al idioma
     * */
    public function buscarUnidadesDuracionPorIdioma(){
        echo $this->comboUnidadesDuracionPorIdioma($_POST['idioma']);
    }  
    
    /**
     * Método para listar unidades de temperatura de acuerdo al idioma
     * */
    public function buscarUnidadesTemperaturaPorIdioma(){
        echo $this->comboUnidadesTemperaturaPorIdioma($_POST['idioma']);
    }  
        
    /**
     * Método para listar las concentraciones de tratamiento de acuerdo al idioma
     * */
    public function buscarConcentracionesTratamientoPorIdioma(){
        echo $this->comboConcentracionesTratamientoPorIdioma($_POST['idioma']);
    } 
    
    /**
     * Método de inicio del controlador
     */
    public function emisionEphyto()
    {
        $this->cargarPanelCertificadosFitosanitariosEphyto();
        
        require APP . 'CertificadoFitosanitario/vistas/listaEmisionEphyto.php';
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para certificados fitosanitarios
     */
    public function cargarPanelCertificadosFitosanitariosEphyto()
    {
        
        $this->panelBusquedaCertificadosFitosanitarios = '<table class="filtro" style="width: 100%; text-align:left;">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="4">Consulta de Certificados Fitosanitarios:</th>
                                                    </tr>
                                                                
                                					<tr>                                						
                                                        <td>Estado: </td>
                                						<td colspan="3">
                                							<select id="bEstado" name="bEstado" style="width: 100%;">
                                                				<option value="">Seleccionar....</option>
                                                                <option value="Enviado">Enviado</option>
                                                                <option value="RecibidoDestino">Recibido Destino</option>
                                                                <option value="Error">Error</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                						<td >F. Inicio: </td>
                                						<td>
                                							<input id="bFechaInicio" type="text" name="bFechaInicio" value="" style="width: 100%" maxlength="128" readonly="readonly">
                                						</td>
                                                        <td >F. Fin: </td>
                                						<td>
                                							<input id="bFechaFin" type="text" name="bFechaFin" value="" style="width: 100%" maxlength="128" readonly="readonly">
                                						</td>
                                					</tr>
                                					<tr>
                                						<td colspan="4" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Método para listar los certificados fitosanitarios
     * por estado EPHYTO
     */
    public function listarCertificadosEmisionEphyto()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $estadoEphyto = $_POST["estadoCertificado"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];

        $query = "estado_certificado = 'Aprobado' and estado_ephyto = '" . $estadoEphyto . "' and fecha_creacion_certificado >= '" . $fechaInicio . " 00:00:00' and fecha_creacion_certificado <= '" . $fechaFin . " 24:00:00' order by id_certificado_fitosanitario ASC";
        
        $certificadosFitosanitariosEphyto = $this->lNegocioCertificadoFitosanitario->buscarLista($query);
        
        $this->tablaHtmlCertificadoFitosanitarioEphyto($certificadosFitosanitariosEphyto);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }    
    
    /**
     * Construye el código HTML para desplegar la lista de - CertificadoFitosanitario Ephyto
     */
    public function tablaHtmlCertificadoFitosanitarioEphyto($tabla) {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                
                $estadoEphyto = $fila['estado_ephyto'];
                
                switch ($fila['estado_ephyto']){
                    case 'RecibidoDestino':
                        $estadoEphyto = 'Recibido Destino';
                    break;
                }
                
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_certificado_fitosanitario'] . '"
                                    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'CertificadoFitosanitario/CertificadoFitosanitario"
                                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                                    data-destino="detalleItem">
                                    <td>' . ++$contador . '</td>
                                    <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
                                    <td>' . $fila['nombre_pais_destino'] . '</td>
                                    <td>' . $fila['fecha_creacion_certificado'] . '</td>
                                    <td>' . $estadoEphyto . '</td>
                                    </tr>');
            }
        }
    }
    
    /**
     * Proceso automático para generar certificados XML y envio a HUB
     */
    public function paGenerarXmlWebServicesCertificadosFitosanitario(){
    	
    	$fecha = date("Y-m-d h:m:s");
    	echo Constantes::IN_MSG .'<b>PROCESO AUTOMÁTICO DE GENERACIÓN DE ARCHIVO XML PARA ENVIÓ DE WEB SERVICES A TRAVÉS DE HUB '.$fecha.'</b>\n';
    	
    	$this->lNegocioCertificadoFitosanitario->procesoGenerarXmlWebServicesCertificadosFitosanitario();
    	
    	echo Constantes::IN_MSG .'<b>FIN PROCESO DE GENERACIÓN DE ARCHIVO XML PARA ENVIÓ DE WEB SERVICES A TRAVÉS DE HUB '.$fecha.'</b>';
    }
    
    /**
     * Método para verificar que el código POA pertenezca al operador exportador
     * 
     */
    public function verificarCodigoPoaPorExportador()
    {
        $identificadorExportador = $_POST["identificadorExportador"];
        $codigoPoa = $_POST["codigoPoa"];
        
        $arrayParametros = array(
            'identificadorOperador' => $identificadorExportador,
            'codigoPoa' => $codigoPoa
        );
        
        $validacion = "";
        $mensaje = "";
        
        if(preg_match("/^[0-9]{4}-[0-9]{1}$/", $codigoPoa)){
            
            $verificarCodigoPoa = $this->lNegocioCodigosPoa->buscarCodigoPoaPorOperador($arrayParametros);
            
            if(isset($verificarCodigoPoa->current()->codigo_poa)){
                $validacion = 'Exito';
                echo json_encode(array('mensaje' => $mensaje,'validacion' => $validacion));
            }else{
                $validacion = 'Fallo';
                $mensaje = 'El código POA ingresado no está registrado o no corresponde al exportador.';                
                echo json_encode(array('mensaje' => $mensaje, 'validacion' => $validacion,));
            }   
            
        }else{
            $validacion = 'Fallo';
            $mensaje = 'El código POA ingresado no posee el formato correcto.';            
            echo json_encode(array('mensaje' => $mensaje, 'validacion' => $validacion,));
        }       
        
    }
    
    /**
     * Método para los subtipos de producto por idTipoProducto
     * 
     */
    public function buscarSubtipoProductoPorIdTipoProducto(){
    
        echo $this->comboSubtipoProductos($_POST["idTipoProducto"]);
        
    }
    
    /**
     * Método para generar el reporte de catalogos de productos
     *
     */
    public function generarReporteProductos(){
    
        $idSubtipoProducto = $_POST["id_subtipo_producto"];
        
        $arrayParametros = array(
            'id_subtipo_producto' => $idSubtipoProducto
        );

        $reporteProductos = $this->lNegocioSubtipoProductos->buscarProductosSubtipoProductosPorIdSubtipoProducto($arrayParametros);
        
        $this->lNegocioCertificadoFitosanitario->exportarArchivoProductos($reporteProductos);        
        
    }
    
    /**
     *Método para abrir la ventana de búsqueda de certificados fitosanitarios
     */
    public function cosultaCertificadoFitosanitario()
    {        
       require APP . 'CertificadoFitosanitario/vistas/formularioConsultaCertificadoFitosanitarioVista.php';
    }
    
    /**
     * Método para visualizar el motivo de desestimiento de una solicitud
     */
    public function construirDetalleDesestimiento($arrayParametros)
    {
        
        $motivoDesestimiento = $arrayParametros['motivo_desestimiento'];
               
        $this->detalleDesestimiento = '<fieldset>
                                <legend>Desestimiento</legend>
                                <div data-linea="1">
                                    <label for="motivo_desestimiento">Motivo de Desestimiento: </label>' . $motivoDesestimiento . '
                                </div>
                                </fieldset>';
        
        return $this->detalleDesestimiento;
        
    }
    
    /**
     * Proceso automático para generar certificados
     */
    public function paGeneracionCertificadoFitosanitario(){
        
        echo "\n".'Proceso Automático de generación de certificados'."\n"."\n";
        
        $consulta = "certificado = 'No' and estado_certificado = 'Aprobado' LIMIT 20";
        
        $certificados = $this->lNegocioCertificadoFitosanitario->buscarLista($consulta);
        
        foreach ($certificados as $fila) {
            $arrayParametros = array(
                'id_solicitud' => $fila['id_certificado_fitosanitario'],
                'codigo_certificado' => $fila['codigo_certificado']
            );
            
            $mensaje = $this->generarCertificadoFitosanitarioAutomatico($arrayParametros);
            
            echo $mensaje . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Función para generar el certificado y anexo individual de manera automática
     */
    public function generarCertificadoFitosanitarioAutomatico($arrayParametros)
    {
        $anio = date('Y');
        $mes = date('m');
        $dia = date('d');
        
        $datos = array(
            'id_certificado_fitosanitario' => $arrayParametros['id_solicitud'],
            'certificado' => 'W'
        );
        
        $this->lNegocioCertificadoFitosanitario->actualizarEstadoGeneracionCertificado($datos);
        
        $idSolicitud = $arrayParametros['id_solicitud'];
        $codigoCertificado = $arrayParametros['codigo_certificado'];
        
        if (strlen($arrayParametros['id_solicitud']) > 0) {
            
            $rutaFechaCertificado = $anio . "/" . $mes . "/" . $dia . "/";
                      
            //Obtener datos del inspector de revision documental
            $qIdentificadorInspector = $this->lNegocioRevisionesDocumentales->buscarTecnicoRevisionDocumental($arrayParametros);
            $identificadorInspector = $qIdentificadorInspector->current()->identificador_inspector;            
            
            $resultadoUsuarioInterno = $this->lNegocioFichaEmpleado->buscarDatosUsuarioContrato($identificadorInspector);
            $nombreInspector = $resultadoUsuarioInterno->current()->nombre;
            $provinciaInspector = $resultadoUsuarioInterno->current()->provincia;
            
            //Generar el Certificado
            $this->lNegocioCertificadoFitosanitario->generarCertificado($idSolicitud, $codigoCertificado, $rutaFechaCertificado, $nombreInspector, $provinciaInspector);
            $certificado = CERT_FITO_URL . "certificados/" . $rutaFechaCertificado . "C" . $codigoCertificado . ".pdf";
            $certificadoFirma = CERT_FITO_CERT_URL_TCPDF . "certificados/" . $rutaFechaCertificado . "C" . $codigoCertificado . ".pdf";
            
            //Generar el Anexo
            $this->lNegocioCertificadoFitosanitario->generarAnexo($idSolicitud, $codigoCertificado, $rutaFechaCertificado, $nombreInspector, $provinciaInspector);
            $anexo = CERT_FITO_URL . "certificados/" . $rutaFechaCertificado . "A" . $codigoCertificado . ".pdf";
            $anexoFirma = CERT_FITO_CERT_URL_TCPDF. "certificados/" . $rutaFechaCertificado . "A" . $codigoCertificado . ".pdf";
            
            $arrayParametrosCertificado = array(
                'id_certificado_fitosanitario' => $idSolicitud,
                'tipo_adjunto' => 'Certificado Fitosanitario',
                'ruta_adjunto' => $certificado,
                'estado_adjunto' => 'Activo'
            );
            
            $idCertificado = $this->lNegocioDocumentosAdjuntos->guardar($arrayParametrosCertificado);
            
            $arrayParametrosAnexo = array(
                'id_certificado_fitosanitario' => $idSolicitud,
                'tipo_adjunto' => 'Anexo Certificado',
                'ruta_adjunto' => $anexo,
                'estado_adjunto' => 'Activo'
            );
            
            $idAnexo = $this->lNegocioDocumentosAdjuntos->guardar($arrayParametrosAnexo);
            
            $mensaje = 'Se ha generado el certificado ' . $idSolicitud . '-'. $codigoCertificado;
            
            $datos = array(
                'id_certificado_fitosanitario' => $idSolicitud,
                'certificado' => 'Si'
            );
            
            $this->lNegocioCertificadoFitosanitario->actualizarEstadoGeneracionCertificado($datos);
                        
            //Firma Electrónica
            $arrayDocumentoCertificado = array(
             'archivo_entrada' => $certificadoFirma,
             'archivo_salida' => $certificadoFirma,
             'identificador' => $identificadorInspector,//'1717299596', 
             'razon_documento' => 'Certificación fitosanitaria de exportación.',
             'tabla_origen' => 'g_certificado_fitosanitario.documentos_adjuntos',
             'campo_origen' => 'id_documento_adjunto',
             'id_origen' => $idCertificado,
             'estado' => 'Por atender',
             'proceso_firmado' => 'NO'
             );
            
            $this->lNegocioDocumentos->guardar($arrayDocumentoCertificado);
            
            //Firma Electrónica
            $arrayDocumentoAnexo = array(
                'archivo_entrada' => $anexoFirma,
                'archivo_salida' => $anexoFirma,
                'identificador' => $identificadorInspector,//'1717299596',
                'razon_documento' => 'Certificación fitosanitaria de exportación.',
                'tabla_origen' => 'g_certificado_fitosanitario.documentos_adjuntos',
                'campo_origen' => 'id_documento_adjunto',
                'id_origen' => $idAnexo,
                'estado' => 'Por atender',
                'proceso_firmado' => 'NO'
            );
            
            $this->lNegocioDocumentos->guardar($arrayDocumentoAnexo);
                        
        } else {
            $mensaje = 'No se pudo generar el certificado ' . $idSolicitud .'-'.$codigoCertificado;
        }
        
        return $mensaje;
    }
    
}