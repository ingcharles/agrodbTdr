<?php
/**
 * Controlador Solicitudes
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-23
 * @uses    SolicitudesControlador
 * @package CertificacionBPA
 * @subpackage Controladores
 */
namespace Agrodb\CertificacionBPA\Controladores;

use Agrodb\CertificacionBPA\Modelos\SolicitudesLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\SolicitudesModelo;

use Agrodb\CertificacionBPA\Modelos\SitiosAreasProductosLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\SitiosAreasProductosModelo;

use Agrodb\CertificacionBPA\Modelos\AuditoriasSolicitadasLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\AuditoriasSolicitadasModelo;

use Agrodb\CertificacionBPA\Modelos\TiposAuditoriasLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;

use Agrodb\CertificacionBPA\Modelos\AsociacionesLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\MiembrosAsociacionesLogicaNegocio;

use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SolicitudesControlador extends BaseControlador
{
    private $lNegocioSolicitudes = null;
    private $modeloSolicitudes = null;
    
    private $lNegocioSitiosAreasProductos = null;
    private $modeloSitiosAreasProductos = null;
    
    private $lNegocioAuditoriasSolicitadas = null;
    private $modeloAuditoriasSolicitadas = null;
    
    private $lNegocioTiposAuditorias = null;
    
    private $lNegocioOperadores = null;
    private $lNegocioSitios = null;
    
    private $lNegocioAsociaciones = null;
    private $lNegocioMiembrosAsociaciones = null;
    
    private $lNegocioTipoProductos = null;
    private $lNegocioSubtipoProductos = null;
    private $lNegocioProductos = null;

    private $accion = null;
    private $rutaFecha = null;
    private $article = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = new SolicitudesModelo();
        
        $this->lNegocioSitiosAreasProductos = new SitiosAreasProductosLogicaNegocio();
        $this->modeloSitiosAreasProductos = new SitiosAreasProductosModelo();
        
        $this->lNegocioAuditoriasSolicitadas = new AuditoriasSolicitadasLogicaNegocio();
        $this->modeloAuditoriasSolicitadas = new AuditoriasSolicitadasModelo();
        
        $this->lNegocioTiposAuditorias = new TiposAuditoriasLogicaNegocio();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->lNegocioSitios = new SitiosLogicaNegocio();
        
        $this->lNegocioAsociaciones = new AsociacionesLogicaNegocio();
        $this->lNegocioMiembrosAsociaciones = new MiembrosAsociacionesLogicaNegocio();
        
        $this->lNegocioTipoProductos = new TipoProductosLogicaNegocio();
        $this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        
        $this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');
        $this->auditoria = array();
        
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
        $this->articleHtmlSolicitudes();
        require APP . 'CertificacionBPA/vistas/listaSolicitudesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Ingresar Solicitud";
        
        require APP . 'CertificacionBPA/vistas/formularioSolicitudesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Solicitudes
     */
    public function guardar()
    {
        switch($_POST["id_solicitud"]){
            case '':{
                if ($_POST["id_solicitud"] == '') {
                    $_POST['identificador'] = $_SESSION['usuario'];
                }
                
                if ($_POST["es_asociacion"] == 'No') {
                    $_POST['provincia_revision'] = $_POST['provincia_unidad_produccion'];
                }
                
                $idSolicitud = $this->lNegocioSolicitudes->guardar($_POST);
                
                //Guardar registro de sitios/áreas/productos
                
                $_POST['array_sitios_bpa'] = json_decode($_POST['array_sitios_bpa'], true);
                
                if (count($_POST['array_sitios_bpa'])>0){
                    
                    foreach($_POST['array_sitios_bpa'] as $array){
                        $arrayParametrosSitios = array(     'id_solicitud' =>  $idSolicitud,
                            'identificador_operador' =>  $_POST['identificador'],
                            'identificador_sitio' =>  $array['iIdentificadorSitio'],
                            'id_sitio' =>  $array['iSitio'],
                            'nombre_sitio' => $array['nSitio'],
                            'id_area' =>  $array['iArea'],
                            'nombre_area' =>  $array['nArea'],
                            'id_subtipo_producto' =>  $array['iSubtipoProducto'],
                            'nombre_subtipo_producto' => $array['nSubtipoProducto'],
                            'id_producto' =>  $array['iProducto'],
                            'nombre_producto' => $array['nProducto'],
                            'id_operacion' =>  $array['iOperacion'],
                            'nombre_operacion' => $array['nOperacion'],
                            'superficie' => $array['iHectareas'],
                            'estado' => $array['iEstado']
                        );
                        
                        $this->lNegocioSitiosAreasProductos->guardar($arrayParametrosSitios);
                    }                    
                }
                
                //Guardar registro de auditorías
                if (count($_POST['tipoAuditoria'])>0){
                    
                    for($i=0; $i < count($_POST['tipoAuditoria']); $i++){
                        //Obtener el nombre del tipo de auditoría solicitado
                        $tipoAuditoria = $this->obtenerInformacionTipoAuditoria($_POST['tipoAuditoria'][$i]); 
                        
                        $arrayParametrosAuditorias = array( 'id_solicitud' =>  $idSolicitud,
                                                            'id_tipo_auditoria' =>  $_POST['tipoAuditoria'][$i],
                                                            'tipo_auditoria' =>  $tipoAuditoria->tipoAuditoria
                                                          );
                        
                        $this->lNegocioAuditoriasSolicitadas->guardar($arrayParametrosAuditorias);
                    }
                }
                
                break;
            }
            
            default:{
                $_POST['estado'] = 'enviado';
            
                $this->lNegocioSolicitudes->guardar($_POST);
                
                //Guardar registro de auditorías
                
                $arrayParametros = array(   'id_solicitud' =>  $_POST['id_solicitud']);
                
                //Inactiva las auditorías registradas
                $this->lNegocioAuditoriasSolicitadas->desactivarAuditorias($arrayParametros);
                                
                //Recorrer el array de auditorías enviado, activar los registros existentes e ingresar los nuevos de ser el caso
                if (count($_POST['tipoAuditoria'])>0){
                    
                    for($i=0; $i < count($_POST['tipoAuditoria']); $i++){
                        //Buscar si existe la auditoría requerida y cambiar el estado
                        $query = "id_solicitud = ".$_POST['id_solicitud']." and id_tipo_auditoria = ".$_POST['tipoAuditoria'][$i];
                        
                        $auditoria = $this->lNegocioAuditoriasSolicitadas->buscarLista($query);
                        
                        if(isset($auditoria->current()->id_solicitud)){
                            //Actualiza el estado del registro
                            $arrayParametrosAuditorias = array   (  'id_auditoria_solicitada' =>  $auditoria->current()->id_auditoria_solicitada,
                                                                    'estado' => 'Activo'
                                                                 );
                        }else{
                            //Obtener el nombre del tipo de auditoría solicitado
                            $tipoAuditoria = $this->obtenerInformacionTipoAuditoria($_POST['tipoAuditoria'][$i]);
                            
                            //Guarda el nuevo registro
                            $arrayParametrosAuditorias = array  (   'id_solicitud' =>  $_POST['id_solicitud'],
                                                                    'id_tipo_auditoria' =>  $_POST['tipoAuditoria'][$i],
                                                                    'tipo_auditoria' =>  $tipoAuditoria->tipoAuditoria
                                                                );
                        }
                        
                        //Guardar información de auditorías solicitadas
                        $this->lNegocioAuditoriasSolicitadas->guardar($arrayParametrosAuditorias);
                    }
                }
                
                break;
            }
        }     
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para visualizar - Tabla: Solicitudes
     */
    public function abrir()
    {
        $this->accion = "Visualizar Solicitud";
        $this->modeloSolicitudes = $this->lNegocioSolicitudes->buscar($_POST["id"]);
        
        require APP . 'CertificacionBPA/vistas/formularioSolicitudesAbrirVista.php';
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitudes
     */
    public function editar()
    {
        $this->accion = "Subsanar Solicitud";
        $this->modeloSolicitudes = $this->lNegocioSolicitudes->buscar($_POST["id"]);
        
        //Busca los datos del catálogo y genera los radio buttons
        $auditoriaSolicitada = $this->obtenerTiposAuditoriasXSolicitud($_POST["id"]);
        
        foreach($auditoriaSolicitada as $fila){
            array_push($this->auditoria, $auditoriaSolicitada->current()->id_tipo_auditoria);
        }
        
        require APP . 'CertificacionBPA/vistas/formularioSolicitudesSubsanacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Solicitudes
     */
    public function borrar()
    {
        $this->lNegocioSolicitudes->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes
     */
    public function tablaHtmlSolicitudes($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_solicitud'] . '"
                        		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA\solicitudes"
                        		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        		  data-destino="detalleItem">
                        <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_solicitud'] . '</b></td>
                        <td>' . $fila['identificador'] . '</td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['es_asociacion'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar las Solicitudes en forma de artículos
     */
    public function articleHtmlSolicitudes() 
    {        
        /*$datosAsociacion = $this->obtenerInformacionAsociacion($_SESSION['usuario']);
        
        if(isset($datosAsociacion->current()->identificador_operador)){
            $query = "identificador_operador in ('".$_SESSION['usuario']."', '".$datosAsociacion->current()->identificador."') ";
            $identificador = "'".$_SESSION['usuario']."', '".$datosAsociacion->current()->identificador."'";
        }else{
            $query = "identificador_operador = '".$_SESSION['usuario']."' ";
            $identificador = "'".$_SESSION['usuario']."'";
        }*/
        
        //
        $consultaCabecera = $this->lNegocioSolicitudes->buscarEstadoSolicitudes($_SESSION['usuario']);//$identificador
        $contador = 1;
        $this->article ="";
        
        foreach ($consultaCabecera as $fila1) {
            
            switch ($fila1['estado'])
            {
                case 'enviado':
                    $this->article .="<h2> Solicitudes enviadas a Revisión Documental </h2>";
                    break;
                
                case 'inspeccion':
                    $this->article .="<h2> Solicitudes  enviadas a Inspección </h2>";
                    break;
                
                case 'pago':
                    $this->article .="<h2> Solicitudes en etapa de Pago </h2>";
                    break;
                    
                case 'subsanacion':
                    $this->article .="<h2> Solicitudes remitidas para Subsanación </h2>";
                    break;
                    
                case 'Aprobado':
                    $this->article .="<h2> Solicitudes Aprobadas </h2>";
                    break;
                    
                case 'Expirado':
                    $this->article .="<h2> Solicitudes Expiradas </h2>";
                    break;
                    
                case 'Rechazado':
                    $this->article .="<h2> Solicitudes Rechazadas </h2>";
                    break;
                    
                default:
                    $this->article .="<h2> Solicitudes en estado ".$fila1['estado']."</h2>";
                    break;
            }
            
            $query = "identificador = '".$_SESSION['usuario']."' and estado = '".$fila1['estado']."' ";
            
            $consulta = $this->lNegocioSolicitudes->buscarLista($query);
            foreach ($consulta as $fila) {
                
                $this->article .= '<article id="' . $fila['id_solicitud'] . '" class="item"
            								data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA/Solicitudes"
            								data-opcion="' . ($fila['estado']=='subsanacion'?'editar':'abrir') . '" ondragstart="drag(event)"
            								draggable="true" data-destino="detalleItem">
        								<span><small><b>' . ($fila['es_asociacion']=='Si'?'Asociación: ':'Operador: ').'</b>'.$fila["razon_social"] . ' </small></span><br/>
                                        <span><small><b>Tipo Solicitud: </b>' . $fila['tipo_solicitud'] . ' </small></span><br/>
        					 			<span class="ordinal">' . $contador++ . '</span>
        								<aside><small><b>Solicitud: </b>' . $fila['id_solicitud'] . '</small>
                                                <small><b>Explotación: </b>' . $fila['tipo_explotacion'] . '</small></aside>

    								</article>';
            }
        }
        
    }
    
    /**
     * Combo de estados para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoSolicitud($opcion = null)
    {
        $combo = "";
        if ($opcion == "Equivalente") {
            $combo .= '<option value="Equivalente" selected="selected">Equivalente</option>';
            $combo .= '<option value="Nacional">Nacional</option>';
        } else if ($opcion == "Nacional") {
            $combo .= '<option value="Equivalente" >Equivalente</option>';
            $combo .= '<option value="Nacional" selected="selected">Nacional</option>';
        } else {
            $combo .= '<option value="Equivalente">Equivalente</option>';
            $combo .= '<option value="Nacional">Nacional</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de estados para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoExplotacion($opcion = null)
    {
        $combo = "";
        if ($opcion == "IA") {
            $combo .= '<option value="AI" selected="selected">Inocuidad de Alimentos</option>';
            $combo .= '<option value="SA">Sanidad Animal</option>';
            $combo .= '<option value="SV">Sanidad Vegetal</option>';
        } else if ($opcion == "SA") {
            $combo .= '<option value="AI" >Inocuidad de Alimentos</option>';
            $combo .= '<option value="SA" selected="selected">Sanidad Animal</option>';
            $combo .= '<option value="SV">Sanidad Vegetal</option>';
        } else if ($opcion == "SV") {
            $combo .= '<option value="AI" >Inocuidad de Alimentos</option>';
            $combo .= '<option value="SA">Sanidad Animal</option>';
            $combo .= '<option value="SV" selected="selected">Sanidad Vegetal</option>';
        } else {
            $combo .= '<option value="AI">Inocuidad de Alimentos</option>';
            $combo .= '<option value="SA">Sanidad Animal</option>';
            $combo .= '<option value="SV">Sanidad Vegetal</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de tipos de explotación para solicitudes equivalentes
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoExplotacionEquivalente()
    {
        $opcion = $_POST['tipo_explotacion'];
        $combo = "<option value=''>Seleccionar....</option>";
        
        if ($opcion == "IA") {
            $combo .= '<option value="AI" selected="selected">Inocuidad de Alimentos</option>';
            $combo .= '<option value="SV">Sanidad Vegetal</option>';
        } else if ($opcion == "SV") {
            $combo .= '<option value="AI" >Inocuidad de Alimentos</option>';
            $combo .= '<option value="SV" selected="selected">Sanidad Vegetal</option>';
        } else {
            $combo .= '<option value="AI">Inocuidad de Alimentos</option>';
            $combo .= '<option value="SV">Sanidad Vegetal</option>';
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Combo de Tipos de Certificado por Alcance
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoCertificado($opcion = null)
    {
        $combo = "";
        if ($opcion == "Nacional") {
            $combo .= '<option value="Nacional" selected="selected">Nacional</option>';
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        } else if ($opcion == "Global Gap") {
            $combo .= '<option value="Nacional" >Nacional</option>';
            $combo .= '<option value="Global Gap" selected="selected">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        } else if ($opcion == "Flor Ecuador") {
            $combo .= '<option value="Nacional" >Nacional</option>';
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador" selected="selected">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        } else if ($opcion == "Certificación Orgánica") {
            $combo .= '<option value="Nacional" >Nacional</option>';
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica" selected="selected">Certificación Orgánica</option>';
        } else {
            $combo .= '<option value="Nacional">Nacional</option>';
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de Tipos de Certificado por Alcance Nacional
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoCertificadoNacional()
    {
        $opcion = $_POST['tipo_solicitud'];
        $combo = "";
        
        if ($opcion == "Nacional") {
            $combo .= '<option value="Nacional" selected="selected">Nacional</option>';
        } else {
            $combo .= '<option value="Nacional">Nacional</option>';
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Combo de Tipos de Certificado por Alcance Equivalente
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoCertificadoEquivalente()
    {
        $opcion = $_POST['tipo_solicitud'];
        $combo = "<option value=''>Seleccione....</option>";
        
        if ($opcion == "Global Gap") {
            $combo .= '<option value="Global Gap" selected="selected">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        } else if ($opcion == "Flor Ecuador") {
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador" selected="selected">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        } else if ($opcion == "Certificación Orgánica") {
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica" selected="selected">Certificación Orgánica</option>';
        } else {
            $combo .= '<option value="Global Gap">Global Gap</option>';
            $combo .= '<option value="Flor Ecuador">Flor Ecuador</option>';
            $combo .= '<option value="Certificación Orgánica">Certificación Orgánica</option>';
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Método para obtener los datos del operador
     * */
    public function obtenerDatosOperador()
    {
        $validacion = "Fallo";
        $resultado="El operador no existe.";
        
        $identificador = $_POST["identificador"];
        $asociacion = $_POST["asociacion"];
        
        if($asociacion === 'Si'){
            //Busca los datos de registro de la asociación
            $datosAsociacion = $this->obtenerInformacionAsociacion($identificador);
            
            if(isset($datosAsociacion->current()->identificador_operador)){
                $validacion = "Exito";
                $resultado="El operador posee una asociación registrada.";
                
                echo json_encode(array( 'resultado' => $resultado,
                    'id' => $datosAsociacion->current()->identificador,
                    'razon_social' => $datosAsociacion->current()->razon_social,
                    'id_representante' => $datosAsociacion->current()->identificador_representante_legal,
                    'nombre_representante' => $datosAsociacion->current()->nombre_representante_legal,
                    'correo' => $datosAsociacion->current()->correo,
                    'telefono' => $datosAsociacion->current()->telefono,
                    'direccion' => $datosAsociacion->current()->direccion,
                    'id_tecnico' => $datosAsociacion->current()->identificador_representante_tecnico,
                    'nombre_tecnico' => $datosAsociacion->current()->nombre_representante_tecnico,
                    'correo_tecnico' => $datosAsociacion->current()->correo_representante_tecnico,
                    'telefono_tecnico' => $datosAsociacion->current()->telefono_representante_tecnico,
                    'provincia' => $datosAsociacion->current()->provincia,
                    'canton' => $datosAsociacion->current()->canton,
                    'parroquia' => $datosAsociacion->current()->parroquia,
                    'validacion' => $validacion));
            }else{
                $resultado="El operador no posee una asociación registrada.";
                
                echo json_encode(array( 'resultado' => $resultado,'validacion' => $validacion));
            }
            
        }else{
            //Busca los datos de registro del operador
            $datosOperador = $this->obtenerInformacionOperador($identificador);
            
            if($datosOperador->identificador != ''){
                $validacion = "Exito";
                $resultado="El operador se encuentra registrado.";
                
                echo json_encode(array( 'resultado' => $resultado,
                    'id' => $datosOperador->identificador,
                    'razon_social' => $datosOperador->razonSocial,
                    'nombre_representante' => $datosOperador->nombreRepresentante . ' ' . $datosOperador->apellidoRepresentante,
                    'correo' => $datosOperador->correo,
                    'telefono' => $datosOperador->telefonoUno,
                    'direccion' => $datosOperador->direccion,
                    'nombre_tecnico' => $datosOperador->nombreTecnico . ' ' . $datosOperador->apellidoTecnico,
                    'provincia' => $datosOperador->provincia,
                    'canton' => $datosOperador->canton,
                    'parroquia' => $datosOperador->parroquia,
                    'validacion' => $validacion));
            }else{
                $resultado="El operador no se encuentra registrado.";
                
                echo json_encode(array( 'resultado' => $resultado,'validacion' => $validacion));
            }
            
        }
    }
    
    /**
     * Método para obtener los datos de registro del operador
     * */
    public function obtenerInformacionOperador($identificador)
    {        
    	$identificador = $identificador;
        
        $operador = $this->lNegocioOperadores->buscar($identificador);
        
        return $operador;
    }
    
    /**
     * Método para obtener los datos de registro de la asociación por el usuario de registro
     * */
    public function obtenerInformacionAsociacion($identificador)
    {        
        $query = "identificador_operador = '$identificador'";
        
        $asociacion = $this->lNegocioAsociaciones->buscarLista($query);
        
        return $asociacion;
    }
    
    /**
     * Método para obtener los miembros de una asociación a partir del número de RUC de la misma
     * */
    public function buscarMiembrosAsociacion($identificador){
        
        $miembros = $this->lNegocioMiembrosAsociaciones->obtenerMiembrosAsociacionXRuc($identificador);
        
        $identificadorMiembros = "";
        
        foreach ($miembros as $item)
        {
            $identificadorMiembros .= "'" . $item->identificador_miembro . "',";
        }
        
        $identificadorMiembros = trim($identificadorMiembros, ',');
        
        return $identificadorMiembros;
    }
    
    /**
     * Método para obtener los tipos de productos disponibles del operador con una operación definida y un área temática
     * */
    public function buscarTipoProductoXOperacionAreaOperador(){
        
        $identificador = $_POST["identificador"];
        $codigo_operacion = "'PRO', 'PRA'";
        $id_area = $_POST["id_area"];
        $asociacion = $_POST["asociacion"];
        
        if($asociacion === 'Si'){
            //Busca a los miembros de la asociación y crea una cadena con sus identificadores
            $identificador = $this->buscarMiembrosAsociacion($identificador);
        }else{
            $identificador = "'" . $identificador . "'";
        }
        
        $arrayParametros = array(
            'identificador' => $identificador,
            'codigo_operacion' => $codigo_operacion,
            'id_area' => $id_area
        );
        
        $tipoProductos = $this->lNegocioTipoProductos->obtenerTipoProductoXOperacionAreaOperador($arrayParametros);
        
        $comboTipoProductos = "";
        $comboTipoProductos .= '<option value="">Seleccione....</option>';
        
        foreach ($tipoProductos as $item)
        {
            $comboTipoProductos .= '<option value="' . $item->id_tipo_producto . '" >' . $item->nombre . '</option>';
        }
        
        echo $comboTipoProductos;
        exit();
    }
    
    /**
     * Método para obtener los subtipos de productos disponibles del operador con una operación definida y un área temática
     * */
    public function buscarSubtipoProductoXOperacionAreaOperador(){
        
        $identificador = $_POST["identificador"];
        $codigo_operacion = "'PRO', 'PRA'";
        $id_tipo_producto = $_POST["id_tipo_producto"];
        $asociacion = $_POST["asociacion"];
        
        if($asociacion === 'Si'){
            //Busca a los miembros de la asociación y crea una cadena con sus identificadores
            $identificador = $this->buscarMiembrosAsociacion($identificador);
        }else{
            $identificador = "'" . $identificador . "'";
        }
        
        $arrayParametros = array(
            'identificador' => $identificador,
            'codigo_operacion' => $codigo_operacion,
            'id_tipo_producto' => $id_tipo_producto
        );
        
        $subtipoProductos = $this->lNegocioSubtipoProductos->obtenerSubtipoProductoXOperacionAreaOperador($arrayParametros);
        
        $comboSubtipoProductos = "";
        $comboSubtipoProductos .= '<option value="">Seleccione....</option>';
        
        foreach ($subtipoProductos as $item)
        {
            $comboSubtipoProductos .= '<option value="' . $item->id_subtipo_producto . '" >' . $item->nombre . '</option>';
        }
        
        echo $comboSubtipoProductos;
        exit();
    }
    
    /**
     * Método para obtener los productos disponibles del operador con una operación definida y un área temática
     * */
    public function buscarProductoXOperacionAreaOperador(){
        
        $identificador = $_POST["identificador"];
        $codigo_operacion = "'PRO', 'PRA'";
        $id_subtipo_producto = $_POST["id_subtipo_producto"];
        $asociacion = $_POST["asociacion"];
        
        if($asociacion === 'Si'){
            //Busca a los miembros de la asociación y crea una cadena con sus identificadores
            $identificador = $this->buscarMiembrosAsociacion($identificador);
        }else{
            $identificador = "'" . $identificador . "'";
        }
        
        $arrayParametros = array(
            'identificador' => $identificador,
            'codigo_operacion' => $codigo_operacion,
            'id_subtipo_producto' => $id_subtipo_producto
        );
        
        $productos = $this->lNegocioProductos->obtenerProductoXOperacionAreaOperador($arrayParametros);
        
        $comboProductos = "";
        $comboProductos .= '<option value="">Seleccione....</option>';
        
        foreach ($productos as $item)
        {
            $comboProductos .= '<option value="' . $item->id_producto . '" >' . $item->nombre_comun . '</option>';
        }
        
        echo $comboProductos;
        exit();
    }
    
    /**
     * Método para obtener los sitios y áreas disponibles del operador con un producto, operación definida y un área temática
     * */
    public function buscarSitioXProductoOperacionAreaOperador(){
        
        $identificador = $_POST["identificador"];//id_operador ruc solicitud
        $codigo_operacion = "'PRO', 'PRA'";
        $id_producto = $_POST["id_producto"];
        $asociacion = $_POST["asociacion"];
        $estado='';
        
        if($asociacion === 'Si'){
            //Busca a los miembros de la asociación y crea una cadena con sus identificadores
            $identificador = $this->buscarMiembrosAsociacion($identificador);
        }else{
            $identificador = "'" . $identificador . "'";
        }
        
        $arrayParametros = array(
            'identificador' => $identificador,
            'codigo_operacion' => $codigo_operacion,
            'id_producto' => $id_producto
        );
        
        $sitios = $this->lNegocioSitios->obtenerSitioXProductoOperacionAreaOperador($arrayParametros);
        
        $comboSitios = "";
        $comboSitios .= '<option value="">Seleccione....</option>';
        
        foreach ($sitios as $item)
        {
            //Operador de la solicitud
            $arrayParametrosRegistro = array(
                'identificador_sitio' => $item->identificador_operador, //dueño del sitio original
                'identificador_operador' => $_POST["identificador"], //ruc de la persona que va a registrar la solicitud
                'id_sitio' => $item->id_sitio,
                'id_area' => $item->id_area,
                'id_producto' => $id_producto,
                'es_asociacion' => $asociacion,
                'codigo_operacion' => $codigo_operacion
            );
            
            //Busca los datos de registro del sitio seleccionado con la misma solicitud
            //Busca si existe un registro de ese sitio, área y producto para alguna solicitud existente para el mismo 
            //operador que hace la solicitud
            $datosSitio = $this->lNegocioSitiosAreasProductos->buscarSitioAreaProducto($arrayParametrosRegistro);
            
            if(isset($datosSitio->current()->id_sitio)){
                $estado = $datosSitio->current()->estado;
                $registroSitioAsociacion = $datosSitio->current()->es_asociacion;
            }else{
                $estado = 'Nuevo';
                $registroSitioAsociacion = 'NoRegistrado';
            }
            
            //Otro operador que lo registró            
            //Busca los datos de registro del sitio seleccionado con la misma solicitud
            $datosSitioOtroRegistro = $this->lNegocioSitiosAreasProductos->buscarSitioAreaProductoOtroRegistro($arrayParametrosRegistro);
            
            if(!isset($datosSitioOtroRegistro->current()->id_sitio)){
                
                if($item->unidad_medida == 'm2'){
                    $hectareas = $this->convertirMetrosCuadradosAHectareas($item->superficie_utilizada);
                }else{
                    $hectareas = $item->superficie_utilizada;
                }
                
                $comboSitios .= '<option value="' . $item->id_sitio. $item->id_area. $item->id_producto. $item->id_tipo_operacion . '"
                                    data-idSitio="'. $item->id_sitio.'" data-nombreSitio="'. $item->nombre_lugar.'"
                                    data-idArea="'. $item->id_area.'" data-nombreArea="'. $item->nombre_area.'"
                                    data-idOperacion="'. $item->id_tipo_operacion.'" data-nombreOperacion="'. $item->nombre.'"
                                    data-hectareas="'. $hectareas.'"
                                    data-superficieCertificada="'. $item->superficie_certificada.'"
                                    data-unidad="'. $item->unidad_medida.'"
                                    data-identificadorSitio="'. $item->identificador_operador.'"
                                    data-asociacionSitio="'. $registroSitioAsociacion.'"
                                    data-estado="'. $estado.'">' . $item->nombre_lugar .' - ' . $item->nombre_area. '</option>';
            }
            
            
        }
        
        echo $comboSitios;
        exit();
    }
    
    /**
     * Método para convertir unidades de metros cuadrados a hectáreas
     * */
    public function convertirMetrosCuadradosAHectareas($metros)
    {
        $hectareas = ($metros/10000);
        
        return $hectareas;
    }
    
    /**
     * Método para obtener los datos de registro de un sitio
     * */
    public function obtenerInformacionSitio($idSitio)
    {
        $asociacion = $this->lNegocioSitios->buscar($idSitio);
        
        return $asociacion;
    }
    
    /**
     * Método para obtener los datos del operador
     * */
    public function obtenerSitio()
    {
        $validacion = "Fallo";
        $resultado="El sitio no existe.";
        
        $idSitio = $_POST["id_sitio"];
        
        //Busca los datos de registro del sitio seleccionado
        $datosSitio = $this->obtenerInformacionSitio($idSitio);
        
        if($datosSitio->idSitio != ''){
            $validacion = "Exito";
            $resultado="El sitio se encuentra registrado.";
            
            echo json_encode(array( 'resultado' => $resultado,
                                    'provincia' => $datosSitio->provincia,
                                    'canton' => $datosSitio->canton,
                                    'parroquia' => $datosSitio->parroquia,
                                    'direccion' => $datosSitio->direccion,
                                    'latitud' => $datosSitio->latitud,
                                    'longitud' => $datosSitio->longitud,
                                    'zona' => $datosSitio->zona,
                                    'validacion' => $validacion
                                ));
        }else{
            $resultado="El sitio no se encuentra registrado.";
            
            echo json_encode(array( 'resultado' => $resultado,'validacion' => $validacion));
        }
   }
   
   /**
    * Método para obtener los datos del operador
    * */
   public function verificarSitioAreaProducto()
   {
       $validacion = "Exito";
       $resultado="El sitio/área/producto no se encuentra registrado en una solicitud BPA.";
       
       $arrayParametros = array(
           'identificador_sitio' => $_POST['identificador_sitio'],
           'id_sitio' => $_POST['id_sitio'],
           'id_area' => $_POST['id_area'],
           'id_producto' => $_POST['id_producto'],
           'es_asociacion' => $_POST['asociacion'],
           'identificador_operador' => $_SESSION['usuario']
       );
       
       //Busca los datos de registro del sitio seleccionado
       $datosSitio = $this->lNegocioSitiosAreasProductos->buscarSitioAreaProducto($arrayParametros);
       
       if(isset($datosSitio->current()->id_sitio)){
           $validacion = "Fallo";
           $resultado="El sitio/área/producto ya ha sido registrado y se encuentra en estado ". $datosSitio->current()->estado .".";
           
           echo json_encode(array( 'validacion' => $validacion, 'resultado' => $resultado));
       }else{
           echo json_encode(array( 'validacion' => $validacion, 'resultado' => $resultado));
       }
   }
   
   /**
    * Método para obtener los elementos del catálogo Tipos de Auditorías
    * */
   public function obtenerTiposAuditorias()
   {
       $query = "estado = 'Activo'";
       
       $tipoAuditoria = $this->lNegocioTiposAuditorias->buscarLista($query);
       
       return $tipoAuditoria;
   }
   
   /**
    * Método para obtener los datos de un tipo de auditoría
    * */
   public function obtenerInformacionTipoAuditoria($idTipoAuditoria)
   {
       $tipoAuditoria = $this->lNegocioTiposAuditorias->buscar($idTipoAuditoria);
       
       return $tipoAuditoria;
   }
   
   /**
    * Checkbox de tipos de auditoría
    *
    * @param
    * $respuesta
    * @return string
    */
   public function radioTiposAuditoria()
   {
       $check = '';
       
       //Busca los datos del catálogo y genera los checkbox
       $tipoAuditoria = $this->obtenerTiposAuditorias();
       
       $check .= '<table style="border-collapse: initial;"><tr>';
       $agregarDiv = 0;
       $cantidadLinea = 0;
       
       foreach($tipoAuditoria as $fila){
           
           $check .= '<td>
                          <input id="c'.$fila['id_tipo_auditoria'].'" type="checkbox" name="tipoAuditoria[]" value="'.$fila['id_tipo_auditoria'].'" class="'.$fila['estado_registros'].'"/>
			 	          <label for="c'.$fila['id_tipo_auditoria'].'">'.$fila['tipo_auditoria'].'</label>
                      </td>';

           $agregarDiv++;
           
           if(($agregarDiv % 3) == 0){
               $check .= '</tr><tr>';
               $cantidadLinea++;
           }
       }
       $check .= '</tr></table>';
       
       return $check;
   }
   
   /**
    * Método para obtener los elementos de tipos de auditorías seleccionados en
    * */
   public function obtenerTiposAuditoriasXSolicitud($idSolicitud)
   {
       $query = "estado = 'Activo' and id_solicitud = $idSolicitud";
       
       $tipoAuditoria = $this->lNegocioAuditoriasSolicitadas->buscarLista($query);
       
       return $tipoAuditoria;
   }
   
   /**
    * Combo de dos estados SI/NO
    * @param type $respuesta
    * @return string
    */
   public function comboIndividualAsociacion($opcion=null)
   {
       $combo = "";
       if ($opcion == "Si")
       {
           $combo .= '<option value="Si" selected="selected">Grupal</option>';
           $combo .= '<option value="No">Individual</option>';
       } else if ($opcion == "No")
       {
           $combo .= '<option value="Si" >Grupal</option>';
           $combo .= '<option value="No" selected="selected">Individual</option>';
       } else
       {
           $combo .= '<option value="" selected="selected">Seleccionar...</option>';
           $combo .= '<option value="Si" >Grupal</option>';
           $combo .= '<option value="No">Individual</option>';
       }
       return $combo;
   }
   
   /**
    * Proceso automático para cambiar de estado las solicitudes expiradas
    */
   public function paCambioEstadoSolicitudExpirada(){
       
       echo "\n".'Proceso Automático de cambio de estado de solicitudes expiradas'."\n"."\n";
       
       $consulta = "   fecha_fin_vigencia <='".date("Y-m-d")."' and
                       estado = 'Aprobado'";
       
       $solicitudes = $this->lNegocioSolicitudes->buscarLista($consulta);
       
       foreach ($solicitudes as $fila) {
           $arrayParametros = array(
               'id_solicitud' => $fila['id_solicitud'],
               'estado' => 'Expirado'
           );
           
           $this->lNegocioSolicitudes->guardar($arrayParametros);
           $this->lNegocioSitiosAreasProductos->cambiarEstadoSitiosAreasProductos($arrayParametros);
           
           echo 'La Solicitud de Certificación BPA ' . $fila['id_solicitud']. ' cambia de estado a Expirado'."\n";
       }
       echo "\n";
   }
   
   /**
    * Proceso automático para cambiar de estado las solicitudes sin respuesta para subsanación
    */
   public function paCambioEstadoSolicitudSinRespuesta(){
       
       echo "\n".'Proceso Automático de cambio de estado de solicitudes sin respuesta'."\n"."\n";
       
       $consulta = "   fecha_max_respuesta <='".date("Y-m-d")."' and
                       estado = 'subsanacion'";
       
       $solicitudes = $this->lNegocioSolicitudes->buscarLista($consulta);
       
       foreach ($solicitudes as $fila) {
           $arrayParametros = array(
               'id_solicitud' => $fila['id_solicitud'],
               'estado' => 'Rechazado'
           );
           
           $this->lNegocioSolicitudes->guardar($arrayParametros);
           $this->lNegocioSitiosAreasProductos->cambiarEstadoSitiosAreasProductos($arrayParametros);
           
           echo 'La Solicitud de Certificación BPA ' . $fila['id_solicitud']. ' cambia de estado a Rechazado por no tener respuesta del usuario'."\n";
       }
       echo "\n";
   }
}