<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$idPrograma = $_POST['id'];
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$programa = pg_fetch_assoc($cpp->abrirPrograma($conexion, $idPrograma));
	$codigoProyectos = $cpp->listarCodigoProyecto($conexion, $idPrograma);
?>

	<header>
		<h1>Programa</h1>
	</header>

	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarPrograma" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarPrograma">
					<input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo $idPrograma;?>">
					<fieldset id="fs_detalle">
						<legend>Programa</legend>
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombrePrograma" name="nombrePrograma" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $programa['nombre']?>" disabled="disabled"/>
						</div>
						<div data-linea="2">
						<label>Código:</label>
							<input type="text" id="codigoPrograma" name="codigoPrograma" maxlength="2" data-er="^[0-9]+$" value="<?php echo $programa['codigo']?>" disabled="disabled"/>
						</div>		
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoCodigoProyecto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarCodigoProyecto" >
					<input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo $idPrograma;?>">
					
					<fieldset>
						<legend>Proyecto</legend>	
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreProyecto" name="nombreProyecto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
						</div>
						<div data-linea="2">
							<label>Código:</label>
								<input type="text" id="codigoProyecto" name="codigoProyecto" maxlength="3" data-er="^[0-9]+$" required="required"/>
						</div>
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Proyectos Registrados</legend>
					<table id="detalleCodigoProyecto">					
						<thead>
							<tr>
							    <th width="15%">Proyecto</th>
								<th width="15%">Código</th>
								<th width="10%">Abrir</th>
								<th width="10%">Eliminar</th>
							</tr>
						</thead>
						
						<?php 
							while ($codigoProyecto = pg_fetch_assoc($codigoProyectos)){
								echo $cpp->imprimirLineaCodigoProyecto($codigoProyecto['id_codigo_proyecto'], $codigoProyecto['nombre'], $codigoProyecto['codigo_proyecto'], $idPrograma, 'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script type="text/javascript">
						
	$('document').ready(function(){
		acciones("#nuevoCodigoProyecto","#detalleCodigoProyecto");
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
	
	$("#modificarPrograma").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombrePrograma").val()) || !esCampoValido("#nombrePrograma")){
			error = true;
			$("#nombrePrograma").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoPrograma").val()) || !esCampoValido("#codigoPrograma")){
			error = true;
			$("#codigoPrograma").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

</script>