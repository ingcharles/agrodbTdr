<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once 'clases/GeneradorDocumentoPecuario.php';

require_once '../ensayoEficacia/clases/Transaccion.php';
require_once '../ensayoEficacia/clases/Flujo.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$procesado=false;

try{
	$conexion = new Transaccion();
	$cp = new ControladorDossierPecuario();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$dato=array();

	$identificador= $_SESSION['usuario'];
	$id_sitio=$_POST['id_sitio'];
	$id_subtipo_producto= htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
	$dossier=array();
	try{
		$id_solicitud=intval($_POST['id_solicitud']);
		if($id_solicitud>0){
			$dato['id_solicitud'] = $id_solicitud;
			$dossier=$cp->obtenerSolicitud($conexion,$id_solicitud);
			$id_sitio=$dossier['id_sitio'];
			
			$id_subtipo_producto=$dossier['codificacion_subtipo_producto'];
		}
	}catch(Exception $e){}
	$id_flujo=$_POST['id_flujo'];

	if($id_flujo==null)
		$id_flujo=$_SESSION['idAplicacion'];

	$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
	$flujoActual=new Flujo($flujos,'DP','id_subtipo_producto',$id_flujo);
	
	$flujoActual=$flujoActual->InicializarFlujo($id_subtipo_producto,'',1);
	

	$division=$cp->identificarDivisionDeAtencionTramite($conexion,$id_sitio);

	$identificador_destino=$flujoActual->PerfilSiguiente();

	switch($id_subtipo_producto){
		case 'RIP-FAR':
		break;
		/*case 'RIP-BIO': EJAR
		case 'RIP-KD':
			if($division=='DIV_PICH' )
				$identificador_destino='PFL_RES_CENTRAL';
			$id_subtipo_producto='';
		break;*/
		default:
			$id_subtipo_producto='';
		break;
	}

	$flujoActual->CambiarSelectorValor($id_subtipo_producto);
	if($id_subtipo_producto==''){
		$flujoActual=$flujoActual->InicializarFlujo($id_subtipo_producto,'',1);
	}
	$flujo=$flujoActual->BuscarFaseSiguiente();

	$flujoSiguiente=array();
	if($id_subtipo_producto!='RIP-FAR'){
		//Continua fase después de pago
		$flujo->CambiarSelector('tramite_destino');

		if($identificador_destino=='PFL_RES_CENTRAL'){
			$flujoSiguiente=$flujo->BuscarFaseSiguienteConCondicion($condicion,null,null,'planta_central');
		}
		else{
			$flujoSiguiente=$flujo->BuscarFaseSiguiente();
		}

		

	}
	else{
		$flujoSiguiente=$flujo;
	}
	
	if($flujo!=null){
		$dato['id_flujo_documento'] =$flujo->Flujo_documento();
		$dato['estado'] =$flujo->EstadoActual();

		$fecha=new DateTime();

		$fechaSubsanacion=clone $fecha;
		$fecha = $fecha->format('Y-m-d');

		$plazo=$flujo->Plazo();
		$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo en DIAS
		$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
		//*************** DISTRIBUYO EL TRAMITE ********
		$dato['id_expediente']=$ce->obtenerExpedienteDossierPecuario($conexion,date('Y'));	//Genera el codigo del expediente
		try{
			$dato['fecha_solicitud'] = $fecha;

			$cDossier=new GeneradorDocumentoPecuario();

			$conexion->Begin();
			$msgDossier=$cDossier->generarDossier($conexion,$id_solicitud,false);
			$dato['ruta_dossier']=$msgDossier['datos'];
			$cp ->guardarSolicitud($conexion,$dato);

			$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujoSiguiente->TipoDocumento(),$id_solicitud,$identificador_destino,$fecha,$fechaSubsanacion,'S',$division);
			$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujoSiguiente->Flujo_documento(),$identificador_destino,$identificador,$identificador,'S',$fecha,$fechaSubsanacion,'',$plazo);

			$conexion->Commit();
			$procesado=true;
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido enviada';
		}
		catch(Exception $e){
			$conexion->Rollback();
			$mensaje['estado'] = 'fallo';
			$mensaje['mensaje'] = 'Error al generar el trámite';
		}
	}
	else{
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al localiza el flujo";
	}
	$conexion->desconectar();
}
catch (Exception $ex) {
	pg_close($conexion);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';

}

?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />

</head>
<body>

  

   <div id="estado"></div>

   

</body>




<script type="text/javascript">

	var procesado= <?php echo json_encode($procesado); ?>;
	var mensaje= <?php echo json_encode($mensaje); ?>;

		$(document).ready(function(){
			if(procesado==true){
				mostrarMensaje('La solicitud de dossier pecuario ha sido enviada','EXITO');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);

			}
			else{
				mostrarMensaje(mensaje.mensaje,'FALLO');
			}
		});


</script>

</html>



