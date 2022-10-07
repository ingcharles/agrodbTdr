<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cc = new ControladorCatalogos();
	$cnes = new ControladorEventoSanitario();
	$listaCatalogos = new ControladorEventoSanitario();
	
	$identificador=$_SESSION['usuario'];
	
	$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Seguimiento de Eventos Sanitarios'),0,'id_perfil');
	
	if($identificador==''){
		$usuario=0;
	}else{		
		$perfilLab = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Laboratorio Seguimiento de Eventos Sanitarios'),0,'id_perfil');
		$laboratorioUsuario = pg_fetch_result($cnes->buscarLaboratorioUsuario($conexion, $identificador),0,'id_laboratorio');
		
		if (($perfilLab != '') && ($laboratorioUsuario != '')){
			$usuario=1;
		}else{
			$usuario=0;
		}
	}
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>
	
<header>
	<h1>Análisis de Laboratorios para Seguimiento de Eventos Sanitarios</h1>
	<nav>
		<form id="listaEventosSanitarios" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="listaEventoSanitarioLaboratoriosFiltrada" data-destino="tabla">
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
				
				<th>Síndrome:</th>
					<td>
						<select id="bSindrome" name="bSindrome" >
							<option value="">Seleccione....</option>
							<?php 
								$patologias = $listaCatalogos->listarCatalogos($conexion,'PATOLOGIAS');
								
								while ($patologia = pg_fetch_assoc($patologias)){
									echo '<option value="' . $patologia['codigo'] . '">' . $patologia['nombre'] . '</option>';
								}
							?>
						</select>
					</td>
				</tr>				
				
				<tr>	
					<th>Estado:</th>
					<td>
						<select id="bEstado" name="bEstado" required="required">
							<!-- option value="">Seleccione....</option-->
							<option value="tomaMuestras">Toma de Muestras</option>
						</select>
					</td>
					
					<th>Laboratorio:</th>
					<td>
						<select id="bIdLaboratorio" name="bIdLaboratorio" required="required">
							
								<?php 
								
									if($perfilAdmin != ''){
										$laboratorios1 = $cnes->abrirCatalogoLaboratorios($conexion);
									
										echo '<option value="">Seleccione....</option>';
										
										while($fila = pg_fetch_assoc($laboratorios1)){
											echo "<option value=".$fila['id_laboratorio'].">".$fila['nombre']."</option>";
										}
									}else{
										$laboratorios2 = $cnes->abrirCatalogoLaboratorios($conexion);
										
										while($fila = pg_fetch_assoc($laboratorios2)){
											if($fila['id_laboratorio'] == $laboratorioUsuario){
												echo "<option value=".$fila['id_laboratorio'].">".$fila['nombre']."</option>";
											}
										}
									}
								?>
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

	if(usuario == '0'){
		alert("Su usuario no dispone del perfil para acceder a esta sección.");
		$("#listaEventosSanitarios").hide();
	}else{
		$("#listaEventosSanitarios").show();
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


	
	$("#listaEventosSanitarios").submit(function(e){
		abrir($(this),e,false);
	});
	
</script>