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
	
	$objetivoOperativo = pg_fetch_assoc($cpp->abrirObjetivoOperativo($conexion, $idObjetivoOperativo));
	$procesoProyecto = $cpp->listarProcesoProyecto($conexion, $idObjetivoOperativo, $anio);
?>

	<header>
		<h1>Objetivo Operativo</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirObjetivoEspecifico" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>"/>
		<button class="regresar">Regresar a Objetivo Específico</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarObjetivoOperativo" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarObjetivoOperativo">
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset id="fs_detalle">
						<legend>Objetivo Operativo</legend>	
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=areaOO name="areaOO" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '" data-area-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idAreaOO' name='idAreaOO' value="<?php echo $objetivoOperativo['id_area'];?>"/>
								<input type='hidden' id='nombreAreaOO' name='nombreAreaOO' value="<?php echo $objetivoOperativo['nombre_area'];?>"/>
						</div>
						
						<div data-linea="2">
							<label>Nombre:</label>
								<input type="text" id="nombreObjetivoOperativo" name="nombreObjetivoOperativo" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,.\-\ ]+$" required="required" value="<?php echo $objetivoOperativo['nombre'];?>" disabled="disabled"/>
						</div>
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoProcesoProyecto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarProcesoProyecto" >
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="anio" name="anio" value="<?php echo $anio;?>"/>
					<input type='hidden' id='idAreaOO' name='idAreaOO' value="<?php echo $objetivoOperativo['id_area'];?>"/>
					
					<fieldset>
						<legend>Proceso - Proyecto</legend>	
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarDivisionEstructura($conexion, $objetivoOperativo['id_area']);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idArea' name='idArea' />
								<input type='hidden' id='nombreArea' name='nombreArea' />
						</div>
						
						<div data-linea="2">
							<label>Tipo:</label>
								<select id=tipo name="tipo" required="required">
									<option value="">Seleccione....</option>
									<option value="Proceso">Proceso</option>
									<option value="Proyecto Gasto Corriente">Proyecto Gasto Corriente</option>
									<option value="Proyecto Inversion">Proyecto Inversion</option>
								</select>
						</div>
						<div data-linea="2">
							<label>Financiamiento:</label>
								<select id=financiamiento name="financiamiento" required="required">
									<option value="">Seleccione....</option>
									<option value="Gasto Corriente">Gasto Corriente</option>
									<option value="Inversion">Inversion</option>
								</select>
						</div>
						
						<div data-linea="3">
							<label>Programa:</label>
								<select id=programa name="programa" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$programas = $cpp->listarProgramas($conexion);
										
										while($fila = pg_fetch_assoc($programas)){
											echo '<option value="' . $fila['id_programa'] . '" data-codigo="'. $fila['codigo'] .'">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idPrograma' name='idPrograma' />
								<input type='hidden' id='nombrePrograma' name='nombrePrograma' />
								<input type='hidden' id='codigoPrograma' name='codigoPrograma' />
						</div>
						
						<div data-linea="4">
							<label>Nombre:</label>
								<input type="text" id="nombreProcesoProyecto" name="nombreProcesoProyecto" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" required="required"/>
						</div>
						
						<div data-linea="5">
							<label>Producto Final:</label>
								<input type="text" id="productoFinal" name="productoFinal" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" />
						</div>
						
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Procesos y Proyectos Registrados</legend>
					<table id="detalleProcesoProyecto">
						<thead>
							<tr>
							    <th width="25%">Proceso/Proyecto</th>
								<th width="25%">Tipo</th>
								<th width="16%">Financiamiento</th>
								<th width="16%">Programa</th>
								<th width="16%">Área</th>
								<th width="5%">Año</th>
								<th width="5%">Abrir</th>
								<th width="17%">Eliminar</th>
							</tr>
						</thead>
						
						<?php 
							while ($procesosProyectos = pg_fetch_assoc($procesoProyecto)){
								echo $cpp->imprimirLineaProcesoProyecto($procesosProyectos['id_proceso_proyecto'], $procesosProyectos['nombre'], 
											$procesosProyectos['tipo'], $procesosProyectos['financiamiento'], $procesosProyectos['codigo_programa'], 
											$procesosProyectos['nombre_area'], $idObjetivoEstrategico, $idObjetivoEspecifico, $idObjetivoOperativo, 
											$objetivoOperativo['id_area'], $anio, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
<script type="text/javascript">
						
	$('document').ready(function(){
		cargarValorDefecto("areaOO","<?php echo $objetivoOperativo['id_area'];?>");
		acciones("#nuevoProcesoProyecto","#detalleProcesoProyecto");
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
	
	$("#modificarObjetivoOperativo").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#areaOO").val())){
			error = true;
			$("#areaOO").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreObjetivoOperativo").val()) || !esCampoValido("#nombreObjetivoOperativo")){
			error = true;
			$("#nombreObjetivoOperativo").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#areaOO").change(function(){
		$('#idAreaOO').val($("#areaOO option:selected").val());
		$('#nombreAreaOO').val($("#areaOO option:selected").text());
	});

	$("#area").change(function(){
		$('#idArea').val($("#area option:selected").val());
		$('#nombreArea').val($("#area option:selected").text());
	});

	$("#programa").change(function(){
		$('#idPrograma').val($("#programa option:selected").val());
		$('#nombrePrograma').val($("#programa option:selected").text());
		$('#codigoPrograma').val($("#programa option:selected").attr('data-codigo'));
	});
</script>