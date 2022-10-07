<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorVacaciones.php';

	define('IN_MSG','<br/> >>> ');
try{
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	$cv = new Controladorvacaciones();
		
		try {
			echo IN_MSG.'Proceso de asignar subrogaciones de permisos aprobados con responsabilidad...';	
				
			$consultaSubrogar = $cc->obtenerSubrogacionesFuncionariosPermisos($conexion, '','','Aprobado','si');
			while($consulta = pg_fetch_assoc($consultaSubrogar)){
								
//------------------------------------agregar subrogacion de funcionarios--------------------------------------------------------------------------------				
						echo IN_MSG.'funcionario registrado -> >'.$consulta['identificador_encargado'];
						$estado='creado';
							$fila = pg_fetch_assoc($cc->asignarResponsable($conexion, $consulta['identificador_responsable'],$consulta['identificador_encargado'],$consulta['fecha_ini'], $consulta['fecha_fin'], $consulta['id_area'], $estado));
							$idResponsable = $fila['id_responsable'];
						
						$perfil=$cc->devolverPerfilesNuevos($conexion, $consulta['identificador_responsable'],$consulta['identificador_encargado']);
						while($consultaPerfil = pg_fetch_assoc($perfil)){
							$cc->asignarPerfilSubrogacion($conexion, $idResponsable,$consultaPerfil['id_perfil']);
						}
						//----------------------------------------------------------------------------------
						$aplicacion=$cc->devolverAplicacionesNuevas($conexion, $consulta['identificador_responsable'],$consulta['identificador_encargado']);
						while($consultaAplicacion = pg_fetch_assoc($aplicacion)){
							$cc->asignarAplicacionSubrogacion($conexion, $consultaAplicacion['id_aplicacion'], $idResponsable);
						}
						
						$cv->actualizarEstadoResponsablePuesto($conexion,$consulta['id_permiso_empleado'],'asignado');
					   				        											
				}	
				$conexion->desconectar();
				} catch (Exception $ex){
					pg_close($conexion);	
					echo IN_MSG.'Error de ejecucion'.$ex;
				}
} catch (Exception $ex) {

	echo IN_MSG.'Error de conexión a la base de datos';
}
?>
</body>
</html>