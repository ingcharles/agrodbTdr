<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cu = new controladorUsuarios();
$ca = new ControladorAreas();

//Obtener usuarios por provincia de acuerdo al perfil

$identificador =  $_SESSION['usuario'];
$nombreOpcion = $_POST['nombreOpcion'];

$usuarioPorArea = true;

switch ($nombreOpcion){
	
	case 'R.O. Sanidad vegetal' :
		$formulario = 'operadoresSV';
		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
		$perfil = 'PFL_REV_OPER_SV';
	break;
	
	case 'R.O. Sanidad animal' :
	    $formulario = 'operadoresSA';
	    $combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
	    $perfil = 'PFL_REV_OPER_SA';
    break;
    
	case 'R.O. Inocuidad de los alimentos' :
	    $formulario = 'operadoresAI';
	    $combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
	    $perfil = 'PFL_REV_OPER_AI';
    break;
    
	case 'R.O. Laboratorios' :
	    $formulario = 'operadoresLT';
	    $combo = '<option value="Documental" >Revisión documental</option><option value="CargarRespaldo" >Subir convenio</option>';
	    $perfil = 'PFL_REV_OPER_LAB';
    break;
    
	case 'Agrícolas' :
	    $formulario = 'operadoresAGR';
	    $combo = '<option value="Técnico" >Inspección</option>';
	    $perfil = 'PFL_REV_OPER_AGR';
    break;
    
	case 'Fertilizantes' :
	    $formulario = 'operadoresFER';
	    $combo = '<option value="Documental" >Revisión documental</option>';
	    $perfil = 'PFL_REV_OPER_FER';
    break;
    
	case 'Pecuarios' :
	    $formulario = 'operadoresPEC';
	    $combo = '<option value="Técnico" >Inspección</option>';
	    $perfil = 'PFL_REV_OPER_PEC';
    break;
		
	case 'Importaciones vegetal' :
		$formulario = 'Importación sanidad vegetal';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_REV_IMP_SV';
	break;
	
	case 'Importaciones animal' :
		$formulario = 'Importación sanidad animal';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_REV_IMP_SA';
	break;
		
	case 'Importaciones plaguicidas' :
		$formulario = 'Importación plaguicidas';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_REV_IMP_IAP';
	break;
			
	case 'Importaciones veterinarios' :
		$formulario = 'Importación veterinarios';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_REV_IMP_IAV';
	break;
	
	case 'Importaciones fertilizantes' :
		$formulario = 'Importación fertilizantes';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_INS_IMP_FER';
	break;
		
	case 'Documento de destinacion aduanera' :
		$formulario = 'DDA';
		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
		$perfil = 'PFL_INSDO_DESAD';
	break;
		
	case 'Fitosanitario de exportación' :
		$formulario = 'Fitosanitario';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil ='PFL_REV_FIT_EXP';
	break;
		
	case 'Zoosanitario de exportación' :
		$formulario = 'Zoosanitario';
		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
		$perfil = 'PFL_REV_ZOO_EXP';
	break;
		
	case 'Certificado de libre venta' :
		$formulario = 'CLV';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil = 'PFL_REV_CLV';
	break;
	
	case 'Certificado de calidad' :
		$formulario = 'certificadoCalidad';
		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
		$perfil = 'PFL_REV_CERT_CAL';
	break;

	case 'Fitosanitario de exportación V2' :
		$formulario = 'FitosanitarioExportacion';
		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico">Inspección</option>';
		$perfil ='PFL_FIT_EX_V2';
	break;
	
	case 'Exportación de mascotas':
		$formulario = 'mercanciasSinValorComercialExportacion';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil = 'PFL_ME_VA_IN_EXP';
	break;
	
	case 'Importación de mascotas':
		$formulario = 'mercanciasSinValorComercialImportacion';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil = 'PFL_ME_VA_IN_IMP';
	break;
	
	case 'RIA Almacenistas':
		$formulario = 'operadoresALM';
		$combo = '<option value="Técnico" >Inspección</option>';
		$perfil = 'PFL_REV_OPER_ALM';
	break;
		
	case 'Importación de muestras':
		$formulario = 'importacionMuestras';
		$combo = '<option value="Documental" >Revisión documental</option>';
		$perfil = 'PFL_INS_IMP_MUE';
		$usuarioPorArea = false;
	break;
	
	case 'Certificación BPA':
	    //Técnico de Provincia
	    if(pg_num_rows($cu->buscarPerfilUsuarioXCodigo($conexion, $identificador, 'PFL_REV_CERT_BPA')) > 0){
    		$formulario = 'certificacionBPA';
    		$combo = '<option value="Documental" >Revisión documental</option><option value="Técnico" >Inspección</option>';
    		$perfil = '';//'PFL_REV_CERT_BPA';
    	//Planta Central
	    }else if(pg_num_rows($cu->buscarPerfilUsuarioXCodigo($conexion, $identificador, 'PFL_ADM_CERT_BPA')) > 0){
	        $formulario = 'certificacionBPA';
	        $combo = '<option value="Documental" >Asignar Provincia</option>';
	        $perfil = '';//'PFL_ADM_CERT_BPA';	
	    //Coordinador
	    }else if(pg_num_rows($cu->buscarPerfilUsuarioXCodigo($conexion, $identificador, 'PFL_APR_CERT_BPA')) > 0){
	        $formulario = 'certificacionBPA';
	        $combo = '<option value="Aprobación" >Aprobación</option>';
	        $perfil = '';//'PFL_APR_CERT_BPA';
	    }
		$usuarioPorArea = false;
	break;
	
	case 'Tránsito Internacional':
	    $formulario = 'transitoInternacional';
	    $combo = '<option value="Documental" >Revisión documental</option>';
	    $perfil = 'PFL_INS_TRAN_INT';
	    $usuarioPorArea = false;
	    break;
	
	default:
		echo 'Formulario desconocido';
}

if($usuarioPorArea){
	$area = pg_fetch_assoc($cu->obtenerAreaUsuario($conexion,$identificador));
	
	if($area['categoria_area'] == '5'){
		$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area_padre']);
		
		while ($fila = pg_fetch_assoc($areaSubproceso)){
			$areaBusqueda .= "'".$fila['id_area']."',";
		}
		
		$areaBusqueda .= "'".$area['id_area_padre']."',";
		$areaBusqueda = "(".rtrim($areaBusqueda,',').")";
		
	}else if($area['categoria_area'] == '4' || $area['categoria_area'] == '3'){
		$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area']);
		
		while ($fila = pg_fetch_assoc($areaSubproceso)){
			$areaBusqueda .= "'".$fila['id_area']."',";
		}
		
		$areaBusqueda .= "'".$area['id_area']."',";
		$areaBusqueda = "(".rtrim($areaBusqueda,',').")";
	}else{
		$areaBusqueda = "('No definido')";
		$advertencia = true;
	}
	
	if($_SESSION['tipoEmpleado'] == 'Externo'){
		$advertencia = false;
	}
	
	$inspectores = $cu->obtenerUsuariosXareaPerfil($conexion, $areaBusqueda, $perfil);
}else{
	$inspectores = $cu->obtenerUsuariosPorCodigoPerfil($conexion, $perfil);
}


?>

<div id="estadoProvicional"></div>

<header>
	<nav>
	<form id="listaRevision" data-rutaAplicacion="revisionFormularios" data-opcion="listaRevisionDocumentalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Condición:</th>
			
				<td>
					<select id="condicion" name="condicion" required>
						<option value="" >Seleccione opción....</option>
						<?php echo $combo;?>		
					</select>	
				</td>
			
				<th>Asignación:</th>

				<td>
					<select id="inspectores" name="inspectores" required>
							<option value="" >Seleccione opción....</option>
							<option value="asignar" >Por asignar</option>
							<?php 
								while($fila = pg_fetch_assoc($inspectores)){
									echo '<option value="' . $fila['identificador'] . '">' . $fila['apellido'] . ', ' . $fila['nombre'] . '</option>';
								}
							?>
					</select>
					
					<input type="hidden" name="opcion" value= "<?php echo $_POST["opcion"];?>">
					<input type="hidden" id="solicitudes" name="solicitudes" value= "<?php echo $formulario;?>">
					<input type="hidden" id="estados" name="estados">
					<input type="hidden" id="estadoActual" name="estadoActual" value="inspeccion">
					<input type="hidden" id="nombreOpcion" name="nombreOpcion" value="<?php echo $nombreOpcion;?>">
					<input type="hidden" id="tipoProcesoCombo" name="tipoProcesoCombo">
					<input type="hidden" id="revisionUbicacion" name="revisionUbicacion">
					<input type="hidden" id="codigoTipoOperacion" name="codigoTipoOperacion">
				</td>
				
				</tr>
				
				<tr id="medio">	
					<th colspan="1">Medio:</th>
					<td>
    					<select id="medioTransporte" name="medioTransporte">
    						<option value="0" >Seleccione transporte</option>
    						<option value="AEREO" >Aéreo</option>
    						<option value="FLUVIAL" >Fluvial</option>
    						<option value="MARITIMO" >Marítimo</option>
    						<option value="TERRESTRE" >Terrestre</option>
    					</select>
    					</td>
				</tr>

				<tr>
					<td id="comboTipoOperacion" colspan="2"></td>
					<td id="operador" colspan="2"></td>
				</tr>

				<tr>
					<td colspan="5"><button>Filtrar lista</button></td>
				</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>

<script>

	var advertencia = <?php echo json_encode($advertencia);?>;
	var datoOperador = false;

	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');

		if(advertencia){
			$('#estadoProvicional').html('Por favor comunicarse con la dirección de Talento Humano para la actualización de datos de contrato.').addClass('alerta');
		}else{
			$('#estadoProvicional').html('');
		}
		
		$("#medio").hide();
		$("#labelDireccion").hide();
		$("#direccion").hide();
	});

	$("#inspectores").change(function (event) {

		$("#tipoOperacion").val("");
		$("#identificadorOperador").val("");
		$("#operador").hide();
		$('#tabla').empty();

		 switch ($('#solicitudes').val()) {

		 	case 'operadoresLT':
		 		if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('documental');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
			    }else if($('#condicion').val() == 'CargarRespaldo'){
			    	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('cargarRespaldo');
		    		}else{
		    			$("#estados").val('asignadoCargarRespaldo');
		    		}
		    	}
		 		datoOperador = true;
			break;
			
		    case 'operadoresAI':
		    	if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('documental');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
			    }else if($('#condicion').val() == 'Técnico'){
			    	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('inspeccion');
		    		}else{
		    			$("#estados").val('asignadoInspeccion');
		    		}
		    	}
		    	datoOperador = true;
		 	break;

			case 'operadoresSV':
			case 'operadoresSA':
			case 'operadoresAGR':
			case 'operadoresFER':
			case 'operadoresPEC':
			case 'operadoresALM':
				if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('documental');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
			    }else if($("#inspectores").val() == 'asignar'){
	    			$("#estados").val('inspeccion');
	    		}else{
	    			$("#estados").val('asignadoInspeccion');
	    		}
	        	datoOperador = true;
	        break;
	        
			case 'Importación sanidad vegetal':
			case 'Importación sanidad animal':
			case 'Importaciones plaguicidas':
			case 'Importaciones veterinarios':
			case 'Importaciones fertilizantes':
			case 'importacionMuestras':
			case 'transitoInternacional':					
				if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('enviado');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
			    }
				$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
			break;

			case 'DDA':
		    case 'Fitosanitario':
		        	if($("#inspectores").val()  != ""){
		        		$("#medio").show();
		        	}
		        	
		        	if($('#condicion').val() == 'Documental'){
			        	if($("#inspectores").val() == 'asignar'){
			    			$("#estados").val('enviado');
			    		}else{
			    			$("#estados").val('asignadoDocumental');
			    		}
			        	$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
				    }else if($('#condicion').val() == 'Técnico'){
				    	if($("#inspectores").val() == 'asignar'){
			    			$("#estados").val('inspeccion');
			    		}else{
			    			$("#estados").val('asignadoInspeccion');
			    		}
				    	$("#listaRevision").attr('data-opcion', 'listaInspeccionesFiltrado');
			    	}
			break;
			
		    case 'Zoosanitario':
	        	if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('enviado');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
		        	$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
			    }else if($('#condicion').val() == 'Técnico'){
			    	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('inspeccion');
		    		}else{
		    			$("#estados").val('asignadoInspeccion');
		    		}
			    	$("#listaRevision").attr('data-opcion', 'listaInspeccionesFiltrado');
		    	}
			break;

		    case 'FitosanitarioExportacion':
		        if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('enviado');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
		        	$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
			    }else if($('#condicion').val() == 'Técnico'){
			    	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('inspeccion');
		    		}else{
		    			$("#estados").val('asignadoInspeccion');
		    		}
			    	$("#listaRevision").attr('data-opcion', 'listaInspeccionesFiltrado');
		    	}
		    break;

			case 'mercanciasSinValorComercialImportacion':
			case 'mercanciasSinValorComercialExportacion':
				if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('revisionDocumental');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
		        	$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
			    }

		    break;

			case 'CLV':
		        if($('#condicion').val() == 'Documental'){
		        	if($("#inspectores").val() == 'asignar'){
		    			$("#estados").val('enviado');
		    		}else{
		    			$("#estados").val('asignadoDocumental');
		    		}
			    }
		        $("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltrado');
		    break;

			case 'certificacionBPA':

				$('#comboTipoOperacion').remove();
				$('#operador').attr('colspan',5);
				
				$('#condicion').prop('required');
				$('#inspectores').prop('required');
				
		    	if($('#condicion').val() == 'Documental'){
		    			$("#estados").val('enviado');        	
			    }else if($('#condicion').val() == 'Técnico'){
		    			$("#estados").val('inspeccion');
		    	}else if($('#condicion').val() == 'Aprobación'){
		    			$("#estados").val('aprobacion');
		    	}

		    	$("#listaRevision").attr('data-opcion', 'combosOperadorBPA');
		    	$("#listaRevision").attr('data-destino', 'operador');
		    	$("#tipoProcesoCombo").val('operadores');
		    	event.stopImmediatePropagation();
		    	abrir($("#listaRevision"), event, false);
		    	
		 	break;
	    }

		 $("#listaRevision").attr('data-destino', 'tabla');

		if(datoOperador){
			$("#listaRevision").attr('data-opcion', 'combosOperador');
	    	$("#listaRevision").attr('data-destino', 'comboTipoOperacion');
	    	$("#tipoProcesoCombo").val('tipoOperacion');
	    	event.stopImmediatePropagation();
	    	abrir($("#listaRevision"), event, false); 
		}
	});

	$("#condicion").change(function (event) {
		$("#inspectores").val("");
		$("#tipoOperacion").val("");
		$("#identificadorOperador").val("");
		$('#tabla').empty();
	});

	$("#listaRevision").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>
