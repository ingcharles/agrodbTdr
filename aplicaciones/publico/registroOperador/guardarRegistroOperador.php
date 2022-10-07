<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorCatalogos.php';
require_once '../../../clases/ControladorUsuarios.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../../general/enviarMail.php';
require_once '../../../clases/ControladorGestionAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$latitud = '795204.2670016843';
$longitud = '9849275.758170985';

try{
	
	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	
	
	$datos = array( 'clasificacion' => htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'),
					'cedula' => htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8'),
					'razon' => htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8'),
					'nombreLegal' => htmlspecialchars ($_POST['nombreLegal'],ENT_NOQUOTES,'UTF-8'),
					'apellidoLegal' => htmlspecialchars ($_POST['apellidoLegal'],ENT_NOQUOTES,'UTF-8'),
					'nombreTecnico' => htmlspecialchars ($_POST['nombreTecnico'],ENT_NOQUOTES,'UTF-8'),
					'apellidoTecnico' => htmlspecialchars ($_POST['apellidoTecnico'],ENT_NOQUOTES,'UTF-8'),
					'parroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
					'provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
					'canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
					'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
					'telefono1' => htmlspecialchars ($_POST['telefono1'],ENT_NOQUOTES,'UTF-8'),
					'telefono2' => htmlspecialchars ($_POST['telefono2'],ENT_NOQUOTES,'UTF-8'),
					'celular1' => htmlspecialchars ($_POST['celular1'],ENT_NOQUOTES,'UTF-8'),
					'celular2' => htmlspecialchars ($_POST['celular2'],ENT_NOQUOTES,'UTF-8'),
					'fax' => htmlspecialchars ($_POST['fax'],ENT_NOQUOTES,'UTF-8'),
					'correo' => htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8'),
					'clave1' => htmlspecialchars ($_POST['clave1'],ENT_NOQUOTES,'UTF-8'),
					'clave2' => htmlspecialchars ($_POST['clave2'],ENT_NOQUOTES,'UTF-8'));
	
	try {

			$provincia = $cc->obtenerNombreLocalizacion($conexion, $datos['provincia']);
			$canton = $cc->obtenerNombreLocalizacion($conexion, $datos['canton']);
			$parroquia = $cc->obtenerNombreLocalizacion($conexion, $datos['parroquia']);
									
			$operador = $cr->buscarOperador($conexion, $datos['cedula']);
			$usuario = $cu->verificarUsuario($conexion, $datos['cedula']);
			
			if( pg_num_rows($operador) > 0 || pg_num_rows($usuario) > 0){
				$mensaje['estado'] = 'error';
				(pg_num_rows($operador) > 0? $mensaje['mensaje'] = 'El operador ya se encuentra registrado en Agrocalidad.': $mensaje['mensaje'] = 'El usuario ya se encuentra registrado en Agrocalidad.');
			}else{
				$cr->guardarRegistroOperador($conexion, $datos['clasificacion'], $datos['cedula'], $datos['razon'], $datos['nombreLegal'], $datos['apellidoLegal'],
						$datos['nombreTecnico'], $datos['apellidoTecnico'], pg_fetch_result($provincia, 0, 'nombre'),pg_fetch_result($canton, 0, 'nombre'),pg_fetch_result($parroquia, 0, 'nombre'),
						$datos['direccion'], $datos['telefono1'], $datos['telefono2'], $datos['celular1'],
						$datos['celular2'], $datos['fax'], $datos['correo'], md5($datos['clave1']));
				
				//Crear Cuenta de usuario
					
				$cu->crearUsuario($conexion, $datos['cedula'], md5($datos['clave1']));
				
				//Asignar perfil a usuario externo
				
				$qPerfilExterno = $cu->buscarPerfilUsuario($conexion, $datos['cedula'], 'Usuario externo');
				
				if(pg_num_rows($qPerfilExterno)==0){
					$cu->crearPerfilUsuario($conexion,  $datos['cedula'], 'Usuario externo');
				}
				
				//Asignar perfil a usuario operador				
				$qPerfilOperador = $cu->buscarPerfilUsuario($conexion, $datos['cedula'], 'Operadores');
				
				if(pg_num_rows($qPerfilOperador)==0){
					$cu->crearPerfilUsuario($conexion,  $datos['cedula'], 'Operadores');
				}
								
				//agregar modulo catastro para todos los operadores
				$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, pg_fetch_result($provincia, 0, 'nombre'), $datos['cedula']);
				$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
				
				
				$qIdSitio = $cr->guardarNuevoSitio($conexion, 'Oficina Central', pg_fetch_result($provincia, 0, 'nombre'),
						pg_fetch_result($canton, 0, 'nombre'), pg_fetch_result($parroquia, 0, 'nombre'), $datos['direccion'], '', 0, $datos['cedula'], $datos['telefono1'],
						$latitud, $longitud, $secuencialSitio, '0','17', substr(pg_fetch_result($provincia, 0, 'codigo_vue'),1));
				
				$qCodigoArea = $cc->buscarAreaOperadorXNombre($conexion, 'Domicilio tributario');
				$codigoArea = pg_fetch_assoc($qCodigoArea);
				
				$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $datos['cedula'], $codigoArea['codigo'],pg_fetch_result($provincia, 0, 'nombre'));
				$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
				$areas = $cr -> guardarNuevaArea($conexion, 'Oficina Principal', 'Domicilio tributario', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'), $codigoArea['codigo'], $secuencial);
				
				//$body = '<a target="_blank" href="http://181.112.155.173/agrodb/registroOperador/validarCuentaOperador.php?U='.$datos['cedula'].'&C='.md5($datos['clave1']).'"><img src="http://181.112.155.173/agrodb/registroOperador/img/congratulations.png"></a>';
				$body = 'Estimado Operador: <br/> 
						Reciba un cordial saludo de Agrocalidad. 
						
						<!--div>
						Para finalizar su registro por favor acceda al siguiente enlace:<br/> 
						<a target="_blank" href="http://181.112.155.173/agrodb/aplicaciones/publico/registroOperador/validarCuentaOperador.php?U='.$datos['cedula'].'&C='.md5($datos['clave1']).'">
								Validar Cuenta
						</a>
								
						<br/><br/>
								
						<div-->
								
						Sus datos de acceso son:<br/>
								
						<b>Usuario:</b> '.$datos['cedula'].'<br/>
						<b>Clave:</b> '.$datos['clave1'].' <br/><br/>
								
						<img src="http://181.112.155.173/agrodb/aplicaciones/publico/registroOperador/img/congratulations.png" width="500" height="609">
								
						<!--a target="_blank" href="http://181.112.155.173/agrodb/aplicaciones/publico/registroOperador/validarCuentaOperador.php?U='.$datos['cedula'].'&C='.md5($datos['clave1']).'">		
							<img src="http://181.112.155.173/agrodb/aplicaciones/publico/registroOperador/img/congratulations.png" width="500" height="609">
						</a-->';
				
			//Cambio temporal eliminacion de envio de correo----------------------------------------------------------------
			
				$qAplicacionOperadores = $ca->obtenerIdAplicacion($conexion,'PRG_REGISTROOPER');
				$aplicacionOperador = pg_fetch_result($qAplicacionOperadores, 0, 'id_aplicacion');
				
				//$qAplicacionTrazabilidad = $ca->obtenerIdAplicacion($conexion,'PRG_TRAZABILIDAD');
				//$aplicacionTrazabilidad = pg_fetch_result($qAplicacionTrazabilidad, 0, 'id_aplicacion');

				$cu ->activarCuenta($conexion, $datos['cedula'], md5($datos['clave1']));
				
				$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionOperador, $datos['cedula']);
				//$aplicacionTrazabilidadRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionTrazabilidad, $datos['cedula']);
				
				if (pg_num_rows($aplicacionOperadorRegistro) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionOperador,$datos['cedula'], 0, 'notificaciones');
				}
				
				//Mis facturas
				$qAplicacionFacturas = $ca->obtenerIdAplicacion($conexion,'PRG_FACT_OPE');
				$aplicacionFacturas = pg_fetch_result($qAplicacionFacturas, 0, 'id_aplicacion');
				
				$aplicacionConsultaFacturas = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionFacturas, $datos['cedula']);
				
				if (pg_num_rows($aplicacionConsultaFacturas) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionFacturas,$datos['cedula'], 0, 'notificaciones');
				}
								
				//Caravana
				$qAplicacionCaravana = $ca->obtenerIdAplicacion($conexion,'PRG_INSCR_CRV');
				$aplicacionCaravana = pg_fetch_result($qAplicacionCaravana, 0, 'id_aplicacion');
				
				$aplicacionCaravanaRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionCaravana, $datos['cedula']);
				//$aplicacionTrazabilidadRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionTrazabilidad, $datos['cedula']);
				
				if (pg_num_rows($aplicacionCaravanaRegistro) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionCaravana,$datos['cedula'], 0, 'notificaciones');
				}				
				
			///agreagar para modulo catastro
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_CATAS_PRODU','PRG_MOVIL_PRODU','PGR_NOTI_OMC','PRG_CERT_BPA')");
				while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_CATAG','PFL_EMISO_MOVIL','PFL_OPE_PRE_NOTI','PFL_USR_CERT_BPA')");
					$perfilesArray=Array();
					while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
					}
				
					if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['cedula']))==0){
						$qAplicacion=$cgap->guardarGestionAplicacion($conexion, $datos['cedula'],$filaAplicacion['codificacion_aplicacion']);
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['cedula']);
							if (pg_num_rows($qPerfil) == 0)


								$cgap->guardarGestionPerfil($conexion, $datos['cedula'],$datosPerfil['codigoPerfil']);
						}
					}else{
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['cedula']);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $datos['cedula'],$datosPerfil['codigoPerfil']);
						}
					}
				}
				
			/*	if (pg_num_rows($aplicacionTrazabilidadRegistro) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionTrazabilidad,$datos['cedula'], 0, 'notificaciones');
				}*/
				
			//---------------------------------------------------------------------------------------------------------------------------
				
				//send_email($datos['correo'], 'sistemas.integrado@agrocalidad.gob.ec', 'Registro de operador clave de acceso.', $body);
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
				//$mensaje['mensaje'] = $respuesta;
			}
			
			
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>