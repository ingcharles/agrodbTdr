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
	$areaOO = $_POST['areaOO'];
	$idProcesoProyecto = $_POST['idProcesoProyecto'];
			
	$procesoProyecto = pg_fetch_assoc($cpp->abrirProcesoProyecto($conexion, $idProcesoProyecto));
	$idPrograma = $procesoProyecto['id_programa'];
	$componente = $cpp->listarComponente($conexion, $idProcesoProyecto, $anio);
	
	$areaPP = pg_fetch_result($ca->buscarArea($conexion, $procesoProyecto['id_area']), 0, 'id_area_padre');
?>

	<header>
		<h1>Proceso - Proyecto</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirObjetivoOperativo" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>"/>
		<input type="hidden" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>"/>
		<input type="hidden" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>"/>
		<button class="regresar">Regresar a Objetivo Operativo</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarProcesoProyecto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarProcesoProyecto">
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset id="fs_detalle">
						<legend>Proceso - Proyecto</legend>	
						
						<div data-linea="1">
							<label>Coordinación/Dirección:</label>
								<select id=area name="area" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$areas = $ca->buscarDivisionEstructura($conexion, $areaPP);
										
										while($fila = pg_fetch_assoc($areas)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idArea' name='idArea' value="<?php echo $procesoProyecto['id_area'];?>"/>
								<input type='hidden' id='nombreArea' name='nombreArea' value="<?php echo $procesoProyecto['nombre_area'];?>"/>
						</div>
						
						<div data-linea="2">
							<label>Tipo:</label>
								<select id=tipo name="tipo" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<option value="Proceso">Proceso</option>
									<option value="Proyecto Gasto Corriente">Proyecto Gasto Corriente</option>
									<option value="Proyecto Inversion">Proyecto Inversion</option>
								</select>
						</div>
						<div data-linea="2">
							<label>Financiamiento:</label>
								<select id=financiamiento name="financiamiento" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<option value="Gasto Corriente">Gasto Corriente</option>
									<option value="Inversion">Inversion</option>
								</select>
						</div>
						
						<div data-linea="3">
							<label>Programa:</label>
								<select id=programa name="programa" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$programas = $cpp->listarProgramas($conexion);
										
										while($fila = pg_fetch_assoc($programas)){
											echo '<option value="' . $fila['id_programa'] . '" data-codigo="'. $fila['codigo'] .'">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idPrograma' name='idPrograma' value="<?php echo $procesoProyecto['id_programa'];?>"/>
								<input type='hidden' id='nombrePrograma' name='nombrePrograma' />
								<input type='hidden' id='codigoPrograma' name='codigoPrograma' value="<?php echo $procesoProyecto['codigo_programa'];?>"/>
						</div>
						
						<div data-linea="4">
							<label>Nombre:</label>
								<input type="text" id="nombreProcesoProyecto" name="nombreProcesoProyecto" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü´,.\-\ ]+$" required="required" value="<?php echo $procesoProyecto['nombre'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="5">
							<label>Producto Final:</label>
								<input type="text" id="productoFinal" name="productoFinal" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü´,.\-\ ]+$" value="<?php echo $procesoProyecto['producto_final'];?>" disabled="disabled"/>
						</div>
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoComponente" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarComponente" >
					<input type="hidden" id="idObjetivoEstrategico" name="idObjetivoEstrategico" value="<?php echo $idObjetivoEstrategico;?>">
					<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" value="<?php echo $idObjetivoEspecifico;?>">
					<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" value="<?php echo $idObjetivoOperativo;?>">
					<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" value="<?php echo $idProcesoProyecto;?>">
					<input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo $idPrograma;?>">
					<input type="hidden" id="anio" name="anio" maxlength="4" data-er="^[0-9]+$" value="<?php echo $anio;?>"/>
					
					<fieldset>
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
								<input type="text" id="nombreComponente" name="nombreComponente" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" required="required"/>
						</div>
						
						<div data-linea="3">
							<label>Proyecto:</label>
								<select id=codigoProyecto name="codigoProyecto" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$codigoProyecto = $cpp->listarCodigoProyecto($conexion, $idPrograma);
										
										while($fila = pg_fetch_assoc($codigoProyecto)){
											echo '<option value="' . $fila['id_codigo_proyecto'] . '" data-codigo="'. $fila['codigo_proyecto'] .'">' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type='hidden' id='idCodigoProyecto' name='idCodigoProyecto' />
								<input type='hidden' id='nombreCodigoProyecto' name='nombreCodigoProyecto' />
								<input type='hidden' id='codigoCodigoProyecto' name='codigoCodigoProyecto' />
						</div>
												
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>
						
					</fieldset>
				</form>
				
				<fieldset>
					<legend>Componentes Registrados</legend>
					<table id="detalleComponente">					
						<thead>
							<tr>
							    <th width="25%">Componente</th>
								<th width="25%">Proyecto</th>
								<th width="16%">Año</th>
								<th width="16%">Abrir</th>
								<th width="17%">Eliminar</th>
							</tr>
						</thead>
						<?php 
							while ($componentes = pg_fetch_assoc($componente)){
								echo $cpp->imprimirLineaComponente($componentes['id_componente'], $componentes['nombre'],
										$componentes['codigo_proyecto'], $idObjetivoEstrategico, $idObjetivoEspecifico, 
										$idObjetivoOperativo, $idProcesoProyecto, $idPrograma, 
										$componentes['id_codigo_proyecto'], $anio, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
<script type="text/javascript">
						
	$('document').ready(function(){
		cargarValorDefecto("area","<?php echo $procesoProyecto['id_area'];?>");
		cargarValorDefecto("tipo","<?php echo $procesoProyecto['tipo'];?>");
		cargarValorDefecto("financiamiento","<?php echo $procesoProyecto['financiamiento'];?>");
		cargarValorDefecto("programa","<?php echo $procesoProyecto['id_programa'];?>");
		acciones("#nuevoComponente","#detalleComponente");
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
	
	$("#modificarProcesoProyecto").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo").val())){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if(!$.trim($("#financiamiento").val())){
			error = true;
			$("#financiamiento").addClass("alertaCombo");
		}

		if(!$.trim($("#programa").val())){
			error = true;
			$("#programa").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreProcesoProyecto").val()) || !esCampoValido("#nombreProcesoProyecto")){
			error = true;
			$("#nombreProcesoProyecto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#productoFinal").val()) || !esCampoValido("#productoFinal")){
			error = true;
			$("#productoFinal").addClass("alertaCombo");
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

	$("#codigoProyecto").change(function(){
		$('#idCodigoProyecto').val($("#codigoProyecto option:selected").val());
		$('#nombreCodigoProyecto').val($("#codigoProyecto option:selected").text());
		$('#codigoCodigoProyecto').val($("#codigoProyecto option:selected").attr('data-codigo'));
	});
</script>