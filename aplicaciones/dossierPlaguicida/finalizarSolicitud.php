<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once 'clases/GeneradorDocumentoPlaguicida.php';

require_once '../ensayoEficacia/clases/Transaccion.php';
require_once '../ensayoEficacia/clases/Flujo.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$procesado=false;

try{
	$conexion = new Transaccion();
	$cg = new ControladorDossierPlaguicida();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$dato=array();

	$identificador= $_SESSION['usuario'];
	$id_sitio=$_POST['id_sitio'];
	try{
		$id_solicitud=intval($_POST['id_solicitud']);
		if($id_solicitud>0)
			$dato['id_solicitud'] = $id_solicitud;
	}catch(Exception $e){}
	$id_flujo=$_POST['id_flujo'];
	$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
	$flujoActual=new Flujo($flujos,'DG','es_clon',$id_flujo);
	$es_clon=$_POST['es_clon'];
	$id_subtipo_producto= htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
	$flujoActual=$flujoActual->InicializarFlujo($es_clon,'',1);

	$identificador_destino=$flujoActual->PerfilSiguiente();


	$flujoActual->CambiarSelectorValor('');
	$flujoActual->CambiarSelector('');
	$flujo=$flujoActual->BuscarFaseSiguiente();

	if($flujo!=null){

		$dato['estado'] =$flujo->EstadoActual();


		$fecha=new DateTime();

		$fechaSubsanacion=clone $fecha;
		$fecha = $fecha->format('Y-m-d');

		$plazo=$flujo->Plazo();
		$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo en DIAS
		$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
		//*************** DISTRIBUYO EL TRAMITE ********
		if($flujo->EstadoActual()=='pago'){
			$flujo=$flujo->BuscarFaseSiguiente();
		}
		//$division="DIV_PICH";
		$dato['id_expediente']=$ce->obtenerExpedienteDossierPlaguicida($conexion,date('Y'));	//Genera el codigo del expediente
		try{
			$cGenerador=new GeneradorDocumentoPlaguicida();

			$dossier=$cg->obtenerSolicitud($conexion,$id_solicitud);
			if($es_clon=='t'){
				try{
					
					if(($dossier['clon_registro_madre']!=null)&&(strlen($dossier['clon_registro_madre'])>1)){
						
						if($ce->contieneParaquatProducto($conexion,$dossier['clon_registro_madre'])){
							$plazo=$flujo->PlazoAlterno();							
						}
					}
				}catch(Exception $e){}
			}
			else{
				$respuesta=$cGenerador->generarDossier($conexion,$id_solicitud);
				$dato['ruta_dossier']=$respuesta['datos'];
				//fecha para conformar grupos
				$fechaGrupo=new DateTime();
				$fechaBase=new DateTime();
				$fechaGrupo->add(new DateInterval('P1M'));	//añade un mes
				$fechaGrupo=$fechaGrupo->setDate((int)$fechaGrupo->format('Y'),(int)$fechaGrupo->format('n'),1);
				$intervalo=$fechaGrupo->diff($fechaBase);
				$plazo=(int)$intervalo->days+(int)$flujo->Plazo();
				//Verifica si contiene paraquat
				try{
					
					if(($dossier['protocolo']!=null)&&(strlen($dossier['protocolo'])>1)){
						//Busca ingredientes en protocolo
						$protocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$dossier['protocolo']);
						if($ce->contieneParaquat($conexion,$protocolo['id_protocolo'])){
							$plazo=(int)$intervalo->days+(int)$flujo->PlazoAlterno();
							
						}
					}
				}catch(Exception $e){}
				$fechaSubsanacion=new DateTime();
				$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo en DIAS
				$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			}
			$respuestaSolicitud=$cGenerador->generarSolicitudRegistro($conexion,'SI',$id_solicitud);

			$conexion->Begin();
			$dato['fecha_solicitud'] = $fecha;			
			$dato['ruta_solicitud']=$respuestaSolicitud['datos'];
			$cg ->guardarSolicitud($conexion,$dato);

			$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujo->TipoDocumento(),$id_solicitud,$identificador_destino,$fecha,$fechaSubsanacion,'S',$division);
			$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujo->Flujo_documento(),$identificador_destino,$identificador,$identificador,'S',$fecha,$fechaSubsanacion,'',$plazo);

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
				mostrarMensaje('La solicitud de dossier de plaguicida ha sido enviada','EXITO');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);

			}
			else{
				mostrarMensaje(mensaje.mensaje,'FALLO');
			}
		});


</script>

</html>

