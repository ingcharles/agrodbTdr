<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$idObjetivoEstrategico = $_POST['idObjetivoEstrategico'];
	$idObjetivoEspecifico = $_POST['idObjetivoEspecifico'];
	$objetivoEstrategico = pg_fetch_assoc($cpp->abrirObjetivoEstrategico($conexion, $idObjetivoEstrategico));
	$objetivoEspecifico = pg_fetch_assoc($cpp->abrirObjetivoEspecifico($conexion, $idObjetivoEspecifico));
	$objetivoOperativo = $cpp->listarObjetivoOperativo($conexion, $idObjetivoEspecifico, $anio);
?>

	<header>
		<h1>Objetivo Específico</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirObjetivoEstrategico" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idObjetivoEstrategico;?>"/>
		<button class="regresar">Regresar a Objetivo Estratégico</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarObjetivoEspecifico" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarObjetivoEspecifico">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="nombreObjetivoEstrategico" name="nombreObjetivoEstrategico" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $objetivoEstrategico['nombre']?>" disabled="disabled"/>
					
					<fieldset id="fs_detalle">
						<legend>Objetivo Específico</legend>
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idArea' name='idArea' value='<?php echo $objetivoEspecifico['id_area'];?>' />
								<input type='hidden' id='nombreArea' name='nombreArea' value='<?php echo $objetivoEspecifico['nombre_area'];?>' />
						</div>
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreObjetivoEspecifico" name="nombreObjetivoEspecifico" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,.\-\ ]+$" required="required" value='<?php echo $objetivoEspecifico['nombre'];?>' disabled="disabled"/>
						</div>
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoObjetivoOperativo" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarObjetivoOperativo" >
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset>
						<legend>Objetivo Operativo</legend>	
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=areaOO name="areaOO" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '" data-area-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idAreaOO' name='idAreaOO' />
								<input type='hidden' id='nombreAreaOO' name='nombreAreaOO' />
						</div>
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreObjetivoOperativo" name="nombreObjetivoOperativo" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
						</div>
						
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Objetivos Operativos Registrados</legend>
					<table id="detalleObjetivoOperativo">
						<thead>
							<tr>
							    <th width="40%">Objetivo Operativo</th>
								<th width="30%">Área</th>
								<th width="10%">Año</th>
								<th width="10%">Abrir</th>
								<th width="10%">Eliminar</th>
							</tr>
						</thead>
						<?php 
							while ($objetivosOperativos = pg_fetch_assoc($objetivoOperativo)){
								echo $cpp->imprimirLineaObjetivoOperativo($objetivosOperativos['id_objetivo_operativo'], $objetivosOperativos['nombre'], $objetivosOperativos['nombre_area'], $idObjetivoEspecifico, $idObjetivoEstrategico, $anio, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script type="text/javascript">
						
	$('document').ready(function(){
		cargarValorDefecto("area","<?php echo $objetivoEspecifico['id_area'];?>");
		cargarValorDefecto("area00","<?php echo $objetivoEspecifico['id_area'];?>");
		acciones("#nuevoObjetivoOperativo","#detalleObjetivoOperativo");
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
	
	$("#modificarObjetivoEspecifico").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreObjetivoEspecifico").val()) || !esCampoValido("#nombreObjetivoEspecifico")){
			error = true;
			$("#nombreObjetivoEspecifico").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#area").change(function(){
		$('#idArea').val($("#area option:selected").val());
		$('#nombreArea').val($("#area option:selected").text());

		/*if($("#area option:selected").val() != 'CGIA' && $("#area option:selected").val() != 'CGL' && $("#area option:selected").val() != 'CGRIA' && $("#area option:selected").val() != 'CGSA' && $("#area option:selected").val() != 'CGSV'){
			$('#nombreObjetivoEspecifico').val('OE - '+$("#nombreObjetivoEstrategico").val());
			$('#nombreObjetivoEspecifico').attr(readonly, 'readonly');
		}else{
			$('#nombreObjetivoEspecifico').val('');
			$('#nombreObjetivoEspecifico').removeAttr(readonly);
		}*/
	});

	$("#areaOO").change(function(){
		$('#idAreaOO').val($("#areaOO option:selected").val());
		$('#nombreAreaOO').val($("#areaOO option:selected").text());
	});
</script>