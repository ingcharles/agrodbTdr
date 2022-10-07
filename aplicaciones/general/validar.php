<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador = trim(pg_escape_string($_POST['identificador']));
	$clave = pg_escape_string($_POST['clave']);

	
	$perfil = (!empty($_POST['perfil']))? pg_escape_string($_POST['perfil']): false;
		
	$tipo_aplicacion = 0;
	
	try {
		$conexion = new Conexion();
		$cu = new ControladorUsuarios();
		$ca = new ControladorAuditoria();
		
		if($identificador == "" || $clave == ""){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor revise el formato de la información ingresada.';
		}else{
			
			//---------------------AUDITORIA
			$idLogUsuario  = $ca->buscarLogIngresoUsuario($conexion, $identificador);
			
			if(pg_num_rows($idLogUsuario)== 0){
				$qIdLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$idLog = pg_fetch_assoc($qIdLog);
			}else{
				$idLog = pg_fetch_assoc($idLogUsuario);
			}
			
			//---------------------
			$resultado =  $cu->verificarUsuario($conexion, $identificador, $perfil);
			$fila=pg_fetch_assoc($resultado);
			if (pg_num_rows($resultado)==0){
				//---------------------AUDITORIA
				$ca->guardarIngresoUsuario($conexion, $idLog['id_log'], $identificador, 'No se encontró el usuario ' . $identificador, 'SIN_USUARIO', $cu->obtenerIPUsuario());
				//---------------------
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'No se encontró el usuario '. $identificador .'.';

			}else {
				
				$qClaveUsuarioIdentificador = $cu->verificarClaveUsuarioIdentificador($conexion, $identificador);
			    
			    if(pg_num_rows($qClaveUsuarioIdentificador) == 0){
			    
				if($fila['clave'] ==md5($clave)){
					
					if($fila['estado']== 1){
						
						$nombre = $cu->obtenerNombresUsuario($conexion,$identificador);
						
						if(pg_num_rows($nombre)!= 0){
							$_SESSION['datosUsuario']= pg_fetch_result($nombre, 0, 'apellido').' '.pg_fetch_result($nombre, 0, 'nombre');
							$_SESSION['tipoEmpleado']=pg_fetch_result($nombre, 0, 'tipo_empleado');
							$_SESSION['auxChat']=pg_fetch_result($nombre, 0, 'validacion_sri');
						}
						
						$_SESSION['usuario']=$identificador;
						$_SESSION['nombre_usuario']=$fila['nombre_usuario'];
						
						//---------------------AUDITORIA
						$ca->guardarIngresoUsuario($conexion, $idLog['id_log'], $identificador, 'El usuario ' . $identificador.' ha iniciado sesión correctamente', 'EXITO', $cu->obtenerIPUsuario());
						//---------------------
						
						$mensaje['estado'] = 'exito';
						//$mensaje['mensaje'] = 'La sesión fue iniciada exitosamente';
						$mensaje['mensaje'] = $fila;
					}else{
						//---------------------AUDITORIA
						$ca->guardarIngresoUsuario($conexion, $idLog['id_log'], $identificador, 'El usuario ' . $identificador.' se encuentra en estado inactivo', 'INACTIVO', $cu->obtenerIPUsuario());
						//---------------------
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Usuario inactivo';
					}
				} else{
					
					//---------------------AUDITORIA
					
					$idIngreso = pg_fetch_result($ca->guardarIngresoUsuario($conexion, $idLog['id_log'], $identificador, 'La contraseña ingresada no corresponde a la del usuario ' . $identificador, 'ERROR', $cu->obtenerIPUsuario()), 0, 'id_ingreso');
					$cantidad = $ca->verificarEstadoUsuario($conexion, $identificador);
					
					if($cantidad == 0){
						$cantidad = $ca->verificarEstadoUsuario($conexion, $identificador, 'EXITO','ERROR', 'ERROR');
					}
			         
					$texto = 'La contraseña ingresada no es válida, cantidad de intentos ('.$cantidad.' de 5).';
					$ca->actualizarIntentoAccesoUsuario($conexion, $cantidad, $idIngreso, $identificador);
					
					if($cantidad == 5){
						$cu->desactivarCuenta($conexion, $identificador);
						$texto = 'Cantidad de intentos permitidos superada, por favor comunicarse al número 23960100 ext. 3203, 3204.';
					}else if($cantidad > 5){
						$texto = 'Cantidad de intentos permitidos superada, por favor comunicarse al número 23960100 ext. 3203, 3204.';
					}
					//---------------------
					
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = $texto;
				}
				
				}else{
			        
			        $mensaje['estado'] = 'error';
			        $mensaje['mensaje'] = 'Su usuario y clave no deben ser los mismos, por favor de click en la opción "Olvidó su contraseña o su usuario está inactivo" para cambiar su contraseña.';
			        
			    }				
			}	
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>