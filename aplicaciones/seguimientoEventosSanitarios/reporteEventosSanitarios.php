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
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Seguimiento de Eventos Sanitarios'),0,'id_perfil');
	}
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>
	
<header>
	<h1>Eventos Sanitarios</h1>
	<nav>
		<form id="NotificacionEventosSanitarios" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="NotificacionEventosSanitarios" data-destino="detalleItem" action="aplicaciones/seguimientoEventosSanitarios/reporteEventosSanitariosDetalle.php" target="_blank" method="post">
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
					<th>Provincia:</th>
					<td>
						<select id="bIdProvincia" name="bIdProvincia">
							<option value="">Seleccione....</option>
								<?php 
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
										echo '<option value="">Todas</option>';
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
					
					<th>Sitio:</th>
					<td>
						<input type="text" id="bSitioPredio" name="bSitioPredio" />
					</td>
				</tr>
							
				<tr>	
					<th>Predio:</th>
					<td>
						<input type="text" id="bFincaPredio" name="bFincaPredio" />
					</td>

					
					<th>Estado:</th>
					<td>
						<select id="bEstado" name="bEstado" >
							<option value="">Seleccione....</option>
							<option value="">Todos</option>
							<option value="Creado">Creado</option>
							<option value="primeraVisita">Primera Visita</option>
							<option value="visita">Visita</option>
							<option value="visitaCierre">Visita de Cierre</option>
							<option value="cerrado">Cerrado</option>
						</select>
					</td>
				</tr>				
				
				<tr>
					<td colspan="5"><button>Generar reporte</button></td>
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
	var array_oficina= <?php echo json_encode($oficina); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');

		$("#bFechaCreacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
	});

	if(usuario == '0'){
		$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#_nuevo").hide();
		$("#_eliminar").hide();
	}

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

	
</script>