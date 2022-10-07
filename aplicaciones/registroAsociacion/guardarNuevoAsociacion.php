<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';

function generarCodigoOperador($longitud) {
	$key = '';
	$pattern = '1234567890';
	$max = strlen($pattern)-1;
	for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
	return $key;
}


$latitud = '795204.2670016843';
$longitud = '9849275.758170985';


	$nombreAsociacion=$_POST['nombreAsociacion'];
	$direccionAsociacion=$_POST['direccionAsociacion'];
	$telefonoAsociacion=$_POST['telefonoAsociacion'];
	$mailAsociacion=$_POST['mailAsociacion'];
	$provincia=$_POST['provincia'];
	$canton=$_POST['canton'];
	$parroquia=$_POST['parroquia'];
	
	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cu = new ControladorUsuarios();
	$cc = new ControladorCatalogos();
	$cMail = new ControladorMail();
	$ca = new ControladorAplicaciones();
	$cgap= new ControladorGestionAplicacionesPerfiles();
		
		
	$i=1;
	
	for($x=0; $x<$i; $x++){
		
		$nuevoCodigoOperador='ORG'.generarCodigoOperador(7);
		
		$operador = $cr->buscarOperador($conexion, $nuevoCodigoOperador);
		$usuario = $cu->verificarUsuario($conexion, $nuevoCodigoOperador);
	
		if(pg_num_rows($operador) > 0 || pg_num_rows($usuario) > 0){
			$x--;
		}else{
			break;
		}
	}
	
////----------------------------------------------//

	$provincia = $cc->obtenerNombreLocalizacion($conexion, $provincia);
	$canton = $cc->obtenerNombreLocalizacion($conexion, $canton);
	$parroquia = $cc->obtenerNombreLocalizacion($conexion, $parroquia);

	//GUARDAR REGISTRO OPERADOR
	
	$cr->guardarRegistroOperador($conexion, 'operadorOrganico', $nuevoCodigoOperador, $nombreAsociacion, '', '',
			'', '', pg_fetch_result($provincia, 0, 'nombre'),pg_fetch_result($canton, 0, 'nombre'),pg_fetch_result($parroquia, 0, 'nombre'),
			$direccionAsociacion, $telefonoAsociacion, '', '',
			'', '', $mailAsociacion, md5($nuevoCodigoOperador));
	
	
	//CREAR CUENTA USUARIO
		
	$cu->crearUsuario($conexion, $nuevoCodigoOperador, md5($nuevoCodigoOperador));
	
	//ASIGNAR PERFIL A USUARIO EXTERNO
	
	$qPerfilExterno = $cu->buscarPerfilUsuario($conexion, $nuevoCodigoOperador, 'Usuario externo');
	
	if(pg_num_rows($qPerfilExterno)==0){
		$cu->crearPerfilUsuario($conexion, $nuevoCodigoOperador, 'Usuario externo');
	}
	
	$qPerfilAsociacion = $cu->buscarPerfilUsuario($conexion, $nuevoCodigoOperador, 'Registro rendimiento de Asociacion');//REVISAR
	
	if(pg_num_rows($qPerfilAsociacion)==0){//REVISAR
		$cu->crearPerfilUsuario($conexion, $nuevoCodigoOperador, 'Registro rendimiento de Asociacion');
	}
	
	//ASIGNAR PERFIL A USUARIO OPERADOR
	
	$qPerfilOperador = $cu->buscarPerfilUsuario($conexion, $nuevoCodigoOperador, 'Operadores');
	
	if(pg_num_rows($qPerfilOperador)==0){
		$cu->crearPerfilUsuario($conexion, $nuevoCodigoOperador, 'Operadores');
	}
	
	//AGREGAR SITIO Y AREA POR DEFECTO
	
	$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, pg_fetch_result($provincia, 0, 'nombre'), $nuevoCodigoOperador);
	$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
	
	
	$qIdSitio = $cr->guardarNuevoSitio($conexion, 'Oficina Central', pg_fetch_result($provincia, 0, 'nombre'),
			pg_fetch_result($canton, 0, 'nombre'), pg_fetch_result($parroquia, 0, 'nombre'), $direccionAsociacion, '', 0, $nuevoCodigoOperador, $telefonoAsociacion,
			$latitud, $longitud, $secuencialSitio, '0','17', substr(pg_fetch_result($provincia, 0, 'codigo_vue'),1));
	
	$qCodigoArea = $cc->buscarAreaOperadorXNombre($conexion, 'Domicilio tributario');
	$codigoArea = pg_fetch_assoc($qCodigoArea);
	
	$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $nuevoCodigoOperador, $codigoArea['codigo'],pg_fetch_result($provincia, 0, 'nombre'));
	$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
	$areas = $cr -> guardarNuevaArea($conexion, 'Oficina Principal', 'Domicilio tributario', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'), $codigoArea['codigo'], $secuencial);
	
	//---------------
	
	$qAplicacionOperadores = $ca->obtenerIdAplicacion($conexion,'PRG_REGISTROOPER');
	$aplicacionOperador = pg_fetch_result($qAplicacionOperadores, 0, 'id_aplicacion');
	
	$cu ->activarCuenta($conexion, $nuevoCodigoOperador, md5($nuevoCodigoOperador));
	
	///-------
			
	$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionOperador, $nuevoCodigoOperador);
	
	if (pg_num_rows($aplicacionOperadorRegistro) == 0){
		$ca->guardarAplicacionPerfil($conexion, $aplicacionOperador, $nuevoCodigoOperador, 0, 'notificaciones');
	}
	
	//AGREGAR APLICACION CARAVANAS
	
	//TODO: Esto se cambio abajo... para correr con el batch///////
	
	/*$qAplicacionCaravana = $ca->obtenerIdAplicacion($conexion,'PRG_INSCR_CRV');
	$aplicacionCaravana = pg_fetch_result($qAplicacionCaravana, 0, 'id_aplicacion');
	
	$aplicacionCaravanaRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionCaravana, $nuevoCodigoOperador);
	
	if (pg_num_rows($aplicacionCaravanaRegistro) == 0){
		$ca->guardarAplicacionPerfil($conexion, $aplicacionCaravana,$nuevoCodigoOperador, 0, 'notificaciones');
	}*/
	
	//AGREGAR APLICACION CATASTRO PRODUCTO, MOVILIZACION PRODUCTO, CARAVANAS
	
	$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_CATAS_PRODU','PRG_MOVIL_PRODU','PRG_INSCR_CRV')");
	
	while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
		$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_CATAG','PFL_EMISO_MOVIL','PFL_INSCR_CRV')");
		$perfilesArray=Array();
		while($fila=pg_fetch_assoc($qGrupoPerfiles)){
			$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
		}
	
		if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $nuevoCodigoOperador))==0){
			$qAplicacion=$cgap->guardarGestionAplicacion($conexion, $nuevoCodigoOperador,$filaAplicacion['codificacion_aplicacion']);
			foreach( $perfilesArray as $datosPerfil){
				$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $nuevoCodigoOperador);
				if (pg_num_rows($qPerfil) == 0)				
					$cgap->guardarGestionPerfil($conexion, $nuevoCodigoOperador,$datosPerfil['codigoPerfil']);
			}
		}else{
			foreach( $perfilesArray as $datosPerfil){
				$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $nuevoCodigoOperador);
				if (pg_num_rows($qPerfil) == 0)
					$cgap->guardarGestionPerfil($conexion, $nuevoCodigoOperador,$datosPerfil['codigoPerfil']);
			}
		}
	}
		
		
//----ENVIO DE MAIL A USUARIO---------//
		
	$familiaLetra = "font-family:Text Me One, Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
	$letraCodigo = "font-family:Segoe UI, Helvetica";

	$asunto = 'Inscripción de operador Orgánico.';
	
	$cuerpoMensaje = '<table><tbody>
						<tr><td style="'.$familiaLetra.'; font-size:25px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Agrocalidad <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
						<td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/registroAsociacion/img/selloOrganico.png"></td>
						<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;">Cuenta Sistema GUIA</td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:38px; color:rgb(236,107,109);">Usuario y contraseña para acceder al Sistema GUIA</td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">Su usuario es: <span style="'.$letraCodigo.' font-size:14px; font-weight:bold; color:#2a2a2a;">'.$nuevoCodigoOperador.'</span></td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">Su contraseña es: <span style="'.$letraCodigo.' font-size:14px; font-weight:bold; color:#2a2a2a;">'.$nuevoCodigoOperador.'</span></td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Se recomienda por su seguridad una vez se acceda al Sistema GUIA, modifique su contraseña.</td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Recuerde que es su responsabilidad el cuidado de la información de acceso al sistema G.U.I.A. Por ningún motivo comparta su contraseña con terceros y si sospecha que alguien más tiene conocimiento de ésta, proceda al cambio inmediato.</td></tr>
						<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">El equipo de Desarrollo Tecnológico de Agrocalidad.</td></tr>
						</tbody></table>';
		
	$destinatario = array();
	array_push($destinatario, $mailAsociacion);
	
	$codigoModulo = '';
	$tablaModulo = '';

	$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $solicitudPendiente['id_comprobante']);
	$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
	
	$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
	
	echo "<header>	<h1>Registro de asociaciones</h1></header>
			<div></br><b>El usuario y la contraseña del operador ".$nuevoCodigoOperador.", para acceder al sistema GUIA han sido enviados al correo: ". $mailAsociacion ." </br>Por favor pedir al operador que revise su bandeja de entrada.</b></div>";

	//----FIN ENVIO DE MAIL A USUARIO---------//

?>

<script type="text/javascript">
	$(document).ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	});		
</script>