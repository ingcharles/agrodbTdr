<?php
 /**
 * Controlador ResultadoInspeccion
 *
 * Este archivo controla la lógica del negocio del modelo:  ResultadoInspeccionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    ResultadoInspeccionControlador
 * @package InspeccionMusaceas
 * @subpackage Controladores
 */
 namespace Agrodb\InspeccionMusaceas\Controladores;
 use Agrodb\InspeccionMusaceas\Modelos\ResultadoInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\ResultadoInspeccionModelo;
 use Agrodb\InspeccionMusaceas\Modelos\SolicitudInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\SolicitudInspeccionModelo;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleSolicitudInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleSolicitudInspeccionModelo;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleEstadoSolicitudLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleEstadoSolicitudModelo;
 use Agrodb\InspeccionMusaceas\Modelos\NotificarSolicitudLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\NotificarSolicitudModelo;
 use Agrodb\Core\JasperReport;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleNotificarInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleNotificarInspeccionModelo;
 
 
class ResultadoInspeccionControlador extends BaseControlador 
{

		 private $lNegocioResultadoInspeccion = null;
		 private $modeloResultadoInspeccion = null;
		 private $lNegocioSolicitudInspeccion = null;
		 private $modeloSolicitudInspeccion = null;
		 private $lNegocioDetalleSolicitudInspeccion = null;
		 private $modeloDetalleSolicitudInspeccion = null;
		 private $lNegocioDetalleEstadoSolicitud = null;
		 private $modeloDetalleEstadoSolicitud = null;
		 private $lNegocioNotificarSolicitud = null;
		 private $modeloNotificarSolicitud = null;
		 private $lNegocioDetalleNotificarInspeccion = null;
		 private $modeloDetalleNotificarInspeccion = null;
		 private $accion = null;
		 private $datosGenerales = null;
		 private $listarProductores = null;
		 private $urlPdf = null;
		 private $operador = null;
		 private $estado = null;
		 private $fechaCambioEstado = null;
		 private $validarOperador = null;
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioResultadoInspeccion = new ResultadoInspeccionLogicaNegocio();
		 $this->modeloResultadoInspeccion = new ResultadoInspeccionModelo();
		 $this->lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
		 $this->modeloSolicitudInspeccion = new SolicitudInspeccionModelo();
		 $this->lNegocioDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionLogicaNegocio();
		 $this->modeloDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionModelo();
		 $this->lNegocioDetalleEstadoSolicitud= new DetalleEstadoSolicitudLogicaNegocio();
		 $this->modeloDetalleEstadoSolicitud = new DetalleEstadoSolicitudModelo();
		 $this->lNegocioNotificarSolicitud = new NotificarSolicitudLogicaNegocio();
		 $this->modeloNotificarSolicitud = new NotificarSolicitudModelo();
		 $this->lNegocioDetalleNotificarInspeccion = new DetalleNotificarInspeccionLogicaNegocio();
		 $this->modeloDetalleNotificarInspeccion = new DetalleNotificarInspeccionModelo();
		 
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $this->estado='revisar';
		 $verificarUsuario = true;
		 $verificarUsuario = $this->verificarOperador();
		 if($verificarUsuario){
		     $this->filtroSolicitud(3);
		 }
    	 require APP . 'InspeccionMusaceas/vistas/listaResultadoInspeccionRevisarVista.php';
		}
		/**
		 * Método de inicio del controlador
		 */
		public function atender() 
		{
		    $this->perfilUsuario();
		    $verificarUsuario = true;
		    $verificarUsuario = $this->verificarOperador();
		    if($verificarUsuario){
			    $this->filtroSolicitud(2);
			    if(in_array('PFL_IEA_MUS', $this->perfilUsuario)){
			    	$arrayParametros = array('identificador_inspeccion_externa' => $_SESSION['usuario'],'estadoSolicitud'=>'Enviada');
			    }else{
			    	$arrayParametros = array('provincia' => $_SESSION['nombreProvincia'],'estadoSolicitud'=>'Enviada', 'inspeccion_externa' => 'no');
			    }
			$modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
		    $this->tablaHtmlResultadoInspeccion($modeloSolicitudInspeccion);
		    require APP . 'InspeccionMusaceas/vistas/listaResultadoInspeccionVista.php';
		    }
		}
		
		/**
		 * Método de inicio del controlador
		 */
		public function notificar()
		{
		    $this->perfilUsuario();
		    $verificarUsuario = true;
		    $verificarUsuario = $this->verificarOperador();
		    if($verificarUsuario){
		        $this->filtroSolicitud(2);
		        
		        $arrayParametros = array('provincia' => $_SESSION['nombreProvincia'],'estadoSolicitud'=>"Enviada','Atendida", 'lugar_inspeccion'=>'Puerto');
		        $modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
		        // $modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscarLista("provincia='".$_SESSION['nombreProvincia']."' and estado in ('Enviada','Atendida') and lugar_inspeccion = 'Puerto' order by 1 DESC");
		        $this->tablaHtmlResultadoNotificacion($modeloSolicitudInspeccion);
		    }
		    require APP . 'InspeccionMusaceas/vistas/listaResultadoNotificarVista.php';
		}
		/**
		 * Método de inicio del controlador
		 */
		public function revisar()
		{
		    $this->perfilUsuario();
		    $this->accion = "Revisar Inspección";
		    $this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST["id"]);
		    $this->operador = $this->modeloSolicitudInspeccion->getIdentificador();
		    $consulta = $this->lNegocioResultadoInspeccion->buscarLista("id_solicitud_inspeccion =".$this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());
		   
		    $fechaEstado = $this->lNegocioDetalleEstadoSolicitud->buscarLista("id_solicitud_inspeccion=".$this->modeloSolicitudInspeccion->getIdSolicitudInspeccion()." order by 1 DESC limit 1");
		    if($fechaEstado->count()){
		        if($fechaEstado->current()->estado == 'Consumida'){
		            $this->fechaCambioEstado = 'Fecha de consumo: '.date('Y-m-d',strtotime($fechaEstado->current()->fecha_creacion));
		        }else if($fechaEstado->current()->estado == 'Dada de baja'){
		            $this->fechaCambioEstado = 'Fecha de baja: '.date('Y-m-d',strtotime($fechaEstado->current()->fecha_creacion));
		        }
		   
		    }
		    if($consulta->count()){
		        $this->modeloResultadoInspeccion = $this->lNegocioResultadoInspeccion->buscar($consulta->current()->id_resultado_inspeccion);
		    }
		    require APP . 'InspeccionMusaceas/vistas/formularioResultadoInspeccionReporteVista.php';
		}
		/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo ResultadoInspeccion"; 
		 require APP . 'InspeccionMusaceas/vistas/formularioResultadoInspeccionVista.php';
		}	/**
		* Método para registrar en la base de datos -ResultadoInspeccion
		*/
		public function guardar()
		{
		  $this->lNegocioResultadoInspeccion->guardar($_POST);
		}
		/**
		* Método para registrar en la base de datos -ResultadoInspeccion
		*/
		public function guardarResultado()
		{ 
		    $estado = 'exito';
		    $mensaje = '';
		        $resultado =  $this->lNegocioResultadoInspeccion->guardarResultado($_POST);
    		    if($resultado){
    		        
    		        $this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST['id_solicitud_inspeccion']);
    		        
    		        $rutaReporte = 'InspeccionMusaceas/vistas/reportes/inspeccionFitosanitariaMusaceas.jasper';
    		        $rutaCarpeta = INSP_MUS_URL."inspeccionMusaceas/". $this->modeloSolicitudInspeccion->getIdentificador();
    		        if (!file_exists('../../' . $rutaCarpeta)) {
    		            mkdir('../../' .$rutaCarpeta, 0777, true);
    		        }
    		        $nombre = 'inspeccion_musaceas_';
    		        $rutaArchivo = "inspeccionMusaceas/".$this->modeloSolicitudInspeccion->getIdentificador()."/".$nombre.$this->modeloSolicitudInspeccion->getCodigoSolicitud();
    		        try {
    		            $jasper = new JasperReport();
    		            $datosReporte = array();
    		            
    		            $rutaArchivoBase = 'InspeccionMusaceas/archivos/';
    		            $datosReporte = array(
    		                'rutaReporte' => $rutaReporte,
    		                'rutaSalidaReporte' => $rutaArchivoBase.$rutaArchivo,
    		                'tipoSalidaReporte' => array('pdf'),
    		                'parametrosReporte' => array(
    		                    'id_solicitud_inspeccion' => $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion(),
    		                    'tecnico' => $_SESSION['datosUsuario'],
    		                    'fondoCertificado' => RUTA_IMG_GENE.'fondoCertificado.png'),
    		                'conexionBase' => 'SI'
    		            );
    		            $validar=1;
    		            $jasper->generarArchivo($datosReporte);
    		            $contenido = INSP_MUS_URL.$rutaArchivo.'.pdf';
    		            
    		            $datosReporte = array(
    		                'id_solicitud_inspeccion' => $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion(),
    		                'ruta_archivo' => $contenido,
    		                'estado' => 'Atendida',
    		                'identificador_registro' => $_SESSION['usuario']
    		            );
    		            $this->lNegocioSolicitudInspeccion->guardar($datosReporte);
    		            
    		            
    		        } catch (\Exception  $e) {
    		            $validar=0;
    		        }
    		        if($validar){
    		            
    		        }else{
    		            $estado = 'ERROR';
    		            $mensaje = 'Error al crear el archivo pdf de la historia clínica';
    		        }
    		    }else {
    		        $estado = 'ERROR';
    		        $mensaje = 'Error al guardar los datos !!';
    		    }
    		
		    echo json_encode(array(
		        'estado' => $estado,
		        'mensaje' => $mensaje,
		        'contenido' => $contenido
		    ));
		}
		    
		    
		public function consumir()
		{
		   $_POST['estado']='Consumida';
		   $_POST['identificador_registro']=$_SESSION['usuario'];
           $this->lNegocioSolicitudInspeccion->guardar($_POST);
		}/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: ResultadoInspeccion
		*/
		public function editar()
		{
		 $this->perfilUsuario();
		 $this->accion = "Atender Inspección"; 
		 $this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST["id"]);
		 $this->datosGenerales=$this->datosGenerales($this->modeloSolicitudInspeccion->getIdentificador());
		 $this->listarProductores = $this->listarProductores($this->modeloSolicitudInspeccion->getIdSolicitudInspeccion(),1);
		 $this->operador =  $this->modeloSolicitudInspeccion->getIdentificador();
		 if($this->modeloSolicitudInspeccion->getEstado() == 'Enviada'){
    		 require APP . 'InspeccionMusaceas/vistas/formularioResultadoInspeccionVista.php';
		 }else{
		     $consulta = $this->lNegocioResultadoInspeccion->buscarLista("id_solicitud_inspeccion =".$this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());
		     if($consulta->count()){
		         $this->modeloResultadoInspeccion = $this->lNegocioResultadoInspeccion->buscar($consulta->current()->id_resultado_inspeccion);
		     }
		     require APP . 'InspeccionMusaceas/vistas/formularioSolicitudInspeccionReporteVista.php';
		 }
		}	/**
		* Método para borrar un registro en la base de datos - ResultadoInspeccion
		*/
		public function borrar()
		{
		  $this->lNegocioResultadoInspeccion->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - ResultadoInspeccion
		*/
		 public function tablaHtmlResultadoInspeccion($tabla) 
		{
		foreach ($tabla as $fila) {
		    $razonSocial='';
		    $consulta = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($fila['identificador']);
		    foreach ($consulta as $value) {
		        $razonSocial=$value->razon_social;
		    }
		    $mes = $this->lNegocioSolicitudInspeccion->nombreMes(date('n',strtotime($fila['fecha_creacion']) ));
		    $fecha = date('d',strtotime($fila['fecha_creacion']) ).' '.$mes.' '.date('Y',strtotime($fila['fecha_creacion']));
		    $this->itemsFiltrados[] = array(
		        '<tr id="' . $fila['id_solicitud_inspeccion'] .'" class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'InspeccionMusaceas\resultadoInspeccion"
		        data-opcion="editar" ondragstart="drag(event)" draggable="true"
		        data-destino="detalleItem">
    		  <td>' . $fila['codigo_solicitud'] . '</td>
    		  <td style="white - space:nowrap; "><b>' . $razonSocial . '</b></td>
                <td>' . $fila['estado'] . '</td>
                <td>' . $fila['pais_destino'] . '</td>
                <td>' . $fecha . '</td>
                <td>' . $fila['provincia'] .'</td>
                </tr>');
		    }
		 }
		 /**
		  * Construye el código HTML para desplegar la lista de - ResultadoInspeccion
		  */
		 public function tablaHtmlResultadoNotificacion($tabla)
		 {
		     foreach ($tabla as $fila) {
		         $razonSocial='';
		         $consulta = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($fila['identificador']);
		         foreach ($consulta as $value) {
		             $razonSocial=$value->razon_social;
		         }
		         $mes = $this->lNegocioSolicitudInspeccion->nombreMes(date('n',strtotime($fila['fecha_creacion']) ));
		         $fecha = date('d',strtotime($fila['fecha_creacion']) ).' '.$mes.' '.date('Y',strtotime($fila['fecha_creacion']));
		         $this->itemsFiltrados[] = array(
		             '<tr id="' . $fila['id_solicitud_inspeccion'] .'" class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'InspeccionMusaceas\resultadoInspeccion"
		        data-opcion="abrirNotificar" ondragstart="drag(event)" draggable="true"
		        data-destino="detalleItem">
    		  <td>' . $fila['codigo_solicitud'] . '</td>
    		  <td style="white - space:nowrap; "><b>' . $razonSocial . '</b></td>
                <td>' . $fila['estado'] . '</td>
                <td>' . $fila['pais_destino'] . '</td>
                <td>' . $fecha . '</td>
                <td>' . $fila['provincia'] .'</td>
                </tr>');
		     }
		 }
		 /**
		  * Construye el código HTML para desplegar la lista de - ResultadoInspeccion
		  */
		 public function tablaHtmlResultadoInspeccionRevisar($tabla)
		 {
		     foreach ($tabla as $fila) {
		         $razonSocial='';
		         $consulta = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($fila['identificador']);
		         foreach ($consulta as $value) {
		             $razonSocial=$value->razon_social;
		         }
		         $mes = $this->lNegocioSolicitudInspeccion->nombreMes(date('n',strtotime($fila['fecha_creacion']) ));
		         $fecha = date('d',strtotime($fila['fecha_creacion']) ).' '.$mes.' '.date('Y',strtotime($fila['fecha_creacion']));
		         $this->itemsFiltrados[] = array(
		             '<tr id="' . $fila['id_solicitud_inspeccion'] .'" class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'InspeccionMusaceas\resultadoInspeccion"
		        data-opcion="revisar" ondragstart="drag(event)" draggable="true"
		        data-destino="detalleItem">
    		  <td>' . $fila['codigo_solicitud'] . '</td>
    		  <td style="white - space:nowrap; "><b>' . $razonSocial . '</b></td>
                <td>' . $fila['estado'] . '</td>
				<td>' . $fila['pais_destino'] . '</td>
                <td>' . $fecha . '</td>
                <td>' . $fila['provincia'] .'</td>
                </tr>');
		     }
		 }
		 //**********************devolver total de cajas
		 public function devolverTotal(){
		     $estado = 'EXITO';
		     $mensaje = '';
		     $contenido = '';
		     if(isset($_POST['idSolicitudInspeccion'])){
		         if($_POST['opcion'] == 'total'){
        		     $resultado = $this->lNegocioDetalleSolicitudInspeccion->sumarCajas($_POST['idSolicitudInspeccion']);
        		     if($resultado->count()){
        		         $contenido = $resultado->current()->total;
        		     }else{
        		         $estado = 'ERROR';
        		         $mensaje = 'No existen registros...!!';
        		     }
		         }else{
		             if(isset($_POST['idCajas'])){
		                 $total=0;
		                 foreach ($_POST['idCajas'] as $value) {
		                     $result = $this->lNegocioDetalleSolicitudInspeccion->buscar($value);
		                     $total = $total + $result->getNumCajas();
		                 }
		                 $contenido= $total;
		             }else{
		                 $estado = 'ERROR';
		                 $mensaje = 'Debe enviar un valor...!!';
		             }
		         }
		     }else{
		         $estado = 'ERROR';
		         $mensaje = 'Debe enviar un valor...!!';
		     }
		     echo json_encode(array(
		         'estado' => $estado,
		         'mensaje' => $mensaje,
		         'contenido' => $contenido
		     ));
		 }
		 
		 public function filtrarInfoAtender(){
		     $estado = 'EXITO';
		     $mensaje = '';
		     $contenido = '';
		     $modeloResultadoInspeccion = array();
		     $this->perfilUsuario();
	         $_POST['estadoSolicitud']="Enviada";
	         if(in_array('PFL_IEA_MUS', $this->perfilUsuario)){
	         	$arrayParametros = array('fecha' => $_POST['fecha'], 'estadoSolicitud'=>(isset($_POST['estadoSolicitud']))?$_POST['estadoSolicitud']:'','identificador' => $_POST['identificador'],'numeroSolicitud' => $_POST['numeroSolicitud'],'identificador_inspeccion_externa' => $_SESSION['usuario']);
	         }else{
	         	$arrayParametros = array('provincia' => $_SESSION['nombreProvincia'],'fecha' => $_POST['fecha'], 'estadoSolicitud'=>(isset($_POST['estadoSolicitud']))?$_POST['estadoSolicitud']:'','identificador' => $_POST['identificador'],'numeroSolicitud' => $_POST['numeroSolicitud'],'inspeccion_externa' => 'no');
	         }
	         $modeloResultadoInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
		     if($modeloResultadoInspeccion->count()==0){
		         $estado = 'FALLO';
		         $mensaje = 'No existen registros para la busqueda..!!';
		     }
		     
             $this->tablaHtmlResultadoInspeccion($modeloResultadoInspeccion);
		     $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		     echo json_encode(array(
		         'estado' => $estado,
		         'mensaje' => $mensaje,
		         'contenido' => $contenido));
		 }
		 
		 public function filtrarInfoNotificar(){
		     $estado = 'EXITO';
		     $mensaje = '';
		     $contenido = '';
		     $modeloResultadoInspeccion = array();
		     $this->perfilUsuario();
		     $_POST['estadoSolicitud']="Enviada','Atendida";
		     $arrayParametros = array('provincia' => $_SESSION['nombreProvincia'],'fecha' => $_POST['fecha'], 'estadoSolicitud'=>(isset($_POST['estadoSolicitud']))?$_POST['estadoSolicitud']:'','identificador' => $_POST['identificador'],'numeroSolicitud' => $_POST['numeroSolicitud'], 'lugar_inspeccion' => 'Puerto');
		     $modeloResultadoInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
		     if($modeloResultadoInspeccion->count()==0){
		         $estado = 'FALLO';
		         $mensaje = 'No existen registros para la busqueda..!!';
		     }
		     
		     $this->tablaHtmlResultadoNotificacion($modeloResultadoInspeccion);
		     $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		     echo json_encode(array(
		         'estado' => $estado,
		         'mensaje' => $mensaje,
		         'contenido' => $contenido));
		 }
		 
		 public function filtrarInfoRevisar(){
		     $estado = 'EXITO';
		     $mensaje = '';
		     $contenido = '';
		     $modeloResultadoInspeccion = array();
		     $this->perfilUsuario();
		     $arrayParametros = array('tiempo_busqueda' => 60,'fecha' => $_POST['fecha'], 'estadoSolicitud'=>(isset($_POST['estadoSolicitud']))?$_POST['estadoSolicitud']:'','identificador' => $_POST['identificador'],'numeroSolicitud' => $_POST['numeroSolicitud']);
		     $modeloResultadoInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
		     if($modeloResultadoInspeccion->count()==0){
		         $estado = 'FALLO';
		         $mensaje = 'No existen registros para la busqueda..!!';
		     }
		     $this->tablaHtmlResultadoInspeccionRevisar($modeloResultadoInspeccion);
		     $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		     echo json_encode(array(
		         'estado' => $estado,
		         'mensaje' => $mensaje,
		         'contenido' => $contenido));
		 }
		 /**
		  * Método para desplegar el inspeccion
		  */
		 public function visualizar()
		 {
		     $this->urlPdf = $_POST['id'];
		     require APP . 'InspeccionMusaceas/vistas/visorPDF.php';
		 }
		 /**
		  *Obtenemos los datos del registro seleccionado para abrir notificar
		  */
		 public function abrirNotificar()
		 {
		     $this->perfilUsuario();
		     $this->accion = "Notificar Inspección";
		     $this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST["id"]);
		     $this->datosGenerales=$this->datosGenerales($this->modeloSolicitudInspeccion->getIdentificador());
		     $this->listarProductores = $this->listarProductores($this->modeloSolicitudInspeccion->getIdSolicitudInspeccion(),1);
		     $this->operador =  $this->modeloSolicitudInspeccion->getIdentificador();
		     require APP . 'InspeccionMusaceas/vistas/formularioResultadoNotificarVista.php';
		     
		 }
		 /**
		  * Método para registrar en la base de datos -notificacion
		  */
		 public function guardarNotificar()
		 {
		 	$estado = 'exito';
		 	$mensaje = '';
		 	$contenido ='';
		 	$operador = $this->lNegocioSolicitudInspeccion->obtenerCorreoOperador($_POST['identificador']);
		 	$correo='';
		 	$correo = $operador->current()->correo;
		 	$exportador=$operador->current()->razon_social;
		 	$_POST['identificador_registro']=$_SESSION['usuario'];
		 	$_POST['correo_exportador']=$correo;
		 	$resultado= $this->lNegocioNotificarSolicitud->guardarNotificar($_POST);
		 	
		 	if($resultado){
		 		$this->modeloNotificarSolicitud = $this->lNegocioNotificarSolicitud->buscar($resultado);
		 		$this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($this->modeloNotificarSolicitud->getIdSolicitudInspeccion());
		 		
		 		$arrayMailsDestino = array();
		 		if($this->modeloNotificarSolicitud->getCorreoExportador()!= ''){
		 			$arrayMailsDestino[] =$this->modeloNotificarSolicitud->getCorreoExportador();
		 		}
		 		// $familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
		 		$familiaLetra = "";
		 		$emails = $this->lNegocioDetalleNotificarInspeccion->buscarLista('id_notificar_inspeccion ='.$this->modeloNotificarSolicitud->getIdNotificarSolicitud());
		 		$listaProductores = '<tr><th>Razón Social</th>          <th>   Provincia </th>      <th>Área(Finca)   </th>      <th>  Código MAG  </th>      <th>    Cajas</th></tr>';
		 		foreach ($emails as $value) {
		 			if( $value->correo_productor != ''){
		 				$arrayMailsDestino[] = $value->correo_productor;
		 			}
		 			$this->modeloDetalleSolicitudInspeccion = $this->lNegocioDetalleSolicitudInspeccion->buscar($value->id_detalle_solicitud_inspeccion);
		 			$listaProductores .= '<tr style=" font-size:12px; "><td >'.$this->modeloDetalleSolicitudInspeccion->getRazonSocial().'</td>
                                              <td >'.$this->modeloDetalleSolicitudInspeccion->getProvincia().'</td>
                                              <td >'.$this->modeloDetalleSolicitudInspeccion->getArea().'</td>
                                              <td >'.$this->modeloDetalleSolicitudInspeccion->getCodigoMag().'</td>
                                              <td >'.$this->modeloDetalleSolicitudInspeccion->getNumCajas().'</td></tr>';
		 		}
		 		$fecha = $this->lNegocioResultadoInspeccion->fecha(date('Y-m-d'));
		 		$mailsDestino = array_unique($arrayMailsDestino);
		 		$arrayEmail = array(
		 			'id_notificar_solicitud' => $resultado,
		 			'fecha' => $fecha,
		 			'puerto' => $this->modeloSolicitudInspeccion->getPuertoEmbarque(),
		 			'codigo_solicitud' => $this->modeloSolicitudInspeccion->getCodigoSolicitud(),
		 			'listaProductores' => $listaProductores,
		 			'tecnicoCargo' => $_SESSION['datosUsuario'],
		 			'correo' => $mailsDestino,
		 			'nombreExportador' => $exportador
		 		);
		 		
		 		$this->lNegocioSolicitudInspeccion->notificarEmail($arrayEmail);
		 		
		 	}else {
		 		$estado = 'ERROR';
		 		$mensaje = 'Error al guardar los datos !!';
		 	}
		 	
		 	echo json_encode(array(
		 		'estado' => $estado,
		 		'mensaje' => $mensaje,
		 		'contenido' => $contenido
		 	));
		 }
		 
		 function verificarOperador(){
		     $verificarUsuario=true;
		     if(!isset($_SESSION['datosUsuario'])){
		         $buscarDatosOperador = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($_SESSION['usuario']);
		         if($buscarDatosOperador->count()){
		             if($buscarDatosOperador->current()->razon_social != ''){
		                 $_SESSION['datosUsuario'] = $buscarDatosOperador->current()->razon_social;
		             }else{
		                 $verificarUsuario=false;
		                 $this->validarOperador = 'error';
		             }
		             if($buscarDatosOperador->current()->provincia != ''){
		                 $_SESSION['nombreProvincia'] = $buscarDatosOperador->current()->provincia;
		             }else{
		                 $verificarUsuario=false;
		                 $this->validarOperador = 'error';
		             }
		             
		         }else{
		             $verificarUsuario=false;
		             $this->validarOperador = 'error';
		         }
		     }
		     if($_SESSION['nombreProvincia'] == ''){
		         $verificarUsuario=false;
		         $this->validarOperador = 'errorProvincia';
		     }
		     return $verificarUsuario;
		 }
		 
		 public function generarReporteInspeccion()
		 {
		 	$estado = 'exito';
		 	$mensaje = '';
		 	$this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST['id_solicitud_inspeccion']);
		 	if(1){
		 		$operador = $this->lNegocioResultadoInspeccion->buscarLista("id_solicitud_inspeccion=".$this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());
		 		
		 		$tecnicoConsulta = $this->lNegocioSolicitudInspeccion->obtenerDatosTecnico($operador->current()->identificador_operador);
		 		if($tecnicoConsulta->count() == 0 ){
		 			$tecnicoConsulta = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($operador->current()->identificador_operador);
		 		}
		 		
		 		$rutaReporte = 'InspeccionMusaceas/vistas/reportes/inspeccionFitosanitariaMusaceas.jasper';
		 		$rutaCarpeta = INSP_MUS_URL."inspeccionMusaceas/". $this->modeloSolicitudInspeccion->getIdentificador();
		 		if (!file_exists('../../' . $rutaCarpeta)) {
		 			mkdir('../../' .$rutaCarpeta, 0777, true);
		 		}
		 		$nombre = 'inspeccion_musaceas_';
		 		$rutaArchivo = "inspeccionMusaceas/".$this->modeloSolicitudInspeccion->getIdentificador()."/".$nombre.$this->modeloSolicitudInspeccion->getCodigoSolicitud();
		 		try {
		 			$jasper = new JasperReport();
		 			$datosReporte = array();
		 			$rutaArchivoBase = 'InspeccionMusaceas/archivos/';
		 			$datosReporte = array(
		 				'rutaReporte' => $rutaReporte,
		 				'rutaSalidaReporte' => $rutaArchivoBase.$rutaArchivo,
		 				'tipoSalidaReporte' => array('pdf'),
		 				'parametrosReporte' => array(
		 					'id_solicitud_inspeccion' => $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion(),
		 					'tecnico' => $tecnicoConsulta->current()->tecnico,
		 					'fondoCertificado' => RUTA_IMG_GENE.'fondoCertificado.png'),
		 				'conexionBase' => 'SI'
		 			);
		 			$validar=1;
		 			$jasper->generarArchivo($datosReporte);
		 			$contenido = INSP_MUS_URL.$rutaArchivo.'.pdf';
		 			
		 			
		 		} catch (\Exception  $e) {
		 			$validar=0;
		 		}
		 		if($validar){
		 			
		 		}else{
		 			$estado = 'ERROR';
		 			$mensaje = 'Error al crear el archivo pdf de la historia clínica';
		 		}
		 	}else {
		 		$estado = 'ERROR';
		 		$mensaje = 'Error al guardar los datos !!';
		 	}
		 	
		 	echo json_encode(array(
		 		'estado' => $estado,
		 		'mensaje' => $mensaje,
		 		'contenido' => $contenido
		 	));
		 }
}

