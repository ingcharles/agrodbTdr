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
	$idObjetivoOperativo = $_POST['idObjetivoOperativo'];
	$idProcesoProyecto = $_POST['idProcesoProyecto'];
	$idComponente = $_POST['idComponente'];
	$idPrograma = $_POST['idPrograma'];
	
	$componente = pg_fetch_assoc($cpp->abrirComponente($conexion, $idComponente));
	$idCodigoProyecto = $componente['id_codigo_proyecto'];
	$actividad = $cpp->listarActividad($conexion, $idComponente, $anio);
?>

	<header>
		<h1>Componente</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirProcesoProyecto" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>"/>
		<input type="hidden" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>"/>
		<input type="hidden" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>"/>
		<button class="regresar">Regresar a Proceso - Proyecto</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarComponente" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarComponente">
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>">
					<input type="hidden" id="idComponente" name="idComponente" value="<?php echo $idComponente;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset id="fs_detalle">
						<legend>Componente</legend>	
						
						<!-- div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required">
									<option value="">Seleccione....</option>
									< ?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idArea' name='idArea' />
								<input type='hidden' id='nombreArea' name='nombreArea' />
						</div-->
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreComponente" name="nombreComponente" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,.\-\ ]+$" required="required" value="<?php echo $componente['nombre'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="3">
							<label>Proyecto:</label>
								<select id=codigoProyecto name="codigoProyecto" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$codigoProyecto = $cpp->listarCodigoProyecto($conexion, $idPrograma);
										
										while($fila = pg_fetch_assoc($codigoProyecto)){
											echo '<option value="' . $fila['id_codigo_proyecto'] . '" data-codigo="'. $fila['codigo_proyecto'] .'">' . $fila['codigo_proyecto'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idCodigoProyecto' name='idCodigoProyecto' />
								<input type='hidden' id='nombreCodigoProyecto' name='nombreCodigoProyecto' />
								<input type='hidden' id='codigoCodigoProyecto' name='codigoCodigoProyecto' />
						</div>
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevaActividad" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarActividad" >
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>">
					<input type="hidden" id="idComponente" name="idComponente" value="<?php echo $idComponente;?>">
					<input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo $idPrograma;?>">
					<input type="hidden" id="idCodigoProyecto" name="idCodigoProyecto" value="<?php echo $idCodigoProyecto;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset>
						<legend>Actividad</legend>	
						
						<!-- div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required">
									<option value="">Seleccione....</option>
									< ?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idArea' name='idArea' />
								<input type='hidden' id='nombreArea' name='nombreArea' />
						</div-->
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreActividad" name="nombreActividad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" required="required"/>
						</div>
						
						<div data-linea="3">
							<label>Actividad:</label>
								<select id=codigoActividad name="codigoActividad" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$codigoActividad = $cpp->listarCodigoActividad($conexion, $idCodigoProyecto);
										
										while($fila = pg_fetch_assoc($codigoActividad)){
											echo '<option value="' . $fila['id_codigo_actividad'] . '" data-codigo="'. $fila['codigo_actividad'] .'">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idCodigoActividad' name='idCodigoActividad' />
								<input type='hidden' id='nombreCodigoActividad' name='nombreCodigoActividad' />
								<input type='hidden' id='codigoCodigoActividad' name='codigoCodigoActividad' />
						</div>
												
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>
						
					</fieldset>
				</form>
				
				<fieldset>
					<legend>Actividades Registradas</legend>
					<table id="detalleActividad">
						<thead>
							<tr>
							    <th width="30%">Actividad</th>
								<th width="30%">Proyecto</th>
								<th width="10%">Año</th>
								<th width="10%">Abrir</th>
								<th width="17%">Eliminar</th>
							</tr>
						</thead>
						<?php 
							while ($actividades = pg_fetch_assoc($actividad)){
								echo $cpp->imprimirLineaActividad($actividades['id_actividad'], $actividades['nombre'], 
										$actividades['codigo_actividad'], $idObjetivoEstrategico, $idObjetivoEspecifico, 
										$idObjetivoOperativo, $idProcesoProyecto, $idComponente, 
										$idPrograma, $idCodigoProyecto, $actividades['id_codigo_actividad'], 
										$anio, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
<script type="text/javascript">
						
	$('document').ready(function(){
		cargarValorDefecto("codigoProyecto","<?php echo $componente['id_codigo_proyecto'];?>");
		acciones("#nuevaActividad","#detalleActividad");
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
	
	$("#modificarComponente").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		/*if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}*/

		if(!$.trim($("#nombreComponente").val()) || !esCampoValido("#nombreComponente")){
			error = true;
			$("#nombreComponente").addClass("alertaCombo");
		}
		
		if(!$.trim($("#codigoProyecto").val())){
			error = true;
			$("#codigoProyecto").addClass("alertaCombo");
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
	});

	$("#codigoActividad").change(function(){
		$('#idCodigoActividad').val($("#codigoActividad option:selected").val());
		$('#nombreCodigoActividad').val($("#codigoActividad option:selected").text());
		$('#codigoCodigoActividad').val($("#codigoActividad option:selected").attr('data-codigo'));
	});
</script>