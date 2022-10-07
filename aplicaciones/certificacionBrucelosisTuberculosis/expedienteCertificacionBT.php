<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cc = new ControladorCatalogos();
	
	$identificador=$_SESSION['usuario'];
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
	}
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>
	
<header>
	<h1>Expediente de Certificación de Predios Libres de Brucelosis y Tuberculosis</h1>
	<nav>
		<form id="listaRecertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="expedienteCertificacionBTDetalle" data-destino="detalleItem" action="aplicaciones/certificacionBrucelosisTuberculosis/expedienteCertificacionBTDetalle.php" target="_blank" method="post">
		
			<table class="filtro">
				<tr>
					<th>Número Solicitud:</th>
					<td>
						<input type="text" id="bNumSolicitud" name="bNumSolicitud" required="required"/>
					</td>
					
					<th>Tipo:</th>
					<td>
						<select id="bTipo" name="bTipo" required="required">
							<option value="">Seleccione....</option>
							<option value="certificacion">Certificación</option>
							<option value="recertificacion">Recertificación</option>
						</select>
					</td>
					
				</tr>
				
				<tr>
			
			
				<!-- tr>
					<th>Número Solicitud:</th>
					<td>
						<input type="text" id="bNumSolicitud" name="bNumSolicitud" required="required"/>
					</td>
					
					<th>Nombre Predio:</th>
					<td>
						<input type="text" id="bNombrePredio" name="bNombrePredio" />
					</td>
					
				</tr>
				
				<tr>
					
					<th>Nombre Propietario:</th>
					<td>
						<input type="text" id="bNombrePropietario" name="bNombrePropietario" />
					</td>
				
					<th>Provincia:</th>
					<td>
						<select id="bIdProvincia" name="bIdProvincia" >
							<option value="">Seleccione....</option>
								< ?php 
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									
									if($perfilAdmin==''){
										foreach ($provincias as $provincia){
											if($provincia['nombre'] == $_SESSION['nombreProvincia']){
												echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
											}
										}
									}else{
										foreach ($provincias as $provincia){
											echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
										}
									}
								?>
						</select>
					</td>
					
				</tr>
				
				<tr>
					<th>Cantón:</th>
					<td>
						<select id="bIdCanton" name="bIdCanton" disabled="disabled">
						</select>
					</td>

					<th>Parroquia:</th>
					<td>
						<select id="bIdParroquia" name="bIdParroquia" disabled="disabled">
						</select>
					</td>
					
				</tr>
				
				<tr>
					
					<th>Certificación:</th>
					<td>
						<select id="bCertificacion" name="bCertificacion">
							<option value="">Seleccione....</option>
							<option value="Brucelosis">Brucelosis</option>
							<option value="Tuberculosis">Tuberculosis</option>
						</select>
					</td>
										
					<th>Tipo:</th>
					<td>
						<select id="bTipo" name="bTipo" required="required">
							<option value="">Seleccione....</option>
							<option value="certificacion">Certificación</option>
							<option value="recertificacion">Recertificación</option>
						</select>
					</td-->

					<!-- >th>Estado:</th>
					<td>
						<select id="bEstado" name="bEstado" >
							<option value="">Seleccione....</option>
							<option value="activo">Activo</option>
							<option value="inspeccion">Inspección</option>
							<option value="tomaMuestras">Muestras de Laboratorio</option>
							<option value="plantaCetral">Enviado a Planta Central</option>
							<option value="aprobado">Aprobado</option>
							<option value="rechazado">Rechazado</option>
							<option value="porExpirar">Para Recertificación</option>
						</select>
					</td-->
				
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

    $("#bTipo").change(function(){
    	if($("#bTipo option:selected").val()=='certificacion'){
	    	$("#listaRecertificacionBT").attr('data-opcion', 'expedienteCertificacionBTDetalle');
		    $("#listaRecertificacionBT").attr('action', 'aplicaciones/certificacionBrucelosisTuberculosis/expedienteCertificacionBTDetalle.php');
        }else{
        	$("#listaRecertificacionBT").attr('data-opcion', 'expedienteRecertificacionBTDetalle');
		    $("#listaRecertificacionBT").attr('action', 'aplicaciones/certificacionBrucelosisTuberculosis/expedienteRecertificacionBTDetalle.php');
        }
	});
</script>