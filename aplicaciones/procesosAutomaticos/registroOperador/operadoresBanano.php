<?php
//if ($_SERVER['REMOTE_ADDR'] == '') {
if(1){
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorUsuarios.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorGestionAplicacionesPerfiles.php';

set_time_limit(6000);

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cu = new ControladorUsuarios();
$ca = new ControladorAplicaciones();
$cgap= new ControladorGestionAplicacionesPerfiles();
$cm = new ControladorMonitoreo();

define('PRO_MSG', '<br/> ');
define('IN_MSG', '<br/> >>> ');
$fecha = date("Y-m-d h:m:s");
$numero = '1';

echo IN_MSG . '<b>INICIO PROCESO DE INGRESO DE OPERADORES, CREACIÓN CUENTA GUIA ' . $fecha . '</b>';

//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_DATO_OPERADORES_BANANO');

	//if($resultadoMonitoreo){
	if(1){
		$operadores = $cr->obtenerOperadoresBanano($conexion);

		while ($operador = pg_fetch_assoc($operadores)) {

			echo IN_MSG . $numero ++ . '.- Identificador operador: ' . $operador['identificador'];

			$cr->actulizarEstadoOperadorBanano($conexion, $operador['identificador'], 'W');

			$datos = array(
				'clasificacion' => trim(htmlspecialchars($operador['tipo_operador'], ENT_NOQUOTES, 'UTF-8')),
				'cedula' => trim(htmlspecialchars($operador['identificador'], ENT_NOQUOTES, 'UTF-8')),
				'razon' => trim(htmlspecialchars($operador['razon_social'], ENT_NOQUOTES, 'UTF-8')),
				'nombreLegal' => trim(htmlspecialchars($operador['nombre_representante'], ENT_NOQUOTES, 'UTF-8')),
				'apellidoLegal' => trim(htmlspecialchars($operador['apellido_representante'], ENT_NOQUOTES, 'UTF-8')),
				'nombreTecnico' => trim(htmlspecialchars($operador['nombre_tecnico'], ENT_NOQUOTES, 'UTF-8')),
				'apellidoTecnico' => trim(htmlspecialchars($operador['apellido_tecnico'], ENT_NOQUOTES, 'UTF-8')),
				'parroquia' => trim(htmlspecialchars($operador['parroquia'], ENT_NOQUOTES, 'UTF-8')),
				'provincia' => trim(htmlspecialchars($operador['provincia'], ENT_NOQUOTES, 'UTF-8')),
				'canton' => trim(htmlspecialchars($operador['canton'], ENT_NOQUOTES, 'UTF-8')),
				'direccion' => trim(htmlspecialchars($operador['direccion'], ENT_NOQUOTES, 'UTF-8')),
				'telefono1' => trim(htmlspecialchars($operador['telefono_uno'], ENT_NOQUOTES, 'UTF-8')),
				'correo' => trim(htmlspecialchars($operador['correo'], ENT_NOQUOTES, 'UTF-8')),
				'clave1' => trim(htmlspecialchars($operador['clave'], ENT_NOQUOTES, 'UTF-8'))
			);
			
			$vOperador = $cr->buscarOperador($conexion, $datos['cedula']);
			$usuario = $cu->verificarUsuario($conexion, $datos['cedula']);
			
			if( pg_num_rows($vOperador) > 0 || pg_num_rows($usuario) > 0){
				
				if(pg_num_rows($vOperador) > 0){
					echo IN_MSG.'El operador ya se encuentra registrado en Agrocalidad.';
				}
				
				if(pg_num_rows($usuario) > 0){
					echo IN_MSG. 'El usuario ya se encuentra registrado en Agrocalidad.';
				}
				echo '</br>';
			}else{
				
				$provincia = pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['provincia'], 1, 'provincia'));
				$canton = pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['canton'],2,'canton', ($provincia['id_localizacion'] == '' ? 0 : $provincia['id_localizacion'])));
				$parroquia =  pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['parroquia'], 3, 'parroquia', ($canton['id_localizacion'] == '' ? 0 : $canton['id_localizacion'])));
				
				$cr->guardarRegistroOperador($conexion, $datos['clasificacion'], $datos['cedula'], $datos['razon'], $datos['nombreLegal'], $datos['apellidoLegal'],
					$datos['nombreTecnico'], $datos['apellidoTecnico'], $provincia['nombre'],$canton['nombre'],$parroquia['nombre'],
					$datos['direccion'], $datos['telefono1'], '', '','', '', $datos['correo'], $datos['clave1']);
				
				echo IN_MSG. 'Crear Cuenta de usuario.';
				
				$cu->crearUsuario($conexion, $datos['cedula'], $datos['clave1']);
				
				echo IN_MSG. 'Activación Cuenta de usuario.';
				
				$cu ->activarCuenta($conexion, $datos['cedula'], $datos['clave1']);
				
				echo IN_MSG. 'Asignación de perfil a usuario externo';
				
				$qPerfilExterno = $cu->buscarPerfilUsuario($conexion, $datos['cedula'], 'Usuario externo');
				
				if(pg_num_rows($qPerfilExterno)==0){
					$cu->crearPerfilUsuario($conexion,  $datos['cedula'], 'Usuario externo');
				}
				
				echo IN_MSG. 'Asignación perfil a usuario operador';
				
				$qPerfilOperador = $cu->buscarPerfilUsuario($conexion, $datos['cedula'], 'Operadores');
				
				if(pg_num_rows($qPerfilOperador)==0){
					$cu->crearPerfilUsuario($conexion,  $datos['cedula'], 'Operadores');
				}
				
				echo IN_MSG. 'Asignación aplicacion a usuario operador';
				$qAplicacionOperadores = $ca->obtenerIdAplicacion($conexion,'PRG_REGISTROOPER');
				$aplicacionOperador = pg_fetch_result($qAplicacionOperadores, 0, 'id_aplicacion');

				$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionOperador, $datos['cedula']);
				
				if (pg_num_rows($aplicacionOperadorRegistro) == 0){
					$ca->guardarAplicacionPerfil($conexion, $aplicacionOperador,$datos['cedula'], 0, 'notificaciones');
				}
				
				echo IN_MSG. 'Asignación aplicación mis facturas.';
				$qAplicacionFacturas = $ca->obtenerIdAplicacion($conexion,'PRG_FACT_OPE');
				$aplicacionFacturas = pg_fetch_result($qAplicacionFacturas, 0, 'id_aplicacion');
				
				$aplicacionConsultaFacturas = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionFacturas, $datos['cedula']);
				
				if (pg_num_rows($aplicacionConsultaFacturas) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionFacturas,$datos['cedula'], 0, 'notificaciones');
				}
				
				echo IN_MSG. 'Asignación aplicación caravanas.';
				$qAplicacionCaravana = $ca->obtenerIdAplicacion($conexion,'PRG_INSCR_CRV');
				$aplicacionCaravana = pg_fetch_result($qAplicacionCaravana, 0, 'id_aplicacion');
				
				$aplicacionCaravanaRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionCaravana, $datos['cedula']);
				//$aplicacionTrazabilidadRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionTrazabilidad, $datos['cedula']);
				
				if (pg_num_rows($aplicacionCaravanaRegistro) == 0){
					//$valor = true;
					$ca->guardarAplicacionPerfil($conexion, $aplicacionCaravana,$datos['cedula'], 0, 'notificaciones');
				}
				
				echo IN_MSG. 'Asignación aplicación catastro y movilización porcinos.';
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_CATAS_PRODU','PRG_MOVIL_PRODU')");
				while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_CATAG','PFL_EMISO_MOVIL')");
					$perfilesArray=Array();
					while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
					}
					
					if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['cedula']))==0){
						$cgap->guardarGestionAplicacion($conexion, $datos['cedula'],$filaAplicacion['codificacion_aplicacion']);
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
				echo IN_MSG. 'Creación de operador y usuario en GUIA finalizada.</br>';
				$cr->actulizarEstadoOperadorBanano($conexion, $operador['identificador'], 'Atendida');
			}
			echo IN_MSG. 'FIN DE PROCESO.';
		}
	}
}else{
	
	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_datos_operadores_banano" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}

?>