<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php'; 

$mensaje = array();


$tmpAreas =array();

try{

	$idOperacion = htmlspecialchars ($_POST['idOperacionIA'],ENT_NOQUOTES,'UTF-8');
	$tipoOperacion = htmlspecialchars ($_POST['idTipoOperacionIA'],ENT_NOQUOTES,'UTF-8');
	$areaProducto = htmlspecialchars ($_POST['areaProducto'],ENT_NOQUOTES,'UTF-8');
	$idFlujoOperacion = htmlspecialchars ($_POST['idFlujo'],ENT_NOQUOTES,'UTF-8');
	$identificadorOperador = $_SESSION['usuario'];

	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cr = new ControladorRegistroOperador();
	$crs = new ControladorRevisionSolicitudesVUE();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$ca = new ControladorAplicaciones();
	$cu = new ControladorUsuarios();
	
	$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idOperacion));
	$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $idOperacion));
	$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'cargarProducto'));
	$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
	
	if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
		$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
	}
	
	$cr->enviarOperacionEstadoAnterior($conexion, $idOperacion);
	
	switch ($estado['estado']){

		case 'pago':
			$res = $cr -> enviarOperacion($conexion, $idOperacion,$estado['estado']);
		break;
		
		case 'cargarAdjunto':
			$res = $cr -> enviarOperacion($conexion, $idOperacion,$estado['estado']);
		break;
		
		case 'inspeccion':
			$res = $cr -> enviarOperacion($conexion, $idOperacion,$estado['estado']);
		break;
		
		case'registrado':
			$fechaActual = date('Y-m-d H-i-s');
			$cr -> enviarOperacion($conexion, $idOperacion,'registrado', 'Solicitud registrada '.$fechaActual);
			$cr -> cambiarEstadoAreaXidSolicitud($conexion, $idOperacion, 'registrado', 'Solicitud registrada '.$fechaActual);
			
			$idArea = $areaProducto;
			$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacionxIdTipoOperacion($conexion, $tipoOperacion);
			$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
			
			$modulosAgregados="";
			$perfilesAgregados="";
			
			switch ($idArea){
					
				case 'SA':
					switch ($opcionArea){
						case 'MVB':
						case 'MVC':
						case 'MVE':
							$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
			
							if(pg_num_rows($qOperaciones)>0){
								$modulosAgregados.="('PRG_NOTIF_ENF'),";
								$perfilesAgregados.="('PFL_NOTIF_ENF'),";
							}
			
							break;
						case 'FER':
							$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
			
							if(pg_num_rows($qOperaciones)>0){
								$modulosAgregados.="('PRG_MOVIL_PRODU'),";
								$perfilesAgregados.="('PFL_FISCA_MOVIL'),";
							}
			
							break;
					}
					break;
			
				case 'SV':
					$contador=0;
					switch ($opcionArea){
						case 'ACO':
						    $qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacionFloresFollajes($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
			
							if(pg_num_rows($qOperaciones)>0){
								$modulosAgregados.="('PRG_EMISI_ETIQU'),";
								$perfilesAgregados.="('PFL_SOLIC_ETIQU'),";
							}
			
							$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
									
							if(pg_num_rows($qOperacionesCacao)>0){
								$modulosAgregados.="('PRG_CONFO_LOTE'),";
								$perfilesAgregados.="('PFL_CONFO_LOTE'),";  
							}
							
							$qOperacionesPitahaya = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
							
							if(pg_num_rows($qOperacionesPitahaya)>0){
							    $modulosAgregados.="('PRG_CONFO_LOTE'),";
							    $perfilesAgregados.="('PFL_CONFO_LOTE'),";
							}
							
						break;
						
						case 'TRA':
						    
						    $qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
						    
						    if(pg_num_rows($qOperacionesCacao)>0){
						        $modulosAgregados.="('PRG_CONFO_LOTE'),";
						        $perfilesAgregados.="('PFL_CONFO_LOTE'),";
						    }
						    
						break;
			
						case 'COM':
							$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
			
							if(pg_num_rows($qOperaciones)>0){
								$modulosAgregados.="('PRG_EMISI_ETIQU'),";
								$perfilesAgregados.="('PFL_SOLIC_ETIQU'),";
							}
							break;
								
						/*case 'EXP':
			
							$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
			
							if(pg_num_rows($qOperacionesCacao)>0){
								$contador++;
								$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $identificadorOperador,"('ACO')","('$idArea')");
									
								if(pg_num_rows($qOperacionesCacao)>0)
									$contador++;
									
								if($contador==2){
									$modulosAgregados.="('PRG_CONFO_LOTE'),";
									$perfilesAgregados.="('PFL_CONFO_LOTE'),";
								}
							}
						break;*/
			
					}
					break;
			}
			
			
		break;
	}
			
	if(strlen($modulosAgregados)==0){
		$modulosAgregados="''";
		$perfilesAgregados="''";
	}
	
	$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion,'('.rtrim($modulosAgregados,',').')' );
	if(pg_num_rows($qGrupoAplicacion)>0){
		while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
			if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorOperador))==0){
				$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $identificadorOperador,$filaAplicacion['codificacion_aplicacion']);
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
				while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
					$cgap->guardarGestionPerfil($conexion, $identificadorOperador,$filaPerfil['codificacion_perfil']);
				}
			}else{
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
				while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
					$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $identificadorOperador);
					if (pg_num_rows($qPerfil) == 0)
						$cgap->guardarGestionPerfil($conexion, $identificadorOperador,$filaPerfil['codificacion_perfil']);
				}
			}
		}
	}

	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'La información adicional ha sido cargada con éxito.';

	$conexion->desconectar();

	echo json_encode($mensaje);

} catch (Exception $ex){
	pg_close($conexion);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia";
	echo json_encode($mensaje);
}

?>
