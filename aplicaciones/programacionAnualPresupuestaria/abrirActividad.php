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
	$idActividad = $_POST['idActividad'];
	$idPrograma = $_POST['idPrograma'];
	
	$actividad = pg_fetch_assoc($cpp->abrirActividad($conexion, $idActividad));
	$idCodigoProyecto = $_POST['idCodigoProyecto'];
	$idCodigoActividad = $_POST['idCodigoActividad'];
	//$actividad = $cpp->listarActividad($conexion, $idComponente, $anio);
?>

	<header>
		<h1>Actividad</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirComponente" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>"/>
		<input type="hidden" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>"/>
		<input type="hidden" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>"/>
		<input type="hidden" name="idComponente" value="<?php echo $idComponente;?>"/>
		<input type="hidden" name="idPrograma" value="<?php echo $idPrograma;?>"/>
		<button class="regresar">Regresar a Componente</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarActividad" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarActividad">
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>">
					<input type="hidden" id="idComponente" name="idComponente" value="<?php echo $idComponente;?>">
					<input type="hidden" id="idActividad" name="idActividad" value="<?php echo $idActividad;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset id="fs_detalle">
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
								<input type="text" id="nombreActividad" name="nombreActividad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,.\-\ ]+$" required="required" value="<?php echo $actividad['nombre'];?>" disabled="disabled" />
						</div>
						
						<div data-linea="3">
							<label>Actividad:</label>
								<select id=codigoActividad name="codigoActividad" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$codigoActividad = $cpp->listarCodigoActividad($conexion, $idCodigoProyecto);
										
										while($fila = pg_fetch_assoc($codigoActividad)){
										    echo '<option value="' . $fila['id_codigo_actividad'] . '" data-codigo="'. $fila['codigo_actividad'] .'">' . $fila['codigo_actividad'] . "-".$fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idCodigoActividad' name='idCodigoActividad' value="<?php echo $actividad['id_codigo_actividad'];?>"/>
								<input type='hidden' id='nombreCodigoActividad' name='nombreCodigoActividad' value="<?php echo $actividad['codigo_actividad'];?>"/>
								<input type='hidden' id='codigoCodigoActividad' name='codigoCodigoActividad' value="<?php echo $actividad['codigo_actividad'];?>"/>
						</div>
						
						<div data-linea="4">
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>
<script type="text/javascript">
						
	$('document').ready(function(){
		cargarValorDefecto("codigoActividad","<?php echo $actividad['id_codigo_actividad'];?>");
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
	
	$("#modificarActividad").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		/*if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}*/

		if(!$.trim($("#nombreActividad").val()) || !esCampoValido("#nombreActividad")){
			error = true;
			$("#nombreActividad").addClass("alertaCombo");
		}
		
		if(!$.trim($("#codigoActividad").val())){
			error = true;
			$("#codigoActividad").addClass("alertaCombo");
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