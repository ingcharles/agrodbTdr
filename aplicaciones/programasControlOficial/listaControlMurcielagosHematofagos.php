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
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Programas Control Oficial Murciélagos Hematófagos'),0,'id_perfil');
	}
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficina = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
?>
	
<header>
	<h1>Murciélagos Hematófagos</h1>
	<nav>
		<form id="listaControlMurcielagosHematofagos" data-rutaAplicacion="programasControlOficial" data-opcion="listaControlMurcielagosHematofagosFiltrada" data-destino="tabla">
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
						<select id="bIdProvincia" name="bIdProvincia" required="required">
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
						<input type="text" id="bSitio" name="bSitio" />
					</td>
				</tr>
				
				<tr>
					<th>Oficina:</th>
					<td>
						<select id="bIdOficina" name="bIdOficina" disabled="disabled">
						</select>
					</td>
					
					<th>Nueva Inspección:</th>
					<td>
						<select id="bNuevaInspeccion" name="bNuevaInspeccion">
							<option value="">Seleccione....</option>
							<option value="Si">Si</option>
							<option value="No">No</option>
							<option value="">No asignado</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>Estado:</th>
					<td>
						<select id="bEstado" name="bEstado" >
							<option value="">Seleccione....</option>
							<option value="">Todos</option>
							<option value="activo">Activo</option>
							<option value="cerrado">Cerrado</option>
							<option value="inspeccion">Inspección Planificada</option>
						</select>
					</td>

					<td colspan="5"><button>Buscar</button></td>
				</tr>
			</table>
		</form>		
	</nav>
	
	<nav>
		<?php 			
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);			
			
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
		?>
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

		soficina ='0';
		soficina = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#bIdCanton").val()==array_oficina[i]['padre']){
		    	soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
			    } 
	    	}
	    soficina += '<option value="0">Otro</option>';

	    $('#bIdOficina').html(soficina);
		$("#bIdOficina").removeAttr("disabled");
	});


	
	$("#listaControlMurcielagosHematofagos").submit(function(e){
		abrir($(this),e,false);
	});
	
</script>