<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cc = new ControladorCatalogos();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Laboratorio Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
		$laboratorioUsuario = pg_fetch_result($cbt->buscarLaboratorioUsuario($conexion, $identificador),0,'id_laboratorio');
		
		if (($perfilAdmin != '') && ($laboratorioUsuario != '')){
			$usuario=1;
		}else{
			$usuario=0;
		}
	}
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>
	
<header>
	<h1>Análisis de Laboratorios para Certificación de Brucelosis y Tuberculosis</h1>
	<nav>
		<form id="listaCertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="listaCertificacionBTLaboratoriosFiltrada" data-destino="tabla">
			<input type='hidden' id='bLaboratorioUsuario' name='bLaboratorioUsuario' value="<?php echo $laboratorioUsuario;?>" />
		
			<table class="filtro">
				<tr>
					<th>Número Solicitud:</th>
					<td>
						<input type="text" id="bNumSolicitud" name="bNumSolicitud" />
					</td>
					
					<th>Fecha creación:</th>
					<td>
						<input type="text" id="bFechaCreacion" name="bFechaCreacion" />
					</td>
				</tr>
				
				<tr>
					<th>Nombre Predio:</th>
					<td>
						<input type="text" id="bNombrePredio" name="bNombrePredio" />
					</td>
					
					<th>Nombre Propietario:</th>
					<td>
						<input type="text" id="bNombrePropietario" name="bNombrePropietario" />
					</td>
				</tr>
				
				<tr>
					<th>Provincia:</th>
					<td>
						<select id="bIdProvincia" name="bIdProvincia">
							<option value="">Seleccione....</option>
							<option value="">Todas</option>
								<?php 
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
									}
								?>
						</select>
					</td>
					
					<th>Cantón:</th>
					<td>
						<select id="bIdCanton" name="bIdCanton" disabled="disabled">
						</select>
					</td>
				</tr>				
				
				<tr>
					<th>Parroquia:</th>
					<td>
						<select id="bIdParroquia" name="bIdParroquia" disabled="disabled">
						</select>
					</td>
					
					<th>Certificación:</th>
					<td>
						<select id="bCertificacion" name="bCertificacion">
							<option value="">Seleccione....</option>
							<option value="Brucelosis">Brucelosis</option>
							<option value="Tuberculosis">Tuberculosis</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>Tipo:</th>
					<td>
						<select id="bTipo" name="bTipo" required="required">
							<option value="">Seleccione....</option>
							<option value="certificacion">Certificación</option>
							<option value="recertificacion">Recertificación</option>
						</select>
					</td>
					
					<th>Estado:</th>
					<td>
						<select id="bEstado" name="bEstado" required="required">
							<!-- option value="">Seleccione....</option-->
							<option value="tomaMuestras">Toma de Muestras</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td colspan="5"><button>Buscar</button></td>
				</tr>
			</table>
		</form>		
	</nav>
	
</header>

<div id="tabla"></div>
	
<script>
	var usuario = <?php echo json_encode($usuario); ?>;
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');

		$("#bFechaCreacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
	});

//	if(usuario == '0'){
//		alert("Su usuario no dispone del perfil para acceder a esta sección.");
//		$("#listaCertificacionBT").hide();
//	}else{
		$("#listaCertificacionBT").show();
//	}

	$("#bIdProvincia").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#bIdProvincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#bIdCanton').html(scanton);
	    $("#bIdCanton").removeAttr("disabled");
	});

    $("#bIdCanton").change(function(){
    	sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#bIdCanton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#bIdParroquia').html(sparroquia);
		$("#bIdParroquia").removeAttr("disabled");
	});


	
	$("#listaCertificacionBT").submit(function(e){
		abrir($(this),e,false);
	});
	
</script>