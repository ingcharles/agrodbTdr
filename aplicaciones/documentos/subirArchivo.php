<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorSolicitudes.php';
require_once '../../clases/ControladorAuditoria.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cd = new ControladorDocumentos();
$cs = new ControladorSolicitudes();
$ca = new ControladorAuditoria();
$cu = new ControladorUsuarios();

$conexion->verificarSesion();

ini_set('max_execution_time', 300);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../general/estilos/agrodb_papel.css' >
<link rel='stylesheet' href='../general/estilos/agrodb.css'>
</head>
<body>
	<?php
		
	$notificar = $_POST['notificar'];
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	$nuevo_nombre = $_POST['id_documento'].'_'.($notificarTodos==1?'borrador':$_SESSION['usuario']).'.'.end($extension);
	//Usado inicialmente para subir archivo PDF como documento final
	//$nombre_final = $_POST['id_documento'].".".end($extension);
	$nombre_final = "";
	$id_solicitud = $_POST['id_solicitud'];
	$rutaBorradores = $_POST['rutaBorradores'];
	$opcion = $_POST['boton'];
	$nuevaObservacion = $_POST['nuevaObservacion'];
	$actualObservacion = $_POST['actualObservacion'];
	
	$r_condicion = $cs -> condicionSolicitud($conexion, $id_solicitud);
	$condicion = pg_fetch_assoc($r_condicion);
	
	$qTransaccion = $ca -> buscarTransaccion($conexion, $id_solicitud, $_SESSION['idAplicacion']);
	$transaccion = pg_fetch_assoc($qTransaccion);
	
	if($transaccion['id_transaccion'] == ''){
		$qLog = $ca -> guardarLog($conexion,$_SESSION['idAplicacion']);
		$qTransaccion = $ca ->guardarTransaccion($conexion, $id_solicitud, pg_fetch_result($qLog, 0, 'id_log'));
	}
	
	$fecha= date('Y-m-d (H:i)');
	
	$actualObservacion .= '<li><b>'.$fecha.':</b> '.$nuevaObservacion.'</li>';
	
	if($nuevaObservacion != ''){
		$cs->actualizarObservacionSolicitud($conexion, $id_solicitud, $actualObservacion);
	}
	
	
	// Hacemos una condicion en la que solo permitiremos que se suban imagenes y que sean menores a 200 KB
	
	if($opcion == 'borrador'){
		if ((/*$archivo_tipo == 'application/rtf' || $archivo_tipo == 'text/rtf' || $archivo_tipo == 'application/msword' && */strtoupper(end($extension)) == 'DOCX') && ($archivo_peso < 5242880)) {

			//Si es que hubo un error en la subida, mostrarlo, de la variable $_FILES podemos extraer el valor de [error], que almacena un valor booleano (1 o 0).
			if ($archivo_error > 0) {
				echo '<p class="alerta">'. $archivo_error . '</p>';
			} else {
				move_uploaded_file($archivo_temporal,  'archivosSubidos/' . $nuevo_nombre);
				//echo " ";
				//echo "Descargar Archivo: <a href='archivosSubidos/" .$nuevo_nombre."'>".$archivo_nombre."</a>";
				//echo"<object type='application/pdf' data='archivosSubidos/" .$_FILES["archivo"]["name"]."' width='100%' height='100%'</object>";
				$rutaArchivo =  "aplicaciones/documentos/archivosSubidos/".$nuevo_nombre;
				
				if($notificar=="TODOS" && $condicion['condicion'] == 'creado'){
					$cs -> actualizarEstadoRevisores($conexion, $id_solicitud);
					$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ENVIADO');
					$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'] .'</b> sube el archivo '.$nuevo_nombre.' y se envia la notificación a los revisores');
					
				//} else if($notificar=="TODOS" && $condicion['condicion'] == 'atendido'){
				} else if($notificar=="TODOS"){
					$cs -> actualizarEstadoRevisores($conexion, $id_solicitud);
					$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'REENVIADO');
					$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'<b>'. $_SESSION['datosUsuario'] .'</b> sube el archivo '.$nuevo_nombre.' corregido y se envia la notificación a los revisores');
					
				} else if ($notificar=="INDIVIDUAL"){
					$rutaArchivo.= ';'.$rutaBorradores;
					$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'].'</b> revisa y reenvia el archivo  '.$nuevo_nombre);
				}
				
				//echo 'ruta del archivo'.$rutaArchivo;
				$cd -> actualizarBorradorDocumento($conexion, $_POST['id_documento'],$rutaArchivo);
				echo '<p class="exito">¡Archivo cargado!</p>';
			}
		} else {
			// Si el usuario intenta subir algo que no es una imagen o una imagen que pesa mas de 20 KB mostramos este mensaje
			echo '<p class="alerta">¡Archivo no permitido!</p>';
		}
	}else if($opcion == 'aprobador'){
		if ((strtoupper(end($extension)) == 'DOCX') && ($archivo_peso < 5242880)) {
			
			$res = $cs -> estadoAprobador($conexion, $id_solicitud);
			$aprobador = pg_fetch_assoc($res);
			
			if ($archivo_error > 0 || ( $_POST['p_aprobador']!=''? false:($aprobador['identificador']== ''?true:false))) {
				echo '<p class="alerta"> Existe un error al procesar su solicitud.'. $archivo_error . '</p>';
			} else {
				move_uploaded_file($archivo_temporal,  'archivosSubidos/' . $nuevo_nombre);
				$rutaArchivo =  "aplicaciones/documentos/archivosSubidos/".$nuevo_nombre;
				
								
				if($aprobador['identificador']== ''){
					$cd -> ingresaRegistradores($conexion, $id_solicitud, $_POST['p_aprobador'],'Aprobador');
				}
				//CAMBIO
				
				$nombreUsuario = $cu->obtenerNombresUsuario($conexion,($aprobador['identificador']== ''? $_POST['p_aprobador']: $aprobador['identificador']));
				
				$cs -> actualizarEstadoAprobador($conexion, $id_solicitud,$aprobador['identificador']);
				
				$cd -> actualizarBorradorDocumento($conexion, $_POST['id_documento'],$rutaArchivo);
				$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'REVISIONRESPONSABLE');
				//$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'REENVIADO');
				echo '<p class="exito">¡Archivo cargado!</p>';
				$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'].'</b> solicita revisión  del archivo a '. pg_fetch_result($nombreUsuario, 0, 'apellido').' '.pg_fetch_result($nombreUsuario, 0, 'nombre') .' (Aprobador)' );
			
			}
		} else {
			echo '<p class="alerta">¡Archivo no permitido!</p>';
		}
	}
	
	
	
	else if($opcion == 'pre_final'){		
		if ((strtoupper(end($extension)) == 'DOCX') && ($archivo_peso < 5242880)) {
			$nombre_final = $cd->generarNombreArchivo($conexion, $_POST['id_documento']); 
			if ($archivo_error > 0) {
				echo $archivo_error . "<br />";
			} else {
				move_uploaded_file($archivo_temporal,  "finales_rtf/" . $nombre_final. '.docx');
				echo "<span class='exito'>Archivo <a href='finales_rtf/$nombre_final.docx'>$nombre_final</a> exitosamente</span>";
			
				
				$rutaArchivo =  "aplicaciones/documentos/finales_rtf/".$nombre_final.".docx";
				$cd -> actualizarDocumentoPreFinal($conexion, $_POST['id_documento'],$nombre_final,$rutaArchivo,$id_solicitud);
				$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ARCHIVADO');
				setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
				$auxNombreArchivo = explode('.', $nombre_final);
				$cd -> actualizarRtf($nombre_final, array('_NUMERO_' => $auxNombreArchivo[1], '_CODIGO_' => $auxNombreArchivo[0], '_FECHA COMPLETA_' => strftime("%d de %B del %Y")));
				//$cd -> rtf($nombre_final, array('_NUMERO_' => $nombre_final, '_FECHA COMPLETA_' => strftime("%d de %B del %Y")), 'finales_rtf/');
				$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'] .'</b> asigna el número ' .$nombre_final.' al archivo.');
			}
		} else {
			// Si el usuario intenta subir algo que no es una imagen o una imagen que pesa mas de 20 KB mostramos este mensaje
			echo "El archivo seleccionado no es permitido.";
		}
	}else if($opcion == 'final'){
		//Esto originalmente usado para subir PDF como archivo final
		//if (($archivo_tipo == "application/pdf") && ($archivo_peso < 5242880)) {
		
		$correo = $_POST['correo'];
		$cMail = new ControladorMail();
		
		if ((strtoupper(end($extension)) == 'PDF') && ($archivo_peso < 6291456) && $correo != '') {
			$res_id = $cd->abrirDocumentoPorSolicitud($conexion, $id_solicitud);
			$documento = pg_fetch_assoc($res_id);
			
		//if (($archivo_tipo == 'application/rtf' || $archivo_tipo == 'text/rtf') && ($archivo_peso < 5242880)) {
			//$nombre_final = $cd->generarNombreArchivo($conexion, $_POST['id_documento']); //nombre definitivo
			//Si es que hubo un error en la subida, mostrarlo, de la variable $_FILES podemos extraer el valor de [error], que almacena un valor booleano (1 o 0).
			if ($archivo_error > 0) {
				echo $archivo_error . "<br />";
			} else {
				//move_uploaded_file($archivo_temporal,  "finales/" . $nombre_final. '.rtf');
				move_uploaded_file($archivo_temporal,  "finales_pdf/" . $documento['id_documento']. '.pdf');
				//echo "<span class='exito'>Archivo <a href='finales/$nombre_final.rtf'>$nombre_final</a> exitosamente</span>";
				
				echo "<span class='exito'>Archivo cargado<a href='finales_pdf/".$documento['id_documento'].".pdf'>".$documento['id_documento']."</a> exitosamente</span>";

				//--------------------------------------------------------------------------------------------------------------------------
				
				$asunto = $documento['asunto'];
				$cuerpoMensaje = 'Estimado Usuario: <br/><br/>Proceso jurídico '.$documento['asunto'].' finalizado. <br/><br/> Sírvase encontrar adjunto documento de respaldo.<br/><br/> Este es un mensaje automático enviado por el sisitema GUIA, por favor no lo responda.' ;
				
				$destinatario = array();
				
				$correos = explode(';', $correo);
				
				foreach ($correos as $correo){
					array_push($destinatario, $correo);
				}
				
				//array_push($destinatario, $correo);
				
				$adjuntos = array();
				array_push($adjuntos, "finales_pdf/".$documento['id_documento'].".pdf");
				
				$estadoMail = $cMail->enviarMail($conexion, $destinatario, $asunto, $cuerpoMensaje, $adjuntos);
				
				//----------------------------------------------------------------------------------------------------------------------------
				
				//echo"<object type='application/pdf' data='archivosSubidos/" .$_FILES["archivo"]["name"]."' width='100%' height='100%'</object>";
				//$cd -> actualizarDocumentoFinal($conexion, $_POST['id_documento'],$nombre_final, "aplicaciones/documentos/finales/".$nombre_final.".rtf");
				$cd -> actualizarDocumentoFinal($conexion, $documento['id_documento'], "aplicaciones/documentos/finales_pdf/".$documento['id_documento'].".pdf", $estadoMail);
				$cs->actualizarFehaAprobacionSolicitud($conexion, $id_solicitud);
				$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'FINALIZADO');
				   
   				$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'].'</b> sube el archivo final (PDF) y el proceso finaliza.');
				
			}
		} else {
			// Si el usuario intenta subir algo que no es una imagen o una imagen que pesa mas de 20 KB mostramos este mensaje
			echo "El archivo seleccionado no es permitido o la dirección de correo electrónico no ha sido ingresada.";
		}
	}

	?>

</body>
</html>

