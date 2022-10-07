<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
	}
	
	$ruta = 'certificacionBrucelosisTuberculosis';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$idRecertificacionBT = $_POST['id'];
	$certificacionBT = pg_fetch_assoc($cbt->abrirRecertificacionBT($conexion, $idRecertificacionBT));
	$resultadoLaboratorio = $cbt->abrirResultadoLaboratorioRecertificacion($conexion, $idRecertificacionBT);
	
	$informacionPredio = $cbt->abrirInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT);
	$informacionPredioConsulta = $cbt->abrirInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT);
	$inventarioConsulta = $cbt->abrirInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	$manejoAnimalConsulta = $cbt->abrirManejoAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	$adquisicionAnimalesConsulta = $cbt->abrirAdquisicionAnimalesRecertificacionBT($conexion, $idRecertificacionBT);
	$veterinarioConsulta = $cbt->abrirVeterinarioRecertificacionBT($conexion, $idRecertificacionBT);
	$vacunacionConsulta = $cbt->abrirVacunacionRecertificacionBT($conexion, $idRecertificacionBT);
	$patologiaConsulta = $cbt->abrirPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT);
	
	
	
?>

<header>
	<h1>Predios para Certificación como Libres de Brucelosis Bovina</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Identificación y Localización del Predio</h2>

	<div id="informacion">
	
		<fieldset>
			<legend>Información de Localización del Predio</legend>
	
			<div data-linea="0">
				<label>N° Solicitud:</label>
				<?php echo $certificacionBT['num_solicitud'];?>
			</div>
			
			<div data-linea="0" >
				<div id="estadoSolicitud">
					<label>Estado:</label>
					<?php echo $certificacionBT['estado'];?>
				</div>
			</div>
				
			<div data-linea="1">
				<label>Fecha:</label>
				<?php echo date('j/n/Y',strtotime($certificacionBT['fecha']));?>
			</div>
			
			<div data-linea="1">
				<div id="certificadoSolicitud">
					<label>Certificado:</label>
					<?php echo ($certificacionBT['ruta_certificado']!=''? '<a href='.$certificacionBT['ruta_certificado'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado</a>':'')?>
				</div>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Encuestado:</label>
				<?php echo $certificacionBT['nombre_encuestado'];?>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Predio:</label>
				<?php echo $certificacionBT['nombre_predio'];?>
			</div>
			
			<div data-linea="3">
				<label>Num. Cert. Fiebre Aftosa:</label>
				<?php echo $certificacionBT['numero_certificado_fiebre_aftosa'];?>
			</div>
			
			<div data-linea="3">
				<label>Certificación:</label>
				<?php echo $certificacionBT['certificacion_bt'];?>
			</div>
			
			<div data-linea="44" id="lFechaMuestreoBrucelosisD">
				<label>Fecha de último muestreo de Brucelosis:</label>
				<?php echo $certificacionBT['fecha_muestreo_brucelosis'];?>	
			</div>
		
		</fieldset>
		
		<fieldset>
			<legend>Información del Propietario</legend>			
			
			<div data-linea="4">
				<label>Nombre:</label>
				<?php echo $certificacionBT['nombre_propietario'];?>
			</div>
			
			<div data-linea="4">
				<label>Cédula:</label>
				<?php echo $certificacionBT['cedula_propietario'];?>
			</div>
			
			<div data-linea="5">
				<label>Teléfono:</label>
				<?php echo $certificacionBT['telefono_propietario'];?>
			</div>
			
			<div data-linea="5">
				<label>Celular:</label>
				<?php echo $certificacionBT['celular_propietario'];?>
			</div>
			
			<div data-linea="6">
				<label>Correo Electrónico:</label>
				<?php echo $certificacionBT['correo_electronico_propietario'];?>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Ubicación y Datos Generales</legend>
	
			<div data-linea="7">
				<label>Provincia</label>
				<?php echo $certificacionBT['provincia'];?>	
			</div>
				
			<div data-linea="7">
				<label>Cantón</label>
					<?php echo $certificacionBT['canton'];?>
				</div>
				
			<div data-linea="8">	
				<label>Parroquia</label>
					<?php echo $certificacionBT['parroquia'];?>
			</div>
						
		</fieldset>
		
		<fieldset>
			<legend>Coordenadas</legend>
			
			<div data-linea="9">
				<label>X:</label>
				<?php echo $certificacionBT['utm_x'];?>
			</div>
			
			<div data-linea="9">
				<label>Y:</label>
				<?php echo $certificacionBT['utm_y'];?>
			</div>
			
			<div data-linea="9">
				<label>Z:</label>
				<?php echo $certificacionBT['utm_z'];?>
			</div>
			
			<div data-linea="9">
				<label>Huso/Zona:</label>
				<?php echo $certificacionBT['huso_zona'];?>
			</div>
	
		</fieldset>	
		
		<fieldset>
			<legend>Información del Técnico Responsable</legend>			
			
			<div data-linea="14">
				<label>Técnico Responsable (externo):</label>
				<?php echo $certificacionBT['nombre_tecnico_responsable'];?>
			</div>
		</fieldset>
		
		<fieldset id="adjuntos">
				<legend>Mapa de Ubicación</legend>
		
					<div data-linea="1">
						<label>Mapa:</label>
						<?php echo ($certificacionBT['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
					</div>
			</fieldset>
			
			<fieldset id="adjuntosInforme">
				<legend>Informe</legend>
		
					<div data-linea="1">
						<label>Informe:</label>
						<?php echo ($certificacionBT['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
					</div>

			</fieldset>		

	</div>
	
</div>

<div class="pestania">

	<h2>Datos Generales del Predio</h2>
	
	<fieldset id="detalleInformacionPredioConsultaFS">
		<legend>Información del Predio Registrada</legend>
		<table id="detalleInformacionPredioConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Cerramientos</th>				    
				    <th width="15%">Control ingreso Personas</th>
				    <th width="15%">Control ingreso Animales</th>
				    <th width="15%">Identificación ind Bovinos</th>
				    <th width="15%">Manga, embudo, brete</th>
				</tr>
			</thead>
			<?php 
				while ($infoPredio = pg_fetch_assoc($informacionPredioConsulta)){
					echo $cbt->imprimirLineaInformacionPredioRecertificacionBTConsulta($infoPredio['id_recertificacion_bt_informacion_predio'],
												$infoPredio['cerramientos'], $infoPredio['control_ingreso_personas'],
												$infoPredio['manga_embudo_brete'], $infoPredio['identificacion_bovinos'], 
												$infoPredio['control_ingreso_animal'], $ruta, $infoPredio['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInventarioAnimalConsultaFS">
		<legend>Inventario de Animales en el Predio Registrados</legend>
		<table id="detalleInventarioAnimalConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoInventario = pg_fetch_assoc($inventarioConsulta)){
					echo $cbt->imprimirLineaInventarioPredioRecertificacionBTConsulta($infoInventario['id_recertificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], $ruta, $infoInventario['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
</div>

<div class="pestania">

	<h2>Manejo General de Animales y Potreros</h2>
	
	<fieldset id="detalleManejoAnimalesPotrerosConsultaFS">
		<legend>Manejo de Animales y Potreros Registrados</legend>
		<table id="detalleManejoAnimalesPotrerosConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Pastos Comunales</th>
				    <th width="15%">Arrienda sus Potreros</th>
				    <th width="15%">Arrienda otros Potreros</th>
				    <th width="15%">Lleva animales a ferias</th>
				    <th width="15%">Desinfecta animales</th>
				    <th width="15%">Están en Programa Certificación</th>
				</tr>
			</thead>
			<?php //cambiar
			while ($infoManejoAnimal = pg_fetch_assoc($manejoAnimalConsulta)){
					echo $cbt->imprimirLineaManejoAnimalesPotrerosRecertificacionBTConsulta($infoManejoAnimal['id_recertificacion_bt_manejo_animales_potreros'], 
																		$infoManejoAnimal['pastos_comunales'], 
																		$infoManejoAnimal['arrienda_potreros'], 
																		$infoManejoAnimal['arrienda_otros_potreros'],
																		$infoManejoAnimal['animales_ferias'], 
																		$infoManejoAnimal['desinfecta_animales'],  
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta, $infoManejoAnimal['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleAdquisicionAnimalesConsultaFS">
		<legend>Adquisición de Animales para el Predio Registrados</legend>
		<table id="detalleAdquisicionAnimalesConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
				</tr>
			</thead>
			<?php //cambio
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimalesConsulta)){
					echo $cbt->imprimirLineaAdquisicionAnimalesRecertificacionBTConsulta($infoAdquisicionAnimales['id_recertificacion_bt_adquisicion_animales'], 
														$infoAdquisicionAnimales['procedencia_animales'], 
														$infoAdquisicionAnimales['categoria_animales_adquiriente'], 
														$ruta, $infoAdquisicionAnimales['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>I. Veterinario</h3>

	<fieldset id="detalleVeterinarioConsultaFS">
		<legend>Veterinario Registrado</legend>
		<table id="detalleVeterinarioConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Nombre</th>
				    <th width="15%">Teléfono</th>
				    <th width="15%">Celular</th>
				    <th width="15%">Correo Electrónico</th>
				    <th width="15%">Frecuencia Visita</th>
				</tr>
			</thead>
			<?php 
				while ($infoVeterinario = pg_fetch_assoc($veterinarioConsulta)){
					echo $cbt->imprimirLineaVeterinarioRecertificacionBTConsulta($infoVeterinario['id_recertificacion_bt_veterinario'], 
											$infoVeterinario['nombre_veterinario'], $infoVeterinario['telefono_veterinario'], 
											$infoVeterinario['celular_veterinario'], $infoVeterinario['correo_electronico_veterinario'], 
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta, $infoVeterinario['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<h3>II. Vacunación</h3>

	<fieldset id="detalleInformacionVacunacionConsultaFS">
		<legend>Información de Vacunación</legend>
		<table id="detalleInformacionVacunacionConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Calendario Vacunación</th>
				    <th width="15%">Motivo Vacunación</th>
					<th width="15%">Vacuna Aplicada</th>
					<th width="10%">Lote Vacuna</th>
					<th width="10%">Fecha Vacunación</th>
				</tr>
			</thead>
			<?php 
				while ($infoVacunacion = pg_fetch_assoc($vacunacionConsulta)){
					echo $cbt->imprimirLineaInformacionVacunacionRecertificacionBTConsulta($infoVacunacion['id_certificacion_bt_informacion_vacunacion'], 
																	$infoVacunacion['motivo_vacunacion'], $infoVacunacion['vacunas_aplicadas'], 
																	$infoVacunacion['lote_vacuna'], $infoVacunacion['fecha_vacunacion'], 
																	$ruta, $infoVacunacion['num_inspeccion'], $infoVacunacion['calendario_vacunacion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>III. Patologías</h3>

	<fieldset id="detallePatologiaBrucelosisConsultaFS">
		<legend>Patologías Registradas</legend>
		<table id="detallePatologiaBrucelosisConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Retención Placenta</th>
				    <th width="15%">Nacimiento Terneros Débiles</th>
				    <th width="15%">Metritis Post-Parto</th>
				    <th width="15%">Abortos</th>
				    <th width="15%">Fiebre Fluctuante</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoPatologia = pg_fetch_assoc($patologiaConsulta)){
					echo $cbt->imprimirLineaPatologiaBrucelosisRecertificacionBTConsulta($infoPatologia['id_recertificacion_bt_patologia_brucelosis'], 
															$infoPatologia['retencion_placenta'], $infoPatologia['nacimiento_terneros_debiles'], 
															$infoPatologia['metritis_post_parto'], 
															$infoPatologia['abortos'], $infoPatologia['fiebre'], 
															$ruta, $infoPatologia['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Resultados de Pruebas de Laboratorio</h2>
	
	<?php 
		while($resultado = pg_fetch_assoc($resultadoLaboratorio)){
			$fecha= strtotime($resultado['fecha_creacion']);
			$mes = date("m", $fecha);
			echo $mes;
			echo date("Y", $fecha);
			echo "
			<fieldset>
				<legend>Resultado del Proceso ".$resultado['num_inspeccion'] ."</legend>
				
				<div data-linea='53'>
					<label>Resultado: </label>". $resultado['resultado_analisis']."				 					
				</div>
				
				<div data-linea='54'>
					<label>Observaciones: </label>". $resultado['observaciones']."
				</div>
					
				<div data-linea='12'>
					<label>Informe de Laboratorio: </label>";
					echo ($resultado['archivo_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$resultado['archivo_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>');
				echo "</div>";
			
			$resultadoLaboratorioDetalle = $cbt->abrirResultadoLaboratorioDetalleRecertificacion($conexion, $idRecertificacionBT, $resultado['id_recertificacion_bt_resultado_laboratorio']);
			
			echo "	<table id='detallePruebaLaboratorio'>
						<thead id='barraTitulo'>
							<tr id='titulo'>
							    <th width='15%'># Muestras</th>
								<th width='15%'># Positivos</th>
							    <th width='15%'>Fecha finalización resultados</th>
							    <th width='15%'>Enfermedad</th>
							    <th width='15%'>Prueba de Laboratorio</th>
								<th width='15%'>Resultado Análisis</th>
							</tr>
						</thead>";
					
			while ($resultadoDetalle = pg_fetch_assoc($resultadoLaboratorioDetalle)){
				echo "<tr>
						<td width='30%'>" .
						$resultadoDetalle['cantidad_muestras'].
						"</td>
						<td width='30%'>" .
						$resultadoDetalle['num_positivos'].
						"</td>
						<td width='30%'>" .
						$resultadoDetalle['fecha_muestra'].
						"</td>
						<td width='30%'>" .
						$resultadoDetalle['enfermedad'].
						"</td>
						<td width='30%'>" .
						$resultadoDetalle['prueba_laboratorio'].
						"</td>
						<td width='30%'>" .
						$resultadoDetalle['resultado'].
						"</td>
						</tr>";
			}
						
			echo "		</table>
			</fieldset>";
		}
	
	
	
	?>
		
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var estado= <?php echo json_encode($certificacionBT['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));

		cargarValorDefecto("provincia","<?php echo $certificacionBT['id_provincia'];?>");

		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

		if(<?php echo json_encode($certificacionBT['estado']); ?>=='aprobado'){
			$('#estadoSolicitud').addClass('exito'); //exito, advertencia, alerta
			$('#certificadoSolicitud').show();			
		}else if(<?php echo json_encode($certificacionBT['estado']); ?>=='porExpirar'){
			$('#estadoSolicitud').addClass('advertencia'); //exito, advertencia, alerta
			$('#certificadoSolicitud').show();
		}else if(<?php echo json_encode($certificacionBT['estado']); ?>=='recertificacion'){
			$('#estadoSolicitud').addClass('advertencia'); //exito, advertencia, alerta
			$('#certificadoSolicitud').hide();
		}else{
			$('#estadoSolicitud').addClass('alerta'); //exito, advertencia, alerta
			$('#certificadoSolicitud').hide();
		}
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//Cierre y Envío a Revisión
	$("#cerrarCertificacionBT").submit(function(event){

		$("#cerrarCertificacionBT").attr('data-opcion', 'guardarCierreCertificacionBTPC');
	    $("#cerrarCertificacionBT").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultado").val())){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if(!$.trim($("#observaciones").val()) || !esCampoValido("#observaciones")){
			error = true;
			$("#observaciones").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

</script>