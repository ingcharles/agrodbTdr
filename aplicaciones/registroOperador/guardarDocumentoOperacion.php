<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorRegistroOperador.php'; 
//nuevas
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';

	$idOperacion = htmlspecialchars($_POST['idOperacion'], ENT_NOQUOTES, 'UTF-8');
	$idGrupoOperaciones = explode(",",$idOperacion);
	    
?>

<?php

    $conexion = new Conexion();
    $cr = new ControladorRegistroOperador();

    $cu = new ControladorUsuarios();
    $cc = new ControladorCatalogos();
    $ca = new ControladorAplicaciones();
    $cgap= new ControladorGestionAplicacionesPerfiles();
    
    $modulosAgregados="";
    $perfilesAgregados="";
    $tipoProceso = false;
    
    foreach ($idGrupoOperaciones as $solicitud){
    	
    	$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
    	$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
    	$identificadorOperador = $operacion['identificador_operador'];
    	
    	$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
    	$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
    	
    	$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
    	$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'cargarAdjunto'));
    	$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
    	
    	$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
    	$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
    	$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
    	
    	if($operacion['estado'] == 'subsanacion'){
    		
    	    switch($idArea){

    			case 'AI':
    			    switch ($opcionArea){
    			        case 'ACO':
    			        case 'MDT':
						case 'PRO':
    			        case 'REC':
    			        case 'COM':
    			        case 'PRC':
    			        case 'INL':
						case 'MDC':
    			            $estado['estado'] = 'documental';
                        break;
                        default:
                            $estado['estado'] = 'inspeccion';
    			    }
    			break;

    			case 'LT':
    			    switch ($opcionArea){
    			        case 'LAL':
    			        case 'LDV':
						case 'LDE':
						case 'LDA':
						case 'LDI':
    			            $estado['estado'] = 'documental';
                        break;
    			        default:
    			            $estado['estado'] = 'inspeccion';
    			    }
                break;
                
    			case 'IAF':
    			    switch ($opcionArea){
    			        case 'DIS':
    			        case 'ENV':
    			        case 'ALM':
    			        case 'FIE':
    			            $estado['estado'] = 'documental';
    			        break;
    			    }
    			break;
    			case 'SA':
    				if($operacion['estado_anterior'] == 'inspeccion' || $operacion['estado_anterior'] == 'asignadoInspeccion'){
    					$estado['estado'] = 'inspeccion';
    				}else{
    					$estado['estado'] = 'documental';
    				}
    			break;
    			default:
    				$estado['estado'] = 'inspeccion';
    		}
    		
    		$cMail = new ControladorMail();
    		$controladorRevisionSolicitudes = new ControladorRevisionSolicitudesVUE();
    		$ce = new ControladorEmpleados();
    		
    		$datosOperacion = $cr->abrirOperacion($conexion, $identificadorOperador, $solicitud);
    		$datosOperador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $identificadorOperador));
    		
    		$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
					<style type="text/css">
    			            	
						.titulo  {
							margin-top: 30px;
							width: 800px;
							text-align: center;
							font-size: 14px;
							font-weight: bold;
							font-family:Times New Roman;
						}
    			            	
						.lineaDos{
							font-style: oblique;
							font-weight: normal;
						}
    			            	
						.lineaLeft{
							text-align: left;
						}
    			            	
						.lineaEspacio{
							height: 35px;
						}
						.lineaEspacioMedio{
							height: 50px;
						}
						.espacioLeft{
							padding-left: 15px;
						}
					</style>';
    		
    		$cuerpoMensaje.='<table class="titulo">
					<thead>
					<tr><th>Estimado/a,</th></tr>
					</thead>
					<tbody>
					<tr><td class="lineaDos lineaEspacio">Se le comunica que usted tiene pendiente la revisión del siguiente registro de operador que ha realizado el proceso de subsanación:</td></tr>
					<tr><td class="lineaDos lineaEspacio"><b>Nombre Operador: </b>'.$datosOperador['nombre_operador'].'</td></tr>
					<tr><td class="lineaDos lineaEspacio"><b>Identificación Operador: </b>'.$identificadorOperador.'</td></tr>
					<tr><td class="lineaDos lineaEspacio"><b>Sitio: </b>'.$datosOperacion[0]['nombreSitio'].'</td></tr>
					<tr><td class="lineaDos lineaEspacio"><b>Operación: </b>'.$datosOperacion[0]['tipoOperacion'].'</td></tr>
					<tr><td class="lineaDos lineaEspacio">Ingrese al siguiente link para revisar dicho registro: </td></tr>
					<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
					</tbody>
					<tfooter>
					<tr><td class="lineaEspacioMedio"></td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
					</tfooter>
					</table>';
    		
    		$asunto = 'Solicitud subsanada por parte de operador.';
    		$codigoModulo='';
    		$tablaModulo='';
    		$destinatarios = array();
    		
    		if($estado['estado'] == 'inspeccion'){
    			$tipoInspector = 'Técnico';
    		}else{
    			$tipoInspector = 'Documental';
    		}
    		
    		$tecnicoRevision = pg_fetch_assoc($controladorRevisionSolicitudes->buscarEstadoSolicitudXtipoInspector($conexion, $solicitud, 'Operadores', $tipoInspector));
    		$datosTecnico = pg_fetch_assoc($ce->obtenerFichaEmpleado($conexion, $tecnicoRevision['identificador_inspector']));
    		
    		
    		if($datosTecnico['mail_institucional']!= ''){
    			array_push($destinatarios, $datosTecnico['mail_institucional']);
    		}
    		
    		if($datosTecnico['mail_personal'] !=''){
    			array_push($destinatarios, $datosTecnico['mail_personal']);
    		}
    		
    		$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
    		$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
    		$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
    		
    	}else{
    		if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
    			$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
    		}
    	}    	
    	
    	$cr->actualizarEstadoDocumentoXoperacion($conexion, $solicitud);
    	
	    foreach ($_POST as $idTipoDomentoOperacion => $idDocumento){
			if ($idOperacion != $idDocumento && $idDocumento != 'M' && $idDocumento != 'NADA'){
				//echo 'Id documento: '.$idDocumento.' - id tipo documento operacion '. $idTipoDomentoOperacion.' - Operacion '.$solicitud.'</br>';
				$cr->guardarDocumentoOperacion($conexion, $idDocumento, $solicitud, $idTipoDomentoOperacion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']); 
			}    	
	    }
	    
	    $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

		if($estado['estado'] == 'pago'){
			if($operacion['proceso_modificacion'] == 't'){
				$tipoProceso = true;
			}
			
			if($tipoProceso){
				$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'verificacion'));
				$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
			}
		}
	    	
	    switch ($estado['estado']){
	    		
	    	case 'pago':
	    		//$cr -> enviarOperacion($conexion, $solicitud,$estado['estado']);	    		
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case 'documental':
	    		//$cr->enviarOperacion($conexion, $solicitud, $estado['estado']);
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    		break;
	    	case 'inspeccion':
	    		//$cr->enviarOperacion($conexion, $solicitud, $estado['estado']);
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case 'cargarRendimiento':
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case 'cargarProducto':
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case 'declararICentroAcopio':
	    	    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case 'declararDVehiculo':
	    	    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	    	break;
	    	case'registrado':
	    		$fechaActual = date('Y-m-d H-i-s');
	    		//$cr -> enviarOperacion($conexion,$solicitud,$estado['estado'], 'Solicitud aprobada '.$fechaActual);
	    		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada '.$fechaActual);
	    		$cr->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
	    		$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
	    		//$cr -> cambiarEstadoAreaXidSolicitud($conexion, $solicitud, $estado['estado'], 'Solicitud aprobada.');
	    		
	    		//////////////////////////////////////////////////////////////
	    		
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
	    				}
	    				break;
	    		}
	    			
	    		/////////////////////////////////////////////////////////////
	    		
	    	break;
	    }
	    //se agrego esta parte pasa asignar opcion de fiscalizacion de movilizacion al usuario	   
    }
    
    $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
    
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
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

 	Los documentos se han almacenado correctamente.

</body>
<script type="text/javascript">

    $("document").ready(function () {
       abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
       abrir($("input:hidden"), null, false);
    });

</script>
</html>


