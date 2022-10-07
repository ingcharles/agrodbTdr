<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$idTipoCompra = $_POST['id'];
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$tipoCompra = pg_fetch_assoc($cpp->abrirTipoCompra($conexion, $idTipoCompra));
	$procedimientoSugerido = $cpp->listarProcedimientoSugerido($conexion, $idTipoCompra);
?>

	<header>
		<h1>Tipo de Compra</h1>
	</header>

	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarTipoCompra" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarTipoCompra">
					<input type="hidden" id="idTipoCompra" name="idTipoCompra" value="<?php echo $idTipoCompra;?>">
					<fieldset id="fs_detalle">
						<legend>Tipo de Compra</legend>
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreTipoCompra" name="nombreTipoCompra" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $tipoCompra['nombre'];?>" disabled="disabled"/>
						</div>

						<div data-linea="3">
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoProcedimientoSugerido" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarProcedimientoSugerido" >
					<input type="hidden" id="idTipoCompra" name="idTipoCompra" value="<?php echo $idTipoCompra;?>">
					
					<fieldset>
						<legend>Procedimiento Sugerido</legend>	
						
						<div data-linea="4">
							<label>Nombre:</label>
							<input type="text" id="nombreProcedimientoSugerido" name="nombreProcedimientoSugerido" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
						</div>	
						
						<div data-linea="6">
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Procedimientos Registrados</legend>
					<table id="detalleProcedimientoSugerido">
						<?php 
							while ($procedimientosSugeridos = pg_fetch_assoc($procedimientoSugerido)){
								echo $cpp->imprimirLineaProcedimientoSugerido($procedimientosSugeridos['id_procedimiento_sugerido'], $procedimientosSugeridos['nombre'], $idTipoCompra, 'programacionAnualPresupuestaria');
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
		distribuirLineas();
		acciones("#nuevoProcedimientoSugerido","#detalleProcedimientoSugerido");
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
	
	$("#modificarTipoCompra").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreTipoCompra").val()) || !esCampoValido("#nombreTipoCompra")){
			error = true;
			$("#nombreTipoCompra").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});
	
</script>