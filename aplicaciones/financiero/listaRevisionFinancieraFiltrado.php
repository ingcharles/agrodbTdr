<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';
//require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
//Controladores formularios
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorDossierPecuario.php'; 

require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cd = new ControladorDestinacionAduanera();
$cce = new ControladorCertificados();

//$crs = new ControladorRevisionSolicitudesVUE();

$tipoSolicitud = $_POST['solicitudes'];
//$estado = 'Financiero';
$estado = $_POST['estados'];
$provinciaUsuario = $_POST['provincia'];

$estadoOrdenPago = htmlspecialchars ($_POST['estadoSolicitud'],ENT_NOQUOTES,'UTF-8');
$tipoEstadoOrdenPago = htmlspecialchars ($_POST['tipoEstado'],ENT_NOQUOTES,'UTF-8');
$numeroOrdenPago = htmlspecialchars ($_POST['factura'],ENT_NOQUOTES,'UTF-8');

$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			while($fila = pg_fetch_assoc($res)){
				if($fila['estilo'] == '_actualizar' || $fila['estilo'] == '_seleccionar'){
					echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				}
			}
	echo'</nav></header>';
	
	//Elección de tipo de formulario para impresión
	
	
	$cabecera='<th>#</th>
				<th>RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>País</th>';
	
	switch ($tipoSolicitud){
		
		/*case 'Operadores' :
			$cr = new ControladorRegistroOperador();
		
			$res = $cr -> listarOperacionesRevisionFinancieroRS($conexion, $provinciaUsuario,$_POST['estados']);
		
			$nombreArchivo = 'abrirOperacionEnviadaFinanciero';
			$nombreModulo = 'registroOperador';
		
			$nombreSolicitud = 'Registro de Operador';
		
		break;*/
		
		case 'Importación' :
			$ci = new ControladorImportaciones();
			
			if($estado == 'pago'){
				$res = $ci -> listarImportacionesRevisionFinancieroRS($conexion, $provinciaUsuario, $estado);
			}else if ($estado == 'verificacionVUE'){
				$res = $ci -> obtenerImportacionFinancieroVerificacion($conexion, $estado, $provinciaUsuario, $tipoSolicitud);
			}else if ($estado == 'verificacion'){
				
				//if($estadoOrdenPago == '3' || $estadoOrdenPago == ''){
					$res = $ci -> listarImportacionesRevisionFinancieroRS($conexion, $provinciaUsuario, $estado);
				//}else{
					//$res = $cce->obtenerOrdenPagoXEstadoImportacion($conexion, $tipoSolicitud, $estadoOrdenPago);
				//}
				
			}
			
			
			
			
			$nombreArchivo = 'abrirImportacionEnviadaFinanciero';
			$nombreModulo = 'importaciones';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		
		case 'Fitosanitario' :
			$cf = new ControladorFitosanitario();
			
			if($estado == 'pago'){
				$res = $cf -> listarFitoRevisionFinancieroRS($conexion, $provinciaUsuario, $estado);
			}else if ($estado == 'verificacionVUE'){
				$res = $cf ->listarFitosanitarioExportacionFinancieroVerificacion($conexion, $estado, $provinciaUsuario, $tipoSolicitud);
			}else if ($estado == 'verificacion'){
				$res = $cf -> listarFitoRevisionFinancieroRS($conexion, $provinciaUsuario, $estado);			
			}
			
			//$res = $cf -> listarFitoRevisionFinancieroRS($conexion, $provinciaUsuario ,$_POST['estados']);
			
			$nombreArchivo = 'abrirFitoExportacionEnviadoFinanciero';
			$nombreModulo = 'exportacionFitosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
			
		case 'Zoosanitario' :
			$cz = new ControladorZoosanitarioExportacion();
			
			if($estado == 'pago'){
				$res = $cz -> listarZooRevisionFinancieroRS($conexion, $provinciaUsuario,$estado);
			}else if ($estado == 'verificacionVUE'){
				//$res = $ci -> obtenerImportacionFinancieroVerificacion($conexion, $estado, $provinciaUsuario, $tipoSolicitud);
			}else if ($estado == 'verificacion'){
				//$res = $ci -> listarImportacionesRevisionFinancieroRS($conexion, $provinciaUsuario, $estado);			
			}
				
			$nombreArchivo = 'abrirZoosanitarioEnviadoFinanciero';
			$nombreModulo = 'exportacionZoosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
						
		case 'CLV' :
			
			$cl = new ControladorClv();
			
			$res = $cl -> listarClvRevisionFinancieroRS($conexion, $_POST['estados']);
				
			$nombreArchivo = 'abrirClvEnviadoFinanciero';
			$nombreModulo = 'certificadoLibreVenta';
			$nombreSolicitud = 'Certificado de Libre Venta';
			
		break;
		
		case 'certificadoCalidad':
			$cc = new ControladorCertificadoCalidad();
			
			$res = $cc->listarCertificadoCalidadImposicionTasa($conexion, $provinciaUsuario, $_POST['estados']);
			
			$nombreArchivo = 'abrirCalidadImposicionTasa';
			$nombreModulo = 'certificadoCalidad';
			$nombreSolicitud = 'Certificado de calidad';
			
			
			
		break;
		
		case 'FitosanitarioExportacion':
			$cfe = new ControladorFitosanitarioExportacion();
			
			if($estado == 'pago'){
				$res = $cfe -> listarFitosanitarioExportacionPorProvincia($conexion, $provinciaUsuario ,$estado);
			}else if ($estado == 'verificacionVUE'){
				$res = $cfe ->listarFitosanitarioExportacionfinancieroVerificacion($conexion, $estado, $provinciaUsuario, $tipoSolicitud);
			}else if ($estado == 'verificacion'){
				$res = $cfe -> listarFitosanitarioExportacionPorProvincia($conexion, $provinciaUsuario ,$estado);
			}
				
			$nombreArchivo = 'abrirFitosanitarioExportacionFinanciero';
			$nombreModulo = 'fitosanitarioExportacion';				
			$nombreSolicitud = 'Certificado fitosanitario de exportación';
			
		break;
		
		case 'Emisión de Etiquetas':
			$ce = new ControladorEtiquetas();
			
			$cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
			
			if( $estado=='pago'){
				$res=$ce->listarSolicitudesEtiquetasPorEstado($conexion, 'Enviado',$provinciaUsuario);
			}else if( $estado=='verificacion'){
				$res=$ce->listarSolicitudesEtiquetasPorEstado($conexion, 'Por Pagar',$provinciaUsuario);
			}else if( $estado=='verificacionVUE'){
				$res=$ce->listarSolicitudesEtiquetasPorEstado($conexion, 'Por Pagar',$provinciaUsuario);
			}
			
			$nombreArchivo = 'abrirEtiquetasEnviadaFinanciero';
			$nombreModulo = 'etiquetas';
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		case 'mercanciasImportacionExportacion':
			
			$cmsn = new ControladorMercanciasSinValorComercial();
			
			if( $estado=='pago'){
				$res = $cmsn->listarImportacionExportacionRevisionProvinciaRS($conexion, 'pago', $provinciaUsuario, "('Importacion','Exportacion')");
			}else if( $estado=='verificacion'){
				$res = $cmsn->listarImportacionExportacionRevisionProvinciaRS($conexion, 'verificacion', $provinciaUsuario, "('Importacion','Exportacion')");
			}else if( $estado=='verificacionVUE'){
				$res = $cmsn->listarImportacionExportacionRevisionProvinciaRS($conexion, 'verificacion', $provinciaUsuario, "('Importacion','Exportacion')");
			}

			$nombreModulo = 'mercanciasSinValorComercial';
			$nombreSolicitud = $tipoSolicitud;
			
		break;

  
		case 'dossierFertilizantes':
		
		    require_once '../../clases/ControladorDossierFertilizante.php';
		    
		    $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		    
		    $cdf = new ControladorDossierFertilizante();
		    
		    if($provinciaUsuario == 'Pichincha'){
		        if( $estado=='pago'){
		            $res=$cdf->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		        }else if( $estado=='verificacion'){
		            $res=$cdf->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		        }else if( $estado=='verificacionVUE'){
		            $res=$cdf->listarSolicitudesPorEstadoProvincia($conexion, 'verificacion', $provinciaUsuario);
		        }
		    }
		   
		    
		    $nombreArchivo = 'abrirDossierEnviadoFinanciero';
		    $nombreModulo = 'dossierFertilizante';
		    $nombreSolicitud = $tipoSolicitud;
		    
		    
		break;
		
		case 'ensayoEficacia':
		    
		    require_once '../../clases/ControladorEnsayoEficacia.php';
		    
		    $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		    
		    $cee = new ControladorEnsayoEficacia();
		    
		    if( $estado=='pago'){
		        $res=$cee->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacion'){
		        $res=$cee->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacionVUE'){
		        $res=$cee->listarSolicitudesPorEstadoProvincia($conexion, 'verificacion', $provinciaUsuario);
		    }
		    
		    $nombreArchivo = 'abrirEnsayoEnviadoFinanciero';
		    $nombreModulo = 'ensayoEficacia';
		    $nombreSolicitud = $tipoSolicitud;		    
		    
		break;
		
		case 'dossierPlaguicida':
		    
		    require_once '../../clases/ControladorDossierPlaguicida.php';
		    
		    $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		    
		    $cep = new ControladorDossierPlaguicida();
		    
		    if($provinciaUsuario == 'Pichincha'){
    		    if( $estado=='pago'){
    		        $res=$cep->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
    		    }else if( $estado=='verificacion'){
    		        $res=$cep->listarSolicitudesPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
    		    }else if( $estado=='verificacionVUE'){
    		        $res=$cep->listarSolicitudesPorEstadoProvincia($conexion, 'verificacion', $provinciaUsuario);
    		    }
		    }
		    
		    $nombreArchivo = 'abrirDossierEnviadoFinanciero';
		    $nombreModulo = 'dossierPlaguicida';
		    $nombreSolicitud = $tipoSolicitud;
		    
		break;
		
		case 'certificacionBPA':
			
			require_once '../../clases/ControladorCertificacionBPA.php';
			
			$cbpa = new ControladorCertificacionBPA();
			
			$cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
			
			if( $estado=='pago'){
				$res=$cbpa->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
			}else if( $estado=='verificacion'){
				$res=$cbpa->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
			}else if( $estado=='verificacionVUE'){
				$res=$cbpa->obtenerSolicitudPorEstadoProvincia($conexion, 'verificacion',$provinciaUsuario);
			}
			
			$nombreArchivo = 'abrirSolicitudEnviadaFinanciero';
			$nombreModulo = 'certificacionesBPA';
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		case 'certificadoFito':
		    
		    require_once '../../clases/ControladorCertificadoFito.php';
		    
		    $ccf = new ControladorCertificadoFito();
		    
		    $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		    
		    if( $estado=='pago'){
		        $res=$ccf->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacion'){
		        $res=$ccf->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacionVUE'){
		        $res=$ccf->obtenerSolicitudPorEstadoProvincia($conexion, 'verificacion',$provinciaUsuario);
		    }
		    
		    $nombreArchivo = 'abrirSolicitudEnviadaFinanciero';
		    $nombreModulo = 'certificadoFito';
		    $nombreSolicitud = $tipoSolicitud;
		    
		 break;
		 
		 case 'dossierPecuario':
		    
		    $cdpmvc = new ControladorDossierPecuario();
		    
		    $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		    
		    if( $estado=='pago'){
		        $res=$cdpmvc->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacion'){
		        $res=$cdpmvc->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		    }else if( $estado=='verificacionVUE'){
		        $res=$cdpmvc->obtenerSolicitudPorEstadoProvincia($conexion, 'verificacion',$provinciaUsuario);
		    }
		    
		    $nombreArchivo = 'abrirSolicitudEnviadaFinanciero';
		    $nombreModulo = 'dossierPecuario';
		    $nombreSolicitud = $tipoSolicitud;
		    
		    break;
		    
		 case 'modificacionProductoRia':
		     
		     require_once '../../clases/ControladorModificacionProductoRia.php';
		     
		     $cmp = new ControladorModificacionProductoRia();
		     
		     $cabecera='<th>#</th>
						<th>Identificador</th>
						<th>#Solicitud</th>
						<th>Fecha solicitud</th>';
		     
		     if($estado=='pago'){
		         $res=$cmp->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		     }else if($estado=='verificacion'){
		         $res=$cmp->obtenerSolicitudPorEstadoProvincia($conexion, $estado, $provinciaUsuario);
		     }else if($estado=='verificacionVUE'){
		         $res=$cmp->obtenerSolicitudPorEstadoProvincia($conexion, 'verificacion',$provinciaUsuario);
		     }
		     
		     $nombreArchivo = 'abrirSolicitudEnviadaFinanciero';
		     $nombreModulo = 'modificacionProductoRia';
		     $nombreSolicitud = $tipoSolicitud;
		     
		     break;
			
		default :
			$res='';
			$nombreArchivo = '';
			$nombreModulo = '';
		break;

	}
	
	if($tipoSolicitud=='Emisión de Etiquetas' || $tipoSolicitud == 'dossierPecuario' || $tipoSolicitud == 'dossierPlaguicida' || 
		$tipoSolicitud == 'dossierFertilizantes' || $tipoSolicitud == 'ensayoEficacia' || $tipoSolicitud == 'certificacionBPA' ||
		$tipoSolicitud == 'certificadoFito' || $tipoSolicitud == 'modificacionProductoRia'){
			
		while($solicitud = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
								id="'.$solicitud['id_solicitud'].'"
								class="item"
								data-rutaAplicacion="'.$nombreModulo.'"
								data-opcion="'.$nombreArchivo.'"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem"
								data-idOpcion ="'.$estado.'">
								<td>'.++$contador.'</td>
								<td style="white-space:nowrap;"><b>'.$solicitud['identificador_operador'].'</b></td>
								<td>'.$solicitud['numero_solicitud'].'</td>
								<td>'.date('d/m/Y (G:i)',strtotime($solicitud['fecha_registro'])).'</td>
						</tr>');
		}
	}else{
		while($solicitud = pg_fetch_assoc($res)){
		
			if($solicitud['id_vue'] != ''){
				$numeroSolicitud = $solicitud['id_vue'];
			}else{
				$numeroSolicitud = $solicitud['id_solicitud'];
			}
			
			if($tipoSolicitud == 'mercanciasImportacionExportacion'){
				switch ($solicitud['tipo_certificado']){
					case 'Exportacion':
						$nombreArchivo = 'abrirExportacionFinanciero';
					break;
					case 'Importacion':
						$nombreArchivo = 'abrirImportacionFinanciero';
					break;
				}
			}
		
			$itemsFiltrados[] = array('<tr
							id="'.$solicitud['id_solicitud'].'"
							class="item"
							data-rutaAplicacion="'.$nombreModulo.'"
							data-opcion="'.$nombreArchivo.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem"
							data-idOpcion ="'.$estado.'">
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$solicitud['identificador_operador'].'</b></td>
							<td>'.$numeroSolicitud.'</td>
							<td>'.($solicitud['tipo_certificado']!=''?$solicitud['tipo_certificado']:$nombreSolicitud).'</td>
							<td>'.$solicitud['pais'].'</td>
		
					</tr>');
		}
		
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<?php echo $cabecera;?>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#listadoItems").removeClass("comunes");
	});

	//$('#_asignar').addClass('_asignar');
	//$('#_asignar').attr('id', < ?php echo json_encode($tipoSolicitud);?>+'-'+< ?php echo json_encode($estado);?>);
</script>

