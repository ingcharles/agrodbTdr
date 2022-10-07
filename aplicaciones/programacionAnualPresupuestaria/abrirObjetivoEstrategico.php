<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$idObjetivoEstrategico = $_POST['id'];
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$objetivoEstrategico = pg_fetch_assoc($cpp->abrirObjetivoEstrategico($conexion, $idObjetivoEstrategico));
	$objetivoEspecifico = $cpp->listarObjetivoEspecifico($conexion, $idObjetivoEstrategico, $anio);
?>

	<header>
		<h1>Objetivo Estratégico</h1>
	</header>

	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarObjetivoEstrategico" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarObjetivoEstrategico">
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<fieldset id="fs_detalle">
						<legend>Objetivo Estratégico</legend>
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreObjetivoEstrategico" name="nombreObjetivoEstrategico" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" value="<?php echo $objetivoEstrategico['nombre']?>" disabled="disabled"/>
						</div>	
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoObjetivoEspecifico" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarObjetivoEspecifico" >
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset>
						<legend>Objetivo Específico</legend>	
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='nombreArea' name='nombreArea' />
						</div>
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreObjetivoEspecifico" name="nombreObjetivoEspecifico" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" required="required"/>
						</div>
						
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Objetivos Específicos Registrados</legend>
					<table id="detalleObjetivoEspecifico">
						<thead>
							<tr>
							    <th width="25%">Objetivo Específico</th>
								<th width="25%">Área</th>
								<th width="16%">Año</th>
								<th width="16%">Abrir</th>
								<th width="17%">Eliminar</th>
							</tr>
						</thead>
						<?php 
							while ($objetivosEspecificos = pg_fetch_assoc($objetivoEspecifico)){
								echo $cpp->imprimirLineaObjetivoEspecifico($objetivosEspecificos['id_objetivo_especifico'], $objetivosEspecificos['nombre'], $objetivosEspecificos['nombre_area'], $idObjetivoEstrategico, $anio, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script type="text/javascript">
var anio= <?php echo json_encode($anio); ?>;
						
	$('document').ready(function(){
		acciones("#nuevoObjetivoEspecifico","#detalleObjetivoEspecifico");
		$("#anio").val(anio);
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#modificarObjetivoEstrategico").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreObjetivoEstrategico").val()) || !esCampoValido("#nombreObjetivoEstrategico")){
			error = true;
			$("#nombreObjetivoEstrategico").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#area").change(function(){
	    $('#nombreArea').val($("#area option:selected").text());

		/*if($("#area option:selected").val() != 'CGIA' && $("#area option:selected").val() != 'CGL' && $("#area option:selected").val() != 'CGRIA' && $("#area option:selected").val() != 'CGSA' && $("#area option:selected").val() != 'CGSV'){
			$('#nombreObjetivoEspecifico').val('OE - '+$("#nombreObjetivoEstrategico").val());
			$('#nombreObjetivoEspecifico').attr(readonly, 'readonly');
		}else{
			$('#nombreObjetivoEspecifico').val('');
			$('#nombreObjetivoEspecifico').removeAttr(readonly);
		}*/
	});
	
</script>