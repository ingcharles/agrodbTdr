<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorConsultaInspecciones.php';

	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$cci = new ControladorConsultaInspecciones();
	
	define ( 'IN_MSG', '<br/> >>> ' );
	define ( 'OUT_MSG', '<br/> <<< ' );
	define ( 'PRO_MSG', '<br/> ... ' );
	

	echo '<h1>ACTIVACION AUTOMATICA DE MODULOS Y PERFILES - MODULO (consulta inspecciones)</h1>';
	echo '<strong>FECHA: </strong> '.date ( 'Y-m-d H-i-s').'<br>';
	echo '<strong>INICIO PROCESO DE DE ACTIVACION </strong> <br><br>';
	
	$contador = 1;
	$qOperadores=$cci->consultaOperadoresActivarModuloConsultaInspecciones($conexion);
		
	while($filaOperador=pg_fetch_assoc($qOperadores)){
		echo '<b>' . PRO_MSG . 'Proceso #' . $contador ++ . ' - Usuario: ' . $filaOperador['identificador_operador'] . '</b>';
		echo IN_MSG . 'Envio solicitud para activar modulos y perfiles';
		$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_CONSU_INSPE')");
		while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
		
			$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_COINS_OPERA')");
			$perfilesArray=Array();
			while($fila=pg_fetch_assoc($qGrupoPerfiles)){
				$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
			}
			if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $filaOperador['identificador_operador']))==0){
				$cgap->guardarGestionAplicacion($conexion, $filaOperador['identificador_operador'],$filaAplicacion['codificacion_aplicacion']);
				echo PRO_MSG.' Modulo activado para el usuario';
					
				foreach( $perfilesArray as $datosPerfil){
					$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $filaOperador['identificador_operador']);
					if (pg_num_rows($qPerfil) == 0){
						$cgap->guardarGestionPerfil($conexion, $filaOperador['identificador_operador'],$datosPerfil['codigoPerfil']);
						echo PRO_MSG.' Perfil de operador activado para el usuario';
					}
				}
			}else{
				foreach( $perfilesArray as $datosPerfil){
					$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $filaOperador['identificador_operador']);
					if (pg_num_rows($qPerfil) == 0){
						$cgap->guardarGestionPerfil($conexion, $filaOperador['identificador_operador'],$datosPerfil['codigoPerfil']);
						echo PRO_MSG.' Modulo ya se encuentra activado - solo se activó el perfil de operador al usuario';
					}
				}
			}
			
		}
		echo OUT_MSG . 'Fin envio solicitud enviada'.'<br>';
	}	
			

?>